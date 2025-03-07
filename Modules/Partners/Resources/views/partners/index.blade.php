@extends('layouts.app')
@section('title', __('partners::lang.partners'))
@section('content')
    <style>
        .table-striped th {
            background-color: #626161;
            color: #ffffff;
        }
    </style>

    @include('partners::layouts.nav')

    <section class="content-header">
        <h1>@lang('partners::lang.partners')</h1>
    </section>

    <div style="margin:auto;max-width: 70%;">
        <div class="row">

            <div class="col-md-3">
                <div class="form-group">
                    {!! Form::label('capital', __('partners::lang.capital_approved')) !!}
                    {!! Form::text('capital', $business_data->capital, ['class' => 'form-control decimal', 'required', 'placeholder' => __('partners::lang.name')]); !!}
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    {!! Form::label('sharenumber', __('partners::lang.number_of_shares')) !!}
                    {!! Form::text('sharenumber', $business_data->sharenumber, ['class' => 'form-control', 'required', 'placeholder' => __('partners::lang.name')]); !!}
                </div>
            </div>

            @if(auth()->user()->can('partners.create'))
                <div class="col-md-2" style="margin-top: 23px;">
                    <button class="btn btn-danger" onclick="savedata()">@lang('messages.save')</button>
                </div>
            @endif

        </div>

        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    {!! Form::label('capital_rem', __('partners::lang.remaining_approved_capital')) !!}
                    {!! Form::text('capital_rem', $business_data->capital - $totalcapital , ['class' => 'form-control decimal', 'readonly', 'placeholder' => __('partners::lang.name')]); !!}
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    {!! Form::label('sharenumber_rem', __('partners::lang.remaining_number_of_shares')) !!}
                    {!! Form::text('sharenumber_rem', $business_data->sharenumber - $totalshare, ['class' => 'form-control', 'readonly', 'placeholder' => __('partners::lang.name')]); !!}
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        @component('components.widget', ['class' => 'box-primary', 'title' => ''])
            @can('assets.create')
                @slot('tool')
                    <div class="box-tools">
                        @if(auth()->user()->can('partners.create'))
                            <button type="button" class="btn btn-block btn-primary btn-modal"
                                    data-href="{{ action('\Modules\Partners\Http\Controllers\PartnersController@create') }}"
                                    data-container=".brands_modal">
                                <i class="fa fa-plus"></i> @lang('messages.add')
                            </button>
                        @endif
                    </div>
                @endslot
            @endcan
            @can('assets.view')
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="assete_table">
                        <thead>
                        <tr>
                            <th>@lang('partners::lang.name')</th>
                            <th>@lang('partners::lang.address')</th>
                            <th>@lang('partners::lang.phone_number')</th>
                            <th>@lang('partners::lang.paid_capital_value')</th>
                            <th>@lang('partners::lang.number_of_shares')</th>
                            <th>@lang('partners::lang.credit_balance')</th>
                            <th>@lang('partners::lang.debit_balance')</th>
                            <th>@lang('messages.action')</th>
                        </tr>
                        </thead>
                        <tbody id="datatable">
                        @foreach($partners as $partner)
                            <tr id="{{ $partner->id }}">
                                <td>{{ $partner->name }}</td>
                                <td>{{ $partner->address }}</td>
                                <td>{{ $partner->mobile }}</td>
                                <td>{{ $partner->capital }}</td>
                                <td>{{ $partner->share }}</td>
                                <td>@if($partner->value < 0) {{ abs($partner->value) }} @endif</td>
                                <td>@if($partner->value > 0) {{ abs($partner->value) }} @endif</td>
                                <td>
                                    @if(auth()->user()->can('partners.edit'))
                                        <button onclick="assetedit({{ $partner->id }})" class="btn btn-xs btn-primary btn-modal">
                                            <i class="glyphicon glyphicon-edit"></i> @lang("messages.edit")
                                        </button>
                                        <button onclick="deleteasset({{ $partner->id }})" class="btn btn-xs btn-danger delete_asset_button">
                                            <i class="glyphicon glyphicon-trash"></i> @lang("messages.delete")
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach

                        <tr id="0">
                            <th colspan="3">@lang('messages.total'):</th>
                            <th>{{ $totalcapital }}</th>
                            <th>{{ $totalshare }}</th>
                            <th colspan="3"></th>
                        </tr>
                        </tbody>

                    </table>
                </div>
            @endcan
        @endcomponent
    </section>

    <div class="modal fade brands_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
    </div>
@endsection


<script type="text/javascript" src="{{ asset('Partners/Resources/assets/js/app.js')}}"></script>
<script>

    function assetedit(id) {
        $.ajax({
            url: '/partners/partners/'+id+'/edit',
            dataType: 'html',
            success: function(result) {
                $(".brands_modal").html(result)
                    .modal('show');
            },
        });
    }


    function  deleteasset(id) {
        swal({
            title: LANG.sure,
            text: 'Delete?',
            icon: 'warning',
            buttons: true,
            dangerMode: true,
        }).then(willDelete => {
            if (willDelete) {
                var href = '/partners/partners/'+id;
                var data = id;
                $.ajax({
                    method: 'DELETE',
                    url: href,
                    dataType: 'json',
                    data:{
                        data:data
                    },
                    success: function(result) {
                        if (result.success == true) {
                            toastr.success(result.msg);
                            var drow = document.getElementById(id);
                            drow.parentNode.removeChild(drow);
                        } else {
                            toastr.error(result.msg);
                        }
                    },
                });
            }
        });
    }


    function  savedata() {
        var capital=$('#capital').val();
        if(capital==''){
            toastr.error('عفوا برجاء إدخال رأس المال');;
            return true;
        }

        var sharenumber=$('#sharenumber').val();
        if(sharenumber==''){
            toastr.error('عفوا برجاء إدخال عدد الأسهم');
            return true;
        }

        swal({
            title: LANG.sure,
            text: 'هل تريد تعديل رأس مال الشركة وعدد الأسهم',
            icon: 'warning',
            buttons: true,
            dangerMode: true,
        }).then(willDelete => {
            if (willDelete) {
                var href = '/partners/savecapital';
               $.ajax({
                    method: 'POST',
                    url: href,
                    data:{
                        capital:capital
                        ,sharenumber:sharenumber
                    },
                    success: function(result) {
                        swal({
                            title: result.message,
                            icon: 'info',
                            });



                        if (result.success == true) {
                            toastr.success(result.msg);

                        } else {
                            toastr.error(result.msg);
                        }
                    },
                });
            }
        });
    }




</script>

