<?php

namespace App\Console\Commands;
use App\Jobs\BonusCommissionJob;
use App\Models\Rank;
use Facades\App\Services\BasicService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UpdateBadgeCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'badge:cron';

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

    public function handle()
    {
        \App\Models\User::where('status', 1)
            ->where('last_login', '>', now()->subHours(6))
            ->chunk(50, function ($users) {
                foreach ($users as $user) {

                    $interestBalance = $user->total_interest_balance;
                    $investBalance = $user->total_invest;
                    $depositBalance = $user->total_deposit;

                    $userPreviousRank = json_decode($user->all_ranks);

                    $badges = Rank::where([
                        ['min_invest', '<=', $investBalance],
                        ['min_deposit', '<=', $depositBalance],
                        ['min_earning', '<=', $interestBalance]
                    ])->where('status', 1)->get();


                    if (count($badges) > 0) {
                        $allBadges = [];
                        foreach ($badges as $badge) {
                            $user->last_level = $badge->id;
                            $allBadges[] = $badge->id;
                        }

                        $recentUpdateBadges = array_diff($allBadges, $userPreviousRank ?? []);
                        $basic = basicControl();

                        if (count($recentUpdateBadges) > 0 && $basic->is_rank_bonus) {
                            foreach ($recentUpdateBadges as $recentBadge) {
                                $singleRank = DB::table('ranks')->select('bonus', 'rank_level')->find($recentBadge);

                                $user->total_rank_bonous += (int)$singleRank->bonus;
                                $amount = $singleRank->bonus;
                                $trx = strRandom();
                                $remarks = 'You got ' . $basic->currency_symbol . getAmount($singleRank->bonus) . ' ' . $singleRank->rank_level . ' ranking bonus';
                                BasicService::makeTransaction($user, $amount, 0, $trx_type = '+', 'interest_balance', $trx, $remarks);

                                if ($basic->rank_commission == 1) {
                                    BasicService::setBonus($user, getAmount($singleRank->bonus), $type = 'rank_commission');
                                }
                            }
                        }

                        $user->all_ranks = json_encode($allBadges);
                        $user->save();
                    }
                }
            });
    }


}
