<div class="row pos_form_totals">
	<div class="col-md-12">
		<table class="table table-condensed">
			<tr>
				<td><b>@lang('sale.item'):</b>&nbsp;
					<span class="total_quantity">0</span></td>
				<td>
					<b>@lang('sale.total'):</b> &nbsp;
					<span class="price_total">0</span>
				</td>
			</tr>
			<tr>
				@if(!Gate::check('disable_discount') || auth()->user()->can('superadmin') || auth()->user()->can('admin'))
					<td>
						<b>
							@if($is_discount_enabled)
								@lang('codecraftpluspos::lang.discount')
								@show_tooltip(__('codecraftpluspos::lang.sale_discount_tool'))
							@endif
							@if($is_rp_enabled)
								{{session('business.rp_name')}}
							@endif
							@if($is_discount_enabled)
								(-):
								@if($edit_discount)
								<i class="fas fa-edit cursor-pointer" id="pos-edit-discount" title="@lang('sale.edit_discount')" aria-hidden="true" data-toggle="modal" data-target="#posEditDiscountModal"></i>
								@endif
							
								<span id="total_discount">0</span>
							@endif
								<input type="hidden" name="discount_type" id="discount_type" value="@if(empty($edit)){{'percentage'}}@else{{$transaction->discount_type}}@endif" data-default="percentage">

								<input type="hidden" name="discount_amount" id="discount_amount" value="@if(empty($edit)) {{@num_format($business_details->default_sales_discount)}} @else {{@num_format($transaction->discount_amount)}} @endif" data-default="{{$business_details->default_sales_discount}}">

								<input type="hidden" name="rp_redeemed" id="rp_redeemed" value="@if(empty($edit)){{'0'}}@else{{$transaction->rp_redeemed}}@endif">

								<input type="hidden" name="rp_redeemed_amount" id="rp_redeemed_amount" value="@if(empty($edit)){{'0'}}@else {{$transaction->rp_redeemed_amount}} @endif">

								</span>
						</b> 
					</td>
				@endif

			

				<td class="@if($pos_settings['disable_order_tax'] != 0) hide @endif">
					<span>
						<b>@lang('codecraftpluspos::lang.order_tax')(+): @show_tooltip(__('codecraftpluspos::lang.sale_tax_tool'))</b>
						<i class="fas fa-edit cursor-pointer" title="@lang('sale.edit_order_tax')" aria-hidden="true" data-toggle="modal" data-target="#posEditOrderTaxModal" id="pos-edit-tax" ></i> 
						<span id="order_tax">
							@if(empty($edit))
								0
							@else
								{{$transaction->tax_amount}}
							@endif
						</span>

						<input type="hidden" name="tax_rate_id" 
							id="tax_rate_id" 
							value="@if(empty($edit)) {{$business_details->default_sales_tax}} @else {{$transaction->tax_id}} @endif" 
							data-default="{{$business_details->default_sales_tax}}">

						<input type="hidden" name="tax_calculation_amount" id="tax_calculation_amount" 
							value="@if(empty($edit)) {{@num_format($business_details->tax_calculation_amount)}} @else {{@num_format($transaction->tax?->amount)}} @endif" data-default="{{$business_details->tax_calculation_amount}}">

			
					</span>
				</td>

				



				<td>
					<span>

						<b>@lang('codecraftpluspos::lang.shipping')(+): @show_tooltip(__('codecraftpluspos::lang.shipping_tool'))</b> 
						<i class="fas fa-edit cursor-pointer"  title="@lang('sale.shipping')" aria-hidden="true" data-toggle="modal" data-target="#posShippingModal"></i>
						<span id="shipping_charges_amount">0</span>
						<input type="hidden" name="shipping_details" id="shipping_details" value="@if(empty($edit)){{''}}@else{{$transaction->shipping_details}}@endif" data-default="">

						<input type="hidden" name="shipping_address" id="shipping_address" value="@if(empty($edit)){{''}}@else{{$transaction->shipping_address}}@endif">

						<input type="hidden" name="shipping_status" id="shipping_status" value="@if(empty($edit)){{''}}@else{{$transaction->shipping_status}}@endif">

						<input type="hidden" name="delivered_to" id="delivered_to" value="@if(empty($edit)){{''}}@else{{$transaction->delivered_to}}@endif">

						<input type="hidden" name="delivery_person" id="delivery_person" value="@if(empty($edit)){{''}}@else{{$transaction->delivery_person}}@endif">

						<input type="hidden" name="shipping_charges" id="shipping_charges" value="@if(empty($edit)){{@num_format(0.00)}} @else{{@num_format($transaction->shipping_charges)}} @endif" data-default="0.00">
					</span>
				</td>
				@if(in_array('types_of_service', $enabled_modules))
					<td class="col-sm-3 col-xs-6 d-inline-table">
						<b>@lang('lang_v1.packing_charge')(+):</b>
						<i class="fas fa-edit cursor-pointer service_modal_btn"></i> 
						<span id="packing_charge_text">
							0
						</span>
					</td>
				@endif
				@if(!empty($pos_settings['amount_rounding_method']) && $pos_settings['amount_rounding_method'] > 0)
				<td>
					<b>@lang('codecraftpluspos::lang.shipping')(+): @show_tooltip(__('codecraftpluspos::lang.shipping_tool'))</b> 								
					<input type="hidden" name="round_off_amount" id="round_off_amount" value=0>
				
				@endif

				<td>
				<b>@lang('codecraftpluspos::lang.rate')(+): @show_tooltip(__('codecraftpluspos::lang.rate_tool'))</b> 
						
					<select class="select2" name="taza_id" id="taza_id" class="form-control">
						<option value="1.00">{{ __('codecraftpluspos::lang.select_rate') }}</option>
						@foreach($tazas as $taza)
							<option value="{{ $taza->value }}">{{ $taza->alias }} - {{ $taza->value }}</option>
						@endforeach
					</select>
					<input type="hidden" name="taza_value" id="taza_value" value="1.00">
				</td>
			</tr>
		</table>
	</div>
</div>


