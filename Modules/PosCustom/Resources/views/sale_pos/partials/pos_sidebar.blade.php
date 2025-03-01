@php
	$is_mobile = isMobile(); 
	$custom_labels = json_decode($business_details['custom_labels']);
	$theme_divider_class = 'theme_' . $business_details->theme_color . '_divider';
	/* Old style 5.40 using css/app.css */
    $theme_pos_class1 = 'theme_' . $business_details->theme_color . '_pos';
    /* New style usin css/tailwind/app.css */
    $theme_pos_class = 'tw-bg-gradient-to-r tw-from-' . $business_details->theme_color . '-800'
    .' tw-to-'.$business_details->theme_color.'-500';

    /*
    tw-bg-gradient-to-r tw-from-orange-800 tw-to-blue-500
    */

	/*
	skin-@if(!empty(session('business.theme_color'))){{session('business.theme_color')}}@else{{'blue-light'}}@endif   
	*/
@endphp



<div class="row tw-mb-1">
    @if (!empty($categories))
        <div class="col-md-4 !tw-px-2" id="product_category_div">
            <div class="tw-dw-drawer tw-dw-drawer-end">
                <input id="my-drawer-4" type="checkbox" class="tw-dw-drawer-toggle">
                <div class="tw-dw-drawer-content">
                    <!-- Page content here -->
                    <label for="my-drawer-4"
                        class="{{$theme_pos_class}} hover:tw-animate-pulse focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-blue-500 focus:tw-ring-offset-2 active:tw-from-indigo-700 active:tw-to-blue-700 lg:tw-w-[98%] tw-w-full tw-flex tw-items-center tw-justify-center tw-gap-1 tw-text-base md:tw-text-lg tw-text-white tw-font-semibold tw-rounded-xl {{-- tw-h-12 --}} tw-cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="tw-w-5 icon icon-tabler icon-tabler-category-plus" width="44" height="30"
                            viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffffff" fill="none"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M4 4h6v6h-6zm10 0h6v6h-6zm-10 10h6v6h-6zm10 3h6m-3 -3v6" />
                        </svg>
                        @lang('category.category')
                    </label>
                </div>
                <div class="tw-dw-drawer-side" style="z-index: 4000">
                    <label for="my-drawer-4" aria-label="close sidebar"
                        class="tw-dw-drawer-overlay overlay-category"></label>
                    <div class="tw-dw-menu tw-w-2/4 tw-min-h-full tw-bg-white tw-p-6">
                        <div class="tw-flex tw-items-center tw-mb-16">
                            <button type="button"
                                class="tw-dw-btn tw-dw-btn-accent category-back tw-bg-transparent tw-border-2"
                                style="display: none">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="tw-w-5 icon icon-tabler icon-tabler-chevron-left" width="44"
                                    height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2c3e50"
                                    fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M15 6l-6 6l6 6" />
                                </svg>
                            </button>

                            <h3 class="tw-text-center tw-flex-grow mx-auto category_heading tw-bg-gradient-to-r tw-from-indigo-600 tw-to-blue-500 tw-inline-block tw-text-transparent tw-bg-clip-text tw-font-bold tw-text-base md:tw-text-2xl"
                                style="margin-bottom: 0px; margin-top:5px;">@lang('poscustom::lang.categories')</h3>

                            <button type="button" class="tw-dw-btn tw-dw-btn-error close-side-bar-category">
                                <i class="fa fa-times-circle" aria-hidden="true"></i>
                            </button>

                        </div>
                        <div class="row tw-mr-5">
                            <div class="col-md-3 col-xs-12 tw-mb-2 {{-- tw-mb-7 --}} tw-w-auto  tw-h-auto tw-cursor-pointer  main-category-div main-category no-print"
                                data-value="all" data-name="@lang('lang_v1.all_category')" data-parent="0">
                                <div class="tw-dw-card tw-w-25 tw-bg-base-100 tw-shadow-sm tw-h-auto tw-shadow-[rgba(17,_17,_26,_0.1)_0px_0px_16px] tw-bg-white hover:tw-bg-white/60 tw-cursor-pointer !tw-text-xs md:!tw-text-sm tw-font-semibold tw-text-center tw-border-2">
                                    <div class="tw-dw-card-body tw-h-28" style="margin-bottom: 20px">
                                        {{--#JCN add the img to category--}}
                                        <button type="button" >
                                            <img src="{{asset('/img/all.jpg')}}" class="img-tumbnail">                                        
                                        </button>
                                        <h4 class="tw-flex tw-items-center tw-justify-center" 
                                        style="margin-bottom: 0px; margin-top:0px; font-size: inherit; font-weight: inherit;">
                                        @lang('lang_v1.all_category')</h4>
                                    </div>
                                </div>
                            </div>
                            @foreach ($categories as $category)
                                    <div class="col-md-3 col-xs-12 tw-mb-2 {{-- tw-mb-7 --}} tw-w-auto  {{-- tw-h-28 --}}  tw-cursor-pointer main-category-div  no-print">
                                        <div
                                            class="tw-dw-card tw-w-25 tw-bg-base-100 tw-shadow-sm tw-h-auto tw-shadow-[rgba(17,_17,_26,_0.1)_0px_0px_16px] tw-bg-white hover:tw-bg-white/60 tw-cursor-pointer tw-text-xs md:tw-text-sm tw-font-semibold !tw-text-center tw-border-2">
                                            <div class="tw-dw-card-body tw-h-28 @if (empty($category['sub_categories'])) main-category @endif" style="margin-bottom: 20px"
                                            data-value="{{ $category['id'] }}" data-name="{{ $category['name'] }}" data-parent="0">
                                            {{--#JCN add the img to category--}}
                                                <button type="button" >
                                                    <img src="{{asset('/img/' . $category['id'] .'.jpg')}}" class="img-tumbnail">
                                                </button>
                                                <h4 class="tw-flex tw-items-center tw-justify-center"
                                                    style="margin-bottom: 0px; margin-top:0px; font-size: inherit; font-weight: inherit;">
                                                    {{ $category['name'] }}</h4>
                                            </div>
                                            <div class="tw-dw-card-actions tw-justify-center">
                                                {{--#JCN rename because form the box fi posible to call main-category to trigger th function--}}
                                                {{-- <button type="button" class="tw-dw-btn tw-dw-btn-accent tw-dw-btn-outline tw-dw-btn-sm main-category tw-mb-2" data-value="{{ $category['id'] }}" data-parent="0">{{ __('lang_v1.all') }}</button> --}}
                                                @if (!empty($category['sub_categories']))
                                                <button type="button" 
                                                class="tw-dw-btn tw-dw-btn-primary tw-dw-btn-outline tw-dw-btn-sm main-category tw-mb-2" data-parent="1" data-value="{{ $category['id'] }}" data-name="{{ $category['name'] }}">@lang('pagination.next')</button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                            @endforeach
                            @foreach ($categories as $category)
                                @if (!empty($category['sub_categories']))
                                    <div class="{{ $category['id'] }} all-sub-category" style="display: none">
                                        @foreach ($category['sub_categories'] as $sc)
                                            @if ($sc['parent_id'] != 0)
                                                <div class="col-md-3 col-xs-12 tw-mb-2 {{-- tw-mb-7 --}} tw-w-auto tw-h-auto tw-cursor-pointer product_category no-print"
                                                data-name="{{ $sc['name'] }}" data-value="{{ $sc['id'] }}">
                                                    <div
                                                        class="tw-dw-card tw-w-25 tw-bg-base-100 tw-shadow-sm tw-h-auto tw-shadow-[rgba(17,_17,_26,_0.1)_0px_0px_16px] tw-bg-white hover:tw-bg-white/60 tw-cursor-pointer tw-text-xs md:tw-text-sm tw-font-semibold tw-text-center tw-border-2">
                                                        <div class="tw-dw-card-body tw-h-28" style="margin-bottom: 20px">
                                                            {{--#JCN add the img to category--}}
                                                            <button type="button" >
                                                                <img src="{{asset('/img/' . $sc['id'] .'.jpg')}}" class="img-tumbnail">
                                                            </button>
                                                            <h4 class="tw-flex tw-items-center tw-justify-center"
                                                                style="margin-bottom: 0px; margin-top:0px; font-size: inherit; font-weight: inherit;">
                                                                {{ $sc['name'] }}</h4>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if (!empty($brands))
        <div class="col-md-4 !tw-px-2" id="product_brand_div">
            <div class="tw-dw-drawer tw-dw-drawer-end">
                <input id="my-drawer-brand" type="checkbox" class="tw-dw-drawer-toggle">
                <div class="tw-dw-drawer-content">
                    <!-- Page content here -->
                    <label for="my-drawer-brand"
                        class="{{$theme_pos_class}} hover:tw-animate-pulse focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-blue-500 focus:tw-ring-offset-2 active:tw-from-indigo-700 active:tw-to-blue-700 lg:tw-w-[98%] tw-w-full tw-flex tw-items-center tw-justify-center tw-gap-1 tw-text-base md:tw-text-lg tw-text-white tw-font-semibold tw-rounded-xl {{-- tw-h-12 --}} tw-cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" class="tw-w-5 icon icon-tabler icon-tabler-brand-beats"
                            width="44" height="30" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffffff"
                            fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                            <path d="M12.5 12.5m-3.5 0a3.5 3.5 0 1 0 7 0a3.5 3.5 0 1 0 -7 0" />
                            <path d="M9 12v-8" />
                        </svg>
                        @lang('brand.brands')
                    </label>
                </div>
                <div class="tw-dw-drawer-side" style="z-index: 4000">
                    <label for="my-drawer-brand" aria-label="close sidebar"
                        class="tw-dw-drawer-overlay overlay-brand"></label>
                    <div class="tw-dw-menu tw-w-2/4 tw-min-h-full tw-bg-white tw-p-6">

                        <div class="tw-flex tw-items-center tw-mb-16">
                            <h3 class="tw-text-center tw-mx-auto tw-bg-gradient-to-r tw-from-indigo-600 tw-to-blue-500 tw-text-transparent tw-bg-clip-text tw-font-bold tw-text-base md:tw-text-2xl tw-mb-16"
                                style="margin-bottom: 0px; margin-top:5px;">@lang('brand.brands')</h3>
                            <button type="button" class="tw-dw-btn tw-dw-btn-error close-side-bar-brand">
                                <i class="fa fa-times-circle" aria-hidden="true"></i>
                            </button>
                        </div>

                        <div class="row tw-mr-5">
                            @foreach ($brands as $key => $brand)
                                <div class="col-md-4 col-xs-12 tw-mb-5 tw-w-auto tw-h-auto tw-cursor-pointer product_brand no-print"
                                    data-value="{{ $key }}">
                                    <div
                                        class="tw-dw-card tw-w-25 tw-bg-base-100 tw-shadow-sm tw-h-auto tw-shadow-[rgba(17,_17,_26,_0.1)_0px_0px_16px] tw-bg-white hover:tw-bg-white/60 tw-cursor-pointer tw-text-xs md:tw-text-sm tw-font-semibold tw-text-center tw-border-2">
                                        <div class="tw-dw-card-body">
                                            <h4 class="tw-flex tw-items-center tw-justify-center"
                                                style="margin-bottom: 0px; margin-top:0px; font-size: inherit; font-weight: inherit;">
                                                {{ $brand }}
                                            </h4>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- used in repair : filter for service/product -->
    <div class="col-md-6 hide" id="product_service_div">
        {!! Form::select(
            'is_enabled_stock',
            ['' => __('messages.all'), 'product' => __('sale.product'), 'service' => __('lang_v1.service')],
            null,
            ['id' => 'is_enabled_stock', 'class' => 'select2', 'name' => null, 'style' => 'width:100% !important'],
        ) !!}
    </div>

    {{-- #JCN remove this button to show features products
    <div class="col-sm-4 @if (empty($featured_products)) hide @endif" id="feature_product_div">
        <button type="button" class="btn btn-primary btn-flat"
            id="show_featured_products">@lang('lang_v1.featured_products')</button>
    </div>
    --}}

    {{--#JCN Add button style like category to $featured_products --}}
    <div class=" @if (empty($featured_products)) hide @else col-md-4 @endif" id="feature_product_div">
        <div class="tw-dw-drawer tw-dw-drawer-end" >
            <input id="my-drawer-features" type="checkbox" class="tw-dw-drawer-toggle">
            <div class="tw-dw-drawer-features" id="show_featured_products">
                <!-- Page content here -->
                <label for="my-drawer-features" 
                    class="{{$theme_pos_class}} hover:tw-animate-pulse focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-blue-500 focus:tw-ring-offset-2 active:tw-from-indigo-700 active:tw-to-blue-700 lg:tw-w-[98%] tw-w-full tw-flex tw-items-center tw-justify-center tw-gap-1 tw-text-base md:tw-text-lg tw-text-white tw-font-semibold tw-rounded-xl {{-- tw-h-12 --}} tw-cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" class="tw-w-5 icon icon-tabler icon-tabler-brand-beats" height="25" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m17 21-5-4-5 4V3.889a.92.92 0 0 1 .244-.629.808.808 0 0 1 .59-.26h8.333a.81.81 0 0 1 .589.26.92.92 0 0 1 .244.63V21Z"/>
                        </svg>
                    @lang('lang_v1.featured_products')
                </label>
            </div>
        </div>
    </div>
</div>

<div class="row" id="featured_products_box" style="display: none;">
    @if (!empty($featured_products))
        @include('sale_pos.partials.featured_products')
    @endif
</div>

{{--#JCN Ini--}}
{{-- 2024 If no allowed dont show the bar--}}
<!-- Divider and categories -->
@can('poscustom.category_bar')
    <div style="clear:both; padding: 2px 0px 2px 0px;"></div> 
    {{-- To center items in the DIV and make responsive--}}
    <div class="pos_items_category_center {{$theme_pos_class}}" style="border-radius: 8px; border: 2px solid #FFF;" > {{-- To center items in the DIV + responsive--}}
        @if(!empty($categories))            
            @include('PosCustom::sale_pos.partials.pos_category_product')
        @endif
    </div>
    @php
        $max_winproduct_size = 475;
    @endphp
@else
    @php
        $max_winproduct_size = 525;
    @endphp
@endcan

<div class="{{$theme_pos_class}}" style="border-radius: 8px; border: 2px solid #FFF;" >
	{{-- 
	<i class="fas fa-align-left"></i> @lang('product.available_products')  
	<i class="fas fa-hamburger"></i>
	<i class="fas fa-hotdog"></i> 
	<i class="fas fa-coffee"></i> <i> ... @lang('product.category'): </i> 
	<input class="{{$theme_pos_class}}" disabled id="name_category"  style="border:#ccc; align:center;" >
 	--}}


	{{-- #JCN using pagination no scroll background-color:#D0D5DD; --}}
	<div class="btn-group btn-group-justified pos-grid-nav">
		<div class="btn-group" style="width: 35%" >
			<button style="color: #FFF" class="btn {{$theme_pos_class}}" title="Previous" type="button" id="previous">
				<i class="fa fa-chevron-circle-left fa-lg" aria-hidden="true" > </i> @lang('poscustom::lang.previous') 
			</button>
		</div>
		{{-- Pagination: To show the current and last page --}}
		<div class="btn-group primary" style="width: 30%">
			<input value="@lang('lang_v1.all_category')"  disabled class="btn"  id="name_category" style="background-color:#D0D5DD; font-weight: bold; text-align: center;color: red; "> 
		</div>
		<div class="btn-group primary" style="width: 10%">
			<input  disabled class="btn"  id="pagePN" style="background-color:#D0D5DD; font-weight: bold; text-align: center;color: red; "> 
		</div>
			
		<div class="btn-group" style="width: 35%">
			<button style="color: #FFF" class="btn {{$theme_pos_class}}" title="Next" type="button" id="next">
				@lang('poscustom::lang.next') <i class="fa fa-chevron-circle-right fa-lg" aria-hidden="true"></i>
			</button>
		</div>
	</div>
	
</div>
{{-- #JCN End  --}}


{{--#JCN testing window size --}}
<div class="row">
    <input type="hidden" id="suggestion_page" value="1">
    <div class="col-md-12">
        <div class="tw-text-center" style="max-height: {{$max_winproduct_size}}px; overflow: visible; overflow-y: auto;" id="product_list_body"></div>
    </div>
    <div class="col-md-12 text-center" id="suggestion_page_loader" style="display: none;">
        <i class="fa fa-spinner fa-spin fa-2x" style="color:red"></i>
    </div>
</div>
