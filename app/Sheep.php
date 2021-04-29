<?php

namespace App;

use Illuminate\Support\Facades\DB;

class Sheep
{
	const NumberToReproduce = 2;
	const MinPaddock = 1;
	const MaxPaddock = 4;

	const ActionAdd = 'add';
	const ActionSleep = 'sleep';

	public static function add($paddock)
	{
		$id = false;
		if ( abs($paddock) > 0 ) {
			$id = DB::table('sheep')->insertGetId(['paddock' => $paddock]);

			Log::write(Sheep::ActionAdd);
		}

		return $id;
	}

	public static function isPaddockEmpty()
	{
        $oSheep = DB::table('sheep')->latest()->first();

		return empty($oSheep->id);
	}

    //Reset table sheep and history
	public static function reset()
	{
		DB::table('sheep')->truncate();
		DB::table('history')->truncate();
	}

	// They just sleep
	public static function sleepOne($paddock = false)
	{
		if ( abs($paddock) > 0 ) {
            $oSheep = DB::table('sheep')->where([['paddock', '=', $paddock], ['active', '=', '1']])->first();
		} else {
            $oSheep = DB::table('sheep')
				->select('id', DB::raw('COUNT(id) as my'))
				->where('active', '1')
				->groupBy('paddock')
				->havingRaw('COUNT(id) > 1')->inRandomOrder()->first();
		}

		if ( !empty($oSheep->id) ) {
			DB::table('sheep')->where('id', $oSheep->id)->update(['active' => 0]);
			Log::write(Sheep::ActionSleep);
		}

		return empty($oSheep->id) ? 0 : $oSheep->id;

	}

	// If in any corral there is less than one left, add from the most populated
	public static function checkPaddock()
	{
		$padList = [];

		for ( $i = Sheep::MinPaddock; $i <= Sheep::MaxPaddock; $i++ ) {
			$padList[$i] = DB::table('sheep')->where([['paddock', '=', $i], ['active', '=', '1']])->count();
		}

		$max = array_search(max($padList), $padList);
		$min = array_search(min($padList), $padList);

		$total = min($padList);

		if ( $min != $max && $total === 1 ) {
			$id  = Sheep::move($max, $min);
			$msg = ['id' => $id, 'from' => $max, 'to' => $min];
		} else {
			$msg = ['none'];
		}

		return $msg;
	}

	public static function move($from, $to)
	{
		$oSheep = DB::table('sheep')->where([['paddock', '=', $from], ['active', '=', '1']])->latest()->first();
		DB::table('sheep')->where('id', $oSheep->id)->update(['paddock' => $to]);
		return $oSheep->id;
	}
}
