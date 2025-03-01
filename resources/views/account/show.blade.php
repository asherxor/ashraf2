@extends('layouts.app')
@section('title', __('account.account_book'))

@section('content')
<style>
@media print {
    body {
        width: 190mm;
        height: 277mm;
        margin: 10mm;
        font-size: 12px;
    }

    /* ✅ Hide UI elements in print mode */
    .content-header, .box-header, .btn, .filters {
        display: none !important;
    }

    /* ✅ Ensure table fits within A4 width */
    .table {
        width: 100%;
        border-collapse: collapse;
        font-size: 12px;
    }

    .table th, .table td {
        padding: 8px;
        text-align: center; /* ✅ Center text */
        border: 1px solid black;
        -webkit-print-color-adjust: exact; /* ✅ Force color printing in WebKit browsers */
        print-color-adjust: exact;
    }

    /* ✅ Style table headers */
    .table thead th {
        background-color: #007BFF !important; /* ✅ Blue header */
        color: white !important;
        font-weight: bold;
    }

    
}
</style>

<section class="content-header">
    <h1 class="tw-text-xl md:tw-text-3xl tw-font-bold tw-text-black">@lang('account.account_book')</h1>
</section>

<section class="content">
    <div class="row">
        <div class="col-sm-4 col-xs-6">
            <div class="box box-solid">
                <div class="box-body">
                    <table class="table">
                        <tr>
                            <th>@lang('account.account_name'):</th>
                            <td>{{ $account->name }}</td>
                        </tr>
                        <tr>
                            <th>@lang('lang_v1.account_type'):</th>
                            <td>
                                @if(!empty($account->account_type->parent_account)) 
                                    {{ $account->account_type->parent_account->name }} - 
                                @endif 
                                {{ $account->account_type->name ?? '' }}
                            </td>
                        </tr>
                        <tr>
                            <th>@lang('account.account_number'):</th>
                            <td>{{ $account->account_number }}</td>
                        </tr>
                        <tr>
                            <th>@lang('lang_v1.balance'):</th>
                            <td><span id="account_balance"></span></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-sm-8 col-xs-12">
            <div class="box box-solid">
                <div class="box-header">
                    <h3 class="box-title"><i class="fa fa-filter"></i> @lang('report.filters'):</h3>
                </div>
                <div class="box-body">
                    <div class="col-sm-6">
                        <div class="form-group">
                            {!! Form::label('transaction_date_range', __('report.date_range') . ':') !!}
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                {!! Form::text('transaction_date_range', null, ['class' => 'form-control', 'readonly', 'placeholder' => __('report.date_range')]) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            {!! Form::label('transaction_type', __('account.transaction_type') . ':') !!}
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fas fa-exchange-alt"></i></span>
                                {!! Form::select('transaction_type', ['' => __('messages.all'),'credit' => __('account.credit'), 'debit' => __('account.debit')], '', ['class' => 'form-control']) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="box">
                <div class="box-body">
                    @can('account.access')
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="account_book">
                                <thead>
                                    <tr>
                                        <th>@lang('messages.date')</th>
                                        <th>@lang('lang_v1.description')</th>
                                        <th>@lang('lang_v1.payment_method')</th>
                                        <th>@lang('lang_v1.payment_details')</th>
                                        <th>@lang('brand.note')</th>
                                        <th>@lang('lang_v1.added_by')</th>
                                        <th>@lang('account.debit')</th>
                                        <th>@lang('account.credit')</th>
                                        <th>@lang('lang_v1.balance')</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr class="bg-gray font-17 footer-total text-center">
                                        <td colspan="6"><strong>@lang('sale.total'):</strong></td>
                                        <td class="footer_total_debit"></td>
                                        <td class="footer_total_credit"></td>
                                        <td class="footer_total_balance"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @endcan
                </div>
            </div>
        </div>
    </div>

</section>

@endsection

@section('javascript')
<script>
$(document).ready(function () {
    loadAccountData();

    function loadAccountData() {
        updateAccountBalance();
        initDateRangePicker();
        loadTransactionTable();
    }

    function updateAccountBalance() {
        $('#account_balance').html('<i class="fas fa-sync fa-spin"></i>');

        $.ajax({
            url: '{{ action([\App\Http\Controllers\AccountController::class, "getAccountBalance"], [$account->id]) }}',
            dataType: "json",
            success: function (data) {
                console.log("Balance Data:", data);

                if (data.balance !== undefined) {
                    $('#account_balance').text(__currency_trans_from_en(data.balance, true));
                } else {
                    $('#account_balance').html('<span class="text-danger">@lang("messages.error_loading_balance")</span>');
                }
            },
            error: function (xhr) {
                console.error("Balance Update Error:", xhr.responseText);
                $('#account_balance').html('<span class="text-danger">@lang("messages.error_loading_balance")</span>');
            }
        });
    }

    function initDateRangePicker() {
        dateRangeSettings.startDate = moment().subtract(6, 'days');
        dateRangeSettings.endDate = moment();

        $('#transaction_date_range').daterangepicker(dateRangeSettings, function (start, end) {
            $('#transaction_date_range').val(start.format(moment_date_format) + ' ~ ' + end.format(moment_date_format));
            account_book.ajax.reload();
            updateAccountBalance();
        });

        $('#transaction_date_range').on('cancel.daterangepicker', function () {
            $('#transaction_date_range').val('');
            account_book.ajax.reload();
            updateAccountBalance();
        });
    }

    function loadTransactionTable() {
        if ($.fn.DataTable.isDataTable('#account_book')) {
            $('#account_book').DataTable().destroy();
        }

        account_book = $('#account_book').DataTable({
            processing: true,
            serverSide: true,
            order: [[0, 'asc']],
            ajax: {
                url: '{{ action([\App\Http\Controllers\AccountController::class, "show"], [$account->id]) }}',
                data: function (d) {
                    let start = '', end = '';
                    if ($('#transaction_date_range').val()) {
                        start = $('input#transaction_date_range').data('daterangepicker').startDate.format('YYYY-MM-DD');
                        end = $('input#transaction_date_range').data('daterangepicker').endDate.format('YYYY-MM-DD');
                    }
                    d.start_date = start;
                    d.end_date = end;
                    d.transaction_type = $('#transaction_type').val() || ''; // ✅ Ensure type is sent
                    console.log("📩 Sending Filters: Start:", start, "End:", end, "Type:", d.transaction_type);
                }
            },
            columns: [
                {data: 'operation_date', name: 'operation_date'},
                {data: 'sub_type', name: 'sub_type'},
                {data: 'type', name: 'type'},
                {data: 'ref_no', name: 'ref_no'},
                {data: 'note', name: 'note'},
                {data: 'added_by', name: 'added_by'},
                {data: 'debit', name: 'debit', searchable: false},
                {data: 'credit', name: 'credit', searchable: false},
                {data: 'balance', name: 'balance', searchable: false},
            ],
            drawCallback: function () {
                console.log("🔄 Transactions reloaded. Updating balance...");
                updateAccountBalance();
                updateFooterTotals();
            },
        });
    }

    function updateFooterTotals() {
        let totalDebit = 0, totalCredit = 0, totalBalance = 0;

        $('#account_book tbody tr').each(function () {
            let debitValue = parseFloat($(this).find("td:eq(6)").text().replace(/[^\d.-]/g, '')) || 0;
            let creditValue = parseFloat($(this).find("td:eq(7)").text().replace(/[^\d.-]/g, '')) || 0;
            let balanceValue = parseFloat($(this).find("td:eq(8)").text().replace(/[^\d.-]/g, '')) || 0;

            totalDebit += debitValue;
            totalCredit += creditValue;
            totalBalance = balanceValue; // ✅ Running balance from last row
        });

        console.log("✅ Total Debit:", totalDebit, "✅ Total Credit:", totalCredit, "✅ Final Balance:", totalBalance);

        $('.footer_total_debit').html(__currency_trans_from_en(totalDebit, true));
        $('.footer_total_credit').html(__currency_trans_from_en(totalCredit, true));
        $('.footer_total_balance').html(__currency_trans_from_en(totalBalance, true));
    }

    // ✅ Reload table and update totals when transaction type filter changes
    $('#transaction_type').change(function () {
        console.log("🔄 Transaction Type Changed:", $(this).val());
        account_book.ajax.reload(function () {
            updateFooterTotals();
        });
    });
});

</script>
@endsection
