<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Meja;
use App\Pesanan;
use App\EntranceLog;
use App\Activitys;
use Auth;
use DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $pesanans = Pesanan::where('status', 1)->get();

        return view('home', compact( 'pesanans' ));
    }

    public function activity( $id = '' )
    {
        if( Auth::user()->id == '1' ) {
            if ( $id ) {
                $login = EntranceLog::orderBy('loged_in_at', 'desc')->where('user_id', '=', $id)->get();
                $aktivitas = Activitys::orderBy('time_at', 'desc')->where('user_id', '=',$id)->get();
            } else {
                $users = User::where('level', '!=', '1')->get();
                return view('user', compact('users'));
            }
        } else {
            $id = Auth::user()->id;
            $login = EntranceLog::orderBy('loged_in_at', 'desc')->where('user_id', '=', $id)->get();
            $aktivitas = Activitys::orderBy('time_at', 'desc')->where('user_id', '=',$id)->get();
        }
        // dd($login, $aktivitas, $dates);
        return view('aktivitas', compact('login', 'aktivitas'));
    }
}
