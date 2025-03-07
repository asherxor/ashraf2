@extends('layouts.app')
@section('title', __('project::lang.invoices'))

@section('content')
<section class="content">
	<h1>
		<i class="fa fa-file"></i>
    	@lang('project::lang.invoice')
    	<small>@lang('project::lang.create')</small>
    </h1>
    <!-- form open -->
    {!! Form::open(['action' => '\Modules\Project\Http\Controllers\InvoiceController@store', 'id' => 'invoice_form', 'method' => 'post']) !!}
		<div class="box box-primary">
			<div class="box-body">
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							{!! Form::label('pjt_title', __('project::lang.title') . ':*' )!!}
	                        {!! Form::text('pjt_title', null, ['class' => 'form-control', 'required' ]) !!}
						</div>
					</div>
					<!-- project_id -->
					{!! Form::hidden('pjt_project_id', $project->id, ['class' => 'form-control']) !!}
					<div class="col-md-6">
						<div class="form-group">
							{!! Form::label('invoice_scheme_id', __('invoice.invoice_scheme') . ':*' )!!}
	                        {!! Form::select('invoice_scheme_id', $invoice_schemes, $default_scheme->id, ['class' => 'form-control select2', 'placeholder' => __('messages.please_select'), 'required', 'style' => 'width: 100%;']); !!}
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							{!! Form::label('contact_id', __('role.customer') . ':*' )!!}
	                        {!! Form::select('contact_id', $customers, $project->contact_id, ['class' => 'form-control select2', 'placeholder' => __('messages.please_select'), 'required', 'style' => 'width: 100%;']); !!}
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							{!! Form::label('location_id', __('business.business_location') . ':*' )!!}
	                        {!! Form::select('location_id', $business_locations, null, ['class' => 'form-control', 'placeholder' => __('messages.please_select'), 'required', 'style' => 'width: 100%;']); !!}
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							{!! Form::label('transaction_date', __('project::lang.invoice_date') . ':*' )!!}
	                        {!! Form::text('transaction_date', '', ['class' => 'form-control date-picker','required', 'readonly']); !!}
						</div>
					</div>
					<div class="col-md-4">
	                    <div class="form-group">
	                       <div class="multi-input">
				              {!! Form::label('pay_term_number', __('contact.pay_term') . ':') !!} @show_tooltip(__('tooltip.pay_term'))
				              <br/>
				              {!! Form::number('pay_term_number', null, ['class' => 'form-control width-40 pull-left', 'placeholder' => __('contact.pay_term')]); !!}
				              {!! Form::select('pay_term_type', 
				              	['months' => __('lang_v1.months'), 
				              		'days' => __('lang_v1.days')], 
				              		null, 
				              	['class' => 'form-control width-60 pull-left','placeholder' => __('messages.please_select')]); !!}
				            </div>
	                    </div>
	                </div>
	                <div class="col-md-4">
						<div class="form-group">
							{!! Form::label('status', __('sale.status') . ':*' )!!}
	                        {!! Form::select('status', $statuses, null, ['class' => 'form-control', 'placeholder' => __('messages.please_select'), 'required', 'style' => 'width: 100%;']); !!}
						</div>
					</div>
				</div>
			</div>
		</div> <!-- /box -->
		<div class="box box-primary">
			<div class="box-body">
				<div class="col-md-12">
					<div class="col-md-3">
						<label>@lang('project::lang.task'):*</label>
					</div>
					<div class="col-md-2">
						<label>@lang('project::lang.rate'):*</label>
					</div>
					<div class="col-md-2">
						<label>@lang('project::lang.qty'):*</label>
					</div>
					<div class="col-md-2">
						<label>@lang('business.tax')(%):</label>
					</div>
					<div class="col-md-2">
						<label>@lang('receipt.total'):*</label>
					</div>
					<div class="col-md-1">
					</div>
				</div>
				<div class="invoice_lines">
					<div class="col-md-12 il-bg invoice_line">
						<div class="mt-10">
							<div class="col-md-3">
								<div class="form-group">
									<div class="input-group">
										{!! Form::text('task[]', null, ['class' => 'form-control', 'required' ]) !!}
										<span class="input-group-btn">
									        <button class="btn btn-default toggle_description" type="button">
												<i class="fa fa-info-circle text-info" data-toggle="tooltip" title="@lang('project::lang.toggle_invoice_task_description')"></i>
									        </button>
									    </span>
									</div>
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group">
									{!! Form::text('rate[]', null, ['class' => 'form-control rate input_number', 'required' ]) !!}
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group">
									{!! Form::text('quantity[]', null, ['class' => 'form-control quantity input_number', 'required' ]) !!}
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group">
									{!! Form::select('tax_rate_id[]', $taxes, null, [ 'class' => 'form-control tax'], $tax_attributes); !!}

								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group">
									{!! Form::text('total[]', null, ['class' => 'form-control total input_number', 'required', 'readonly']) !!}
								</div>
							</div>
							<div class="col-md-11">
								<div class="form-group description" style="display: none;">
									{!! Form::textarea('description[]', null, ['class' => 'form-control ', 'placeholder' => __('lang_v1.description'), 'rows' => '3']); !!}
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-4 col-md-offset-4">
					<br>
					<button type="button" class="tw-dw-btn tw-dw-btn-primary tw-text-white tw-dw-btn-sm tw-w-full add_invoice_line">
						@lang('project::lang.add_a_row')
						<i class="fa fa-plus-circle"></i>
					</button>
				</div>
			</div>
			<!-- including invoice line row -->
			@includeIf('project::invoice.partials.invoice_line_row')
		</div>  <!-- /box -->
		<div class="box box-primary">
			<div class="box-body">
				<div class="row">
					<div class="col-md-6 col-md-offset-10">
						<b>@lang('sale.subtotal'):</b>
						<span class="subtotal display_currency" data-currency_symbol="true" >0.00</span>
						<input type="hidden" name="total_before_tax" id="subtotal" value="0.00">
					</div>
				</div> <br>
				<div class="row">
					<div class="col-md-6">
						{!! Form::label('discount_type', __('sale.discount_type') . ':' )!!}
	                    {!! Form::select('discount_type', $discount_types, null, ['class' => 'form-control select2', 'placeholder' => __('messages.please_select'), 'style' => 'width: 100%;']); !!}
					</div>
					<div class="col-md-6">
						{!! Form::label('discount_amount', __('sale.discount_amount') . ':' )!!}
	                    {!! Form::text('discount_amount', null, ['class' => 'form-control input_number']) !!}
					</div>
				</div> <br>

				<div class="row">
					<div class="col-md-6 col-md-offset-6">
						<b>@lang('project::lang.invoice_total'):</b>
						<span class="invoice_total display_currency" data-currency_symbol="true" >0.00</span>
						<input type="hidden" name="final_total" id="invoice_total" value="0.00">
					</div>
				</div> <br>

				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
	                        {!! Form::label('staff_note', __('project::lang.terms') . ':') !!}
	                        {!! Form::textarea('staff_note', null, ['class' => 'form-control ', 'rows' => '3']); !!}
	                    </div>
					</div>
					<div class="col-md-12">
						<div class="form-group">
	                        {!! Form::label('additional_notes', __('project::lang.notes') . ':') !!}
	                        {!! Form::textarea('additional_notes', null, ['class' => 'form-control ', 'rows' => '3']); !!}
	                    </div>
					</div>
				</div>
				<button type="submit" class="tw-dw-btn tw-dw-btn-primary tw-text-white pull-right">
	                @lang('messages.save')
	            </button>
			</div>
		</div> <!-- /box -->
	{!! Form::close() !!} <!-- /form close -->
</section>
<link rel="stylesheet" href="{{ asset('modules/project/sass/project.css?v=' . $asset_v) }}">
@endsection
@section('javascript')
<script src="{{ asset('modules/project/js/project.js?v=' . $asset_v) }}"></script>
@endsection