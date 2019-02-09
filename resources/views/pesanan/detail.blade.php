@php
    $menu_s = ( $pesanan ? unserialize($pesanan->pesanan) : '' );
@endphp

@section('js')
<script type="text/javascript">
$(document).ready(function() {
    update();
    kalkulasiTotal();
    
    $("input.qty-update").keydown(function () {
        if (!$(this).val() || (parseInt($(this).val()) <= 11 && parseInt($(this).val()) >= 0))
        $(this).data("old", $(this).val());
    });
    $("input.qty-update").keyup(function () {
    if (!$(this).val() || (parseInt($(this).val()) <= 11 && parseInt($(this).val()) >= 0))
    ;
    else
        $(this).val($(this).data("old"));
    });
    
    var no = 1;
    $('.addMenu').click(function () {
        var id = $(this).parents('tr').find('.id').text();
        var nama = $(this).parents('tr').find('.nama').text();
        var harga = $(this).parents('tr').find('.harga').text();

        if ( $('#pesanan-table').text().indexOf( nama ) != -1 ) {
            var qtyup = $("td:contains("+nama+")").parents('tr').find('.qty-update').val();
            console.log(qtyup);
            qtyups = parseInt(qtyup) + 1;
            $("td:contains("+nama+")").parents('tr').find('.qty-update').val( qtyups );
            $("td:contains("+nama+")").parents('tr').find('.sub-total').val( qtyups * harga );
        } else {
            var itemRow =
                '<tr>' +
                '<td class="align-middle"><input type="hidden" name="id[]" value="'+ id +'"/>'+ no +'</td>' +
                '<td class="align-middle nama">'+ nama +'</td>' +
                '<td class="align-middle harga">'+ harga +'</td>' +
                '<td class="align-middle"><input type="number" min="1" max="23" name="jumlah[]" class="form-control qty-update" value="1" /></td>' +
                '<td class="align-middle"><input type="number" name="total[]" class="form-control sub-total" readonly value="'+ harga +'"/></td>' +
                '<td class="align-middle"><button class="btn btn-danger delMenu" >x</button></td>' +
                '</tr>';
            $("#items_table").append(itemRow);
        }

        update();
        kalkulasiTotal();
        no++;
    });



    function update() {
        $('.qty-update').change(function () {
            kalkulasi($(this));
        });
        $('.delMenu').click(function () {
            $(this).parents('tr').remove();
            kalkulasiTotal();
        });
    }

    function kalkulasi(thisObj) {
        var harga = thisObj.parents('tr').find('.harga').text();
        var qty = thisObj.val();
        var sub_total = thisObj.parents('tr').find('.sub-total').val( harga * qty );
        kalkulasiTotal();
    }

    function kalkulasiTotal() {
        var i;
        var jumlah_total = 0;
        var inputs = $(".sub-total");
        for (i = 0; i < inputs.length; ++i) {
            jumlah_total = jumlah_total + parseInt(inputs.eq(i).val());
        }
        $('#jtotal').text('');
        $('#jtotal').text(jumlah_total);
        $('#jtotals').val('');
        $('#jtotals').val(jumlah_total);

    }
});
</script>
@endsection

@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card mt-4">
                <div class="card-header">List Menu</div>

                <div class="card-body">
                    <table id="tblEditavel" class="table table-bordered  table-responsive-sm">
                        <thead>
                            <tr>
                                <td>ID</td>
                                <td width="40%">Nama</td>
                                <td width="20%">Harga</td>
                                <td width="20%">Tambah</td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($menus as $menu)
                                <tr>
                                    <td class="id">{{ $menu->id }}</td>
                                    <td class="nama" title="nama">{{ $menu->nama }}</td>
                                    <td class="harga" title="harga">{{ $menu->harga }}</td>
                                    <td title="tambah"><button class="btn btn-sm btn-success btn-block addMenu">Tambah</button></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>


        <div class="col-md-8">
            <div class="card mt-4">
                <div class="card-header">Pesanan untuk meja {{ $pesanan->meja }}</div>

                <div class="card-body">
                    @if(session()->get('success'))
                        <div class="alert alert-success mt-3">
                        {{ session()->get('success') }}  
                        </div>
                    @endif
                    <form method="post" action="{{ route('pesanan.update', $pesanan->id) }}">
                        @method('PATCH')
                        @csrf
                        <table id="pesanan-table" class="table table-bordered  table-responsive-sm" >
                            <thead>
                                <tr>
                                    <td>No.</td>
                                    <td width="35%">Nama</td>
                                    <td width="20%">Harga</td>
                                    <td width="12%">Jumlah</td>
                                    <td width="22%" colspan="2">Total</td>
                                </tr>
                            </thead>
                            <tbody id="items_table">
                                @php( $num = 1 )
                                @if( $pesanan )
                                @foreach( $menu_s as $key )
                                    <tr>
                                        <td class="align-middle"><input type="hidden" name="id[]" value="{{ $key['id'] }}"/>{{ $num }}</td>
                                        <td class="align-middle nama"><input type="hidden" name="nama[]" value="{{ $key['nama'] }}"/>{{ $key['nama'] }}</td>
                                        <td class="align-middle harga"><input type="hidden" name="harga[]" value="{{ $key['harga'] }}"/>{{ $key['harga'] }}</td>
                                        <td class="align-middle"><input type="number" min="1" max="23" name="jumlah[]" class="form-control qty-update" value="{{ $key['jumlah'] }}" /></td>
                                        <td class="align-middle"><input type="number" name="total[]" class="form-control sub-total" readonly value="{{ $key['total'] }}"/></td>
                                        <td class="align-middle"><button class="btn btn-danger delMenu" >x</button></td>
                                    </tr>
                                    @php( $num++ )
                                @endforeach
                                @endif
                            </tbody>
                        </table>

                        <table class="table table-bordered  table-responsive-sm">
                            <tr>
                                <td>Jumlah Total</td>
                                <td width="30%" id="jtotal">{{ $pesanan->jumlah_total }}</td>
                            </tr>
                        </table>
                        <input id="jtotals" type="hidden" class="form-control" name="jumlah_total" value="{{ $pesanan->jumlah_total }}">
                        <input type="hidden" name="meja" value="{{ $pesanan->nomor }}">
                        <input type="hidden" name="nomor_pesanan" value="{{ $pesanan->nomor }}">
                        @if( $pesanan->status == '1' )
                        <input type="submit" name="submit" class="btn btn-block btn-success" value="{{ ( $pesanan ? 'Perbarui Pesanan' : 'Buat pesanan' ) }}">
                        @endif
                    </form>
                    @if ( Auth::user()->level == 3 )
                    <form method="post" action="{{ route('pesanan.update', $pesanan->id) }}" class="mt-3">
                        @method('PATCH')
                        @csrf
                        <input type="hidden" name="meja" value="{{ $pesanan->meja }}">
                        <input type="hidden" name="nomor_pesanan" value="{{ $pesanan->nomor }}">
                        <input type="hidden" name="bayar" value="bayar">
                        @if( $pesanan->status == '1' )
                        <input type="submit" name="submit" class="btn btn-block btn-primary" value="Bayar">
                        @endif
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
