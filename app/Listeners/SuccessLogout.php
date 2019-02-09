<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Logout;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\EntranceLog;
use Auth;

class SuccessLogout
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  Logout  $event
     * @return void
     */
    public function handle(Logout $event)
    {
        date_default_timezone_set('Asia/Jakarta');
        $EntranceLog = EntranceLog::where('loged_in_at', '>=', date('Y-m-d H:i:s', time()-86400))
                          ->whereNull('loged_out_at')
                          ->where('user_id', '=', Auth::user()->id)
                          ->orderBy('loged_in_at', 'desc')->first();
        if($EntranceLog) {
            $EntranceLog->loged_out_at = date('Y-m-d H:i:s');
            $hours = strtotime($EntranceLog->loged_out_at) - strtotime($EntranceLog->loged_in_at);
            $hours/= 3600;
            $hours = (double)$hours;
            $EntranceLog->login_hours = $hours;
            $EntranceLog->save();
        }
    }
}
