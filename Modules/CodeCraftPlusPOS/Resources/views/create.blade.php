@extends('codecraftpluspos::app-pos')

@section('title', __('sale.pos_sale'))

@section('content')

<body>
	<section class="content no-print">
		<input type="hidden" id="amount_rounding_method" value="{{$pos_settings['amount_rounding_method'] ?? ''}}">
		@if(!empty($pos_settings['allow_overselling']))
			<input type="hidden" id="is_overselling_allowed">
		@endif
		@if(session('business.enable_rp') == 1)
			<input type="hidden" id="reward_point_enabled">
		@endif
		@php
			$is_discount_enabled = $pos_settings['disable_discount'] != 1;
			$is_rp_enabled = session('business.enable_rp') == 1;
		@endphp
		{!! Form::open(['url' => action([Modules\CodeCraftPlusPOS\Http\Controllers\SellPosController::class, 'store']), 'method' => 'post', 'id' => 'add_pos_sell_form' ]) !!}
		<div class="row mb-12">
			<div class="col-md-12">
				
				<div class="row">
					<div class="@if(empty($pos_settings['hide_product_suggestion'])) col-md-7 @else col-md-10 col-md-offset-1 @endif no-padding pr-12">
						<div class="box box-solid mb-12 @if(!isMobile()) mb-40 @endif">
							<div class="box-body pb-0">
								{!! Form::hidden('location_id', $default_location->id ?? null , ['id' => 'location_id', 'data-receipt_printer_type' => $default_location->receipt_printer_type ?? 'browser', 'data-default_payment_accounts' => $default_location->default_payment_accounts ?? '']) !!}
								{!! Form::hidden('sub_type', $sub_type ?? null) !!}
								<input type="hidden" id="item_addition_method" value="{{$business_details->item_addition_method}}">
								@include('codecraftpluspos::recursos.pos_form')
								@include('codecraftpluspos::recursos.pos_form_totals')
								@include('codecraftpluspos::recursos.payment_modal')
								
								@if(empty($pos_settings['disable_suspend']))
									@include('codecraftpluspos::recursos.suspend_note_modal')
								@endif

								@if(empty($pos_settings['disable_recurring_invoice']))
									@include('codecraftpluspos::recursos.recurring_invoice_modal')
								@endif
							</div>
						</div>
					</div>
					
					@if(empty($pos_settings['hide_product_suggestion']) && !isMobile())
						<div class="col-md-5 no-padding">
							@include('codecraftpluspos::recursos.pos_sidebar')
						</div>
					@endif
				</div>
			</div>
		</div>
		@include('codecraftpluspos::recursos.pos_form_actions')
		{!! Form::close() !!}
	</section>

	<!-- This will be printed -->
	<section class="invoice print_section" id="receipt_section">
	</section>

	<div class="modal fade contact_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
		@include('contact.create', ['quick_add' => true])
	</div>

	@if(empty($pos_settings['hide_product_suggestion']) && isMobile())
		@include('codecraftpluspos::recursos.mobile_product_suggestions')
	@endif

	<div class="modal fade register_details_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel"></div>
	<div class="modal fade close_register_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel"></div>

	<!-- quick product modal -->
	<div class="modal fade quick_add_product_modal" tabindex="-1" role="dialog" aria-labelledby="modalTitle"></div>

	<div class="modal fade" id="expense_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel"></div>

	@include('codecraftpluspos::recursos.configure_search_modal')
	@include('codecraftpluspos::recursos.recent_transactions_modal')
	@include('codecraftpluspos::recursos.weighing_scale_modal')
</body>


@stop

@section('css')
	<!-- include module css -->
    @if(!empty($pos_module_data))
        @foreach($pos_module_data as $key => $value)
            @if(!empty($value['module_css_path']))
                @includeIf($value['module_css_path'])
            @endif
        @endforeach
    @endif
@stop

@section('javascript')
	

	<script src="{{ asset('js/pos.js?v=' . $asset_v) }}"></script>
	<script src="{{ asset('js/printer.js?v=' . $asset_v) }}"></script>
	<script src="{{ asset('js/product.js?v=' . $asset_v) }}"></script>
	<script src="{{ asset('js/opening_stock.js?v=' . $asset_v) }}"></script>
	
	<script>
 

	$(document).ready(function() {
        // Event listener para el cambio en el select
        $('select#taza_id').change(function() {
            // Obtener el valor seleccionado del select
            var selectedValue = $(this).val();
            // Actualizar el valor del campo oculto con el valor seleccionado
            $('#taza_value').val(selectedValue);
        });



		   // Function to update total sales amount
		   function get_subtotal() {
        var price_total = 0;

        $('table#pos_table tbody tr').each(function() {
            price_total = price_total + parseFloat($(this).find('input.pos_line_total').val());
        });

        // Go through the modifier prices.
        $('input.modifiers_price').each(function() {
            var modifier_price = parseFloat($(this).val());
            var modifier_quantity = parseFloat($(this).closest('.product_modifier').find('.modifiers_quantity').val());
            var modifier_subtotal = modifier_price * modifier_quantity;
            price_total = price_total + modifier_subtotal;
        });

        return price_total;
    }

    // Update Order tax
    $('button#posEditOrderTaxModalUpdate').click(function() {
        // Close modal
        $('div#posEditOrderTaxModal').modal('hide');

        var tax_obj = $('select#order_tax_modal');
        var tax_id = tax_obj.val();
        var tax_rate = tax_obj.find(':selected').data('rate');

        $('input#tax_rate_id').val(tax_id);

        __write_number($('input#tax_calculation_amount'), tax_rate);

        // Call total_with_rate to update other values
        total_with_rate();
    });

    	// Function to update total sales amount
    function total_with_rate() {
        var total_quantity = 0;
        var subtotal_original = get_subtotal(); // Get the original subtotal before selecting from the select

        $('table#pos_table tbody tr').each(function() {
            total_quantity = total_quantity + parseFloat($(this).find('input.pos_quantity').val());
        });

        // Updating shipping charges
        $('span#shipping_charges_amount').text(
            __currency_trans_from_en(parseFloat($('input#shipping_charges_modal').val()), false)
        );

        $('span.total_quantity').each(function() {
            $(this).html(__number_f(total_quantity));
        });

        // Calculate the new total based on the selected value from the select
        var selected_value = parseFloat($('select#taza_id').val());
        var new_total =     selected_value * subtotal_original;

        $('span.price_total').html(__currency_trans_from_en(new_total, false));
        calculate_billing_details(new_total);
    }

    // Event listener for select taza_id
    $('select#taza_id').change(function() {
        // Call total_with_rate to update other values
        total_with_rate();
    });
    });
</script>






	@include('codecraftpluspos::recursos.keyboard_shortcuts')

	<!-- Call restaurant module if defined -->
    @if(in_array('tables' ,$enabled_modules) || in_array('modifiers' ,$enabled_modules) || in_array('service_staff' ,$enabled_modules))
    	<script src="{{ asset('js/restaurant.js?v=' . $asset_v) }}"></script>
    @endif
    <!-- include module js -->
    @if(!empty($pos_module_data))
	    @foreach($pos_module_data as $key => $value)
            @if(!empty($value['module_js_path']))
                @includeIf($value['module_js_path'], ['view_data' => $value['view_data']])
            @endif
	    @endforeach
	@endif

	<script type="text/javascript">
        if(localStorage.getItem("upos_sidebar_collapse") == 'true'){
            var body = document.getElementsByTagName("body")[0];
            body.className += " sidebar-collapse";
        }
    </script>
@endsection