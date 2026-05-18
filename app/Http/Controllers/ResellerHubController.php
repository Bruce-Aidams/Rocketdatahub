<?php

namespace App\Http\Controllers;

use App\Models\Bundle;
use App\Models\ResellerPrice;
use App\Models\Order;
use App\Models\User;
use App\Models\Commission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ResellerHubController extends Controller
{
    /**
     * Get the authenticated reseller user or abort.
     */
    private function getResellerUser($redirect = false)
    {
        $user = Auth::user();
        
        if (!$user || !$user->isReseller()) {
            if ($redirect) {
                // Return a redirect response to break execution
                abort(redirect()->route('dashboard')->with('error', 'Access denied to Reseller Hub.'));
            }
            abort(403);
        }

        if (!$user->referral_code) {
            $user->update(['referral_code' => strtoupper(Str::random(10))]);
        }

        return $user;
    }

    /**
     * Base query for orders belonging to a reseller's referrals or storefront.
     */
    private function getResellerOrdersQuery(User $user)
    {
        $referralIds = User::where('referred_by_id', $user->id)->pluck('id');

        return Order::where(function ($q) use ($user, $referralIds) {
            $q->whereIn('user_id', $referralIds)
                ->orWhere(function ($sub) use ($user) {
                    $sub->where('user_id', $user->id)
                        ->where('source', 'storefront');
                });
        });
    }
    public function index()
    {
        $user = $this->getResellerUser(true);
        $referralIds = User::where('referred_by_id', $user->id)->pluck('id');

        // Stats Logic
        $stats = [
            'referral_count' => $referralIds->count(),
            'wallet_balance' => $user->wallet_balance,
            'total_sales' => $this->getResellerOrdersQuery($user)->where('status', 'delivered')->count(),
            'storefront_profit' => Order::where('user_id', $user->id)
                ->where('source', 'storefront')
                ->where('status', 'delivered')
                ->sum('profit'),
            'referral_earnings' => Commission::where('user_id', $user->id)->sum('amount'),
        ];

        $stats['total_earnings'] = $stats['storefront_profit'] + $stats['referral_earnings'];

        $recentOrders = $this->getResellerOrdersQuery($user)
            ->with(['user', 'bundle'])
            ->latest()
            ->limit(5)
            ->get();

        $recentReferrals = User::where('referred_by_id', $user->id)
            ->latest()
            ->limit(5)
            ->get();

        return view('dashboard.reseller.index', compact('stats', 'recentReferrals', 'recentOrders', 'user'));
    }

    public function manageStore()
    {
        $user = $this->getResellerUser();

        $bundles = Bundle::where('is_active', true)->get();
        $customPrices = ResellerPrice::where('user_id', $user->id)->pluck('price', 'bundle_id');

        // Logic to get reseller's cost price
        $bundles->each(function ($bundle) use ($user, $customPrices) {
            $rolePrices = $bundle->role_prices ?: [];
            $bundle->cost_to_reseller = $rolePrices[$user->role] ?? $bundle->price;
            $bundle->custom_price = $customPrices[$bundle->id] ?? $bundle->price;
            $bundle->profit_per_unit = $bundle->custom_price - $bundle->cost_to_reseller;
        });

        return view('dashboard.reseller.manage-store', compact('bundles', 'user'));
    }

    public function updatePrice(Request $request)
    {
        $request->validate([
            'bundle_id' => 'required|exists:bundles,id',
            'price' => 'required|numeric|min:0',
        ]);

        $user = $this->getResellerUser();
        $bundle = Bundle::findOrFail($request->bundle_id);

        // Reseller can't set price lower than the main base price set by admin
        $basePrice = (float) $bundle->price;

        if ((float) $request->price < $basePrice) {
            return back()->with('error', 'Selling price cannot be lower than the main base price set by Admin (GHC ' . number_format($basePrice, 2) . ')');
        }

        ResellerPrice::updateOrCreate(
            ['user_id' => $user->id, 'bundle_id' => $request->bundle_id],
            ['price' => $request->price]
        );

        return back()->with('success', 'Store price updated successfully.');
    }

    public function updateStoreName(Request $request)
    {
        $request->validate(['store_name' => 'required|string|max:50']);
        $user = $this->getResellerUser();

        $user->update(['store_name' => $request->store_name]);
        return back()->with('success', 'Store name updated successfully.');
    }

    public function toggleStoreStatus()
    {
        $user = $this->getResellerUser();

        $user->store_active = !$user->store_active;
        $user->save();

        $status = $user->store_active ? 'enabled' : 'disabled';
        return back()->with('success', "Your store is now {$status}.");
    }

    public function regenerateStoreLink()
    {
        $user = $this->getResellerUser();

        do {
            $newCode = strtoupper(Str::random(10));
        } while (User::where('referral_code', $newCode)->exists());

        $user->referral_code = $newCode;
        $user->save();

        return back()->with('success', 'Your store link has been regenerated. Old links will no longer work.');
    }

    public function customerOrders(Request $request)
    {
        $user = $this->getResellerUser();

        // Capture both: 
        // 1. Orders from registered referred users
        // 2. Guest storefront purchases (where user_id is the reseller's ID and source is storefront)
        $query = $this->getResellerOrdersQuery($user);

        // Search Filter (Reference or Phone)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('reference', 'like', "%$search%")
                    ->orWhere('recipient_phone', 'like', "%$search%");
            });
        }

        // Status Filter
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Network Filter
        if ($request->filled('network') && $request->network !== 'all') {
            $query->whereHas('bundle', function ($q) use ($request) {
                $q->where('network', $request->network);
            });
        }

        // Date Range Filter
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [$request->start_date . ' 00:00:00', $request->end_date . ' 23:59:59']);
        }

        $orders = $query->with(['user', 'bundle'])
            ->latest()
            ->paginate($request->input('per_page', 10))
            ->withQueryString();

        $networks = Bundle::distinct()->pluck('network');

        return view('dashboard.reseller.customer-orders', compact('orders', 'networks'));
    }
}
