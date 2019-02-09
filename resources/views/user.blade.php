@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">User List</div>

                <div class="card-body">

                    <div class="row">
                        <table id="tblEditavel" class="table table-bordered  table-responsive-sm">
                            <thead>
                                <tr>
                                    <td width="20%">ID</td>
                                    <td width="20%">Nama</td>
                                    <td width="20%">Email</td>
                                    <td width="20%">Level</td>
                                    <td width="20%">Aktivitas</td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                    <tr>
                                        <td>{{ $user->id }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ ( $user->level == 2 ? 'Pelayan' : ( $user->level == 3 ? 'Kasir' : '' ) ) }}</td>
                                        <td><a href="{{ url('/aktivitas/'.$user->id) }}" class="btn btn-info btn-block btn-sm" style="color: white">Lihat</a></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
