<?php
/**
 * Created by PhpStorm.
 * User: Acer
 * Date: 2018/11/27
 * Time: 16:35
 */

namespace App\Components;


use App\Models\UserCreditRecord;
use App\User;

class UserCreditManager
{
	public function changeCredit(User $user, array $data)
	{
		$credit = $user->credit;
		$data['user_id'] = $user->id;
		$record = new UserCreditRecord($data);
		$credit->credit -= $record->amount;
		$record->balance = $credit->credit;
		$credit->save();
		$record->save();
	}
}