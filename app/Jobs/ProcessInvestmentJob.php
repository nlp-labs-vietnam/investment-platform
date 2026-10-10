<?php

namespace App\Jobs;

use App\Console\Commands\MainCron;
use App\Models\Investment;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ProcessInvestmentJob implements ShouldQueue
{
    use Queueable;

    protected $investmentId;
    protected $now;
    protected $basic;

    public function __construct($investId, $now, $basic)
    {
        $this->investmentId = $investId;
        $this->now = $now;
        $this->basic = $basic;
    }


    public function handle(): void
    {
        $investment = Investment::with(['user', 'property'])
            ->where('id', $this->investmentId)
            ->firstOrFail();

        MainCron::processInvestment($investment, $this->now, $this->basic);
    }
}
