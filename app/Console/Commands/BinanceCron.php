<?php

namespace App\Console\Commands;

use App\Models\Payout;
use App\Models\Transaction;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BinanceCron extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'binance-payout-status:update';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'A command to binance payout status update.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return int
	 */
	public function handle()
	{
		$methodObj = 'App\\Services\\Payout\\binance\\Card';
		$data = $methodObj::getStatus();
		if ($data) {
			$apiResponses = collect($data);
			$binaceIds = $apiResponses->pluck('id');
			$payouts = Payout::whereIn('response_id', $binaceIds)->where('status', 1)->get();
			foreach ($payouts as $payout) {
				foreach ($apiResponses as $apiResponse) {
					if ($payout->response_id == $apiResponse->id) {
						$status = $apiResponse->status;
						if ($status == 6) {
							$transaction = new Transaction();
							$transaction->amount = $payout->amount;
							$transaction->charge = $payout->charge;
							$transaction->currency_id = $payout->currency_id;
							$payout->transactional()->save($transaction);
							$payout->status = 2;
							$payout->save();
							$binance = new BinanceCron();
							$binance->userNotify($payout, 1);

						} elseif ($status == 1 || $status == 3 || $status == 5) {
							updateWallet($payout->user_id, $payout->transfer_amount, 1);
							$payout->status = 6;
							$payout->save();
							$binance = new BinanceCron();
							$binance->userNotify($payout, 0);
						}
						break;
					}
				}
			}
		}
		return 0;
	}

	public function userNotify($payout, $type = 1)
	{
		$receivedUser = $payout->user;
		$params = [
			'sender' => $receivedUser->name,
			'amount' => getAmount($payout->amount),
			'currency' => optional($payout->currency)->code,
			'transaction' => $payout->utr,
		];

		$action = [
			"link" => route('user.payout.history'),
			"icon" => "fa fa-money-bill-alt text-white"
		];
		$firebaseAction = "#";
		if ($type == 1) {
			$template = 'PAYOUT_CONFIRM';
		} else {
			$template = 'PAYOUT_FAIL';
		}

		$this->sendMailSms($receivedUser, $template, $params);
		$this->userPushNotification($receivedUser, $template, $params, $action);
		return 0;
	}
}
