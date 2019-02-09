@section('js')
    <script type="text/javascript">
        $(document).ready(function() {
            var user_level = "{{$user->level}}";
            if ( user_level == 1 ) {
                $('[data-toggle="tooltip"]').tooltip();
            }
            $('#tblEditavel tbody tr td.editavel').dblclick(function() {
                if ($('td > input').length > 0) {
                    return;
                }
                var valuesOriginal = $(this).text();
                var Elm = $('<input/>', {
                    type: 'text',
                    value: valuesOriginal
                });
                Elm.addClass('form-control');
                $(this).html(Elm.bind('blur keydown', function(e) {
                    var keyCode = e.which;
                    var values = $(this).val();
                    var idmeja = $(this).parents('tr').children().first().text();
                    if (keyCode == 13 || keyCode == 0 && values != '' && values != valuesOriginal) {
                        var objeto = $(this);
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                        $.ajax({
                            type: "post",
                            url: "{{ url('/meja/updateable') }}",
                            data: {
                                id: idmeja,
                                keys: $(this).parent().attr('title'),
                                vals: values
                            }, 
                            success: function(result) {
                                objeto.parent().html(values);
                            }
                        })
                    } else if (keyCode == 27 || e.type == 'blur'){
                        $(this).parent().html(valuesOriginal);
                    }
                }));
                $(this).children().select();
            });

            $(document).ready(function() {
            $('#tblEditavel tbody tr td.editavelSelect').dblclick(function() {
                if ($('td > input').length > 0) {
                    return;
                }
                var valuesOriginal = $(this).text();
                var Elm = $('<select/>');
                Elm.addClass('form-control');
                Elm.append($('<option/>', { value: '1', text : 'Siap' } ));
                Elm.append($('<option/>', { value: '0', text : 'Dipakai' } ));
                $(this).html(Elm.bind('blur keydown', function(e) {
                    var keyCode = e.which;
                    var values = $(this).val();
                    var texts = ( values == 1 ? 'Siap' : 'Dipakai');
                    var idmeja = $(this).parents('tr').children().first().text();
                    if (keyCode == 13 || keyCode == 0 && values != '' && values != valuesOriginal) {
                        var objeto = $(this);
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                        $.ajax({
                            type: "post",
                            url: "{{ url('/meja/updateable') }}",
                            data: {
                                id: idmeja,
                                keys: $(this).parent().attr('title'),
                                vals: values
                            }, 
                            success: function(result) {
                                objeto.parent().html(texts);
                            }
                        })
                    } else if (keyCode == 27 || e.type == 'blur'){
                        $(this).parent().html(valuesOriginal);
                    }
                }));
                $(this).children().select();
            });

        })

        })
    </script>
@endsection

@section('css')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card mt-4">
                <div class="card-header">
                    <form method="POST" action="{{ route('meja.store') }}">
                        @csrf
                        <div class="row">
                            <div class="col-6 text-left align-self-center">List Meja</div>
                            <div class="col-6 text-right">
                                @if( $user->level == '1' )
                                <input type="hidden" name="status" value="1"><button id="sub" type="submit" class="btn btn-primary btn-sm">Tambah Meja</button>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>

                <div class="card-body">
                    @if(session()->get('delete'))
                        <div class="alert alert-success">
                        {{ session()->get('delete') }}  
                        </div>
                    @endif
                    
                    <table id="tblEditavel" class="table table-bordered  table-responsive-sm" data-toggle="tooltip" title="{{ ( $user->level == 1 ? 'Double klik untuk Edit' : '' ) }}">
                        <thead>
                            <tr>
                                <td>ID</td>
                                <td width="40%">Nomor</td>
                                @if( $user->level == '1' )
                                <td width="20%">Status</td>
                                <td class="text-center">Hapus</td>
                                @elseif( $user->level == '2')
                                <td width="40%">Pesanan</td>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($mejas as $meja)
                                <tr>
                                    <td>{{ $meja->id }}</td>
                                    <td title="nomor" class="{{ ( $user->level == 1? 'editavel' : '' ) }}">{{ $meja->nomor }}</td>
                                    @if( $user->level == '1' )
                                    <td title="status" class="editavelSelect">{{ ( $meja->status == 1 ? 'Siap' : 'Dipakai') }}</td>
                                    <td>
                                        <form action="{{ route('meja.destroy', $meja->id)}}" method="post">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-block btn-sm" type="submit">Hapus</button>
                                        </form>
                                    </td>
                                    @elseif( $user->level == '2')
                                    <td><a href="{{ url('/pesan/'.$meja->id) }}"><button class="btn btn-success btn-block btn-sm add-menu" type="submit">Buat pesanan</button></td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
