@extends('layouts.app')
@section('title', __('inventorymanagement::inventory.inventory'))
@section('css')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.13.1/datatables.min.css"/>
    <link rel="stylesheet" type="text/css" href="{{ url('css/inventory.css') }}"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
@endsection
@section('content')

    <section class="content-header">
        <h1>@lang('inventorymanagement::inventory.stock_inventory')</h1>
    </section>

    <section class="content">
        <div class="box box-primary">
            <div class="box-header text-center" style="background-color:#484848;color:#EDAF11;font-size: 30px;">
                @lang("inventorymanagement::inventory.products_inventory")
            </div>
        </div>
        <div class="box-body">
            <div class="row">
                <div class="col-sm-8 col-sm-offset-2">
                    <div class="form-group">
                        <div class="input-group">
							<span class="input-group-addon">
								<i class="fa fa-search"></i>
							</span>
                            {!! Form::text('search_product', null, ['class' => 'form-control', 'id' => 'search_product_inventory', 'placeholder' => __('stock_adjustment.search_products'), 'enabled']); !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <input type="hidden" id="product_row_index" value="0">
                    <input type="hidden" id="total_amount" name="final_total" value="0">
                    <div class="table-responsive">

                    </div>
                </div>
                <div class="clearfix"></div>

            </div>
        </div>

        <table id="purchase_entry_table" class="display nowrap table table-bordered table-hover table-th-green" style="width:100%">
            <thead>
            <tr>
                <th  style="text-align: right">@lang("inventorymanagement::inventory.product_name")</th>
                <th  style="text-align: right">@lang("inventorymanagement::inventory.product_barcode")</th>
                <th  style="text-align: right">@lang("inventorymanagement::inventory.current_amount")</th>
                <th  style="text-align: right">@lang("inventorymanagement::inventory.amount_after_inventory")</th>
                <th  style="text-align: right">@lang("inventorymanagement::inventory.amount_difference")</th>
                <th  style="text-align: right">@lang("inventorymanagement::inventory.options")</th>

            </tr>
            </thead>
           <tbody>

          @include("inventorymanagement::partials.tableRowForListing" , [$inventories , $quantityProductsArray,$products])
           </tbody>

        </table>
            <div class="bg-gray p-10 text-center"><button type="button" id="saveProducts" class="btn btn-danger">
                @lang('inventorymanagement::inventory.save')
            </button></div>
    </section>

@endsection
@include("inventorymanagement::partials.modals.editProductInventoryModal")
@section('javascript')
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.2/moment.min.js"></script>
    <script src="https://cdn.datatables.net/datetime/1.2.0/js/dataTables.dateTime.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#example').DataTable({
                columnDefs: [
                    {
                        targets: [0],
                        orderData: [0, 1],
                    },
                    {
                        targets: [1],
                        orderData: [1, 0],
                    },
                    {
                        targets: [4],
                        orderData: [4, 0],
                    },
                ],
            });
        });
    </script>
    <script src="{{ asset('js/purchase.js?v=' . $asset_v) }}"></script>

    @include('inventorymanagement::partials.mainscript')

    <script src="{{ asset('js/vendor.js?v=' . $asset_v) }}"></script>
    <script type="text/javascript">__page_leave_confirmation('#purchase_return_form');</script>
    {{-- <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script> --}}

@endsection

