@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">

                    <div class="row">
                        <div class="col-12 col-sm-6">
                            <a class="btn btn-primary btn-block" href="{{ url('/menu') }}">{{ ( Auth::user()->level == 1 ? 'Kelola Menu' : 'Daftar Menu yang Tersedia' ) }}</a>
                        </div>
                        <div class="col-12 col-sm-6">
                            <a class="btn btn-primary btn-block" href="{{ url('/meja') }}">{{ ( Auth::user()->level == 1 ? 'Kelola Meja' : ( Auth::user()->level == 2 ? 'Buat Pesanan' : 'Meja Kosong' ) ) }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if( count($pesanans) > 0 )
        <div class="col-md-8 mt-4">
            <div class="card">
                <div class="card-header">Status pesanan</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <div class="row">
                        <table id="tblEditavel" class="table table-bordered  table-responsive-sm">
                            <thead>
                                <tr>
                                    <td width="30%">Nomor Pesanan</td>
                                    <td width="20%">Nomor Meja</td>
                                    <td width="20%">Status</td>
                                    <td width="20%">Lihat</td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pesanans as $pesanan)
                                    <tr>
                                        <td title="nomor" class="align-middle">{{ $pesanan->nomor }}</td>
                                        <td title="nomor" class="align-middle">{{ $pesanan->meja }}</td>
                                        <td title="status" class="align-middle">{{ ( $pesanan->status == 1 ? 'Belum dibayar' : 'Sudah dibayar') }}</td>
                                        <td title="lihat" class=""><a href="{{ route('pesanan.show',$pesanan->id)}}" class="btn btn-success btn-sm btn btn-block">lihat</a></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
