<?php

namespace App\Console;

use App\Console\Commands\BinanceCron;
use App\Console\Commands\BlockIoIPN;
use App\Console\Commands\MainCron;
use App\Console\Commands\PayoutCryptoCurrencyUpdateCron;
use App\Console\Commands\PayoutCurrencyUpdateCron;
use App\Console\Commands\PruneStaleFileCache;
use App\Console\Commands\UpdateBadgeCron;
use App\Console\Commands\WarmCacheCommand;
use App\Jobs\UpdatePropertyJob;
use App\Models\Deposit;
use App\Models\Gateway;
use App\Models\Payout;
use App\Models\PayoutMethod;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{


    protected $commands = [
        BlockIoIPN::class,
        UpdateBadgeCron::class,
        MainCron::class,
        BinanceCron::class,
        PayoutCurrencyUpdateCron::class,
        PayoutCryptoCurrencyUpdateCron::class,
        WarmCacheCommand::class,
        PruneStaleFileCache::class,
    ];

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {

        $basicControl = basicControl();

        // ── Cache management ──────────────────────────────────────────────
        // Warm critical cache keys every 30 min (aligned with TTL=30min keys in helpers.php)
        // Runs just before keys expire so users never hit a cold-cache request.
        $schedule->command('cache:warm')->everyThirtyMinutes();

        // Prune stale file-cache entries once per day (off-peak: 03:00)
        $schedule->command('cache:prune-stale-tags')
                 ->dailyAt('03:00')
                 ->withoutOverlapping();

        $schedule->command('main:cron')->daily();
        $schedule->command('badge:cron')->daily();

        $schedule->job(new UpdatePropertyJob)->daily();

        $binance = PayoutMethod::where('code', 'binance')->where('is_active', 1)->first();
        if ($binance) {
            $schedule->command('binance-payout-status:update')->everyFiveMinutes();
        }

        $blockIoGateway = Gateway::where(['code' => 'blockio', 'status' => 1])->count();
        if ($blockIoGateway == 1) {
            $schedule->command('blockIo:ipn')->everyThirtyMinutes();
        }

        $schedule->command('model:prune', [
            '--model' => [Deposit::class, Payout::class],
        ])->daily();

        if ($basicControl->currency_layer_auto_update == 1) {
            $schedule->command('fiat-currency:update')->{$basicControl->currency_layer_auto_update_at}();
            $schedule->command('payout-currency:update')
                ->{$basicControl->currency_layer_auto_update_at}();
        }
        if ($basicControl->coin_market_cap_auto_update == 1) {
            $schedule->command('crypto-currency:update')->{$basicControl->coin_market_cap_auto_update_at}();
            $schedule->command('payout-crypto-currency:update')->{$basicControl->coin_market_cap_auto_update_at}();
        }



    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
