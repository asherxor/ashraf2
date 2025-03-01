@extends('layouts.app')
@section('title', __('codecraftcurrencyadd::lang.tazas'))

@section('content')

    <section class="content-header">
        <h1>@lang( 'codecraftcurrencyadd::lang.tazas_sets' )
            <small>@lang( 'codecraftcurrencyadd::lang.manage_your_tazas' )</small>
        </h1>
    </section>
    <section class="content">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">@lang( 'codecraftcurrencyadd::lang.all_your_tazas' )</h3>
                @can('codecraftcurrencyadd.create')
                    <div class="box-tools">
                        <button type="button" class="btn btn-block btn-primary btn-modal" 
                            data-href="{{route('create-tazas')}}" 
                            data-container=".currency_modal">
                            <i class="fa fa-plus"></i> @lang( 'codecraftcurrencyadd::lang.add' )</button>
                    </div>
                @endcan

                <div class="box-tools">
                    @include('codecraftcurrencyadd::paypal')
                </div>
                
            </div>
            
            <div class="box-body">
                @can('codecraftcurrencyadd.view')
                    <table class="table table-bordered table-striped" id="taza_table">
                        <thead>
                            <tr>
                                <th>@lang( 'codecraftcurrencyadd::lang.alias' )</th>
                                <th>@lang( 'codecraftcurrencyadd::lang.country_currency' )</th>
                                <th>@lang( 'codecraftcurrencyadd::lang.value_tax' )</th>
                                <th>@lang( 'codecraftcurrencyadd::lang.action' )</th>
                                
                            </tr>
                        </thead>
                    </table>
                @endcan
                </div>
        </div>
        <div class="modal fade taza_modal" tabindex="-1" role="dialog" 
    	    aria-labelledby="gridSystemModalLabel" id="taza_modal">
        </div>
    </section>

@endsection
@section('javascript')
<script>
$(document).ready(function () {
    var taza_table = $('#taza_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '/codecraftcurrencyadd/taza_s',
        columnDefs: [{
            "targets": [1,2, 3],
            "orderable": false,
            "searchable": false
        }],
        columns: [
            { data: 'alias', name: 'alias' },
            { data: 'country_currency', name: 'country_currency' },
            { data: 'value_tax', name: 'value_tax' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });

    $(document).on('click', 'button.delete_taza_button', function(){
        var button = $(this);
        swal({
            title: LANG.sure,
            text: LANG.confirm_delete_table,
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                var href = button.data('href');
                var data = button.serialize();

                $.ajax({
                    method: "DELETE",
                    url: href,
                    dataType: "json",
                    data: data,
                    success: function(result){
                        if(result.success == true){
                            toastr.success(result.msg);
                            taza_table.ajax.reload();
                        } else {
                            toastr.error(result.msg);
                        }
                    }
                });
            }
        });
    });

    // Abre el modal cuando se hace clic en el botón
    $(document).on('click', 'button.btn-modal', function(){
        var href = $(this).data('href');
        $.get(href, function(data) {
            $('#taza_modal').html(data);
            $('#taza_modal').modal('show');
        });
    });
});


    </script>
@endsection