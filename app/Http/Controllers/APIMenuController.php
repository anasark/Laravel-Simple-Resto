<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Menu;
use App\Meja;
use App\Pesanan;
use App\Activitys;
use Auth;

class APIMenuController extends Controller
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
        $menu = Menu::All();
 
        return response()->json([
            'success' => true,
            'data' => $menu,
        ]);
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

        return response()->json([
            'success' => true,
            'message' => 'Menu berhasil ditambahan'
        ], 500);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $menu = Menu::find($id);

        if (!$menu) {
            return response()->json([
                'success' => false,
                'message' => 'Menu dengan id ' . $id . ' tidak ada'
            ], 400);
        }
 
        return response()->json([
            'success' => true,
            'data' => $menu,
        ]);
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
        $menu = Menu::find($id);
 
        if (!$menu) {
            return response()->json([
                'success' => false,
                'message' => 'Menu dengan id ' . $id . ' tidak ada'
            ], 400);
        }
 
        $updated = $menu->fill($request->all())->save();
 
        if ($updated)
            return response()->json([
                'success' => true
            ]);
        else
            return response()->json([
                'success' => false,
                'message' => 'Menu berhasil diubah'
            ], 500);

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

        if (!$menu) {
            return response()->json([
                'success' => false,
                'message' => 'Menu dengan id ' . $id . ' tidak ada'
            ], 400);
        }
 
        if ($menu->delete()) {
            return response()->json([
                'success' => true
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Menu berhasil dihapus'
            ], 500);
        }
    }
}
