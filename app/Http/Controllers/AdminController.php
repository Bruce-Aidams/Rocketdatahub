<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    /**
     * Get database-agnostic date format for grouping
     */
    private function getDateFormat($format)
    {
        $driver = DB::connection()->getDriverName();

        if ($driver === 'sqlite') {
            return "strftime('$format', created_at)";
        }

        // MySQL/PostgreSQL
        return "DATE_FORMAT(created_at, '$format')";
    }

    private function parseDataAmountToGB($dataAmount)
    {
        $dataAmount = strtoupper(trim($dataAmount));
        $amount = (float) filter_var($dataAmount, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

        if (strpos($dataAmount, 'GB') !== false) {
            return $amount;
        } elseif (strpos($dataAmount, 'MB') !== false) {
            return $amount / 1024;
        } elseif (strpos($dataAmount, 'TB') !== false) {
            return $amount * 1024;
        }
        return 0;
    }

    public function index(Request $request)
    {
        $todayRange = [now()->startOfDay(), now()->endOfDay()];

        // 1. Today's Orders
        $todayOrders = Order::whereBetween('created_at', $todayRange)->count();

        // 2. Today's Revenue
        $todayRevenue = Order::where('status', 'completed')->whereBetween('created_at', $todayRange)->sum('cost');

        // 3. Today's Profit
        $todayProfit = Order::where('status', 'completed')->whereBetween('created_at', $todayRange)->sum('profit');

        // 4. Data Delivered Today (GB)
        $todayCompletedOrders = Order::with('bundle')->where('status', 'completed')->whereBetween('created_at', $todayRange)->get();
        $todayDataGb = 0;
        foreach ($todayCompletedOrders as $order) {
            $todayDataGb += $this->parseDataAmountToGB($order->bundle?->data_amount ?? '0MB');
        }
        $todayDataGb = round($todayDataGb, 2);

        // 5. Total Agents
        $totalAgents = User::whereIn('role', ['retail_seller', 'super_agent', 'dealer'])->count();

        // 6. Today's Topups
        $todayTopups = Transaction::where('type', 'credit')
            ->where('status', 'success')
            ->whereBetween('created_at', $todayRange)
            ->sum('amount');

        // 7. Total Agent Balance
        $totalAgentBalance = User::whereIn('role', ['retail_seller', 'super_agent', 'dealer'])->sum('wallet_balance');

        // 8. Pending Data (GB)
        $pendingOrders = Order::with('bundle')->where('status', 'pending')->get();
        $pendingDataGb = 0;
        foreach ($pendingOrders as $order) {
            $pendingDataGb += $this->parseDataAmountToGB($order->bundle?->data_amount ?? '0MB');
        }
        $pendingDataGb = round($pendingDataGb, 2);

        // Additional data for existing view sections
        $totalUsers = User::where('role', 'user')->count();
        $pendingOrdersCount = Order::where('status', 'pending')->count();
        $totalRevenueAllTime = Order::where('status', 'completed')->sum('cost');
        $totalProfitAllTime = Order::where('status', 'completed')->sum('profit');
        $recentOrders = Order::with(['user', 'bundle'])->latest()->take(5)->get();

        // Monthly Revenue (Last 6 Months) for Chart
        $monthlyRevenueRaw = Order::selectRaw('SUM(cost) as total, ' . $this->getDateFormat('%Y-%m') . ' as month')
            ->where('status', 'completed')
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $chartData = [
            'labels' => $monthlyRevenueRaw->pluck('month'),
            'values' => $monthlyRevenueRaw->pluck('total')
        ];

        // Top Performing Agents
        $topAgents = User::whereIn('role', ['retail_seller', 'super_agent', 'dealer'])
            ->withCount([
                'orders as total_orders',
                'orders as completed_orders' => function ($query) {
                    $query->where('status', 'completed');
                }
            ])
            ->withSum([
                'orders as total_spent' => function ($query) {
                    $query->where('status', 'completed');
                }
            ], 'cost')
            ->orderBy('total_spent', 'desc')
            ->take(10)
            ->get();

        return view('admin.index', compact(
            'todayRevenue',
            'todayOrders',
            'todayDataGb',
            'totalAgents',
            'todayTopups',
            'totalAgentBalance',
            'pendingDataGb',
            'totalUsers',
            'pendingOrdersCount',
            'totalRevenueAllTime',
            'totalProfitAllTime',
            'recentOrders',
            'chartData',
            'topAgents'
        ));
    }

    public function getPublicSettings()
    {
        $keys = [
            'site_name',
            'support_email',
            'min_payment',
            'max_payment',
            'charge_type',
            'charge_value',
            'paystack_public',
            'bank_name',
            'bank_account_name',
            'bank_account_number',
            'enable_paystack',
            'enable_momo_deposits',
            'enable_manual_transfer'
        ];
        $settings = \App\Models\Setting::whereIn('key', $keys)->get()->pluck('value', 'key');

        if (!isset($settings['paystack_public']) || empty($settings['paystack_public'])) {
            $settings['paystack_public'] = env('PAYSTACK_PUBLIC_KEY');
        }

        return $settings;
    }

    public function getSettings()
    {
        $settings = \App\Models\Setting::all()->pluck('value', 'key');
        return view('admin.settings', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        $data = $request->validate([
            'settings' => 'present|array'
        ]);

        foreach ($data['settings'] as $key => $value) {
            \App\Models\Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        return response()->json(['message' => 'Settings updated']);
    }

    public function transactions(Request $request)
    {
        $startDate = $request->input('start_date', now()->subDays(30));
        $endDate = $request->input('end_date', now());

        $totalRevenue = Order::where('status', 'completed')->sum('cost');
        $grossProfit = Order::where('status', 'completed')->sum('profit');

        $transactions = Transaction::where('status', 'success')->whereNotNull('metadata')->get();
        $totalCharges = 0;
        foreach ($transactions as $t) {
            $totalCharges += (float) ($t->metadata['charge'] ?? 0);
        }

        $totalCommissions = \App\Models\Commission::sum('amount');
        $netProfit = $grossProfit + $totalCharges - $totalCommissions;

        $stats = [
            'total_revenue' => $totalRevenue,
            'gross_profit' => $grossProfit,
            'total_charges' => $totalCharges,
            'total_commissions' => $totalCommissions,
            'net_profit' => $netProfit
        ];

        $monthlyData = Order::selectRaw($this->getDateFormat('%Y-%m') . ' as month, SUM(cost) as revenue, SUM(profit) as profit')
            ->where('status', 'completed')
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->get();

        return view('admin.financials.index', compact('stats', 'monthlyData'));
    }

    public function analytics(Request $request)
    {
        $startDate = $request->input('start_date', now()->subDays(30)->toDateString());
        $endDate = $request->input('end_date', now()->toDateString());
        $range = [$startDate . ' 00:00:00', $endDate . ' 23:59:59'];

        $totalRevenue = Order::where('status', 'completed')->whereBetween('created_at', $range)->sum('cost');
        $totalProfit = Order::where('status', 'completed')->whereBetween('created_at', $range)->sum('profit');

        $totalUsers = User::where('role', 'user')->count();
        $newUsers = User::where('role', 'user')->whereBetween('created_at', $range)->count();
        $totalOrders = Order::whereBetween('created_at', $range)->count();
        $pendingDeposits = \App\Models\Deposit::where('status', 'pending')->count();
        $recentOrders = Order::with('user')->latest()->take(5)->get();
        $pendingOrders = Order::where('status', 'pending')->count();

        $monthlyRevenue = Order::selectRaw('SUM(cost) as total, ' . $this->getDateFormat('%Y-%m') . ' as month')
            ->where('status', 'completed')
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $orderStatus = Order::whereBetween('created_at', $range)
            ->selectRaw('count(*) as count, status')
            ->groupBy('status')
            ->get();

        $networkDistribution = Order::join('bundles', 'orders.bundle_id', '=', 'bundles.id')
            ->whereBetween('orders.created_at', $range)
            ->selectRaw('count(orders.id) as count, bundles.network')
            ->groupBy('bundles.network')
            ->get();

        $velocity = Order::whereBetween('created_at', $range)
            ->selectRaw('count(*) as count, ' . $this->getDateFormat('%Y-%m-%d') . ' as date')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $revenueGrowth = 0;
        $usersGrowth = 0;

        $roleFilter = $request->input('role');
        $usersQuery = User::query()
            ->withCount([
                'orders as total_orders',
                'orders as completed_orders' => function ($query) {
                    $query->where('status', 'completed');
                }
            ])
            ->withSum([
                'orders as total_spent' => function ($query) {
                    $query->where('status', 'completed');
                }
            ], 'cost')
            ->withSum('commissions as total_commission_earned', 'amount');

        if ($roleFilter && $roleFilter !== 'all') {
            $usersQuery->where('role', $roleFilter);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $usersQuery->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%")
                    ->orWhere('phone', 'like', "%$search%");
            });
        }

        $resellers = $usersQuery->latest()->paginate($request->input('per_page', 10))->withQueryString();

        return view('admin.analytics.index', compact(
            'totalRevenue',
            'totalProfit',
            'totalUsers',
            'newUsers',
            'totalOrders',
            'pendingDeposits',
            'pendingOrders',
            'recentOrders',
            'resellers',
            'monthlyRevenue',
            'orderStatus',
            'networkDistribution',
            'velocity',
            'revenueGrowth',
            'usersGrowth'
        ));
    }

    public function getSalesReport(Request $request)
    {
        $startDate = $request->input('start_date', now()->subDays(30)->toDateString());
        $endDate = $request->input('end_date', now()->toDateString());
        $range = [$startDate . ' 00:00:00', $endDate . ' 23:59:59'];

        $orders = Order::with(['user', 'bundle'])
            ->where('status', 'completed')
            ->whereBetween('created_at', $range)
            ->get();

        $summary = [
            'total_sales' => $orders->sum('cost'),
            'total_cost' => $orders->sum('cost_price'),
            'total_profit' => $orders->sum('profit'),
            'order_count' => $orders->count(),
        ];

        return response()->json([
            'summary' => $summary,
            'orders' => $orders
        ]);
    }

    public function getApiProviders()
    {
        return \App\Models\ApiProvider::latest()->get();
    }

    public function storeApiProvider(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'base_url' => 'required|url',
            'request_method' => 'nullable|in:GET,POST,PUT',
            'is_active' => 'required|boolean'
        ]);

        $provider = \App\Models\ApiProvider::create($data);
        return response()->json($provider, 201);
    }

    public function updateApiProvider(Request $request, $id)
    {
        $provider = \App\Models\ApiProvider::findOrFail($id);
        $data = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'base_url' => 'sometimes|required|url',
            'is_active' => 'sometimes|required|boolean'
        ]);

        $provider->update($data);
        return response()->json($provider);
    }

    public function deleteApiProvider($id)
    {
        $provider = \App\Models\ApiProvider::findOrFail($id);
        $provider->delete();
        return response()->json(['message' => 'Provider deleted']);
    }

    public function getApiLogs()
    {
        return \App\Models\ApiLog::with('provider')->latest()->paginate(50);
    }

    public function getDataIntegration()
    {
        $settings = DB::table('data_integration_settings')->first();
        if (!$settings) {
            return response()->json(['is_active' => false]);
        }
        return response()->json($settings);
    }

    public function updateDataIntegration(Request $request)
    {
        $validated = $request->validate([
            'base_url' => 'nullable|url',
            'api_key' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        DB::table('data_integration_settings')->updateOrInsert(
            ['id' => 1],
            array_merge($validated, ['updated_at' => now()])
        );

        return response()->json(['message' => 'Settings updated successfully']);
    }

    public function testDataIntegrationConnection(Request $request)
    {
        $settings = DB::table('data_integration_settings')->first();

        if (!$settings || !$settings->base_url) {
            return response()->json(['success' => false, 'message' => 'No base URL configured'], 422);
        }

        try {
            // We use a simple GET request to the base URL to test connectivity
            // In a real scenario, this might hit a specific /health or /test endpoint
            $response = \Illuminate\Support\Facades\Http::withHeaders([
                'Authorization' => 'Bearer ' . $settings->api_key,
                'Accept' => 'application/json',
                'X-Requested-With' => 'CloudTech-Integration'
            ])->timeout(10)->get($settings->base_url);

            $success = $response->successful();
            $status = $response->status();
            if ($status === 401) {
                $message = 'Authentication failed (401). Please check your API key.';
            } elseif ($status === 404) {
                $message = 'Endpoint not found (404). Check the base URL.';
            } else {
                $message = $success ? 'Connection successful! Status: ' . $status : 'Connection failed. Status: ' . $status;
            }

            DB::table('data_integration_settings')->where('id', $settings->id)->update([
                'last_tested_at' => now(),
                'test_status' => $success ? 'success' : 'failed',
                'test_message' => $message
            ]);

            return response()->json(['success' => $success, 'message' => $message]);
        } catch (\Exception $e) {
            DB::table('data_integration_settings')->where('id', $settings->id)->update([
                'last_tested_at' => now(),
                'test_status' => 'failed',
                'test_message' => $e->getMessage()
            ]);

            return response()->json(['success' => false, 'message' => 'Test failed: ' . $e->getMessage()], 500);
        }
    }

    public function getDashboardStats(Request $request)
    {
        $todayRange = [now()->startOfDay(), now()->endOfDay()];
        $todayOrders = Order::whereBetween('created_at', $todayRange)->count();
        $todayRevenue = Order::where('status', 'completed')->whereBetween('created_at', $todayRange)->sum('cost');

        return response()->json([
            'today_orders' => $todayOrders,
            'today_revenue' => $todayRevenue,
        ]);
    }

    public function getAgentStats(Request $request)
    {
        $agents = User::whereIn('role', ['agent', 'super_agent', 'dealer'])
            ->withCount('orders')
            ->orderBy('orders_count', 'desc')
            ->paginate(20);

        return response()->json($agents);
    }

    public function getLoginActivities(Request $request)
    {
        return \App\Models\LoginActivity::with('user')->latest()->paginate(5);
    }

    public function deleteOldOrders(Request $request)
    {
        $days = $request->input('days', 30);
        $count = Order::where('created_at', '<', now()->subDays($days))->delete();
        return response()->json(['message' => "Deleted $count old orders."]);
    }

    public function adjustWallet(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $type = $request->input('type');
        $amount = (float) $request->input('amount');

        if ($type === 'credit') {
            $user->wallet_balance += $amount;
        } else {
            $user->wallet_balance -= $amount;
        }
        $user->save();

        return response()->json(['message' => 'Wallet adjusted', 'new_balance' => $user->wallet_balance]);
    }

    public function getTransactions(Request $request)
    {
        $transactions = Transaction::with('user')->latest()->paginate(10);
        return view('admin.transactions.index', compact('transactions'));
    }

    public function getInvoices(Request $request)
    {
        $transactions = Transaction::with('user')->latest()->paginate(10);
        return view('admin.invoices.index', compact('transactions'));
    }

    public function downloadInvoice($id)
    {
        $transaction = Transaction::findOrFail($id);
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('invoices.transaction', compact('transaction'));
        return $pdf->download('invoice-' . $transaction->reference . '.pdf');
    }

    public function exportTransactions(Request $request)
    {
        // Simple CSV export logic
        return response()->stream(function () {
            echo "ID,Reference,Amount,Status\n";
        }, 200, ["Content-type" => "text/csv"]);
    }

    public function profit(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfDay()->toDateString());
        $endDate = $request->input('end_date', now()->endOfDay()->toDateString());
        $range = [$startDate . ' 00:00:00', $endDate . ' 23:59:59'];

        $networkProfits = Order::join('bundles', 'orders.bundle_id', '=', 'bundles.id')
            ->where('orders.status', 'completed')
            ->whereBetween('orders.created_at', $range)
            ->selectRaw('bundles.network, SUM(orders.profit) as total_profit, COUNT(orders.id) as order_count')
            ->groupBy('bundles.network')
            ->get();

        $userProfits = User::select('users.id', 'users.name', 'users.email')
            ->join('orders', 'users.id', '=', 'orders.user_id')
            ->where('orders.status', 'completed')
            ->whereBetween('orders.created_at', $range)
            ->selectRaw('SUM(orders.profit) as total_profit, COUNT(orders.id) as order_count')
            ->groupBy('users.id', 'users.name', 'users.email')
            ->orderBy('total_profit', 'desc')
            ->take(20)
            ->get();

        $totalProfitInRange = Order::where('status', 'completed')
            ->whereBetween('created_at', $range)
            ->sum('profit');

        return view('admin.profit.index', compact(
            'networkProfits',
            'userProfits',
            'totalProfitInRange',
            'startDate',
            'endDate'
        ));
    }
}
