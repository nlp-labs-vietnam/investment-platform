<?php

namespace App\Console\Commands;

use App\Jobs\BonusCommissionJob;
use App\Jobs\ProcessInvestmentJob;
use Facades\App\Services\BasicService;
use Facades\App\Services\InvestmentService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MainCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'main:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    static public function handle()
    {
        $now = Carbon::now();
        $basic = basicControl();

        $startTime = microtime(true);
        $startMemory = memory_get_usage(true);

        DB::beginTransaction();
        try {
            DB::table('investments')
                ->join('properties', 'investments.property_id', '=', 'properties.id')
                ->where('properties.is_payment', 1)
                ->where('properties.status', 1)
                ->where('investments.status', 0)
                ->where('investments.is_active', 1)
                ->where('investments.invest_status', 1)
                ->where('investments.return_date', '<', $now)
                ->select('investments.*', 'properties.return_time', 'properties.return_time_type')
                ->orderBy('investments.id')
                ->chunk(500, function ($investments) use ($now, $basic) {
                    foreach ($investments as $investment) {
                        ProcessInvestmentJob::dispatch($investment->id, $now, $basic);
                    }
                });
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            info("Investment cron job failed: " . $e->getMessage());
        }
    }

    public static function processInvestment($investment, $now, $basic)
    {
        $returnTimeType = strtolower($investment->property?->return_time_type);
        $returnTime = (int)$investment->property?->return_time;

        $func = $returnTimeType === 'days' ? 'addDays' : ($returnTimeType === 'months' ? 'addMonths' : 'addYears');
        $nextReturnDate = $now->$func($returnTime);
        $lastReturnDate = $now;

        $amount = $investment->profit_type == 0 ? $investment->profit : $investment->net_profit;
        $records = [
            'return_date' => $nextReturnDate,
            'last_return_date' => $lastReturnDate,
        ];
        if ($investment->profit_type == 0) {
            $records['profit'] = $amount;
        } else {
            $records['net_profit'] = $amount;
        }

        $investment->update($records);
        InvestmentService::investmentProfitReturn($investment, $nextReturnDate, $lastReturnDate, $now, $basic, $amount);

        if ($basic->profit_commission == 1) {
            $user = $investment->user;
            BonusCommissionJob::dispatch($user, $amount, 'profit_commission', $investment);
        }
    }

}
