<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Menu;
use App\Meja;
use App\Pesanan;
use App\Activitys;
use Auth;

class PesananController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id = '')
    {
        if( !$id ) 
            return Redirect::back();

        $meja = Meja::select('nomor', 'id')->where('id', $id)->first();

        $menus = Menu::all();

        return view('pesanan', compact( 'meja', 'menus' ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $id = Pesanan::select('id')->orderBy('created_at', 'desc')->first();
        $id = ( $id ? $id->id + 1 : 1 );
        $urut = sprintf('%03d',$id);
        date_default_timezone_set('Asia/Jakarta');
        $nomor = 'ERP' . date("dmY") . '-' .$urut;
        
        $pesanan = array();

        for ($i=0; $i < count($request->id) ; $i++) { 
            $pesanan[$i]['id'] = $request->id[$i];
            $pesanan[$i]['nama'] = $request->nama[$i];
            $pesanan[$i]['harga'] = $request->harga[$i];
            $pesanan[$i]['jumlah'] = $request->jumlah[$i];
            $pesanan[$i]['total'] = $request->total[$i];
        }

        $meja = new Pesanan([
            'id' => $id,
            'nomor'=> $nomor,
            'meja' => $request->meja,
            'pesanan' => serialize( $pesanan ),
            'jumlah_total' => $request->jumlah_total,
            'status'=> 1,
            'user_id' => Auth::user()->id,
        ]);
        $meja->save();

        Meja::where('nomor', $request->meja)
        ->update([
            'status' => 0,
        ]);

        $activity = new Activitys([
            'user_id' => Auth::user()->id,
            'activity' => 'Membuat pesanan ' . $nomor,
            'time_at' => date('Y-m-d H:i:s'),
        ]);
        $activity->save();
        
        return redirect('/pesanan/'.$id)->with('success', 'Pesanan berhasil dibuat');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if( !$id ) 
            return redirect('/home');

        $menus = Menu::all();
        $pesanan = Pesanan::where('id', $id)->first();

        if( count($pesanan) == 0 ) 
            return redirect('/home');
        
        return view('pesanan.detail', compact( 'pesanan', 'menus' ));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if( $request->bayar == 'bayar' ){
            Pesanan::where('id', $id)
            ->update([
                'status' => 0
            ]);

            Meja::where('nomor', $request->meja)
            ->update([
                'status' => 1
            ]);

            $activity = new Activitys([
                'user_id' => Auth::user()->id,
                'activity' => 'Menutup pesanan ' . $request->nomor_pesanan,
                'time_at' => date('Y-m-d H:i:s'),
            ]);
            $activity->save();

            return redirect('/pesanan/'.$id)->with('success', 'Pesanan berhasil dibayar');

        } else {
            
            $pesanan = array();

            for ($i=0; $i < count($request->id) ; $i++) { 
                $pesanan[$i]['id'] = $request->id[$i];
                $pesanan[$i]['nama'] = $request->nama[$i];
                $pesanan[$i]['harga'] = $request->harga[$i];
                $pesanan[$i]['jumlah'] = $request->jumlah[$i];
                $pesanan[$i]['total'] = $request->total[$i];
            }

            Pesanan::where('id', $id)
            ->update([
                'pesanan' => serialize( $pesanan ),
                'jumlah_total' => $request->jumlah_total,
                'status'=> 1,
            ]);

            Meja::where('nomor', $request->meja)
            ->update([
                'status' => 0,
            ]);
        
            return redirect('/pesanan/'.$id)->with('success', 'Pesanan berhasil diperbarui');
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
