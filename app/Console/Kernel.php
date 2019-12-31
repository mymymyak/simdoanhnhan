<?php

namespace App\Console;

use App\Console\Commands\CheckSoldNumber;
use App\Console\Commands\Inspire;
use App\Console\Commands\RemoveSoldNumber;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel {

	/**
	 * The Artisan commands provided by your application.
	 *
	 * @var array
	 */
	protected $commands = [
		Inspire::class,
		//CheckSoldNumber::class,
		//RemoveSoldNumber::class,
	];

	/**
	 * Define the application's command schedule.
	 *
	 * @param \Illuminate\Console\Scheduling\Schedule $schedule
	 *
	 * @return void
	 */
	protected function schedule (Schedule $schedule) {
		$schedule->command('inspire')->hourly();
		//$schedule->command("checkSoldNumber")->everyMinute();
		//$schedule->command("removeSoldNumber")->everyMinute();
	}

	/**
	 * Register the Closure based commands for the application.
	 *
	 * @return void
	 */
	protected function commands () {
		require base_path('routes/console.php');
	}
}
