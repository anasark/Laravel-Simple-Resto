<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Meja;
use Auth;

class MejaController extends Controller
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

        $user = Auth::user();
        $mejas = Meja::where('status', 1)->get();

        return view('meja', compact('mejas', 'user'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $id = Meja::select('id')->orderBy('created_at', 'desc')->first();
        $id = ( $id ? $id->id + 1 : 1 );

        $nomor = sprintf('%03d',$id);

        $request->validate([
            'status' => 'required|integer'
        ]); 
        $meja = new Meja([
            'id' => $id,
            'nomor'=> $nomor,
            'status'=> $request->get('status')
        ]);
        $meja->save();
        
        return redirect('/meja')->with('success', 'Meja baru telah ditambahkan');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        //
    }

    public function updateable(Request $request)
    {
        Meja::where('id', $request->id)
        ->update([
            $request->keys => $request->vals,
        ]);

        return response()->json( $request->id );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $meja = Meja::find($id);
        $meja->delete();

        return redirect('/meja')->with('delete', 'Meja berhasil di Hapus');
    }
}
