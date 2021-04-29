<?php
namespace App;

use Illuminate\Support\Facades\DB;

class Log
{
	public static function write($action)
	{
		DB::table('history')->insert(['action' => $action, 'create_at' => time()]);
	}
}