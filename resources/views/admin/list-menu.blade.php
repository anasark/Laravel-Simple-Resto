@section('js')
    <script type="text/javascript">
        $(document).ready(function() {
            $('[data-toggle="tooltip"]').tooltip();

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
                    var idmenu = $(this).parents('tr').children().first().text();
                    if (keyCode == 13 || keyCode == 0 && values != '' && values != valuesOriginal) {
                        var objeto = $(this);
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                        $.ajax({
                            type: "post",
                            url: "{{ url('/menu/updateable') }}",
                            data: {
                                id: idmenu,
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
                Elm.append($('<option/>', { value: '0', text : 'Tidak Siap' } ));
                $(this).html(Elm.bind('blur keydown', function(e) {
                    var keyCode = e.which;
                    var values = $(this).val();
                    var texts = ( values == 1 ? 'Siap' : 'Tidak Siap');
                    var idmenu = $(this).parents('tr').children().first().text();
                    if (keyCode == 13 || keyCode == 0 && values != '' && values != valuesOriginal) {
                        var objeto = $(this);
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                        $.ajax({
                            type: "post",
                            url: "{{ url('/menu/updateable') }}",
                            data: {
                                id: idmenu,
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
            @if ( Auth::user()->level == 1 )
            <div class="card">
                <div class="card-header">Tambah Menu</div>

                <div class="card-body">
                    @if(session()->get('success'))
                        <div class="alert alert-success">
                        {{ session()->get('success') }}  
                        </div>
                    @endif

                    <form method="POST" action="{{ route('menu.store') }}">
          
                        @csrf

                        <div class="row">
                            <div class="col-sm-12 col-lg-4">
                                <input type="text" class="form-control{{ $errors->has('nama') ? ' is-invalid' : '' }}" name="nama" required placeholder="Nama menu..." value="{{ old('nama') }}" >
                                @if ($errors->has('nama'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('nama') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="col-sm-12 col-lg-3">
                                <input type="number" class="form-control{{ $errors->has('harga') ? ' is-invalid' : '' }}" name="harga" required placeholder="Harga..." value="{{ old('harga') }}" >
                                @if ($errors->has('harga'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('harga') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="col-sm-12 col-lg-3">
                                <select class="form-control{{ $errors->has('status') ? ' is-invalid' : '' }}" name="status" required>
                                    <option selected disabled>- Status -</option>
                                    <option value="1">Siap</option>
                                    <option value="2">Tidak Siap</option>
                                </select>
                                 @if ($errors->has('status'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('status') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="col-sm-12  col-lg-2">
                                <button id="sub" type="submit" class="btn btn-primary btn-block">Input</button>
                            </div>
                        </div>                        

                    </form>
                </div>
            </div>
            @endif
            <div class="card mt-4">
                <div class="card-header">List Menu</div>

                <div class="card-body">
                    @if(session()->get('delete'))
                        <div class="alert alert-success">
                        {{ session()->get('delete') }}  
                        </div>
                    @endif
                    <table id="tblEditavel" class="table table-bordered  table-responsive-sm" data-toggle="tooltip" title="{{ ( Auth::user()->level == 1 ? 'Double klik untuk Edit' : '' ) }}">
                        <thead>
                            <tr>
                                <td>ID</td>
                                <td width="40%">Nama</td>
                                <td width="20%">Harga</td>
                                <td width="20%">Status</td>
                                @if( Auth::user()->level == 1 )
                                <td class="text-center">Hapus</td>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($menus as $menu)
                                <tr>
                                    <td>{{ $menu->id }}</td>
                                    <td title="nama" class="{{ ( Auth::user()->level == 1 ? 'editavel' : '' ) }}">{{ $menu->nama }}</td>
                                    <td title="harga" class="{{ ( Auth::user()->level == 1 ? 'editavel' : '' ) }}">{{ $menu->harga }}</td>
                                    <td title="status" class="{{ ( Auth::user()->level == 1 ? 'editavelSelect' : '' ) }}">{{ ( $menu->status == 1 ? 'Siap' : 'Tidak Siap') }}</td>
                                    @if( Auth::user()->level == 1 )
                                    <td>
                                        <form action="{{ route('menu.destroy', $menu->id)}}" method="post">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-block btn-sm" type="submit">Hapus</button>
                                        </form>
                                    </td>
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
