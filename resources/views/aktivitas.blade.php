@section('js')

@endsection

@section('css')

@endsection

@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card mt-4">
                <div class="card-header">Altivitas {{ Auth::user()->name }}</div>

                <div class="card-body">
                    
                    <table id="tblEditavel" class="table table-bordered  table-responsive-sm">
                        <tbody>
                            @php( $f_date = '' )
                            @foreach($login as $date)
                                @php($date_time = $date->loged_in_at)
                                @php($new_date = date("d-m-Y",strtotime($date_time)))

                                @if( $f_date != $new_date )
                                    @php( $f_date = $new_date )
                                    <tr style="background-color: cadetblue;color: white;">
                                        <td>tanggal</td>
                                        <td colspan="2">{{ $new_date }}</td>
                                    </tr>
                                    <tr style="background: #5f9ea080;" >
                                        <td>Login</td>
                                        <td>Logout</td>
                                        <td>Lama (jam)</td>
                                    </tr>
                                    @foreach($login as $log)
                                        @php($date_times = $log->loged_in_at)
                                        @php($new_dates = date("d-m-Y",strtotime($date_times)))
                                        @if( $f_date == $new_dates )
                                            <tr>
                                                <td>{{ date("d-m-Y H:i:s",strtotime($log->loged_in_at)) }}</td>
                                                <td>{{ ( $log->loged_out_at ? date("d-m-Y H:i:s",strtotime($log->loged_out_at)) : '' ) }}</td>
                                                <td>{{ $log->login_hours }}</td>
                                            </tr>
                                        @endif
                                    @endforeach
                                    <tr style="background: #5f9ea080;">
                                        <td colspan="2">Aktivitas</td>
                                        <td>Waktu</td>
                                    </tr>
                                    @foreach($aktivitas as $aktiv)
                                        @php($date_timess = $aktiv->time_at)
                                        @php($new_datess = date("d-m-Y",strtotime($date_timess)))
                                        @if( $f_date == $new_datess )
                                            <tr>
                                                <td colspan="2">{{ $aktiv->activity }}</td>
                                                <td>{{ date("d-m-Y H:i:s",strtotime($aktiv->time_at)) }}</td>
                                            </tr>
                                        @endif
                                    @endforeach
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
