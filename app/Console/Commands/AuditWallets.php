<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Transaction;
use App\Models\Setting;
use Illuminate\Support\Facades\Log;

class AuditWallets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'audit:wallets';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Audit all user wallet balances against transaction ledger entries';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting System Wallet & Ledger Audit...');
        
        $users = User::where('role', '!=', 'guest')->get();
        $discrepancyCount = 0;
        $totalDiscrepancyAmount = 0;
        $auditLogs = [];

        foreach ($users as $user) {
            /** @var \App\Models\User $user */
            $currentBalance = (float) $user->wallet_balance;
            
            // Calculate sum of all successful transactions
            $credits = Transaction::where('user_id', $user->id)
                ->where('type', 'credit')
                ->where('status', 'success')
                ->sum('amount');

            $debits = Transaction::where('user_id', $user->id)
                ->where('type', 'debit')
                ->where('status', 'success')
                ->sum('amount');

            $ledgerBalance = (float) ($credits - $debits);
            
            // Allow minor floating point rounding differences (e.g. 0.01)
            $difference = abs($currentBalance - $ledgerBalance);
            
            if ($difference > 0.01) {
                $discrepancyCount++;
                $totalDiscrepancyAmount += $difference;
                
                $message = "Discrepancy found for User ID {$user->id} ({$user->name}): Current Wallet GHS {$currentBalance}, Ledger Calculated GHS {$ledgerBalance}. Diff: GHS " . round($difference, 2);
                $this->warn($message);
                Log::warning("Ledger Audit Mismatch: " . $message);

                // Flag the user settings
                $settings = $user->settings ?? [];
                $settings['ledger_audit_failed'] = true;
                $settings['ledger_audit_mismatch'] = $currentBalance - $ledgerBalance;
                $settings['ledger_audit_checked_at'] = now()->toDateTimeString();
                $user->update(['settings' => $settings]);

                $auditLogs[] = [
                    'user_id' => $user->id,
                    'name' => $user->name,
                    'current_balance' => $currentBalance,
                    'ledger_balance' => $ledgerBalance,
                    'difference' => $currentBalance - $ledgerBalance,
                ];
            } else {
                // Clear any previous audit failures
                if (isset($user->settings['ledger_audit_failed'])) {
                    $settings = $user->settings;
                    unset($settings['ledger_audit_failed']);
                    unset($settings['ledger_audit_mismatch']);
                    unset($settings['ledger_audit_checked_at']);
                    $user->update(['settings' => $settings]);
                }
            }
        }

        // Record global audit settings state
        if ($discrepancyCount > 0) {
            Setting::updateOrCreate(
                ['key' => 'system_ledger_unbalanced'],
                ['value' => '1']
            );
            Setting::updateOrCreate(
                ['key' => 'system_ledger_mismatch_amount'],
                ['value' => (string)$totalDiscrepancyAmount]
            );
            Setting::updateOrCreate(
                ['key' => 'system_ledger_audit_logs'],
                ['value' => json_encode($auditLogs)]
            );
            Setting::updateOrCreate(
                ['key' => 'system_ledger_last_audit'],
                ['value' => now()->toDateTimeString()]
            );

            $this->error("Audit Finished with {$discrepancyCount} discrepancies! Total unbalanced: GHS " . round($totalDiscrepancyAmount, 2));
        } else {
            Setting::updateOrCreate(
                ['key' => 'system_ledger_unbalanced'],
                ['value' => '0']
            );
            Setting::updateOrCreate(
                ['key' => 'system_ledger_last_audit'],
                ['value' => now()->toDateTimeString()]
            );
            
            $this->info('Audit Finished successfully! All balances perfectly match the transaction ledger.');
        }

        return $discrepancyCount === 0 ? self::SUCCESS : self::FAILURE;
    }
}
