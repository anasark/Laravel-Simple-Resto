<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Menu;
use Auth;

class MenuController extends Controller
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
        $menus = Menu::where('status', '1')->get();

        return view('admin.list-menu', compact('menus'));
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
        $id = Menu::select('id')->orderBy('created_at', 'desc')->first();
        $id = ( $id ? $id->id + 1 : 1 );

        $request->validate([
            'nama'=>'required',
            'harga'=> 'required|integer',
            'status' => 'required|integer'
        ]); 
        $menu = new Menu([
            'id' => $id,
            'nama' => $request->get('nama'),
            'harga'=> $request->get('harga'),
            'status'=> $request->get('status')
        ]);
        $menu->save();
        
        return redirect('/menu')->with('success', 'Menu baru telah ditambahkan');
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
        Menu::where('id', $request->id)
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
        $menu = Menu::find($id);
        $menu->delete();

        return redirect('/menu')->with('delete', 'Menu berhasil di Hapus');
    }
}
