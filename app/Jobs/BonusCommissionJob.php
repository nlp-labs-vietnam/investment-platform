<?php

namespace App\Jobs;

use App\Models\Investment;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Facades\App\Services\BasicService;

class BonusCommissionJob implements ShouldQueue
{
    use Queueable;

    protected $user;
    protected $amount;
    protected $commissionType;
    protected $investment;

    public function __construct(User $user, $amount, $commissionType, Investment $investment)
    {
        $this->user = $user;
        $this->commissionType = $commissionType;
        $this->amount = $amount;
        $this->investment = $investment;
    }


    public function handle(): void
    {
        BasicService::setBonus($this->user, $this->amount, $this->commissionType, $this->investment);
    }
}
