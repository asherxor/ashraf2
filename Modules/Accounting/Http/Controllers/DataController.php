<?php

namespace Modules\Accounting\Http\Controllers;

use App\Account;
use App\Contact;
use App\Transaction;
use App\Utils\ModuleUtil;
use App\Utils\Util;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Menu;
use Modules\Accounting\Entities\AccountingAccount;
use Modules\Accounting\Utils\AccountingUtil;

class DataController extends Controller
{
    /**
     * Superadmin package permissions
     *
     * @return array
     */
    public function superadmin_package()
    {
        return [
            [
                'name' => 'accounting_module',
                'label' => __('accounting::lang.accounting_module'),
                'default' => false,
            ],
        ];
    }

    /**
     * Adds cms menus
     *
     * @return null
     */
    public function modifyAdminMenu()
    {
        $business_id = session()->get('user.business_id');
        $module_util = new ModuleUtil();

        $is_accounting_enabled = (bool) $module_util->hasThePermissionInSubscription($business_id, 'accounting_module');

        $commonUtil = new Util();
        $is_admin = $commonUtil->is_admin(auth()->user(), $business_id);
        $menu = Menu::instance('admin-sidebar-menu');
        if (auth()->user()->can('accounting.access_accounting_module') && $is_accounting_enabled) {
            $menu->dropdown(
                __('accounting::lang.accounting'),
                function ($sub) {
//                    $sub->url(
//                        action([\Modules\Accounting\Http\Controllers\AccountingController::class, 'dashboard']),
//                        __('accounting::lang.accounting'),
//                        ['icon' => '', 'active' => request()->segment(1) == 'accounting']
//                    );
                    if(auth()->user()->can('accounting.manage_accounts'))
                    {
                        $sub->url(
                            action([\Modules\Accounting\Http\Controllers\CoaController::class, 'index']),
                            __('accounting::lang.chart_of_accounts'),
                            ['icon' => '', 'active' => request()->segment(2) == 'chart-of-accounts']
                        );
                        $sub->url(
                            action([\Modules\Accounting\Http\Controllers\CoaController::class, 'ledger'],
                                \Modules\Accounting\Utils\AccountingUtil::getAccountingAccountID(4101)
                            ),
                            __('accounting::lang.ledger'),
                            ['icon' => '', 'active' => request()->segment(2) ==  'ledger']
                        );
                    }
                    if(auth()->user()->can('accounting.view_journal'))
                    {
                        $sub->url(
                            action([\Modules\Accounting\Http\Controllers\JournalEntryController::class, 'index']),
                            __('accounting::lang.journal_entry'),
                            ['icon' => '', 'active' =>request()->segment(2) == 'journal-entry']
                        );
                    }
                    if(auth()->user()->can('accounting.view_reports'))
                    {
                        $sub->url(
                            action([\Modules\Accounting\Http\Controllers\ReportController::class, 'index']),
                            __('accounting::lang.reports'),
                            ['icon' => '',  'active' =>request()->segment(2) == 'reports']
                        );
                    }
                },
                ['icon' => 'fas fa-money-check fa', 'id' => 'accounting_mkamel']
            )->order(50);
        }
    }

    /**
     * Defines user permissions for the module.
     *
     * @return array
     */
    public function user_permissions()
    {
        return [
            [
                'value' => 'accounting.access_accounting_module',
                'label' => __('accounting::lang.access_accounting_module'),
                'default' => false,
            ],
            [
                'value' => 'accounting.manage_accounts',
                'label' => __('accounting::lang.manage_accounts'),
                'default' => false,
            ],
            [
                'value' => 'accounting.view_journal',
                'label' => __('accounting::lang.view_journal'),
                'default' => false,
            ],
            [
                'value' => 'accounting.add_journal',
                'label' => __('accounting::lang.add_journal'),
                'default' => false,
            ],
            [
                'value' => 'accounting.edit_journal',
                'label' => __('accounting::lang.edit_journal'),
                'default' => false,
            ],
            [
                'value' => 'accounting.delete_journal',
                'label' => __('accounting::lang.delete_journal'),
                'default' => false,
            ],
            [
                'value' => 'accounting.map_transactions',
                'label' => __('accounting::lang.map_transactions'),
                'default' => false,
            ],
            [
                'value' => 'accounting.view_transfer',
                'label' => __('accounting::lang.view_transfer'),
                'default' => false,
            ],
            [
                'value' => 'accounting.add_transfer',
                'label' => __('accounting::lang.add_transfer'),
                'default' => false,
            ],
            [
                'value' => 'accounting.edit_transfer',
                'label' => __('accounting::lang.edit_transfer'),
                'default' => false,
            ],
            [
                'value' => 'accounting.delete_transfer',
                'label' => __('accounting::lang.delete_transfer'),
                'default' => false,
            ],
            [
                'value' => 'accounting.manage_budget',
                'label' => __('accounting::lang.manage_budget'),
                'default' => false,
            ],
            [
                'value' => 'accounting.view_reports',
                'label' => __('accounting::lang.view_reports'),
                'default' => false,
            ],
        ];
    }

    public function MKamel_checkTreeAccountingDefined()
    {
        return AccountingUtil::checkTreeOfAccountsIsHere();
    }

    public function MKamel_store999($data)
    {
        //Request $request,Account $account

        $request = $data['request'];
        $account = $data['account'];

        $business_id = $request->session()->get('user.business_id');

        $user_id = $request->session()->get('user.id');

        $tree_accs = AccountingUtil::checkTreeOfAccountsIsHere();

        if($tree_accs)
        {
            $acc_c = AccountingAccount::find($request->from_account);

            $account_acc = [
                0 => [
                    'name' => $request->name,
                    'business_id' => $business_id,
                    'account_primary_type' => 'asset',
                    'account_sub_type_id' => 11,
                    'parent_account_id' => $acc_c->id,
                    'detail_type_id' => $acc_c->gl_code,
                    'gl_code' => AccountingUtil::generateGlCodeForAccountingAccount($acc_c->gl_code,$business_id),
                    'status' => 'active',
                    'created_by' => $user_id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                    'link_table' => 'accounts',
                    'link_id' => $account->id,
                ],
            ];

            AccountingAccount::insert($account_acc);

        }
    }

    public function MKamel_store000($data)
    {
        //Request $request,Array $input,Array $output

        $request = $data['request'];
        $input = $data['input'];
        $output = $data['output'];

        $business_id = $request->session()->get('user.business_id');

        $tree_accs =  AccountingUtil::checkTreeOfAccountsIsHere();

        if($tree_accs)
        {
            $contact_is_here = AccountingAccount::where("business_id",$business_id)
                ->where("link_table",'contacts')
                ->where("link_id",$output['data']->id)
                ->first();

            if(!isset($contact_is_here->id))
            {
                if($input['type'] == "customer" || $input['type'] == "both")
                {
                    $default_accounts = [
                        0 => [
                            'name' => $input['supplier_business_name'] != "" ? $input['supplier_business_name'] : $input['first_name']." ".$input['middle_name']." ".$input['last_name'],
                            'business_id' => $business_id,
                            'account_primary_type' => 'asset',
                            'account_sub_type_id' => 11,
                            'parent_account_id' => AccountingUtil::getAccountingAccountID(1103,$business_id),
                            'detail_type_id' => 1103,
                            'gl_code' => AccountingUtil::generateGlCodeForAccountingAccount(1103,$business_id),
                            'status' => 'active',
                            'created_by' => $request->session()->get('user.id'),
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                            'link_table' => 'contacts',
                            'link_id' => $output['data']->id,
                        ],
                    ];

                    AccountingAccount::insert($default_accounts);

                    if(isset($output['opening_balance_transaction']) && $output['opening_balance_transaction'] != null)
                    {
                        $from_account = \Illuminate\Support\Facades\DB::table('accounting_accounts')->orderBy('id', 'desc')->first()->id;

                        $to_account = AccountingUtil::getAccountingAccountID('3201',$business_id);

                        AccountingUtil::create_update_opening_balance_journal_entry
                        (
                            $to_account,
                            $from_account,
                            $output['opening_balance_transaction']->final_total,
                            $business_id,
                            $output['opening_balance_transaction']->location_id,
                            $request->session()->get('user.id'),
                            Carbon::now(),
                            '',
                            'transactions',
                            $output['opening_balance_transaction']->id
                        );
                    }

                }
                elseif($input['type'] == "supplier")
                {
                    $default_accounts = [
                        0 => [
                            'name' => $input['supplier_business_name'] != "" ? $input['supplier_business_name'] : $input['first_name']." ".$input['middle_name']." ".$input['last_name'],
                            'business_id' => $business_id,
                            'account_primary_type' => 'liability',
                            'account_sub_type_id' => 21,
                            'parent_account_id' => AccountingUtil::getAccountingAccountID(2101,$business_id),
                            'detail_type_id' => 2101,
                            'gl_code' => AccountingUtil::generateGlCodeForAccountingAccount(2101,$business_id),
                            'status' => 'active',
                            'created_by' => $request->session()->get('user.id'),
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                            'link_table' => 'contacts',
                            'link_id' => $output['data']->id,
                        ],
                    ];

                    AccountingAccount::insert($default_accounts);

                    if(isset($output['opening_balance_transaction']) && $output['opening_balance_transaction'] != null)
                    {
                        $from_account = AccountingUtil::getAccountingAccountID('3201',$business_id);

                        $to_account = \Illuminate\Support\Facades\DB::table('accounting_accounts')->orderBy('id', 'desc')->first()->id;

                        AccountingUtil::create_update_opening_balance_journal_entry
                        (
                            $to_account,
                            $from_account,
                            $output['opening_balance_transaction']->final_total,
                            $business_id,
                            $output['opening_balance_transaction']->location_id,
                            $request->session()->get('user.id'),
                            Carbon::now(),
                            '',
                            'transactions',
                            $output['opening_balance_transaction']->id
                        );
                    }

                }

            }
            else
            {

                $contact_is_here->name = $input['supplier_business_name'] != "" ? $input['supplier_business_name'] : $input['first_name']." ".$input['middle_name']." ".$input['last_name'];

                $contact_is_here->save();

                if($input['type'] == "customer" || $input['type'] == "both")
                {
                    if(isset($output['opening_balance_transaction']) && $output['opening_balance_transaction'] != null)
                    {

                        $to_account = AccountingUtil::getAccountingAccountID('3201',$business_id);

                        AccountingUtil::create_update_opening_balance_journal_entry
                        (
                            $to_account,
                            $contact_is_here->id,
                            $output['opening_balance_transaction']->final_total,
                            $business_id,
                            $output['opening_balance_transaction']->location_id,
                            $request->session()->get('user.id'),
                            Carbon::now(),
                            '',
                            'transactions',
                            $output['opening_balance_transaction']->id
                        );
                    }
                }
                elseif($input['type'] == "supplier")
                {
                    if(isset($output['opening_balance_transaction']) && $output['opening_balance_transaction'] != null)
                    {

                        $from_account = AccountingUtil::getAccountingAccountID('3201',$business_id);

                        AccountingUtil::create_update_opening_balance_journal_entry
                        (
                            $contact_is_here->id,
                            $from_account,
                            $output['opening_balance_transaction']->final_total,
                            $business_id,
                            $output['opening_balance_transaction']->location_id,
                            $request->session()->get('user.id'),
                            Carbon::now(),
                            '',
                            'transactions',
                            $output['opening_balance_transaction']->id
                        );
                    }
                }
            }
        }

    }

    public function MKamel_check111($data)
    {
        $request = $data['request'];

        $business_id = $request->session()->get('user.business_id');

        $tree_accs = AccountingUtil::checkTreeOfAccountsIsHere();

        if($tree_accs)
        {
            $expense_linked = AccountingUtil::getLinkedWithAccountingAccount($business_id,"expense_categories",$request->expense_category_id);

            if($expense_linked == null)
                return ['success' => 0,
                    'msg' => __('accounting::lang.expense_not_linked'),
                ];

            $acc_id = !empty($request->input('payment')) && $request->input("payment")[0]["account_id"] != null ? $request->input("payment")[0]["account_id"] : -1;

            $account_linked = AccountingUtil::getLinkedWithAccountingAccount($business_id,"accounts",$acc_id);

            if($account_linked == null)
                return ['success' => 0,
                    'msg' => __('accounting::lang.account_not_linked'),
                ];

            return   ['success' => 1,
                'expense_linked' => $expense_linked,
                'account_linked' => $account_linked,
            ];
        }

        return   ['success' => 2,
        ];
    }

    public function MKamel_store111($data)
    {
        $request = $data['request'];

        $expense_linked = $data['expense_linked'];

        $expense = $data['expense'];

        $account_linked = $data['account_linked'];

        $business_id = $request->session()->get('user.business_id');

        $tree_accs = AccountingUtil::checkTreeOfAccountsIsHere();

        if($tree_accs)
        {
            $recs = [];

            //expense
            $_rec1['accounting_account_id'] = $expense_linked->id;
            $_rec1['amount'] = $expense->total_before_tax;
            $_rec1['type'] = 'debit';

            $recs[] = $_rec1;

            if($expense->tax_amount != 0)
            {
                //tax
                $_rec2['accounting_account_id'] = AccountingUtil::getAccountingAccountID(2105,$business_id);
                $_rec2['amount'] = $expense->tax_amount;
                $_rec2['type'] = 'debit';

                $recs[] = $_rec2;
            }

            //cash or banck account
            $_rec3['accounting_account_id'] = $account_linked->id;
            $_rec3['amount'] = $expense->final_total;
            $_rec3['type'] = 'credit';

            $recs[] = $_rec3;

            AccountingUtil::createJournalEntry(
                'expense',
                $business_id,
                $expense->location_id,
                $expense->created_by,
                $expense->transaction_date,
                'transactions',
                $expense->id,
                $recs
            );
        }
    }

    public function MKamel_store100($data)
    {

        $request = $data['request'];

        $user = $data['user'];

        $business_id = $request->session()->get('user.business_id');

        $tree_accs = AccountingUtil::checkTreeOfAccountsIsHere();

        if($tree_accs)
        {
            $user_is_here = AccountingAccount::where("business_id",$business_id)
                ->where("link_table",'users')
                ->where("link_id",$user->id)
                ->first();

            if(!isset($user_is_here->id))
            {
                $default_accounts = [
                    0 => [
                        'name' => $user->user_full_name,
                        'business_id' => $business_id,
                        'account_primary_type' => 'liability',
                        'account_sub_type_id' => 21,
                        'parent_account_id' => AccountingUtil::getAccountingAccountID(2103,$business_id),
                        'detail_type_id' => 2103,
                        'gl_code' => AccountingUtil::generateGlCodeForAccountingAccount(2103,$business_id),
                        'status' => 'active',
                        'created_by' => request()->session()->get('user.id'),
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                        'link_table' => 'users',
                        'link_id' => $user->id,
                    ],
                ];

                AccountingAccount::insert($default_accounts);

            }
            else
            {

                $user_is_here->name = $user->user_full_name;

                $user_is_here->save();

            }
        }

    }

    public function MKamel_store110($data)
    {
        $request = $data['request'];

        $new_transaction_data = $data['new_transaction_data'];

        $edit_transaction_data = $data['edit_transaction_data'];

        $delete_transaction_data = $data['delete_transaction_data'];

        $location_id = $data['location_id'];

        $user_id = $data['user_id'];

        $business_id = $request->session()->get('user.business_id');

        $tree_accs = AccountingUtil::checkTreeOfAccountsIsHere();

        if ($tree_accs) {

            //for new_transaction_data
            foreach ($new_transaction_data as $key=>$value)
            {
                AccountingUtil::addJournal($value,$business_id,$location_id,$user_id);
            }

            //for edit_transaction_data
            foreach ($edit_transaction_data as $key=>$value)
            {
                $purchase_line = $value["purchase_line"];

                $price = $purchase_line->purchase_price * abs($purchase_line->quantity);

                $acc_trans_mapping = AccountingUtil::getLinkedWithAccountingAccTransMapping($business_id,'transactions',$purchase_line->transaction_id);

                if($acc_trans_mapping != null)
                {
                    $acc_trans_mapping->operation_date = $value["transaction_date"];

                    $acc_trans_mapping->note = $value["additional_notes"];

                    foreach ($acc_trans_mapping->childs() as $child)
                    {
                        $acc = $child->account()->first();

                        if($purchase_line->quantity > 0)
                        {
                            if($acc->gl_code == 34)
                            {
                                $child->type = 'credit';
                            }
                            elseif($acc->gl_code == 1106)
                            {
                                $child->type = 'debit';
                            }
                        }
                        else
                        {
                            if($acc->gl_code == 34)
                            {
                                $child->type = 'debit';
                            }
                            elseif($acc->gl_code == 1106)
                            {
                                $child->type = 'credit';
                            }
                        }

                        $child->amount = $price;
                        $child->save();
                    }

                    $acc_trans_mapping->save();
                }
                else
                {
                    AccountingUtil::addJournal($value,$business_id,$location_id,$user_id);
                }

            }

            //for delete_transaction_data
            foreach ($delete_transaction_data as $value)
            {
                $acc_trans_mapping = AccountingUtil::getLinkedWithAccountingAccTransMapping($business_id,'transactions',$value);

                if($acc_trans_mapping != null)
                {
                    foreach ($acc_trans_mapping->childs() as $child)
                    {
                        $child->delete();
                    }

                    $acc_trans_mapping->delete();
                }
            }
        }
    }

    public function MKamel_check333($data)
    {
        $request = $data['request'];

        $business_id = $request->session()->get('user.business_id');

        $tree_accs = AccountingUtil::checkTreeOfAccountsIsHere();

        if($tree_accs)
        {
            $supplier_linked = AccountingUtil::getLinkedWithAccountingAccount($business_id,"contacts",$request->contact_id);

            if($supplier_linked == null)
            {
                return ['success' => 0,
                    'msg' => __('accounting::lang.supplier_not_linked'),
                ];

            }

            return [
                'success' => 1,
                'supplier_linked' => $supplier_linked
            ];
        }

        return [
            'success' => 2,
        ];

    }

    public function MKamel_store333($data)
    {

        $request = $data['request'];

        $transaction_data = $data['transaction_data'];

        $supplier_linked = $data['supplier_linked'];

        $user_id = $data['user_id'];

        $transaction = $data['transaction'];

        $business_id = $request->session()->get('user.business_id');

        $tree_accs = AccountingUtil::checkTreeOfAccountsIsHere();

        if($tree_accs)
        {
            $output_amounts = AccountingUtil::getAmountsAcounts4($request->input('purchases'));

            $recs = [];

            //stock
            $_rec1['accounting_account_id'] =  AccountingUtil::getAccountingAccountID(1106,$business_id);
            $_rec1['amount'] =  $output_amounts["stock"];
            $_rec1['type'] = 'debit';

            $recs[] = $_rec1;

            if($output_amounts["tax"] != 0)
            {
                //tax
                $_rec2['accounting_account_id'] =  AccountingUtil::getAccountingAccountID(2105,$business_id);
                $_rec2['amount'] =  $output_amounts["tax"];
                $_rec2['type'] = 'debit';

                $recs[] = $_rec2;
            }

            if($transaction_data["shipping_charges"] != 0)
            {
                //shipping_charges
                $_rec3['accounting_account_id'] =  AccountingUtil::getAccountingAccountID(5104,$business_id);
                $_rec3['amount'] =  $transaction_data["shipping_charges"];
                $_rec3['type'] = 'debit';

                $recs[] = $_rec3;
            }

            if($output_amounts["discount"] != 0)
            {
                //discount
                $_rec4['accounting_account_id'] =  AccountingUtil::getAccountingAccountID(5105,$business_id);
                $_rec4['amount'] =  $output_amounts["discount"];
                $_rec4['type'] = 'credit';

                $recs[] = $_rec4;
            }

            //supplier
            $_rec5['accounting_account_id'] =  $supplier_linked->id;
            $_rec5['amount'] = $output_amounts["supplier"] + $transaction_data["shipping_charges"];
            $_rec5['type'] = 'credit';

            $recs[] = $_rec5;

            AccountingUtil::createJournalEntry(
                'journal_entry',
                $business_id,
                $transaction_data['location_id'],
                $user_id,
                $transaction_data['transaction_date'],
                'transactions',
                $transaction->id,
                $recs
            );
        }
    }

    public function MKamel_check222($data)
    {
        $request = $data['request'];

        $purchase = $data['purchase'];

        $business_id = $request->session()->get('user.business_id');

        $tree_accs = AccountingUtil::checkTreeOfAccountsIsHere();

        if($tree_accs)
        {
            $supplier_linked = AccountingUtil::getLinkedWithAccountingAccount($business_id,"contacts",$purchase->contact_id);

            if($supplier_linked == null)
            {
                return ['success' => 0,
                    'msg' => __('accounting::lang.supplier_not_linked'),
                ];
            }

            return [
                'success' => 1,
                'supplier_linked' => $supplier_linked
            ];
        }

        return [
            'success' => 2,
        ];
    }


    public function  MKamel_store222($data)
    {
        $request = $data['request'];

        $purchase = $data['purchase'];

        $return_quantities = $data['return_quantities'];

        $supplier_linked = $data['supplier_linked'];

        $return_transaction = $data['return_transaction'];

        $business_id = $request->session()->get('user.business_id');

        $tree_accs = AccountingUtil::checkTreeOfAccountsIsHere();

        if($tree_accs)
        {
            $output_amounts = AccountingUtil::getAmountsAcounts5($return_quantities, $purchase->purchase_lines);

            $recs = [];

            //stock
            $_rec1['accounting_account_id'] = AccountingUtil::getAccountingAccountID(1106, $business_id);
            $_rec1['amount'] = $output_amounts["stock"];
            $_rec1['type'] = 'credit';

            $recs[] = $_rec1;

            if ($output_amounts["tax"] != 0) {
                //tax
                $_rec2['accounting_account_id'] = AccountingUtil::getAccountingAccountID(2105, $business_id);
                $_rec2['amount'] = $output_amounts["tax"];
                $_rec2['type'] = 'credit';

                $recs[] = $_rec2;
            }

            //supplier
            $_rec3['accounting_account_id'] = $supplier_linked->id;
            $_rec3['amount'] = $output_amounts["supplier"];
            $_rec3['type'] = 'debit';

            $recs[] = $_rec3;

            if ($output_amounts["discount"] != 0) {
                //discount
                $_rec4['accounting_account_id'] = AccountingUtil::getAccountingAccountID(5105, $business_id);
                $_rec4['amount'] = $output_amounts["discount"];
                $_rec4['type'] = 'debit';

                $recs[] = $_rec4;
            }

            AccountingUtil::createJournalEntry(
                'journal_entry',
                $business_id,
                $return_transaction->location_id,
                $return_transaction->created_by,
                $return_transaction->transaction_date,
                'transactions',
                $return_transaction->id,
                $recs
            );
        }
    }

    public function MKamel_check444($data)
    {
        $request = $data['request'];

        $input = $data['input'];

        $business_id = $request->session()->get('user.business_id');

        if($input['status'] == 'final' && $input['is_suspend'] == 0)
        {

            $tree_accs = AccountingUtil::checkTreeOfAccountsIsHere();

            if($tree_accs)
            {
                $customer_linked = AccountingUtil::getLinkedWithAccountingAccount($business_id,"contacts",$input['contact_id']);

                if($customer_linked == null) {
                    return ['success' => 0,
                        'msg' => __('accounting::lang.customer_not_linked'),
                    ];
                }

                $accounts_linked = [];
                if($input['is_credit_sale'] == 0)
                {
                    foreach ($input['payment'] as $payment_done)
                    {
                        if($payment_done["method"] != "advance")
                        {
                            $account_linked = AccountingUtil::getLinkedWithAccountingAccount($business_id,"accounts",$payment_done['account_id']);

                            if($account_linked == null)
                            {
                                return [
                                    'success' => 0,
                                    'msg' => __('accounting::lang.account_not_linked'),
                                ];
                            }

                            $accounts_linked [] = $account_linked;
                        }
                    }
                }

                return [
                    'success' => 1,
                    'msg' => __('accounting::lang.account_not_linked'),
                    'accounts_linked' => $accounts_linked,
                    'customer_linked' => $customer_linked
                ];

            }
        }

        return [
            'success' => 2,
        ];
    }

    public function MKamel_store444($data)
    {

        $request = $data['request'];

        $input = $data['input'];

        $user_id = $data['user_id'];

        $transaction = $data['transaction'];

        $customer_linked = $data['customer_linked'];

        $business_id = $request->session()->get('user.business_id');

        $tree_accs = AccountingUtil::checkTreeOfAccountsIsHere();

        if($input['status'] == 'final' && $input['is_suspend'] == 0)
        {
            if($tree_accs) {

                $output_amounts = AccountingUtil::getAmountsAcounts($input['products']);

                //all discount
                $all_discount = AccountingUtil::r_s($input['discount_amount']);
                if($input['discount_type'] == 'percentage')
                    $all_discount = $output_amounts["customer"] * ($all_discount / 100);

                //redeemed
                $rp_redeemed_amount = AccountingUtil::r_s($input['rp_redeemed_amount']);

                $all_discount_and_redeemed_amount = $all_discount + $rp_redeemed_amount;

                if($all_discount_and_redeemed_amount != 0)
                {
                    $new_customer_amount =  $output_amounts["customer"] - $all_discount_and_redeemed_amount;

                    $new_revenue_of_products_amount = $output_amounts["revenue_of_products"] * $new_customer_amount /  $output_amounts["customer"];

                    $output_amounts["tax"] = $new_customer_amount - $new_revenue_of_products_amount;

                    $output_amounts["customer"] = $new_customer_amount;

                    $output_amounts["revenue_of_products"] = $new_revenue_of_products_amount;

                    $output_amounts["discount"] = $output_amounts["discount"] + $all_discount_and_redeemed_amount;

                }

                $recs = [];

                //customer
                $_rec1['accounting_account_id'] = $customer_linked->id;
                $_rec1['amount'] = $output_amounts["customer"] + AccountingUtil::r_s($input['shipping_charges']);
                $_rec1['type'] = 'debit';

                $recs[] = $_rec1;

                if ($output_amounts["cost_of_goods"] != 0) {
                    //cost_of_goods
                    $_rec2['accounting_account_id'] = AccountingUtil::getAccountingAccountID(5101, $business_id);
                    $_rec2['amount'] = $output_amounts["cost_of_goods"];
                    $_rec2['type'] = 'debit';

                    $recs[] = $_rec2;
                }

                if ($output_amounts["discount"] != 0) {
                    //discount
                    $_rec3['accounting_account_id'] = AccountingUtil::getAccountingAccountID(4102, $business_id);
                    $_rec3['amount'] = $output_amounts["discount"];
                    $_rec3['type'] = 'debit';

                    $recs[] = $_rec3;
                }

                if ($output_amounts["stock"] != 0) {
                    //stock
                    $_rec4['accounting_account_id'] = AccountingUtil::getAccountingAccountID(1106, $business_id);
                    $_rec4['amount'] = $output_amounts["stock"] ;
                    $_rec4['type'] = 'credit';

                    $recs[] = $_rec4;
                }

                //revenue_of_products
                $_rec5['accounting_account_id'] = AccountingUtil::getAccountingAccountID(4101, $business_id);
                $_rec5['amount'] = $output_amounts["revenue_of_products"] + $all_discount_and_redeemed_amount;
                $_rec5['type'] = 'credit';

                $recs[] = $_rec5;

                if ($output_amounts["tax"] != 0) {
                    //tax
                    $_rec6['accounting_account_id'] = AccountingUtil::getAccountingAccountID(2105, $business_id);
                    $_rec6['amount'] = $output_amounts["tax"];
                    $_rec6['type'] = 'credit';

                    $recs[] = $_rec6;
                }

                if($input['shipping_charges'] != 0)
                {
                    //shipping_charges
                    $_rec8['accounting_account_id'] =  AccountingUtil::getAccountingAccountID(5104,$business_id);
                    $_rec8['amount'] =  AccountingUtil::r_s($input["shipping_charges"]);
                    $_rec8['type'] = 'credit';

                    $recs[] = $_rec8;
                }

                AccountingUtil::createJournalEntry(
                    'journal_entry',
                    $business_id,
                    $input['location_id'],
                    $user_id,
                    $input['transaction_date'],
                    'transactions',
                    $transaction->id,
                    $recs
                );

            }

        }
    }

    public function MKamel_store_payment444($data)
    {
        $request = $data['request'];

        $input = $data['input'];

        $user_id = $data['user_id'];

        $transaction = $data['transaction'];

        $customer_linked = $data['customer_linked'];

        $accounts_linked = $data['accounts_linked'];

        $business_id = $request->session()->get('user.business_id');

        $tree_accs = AccountingUtil::checkTreeOfAccountsIsHere();

        if($input['status'] == 'final' && $input['is_suspend'] == 0) {
            if ($tree_accs) {

                if (isset($accounts_linked) && count($accounts_linked) > 0) {
                    foreach ($transaction->payment_lines as $one_payment) {
                        if($one_payment->method != "advance")
                        {
                            $acc_linked = AccountingUtil::getLinkedWithAccountingAccount($business_id, "accounts", $one_payment->account_id);

                            if (isset($acc_linked->id)) {
                                $recs = [];

                                $_rec = [];
                                //cash or banck account
                                $_rec['accounting_account_id'] = $acc_linked->id;
                                $_rec['amount'] = $one_payment->amount;
                                $_rec['type'] = 'debit';

                                $recs[] = $_rec;

                                $_rec = [];
                                //customer
                                $_rec['accounting_account_id'] = $customer_linked->id;
                                $_rec['amount'] = $one_payment->amount;
                                $_rec['type'] = 'credit';

                                $recs[] = $_rec;

                                AccountingUtil::createJournalEntry(
                                    'receipt',
                                    $business_id,
                                    $input['location_id'],
                                    $user_id,
                                    $one_payment->paid_on,
                                    'transaction_payments',
                                    $one_payment->id,
                                    $recs
                                );
                            }
                        }
                    }
                }

            }
        }
    }

    public function MKamel_check555($data)
    {

        $request = $data['request'];

        $input = $data['input'];

        $business_id = $request->session()->get('user.business_id');

        $sell = Transaction::where('business_id', $business_id)
            ->with(['sell_lines', 'sell_lines.sub_unit'])
            ->findOrFail($input['transaction_id']);

        $tree_accs = AccountingUtil::checkTreeOfAccountsIsHere();

        if($tree_accs)
        {
            $customer_linked = AccountingUtil::getLinkedWithAccountingAccount($business_id,"contacts",$sell->contact_id);

            if($customer_linked == null)
            {
                return [
                    'success' => 0,
                    'msg' => __('accounting::lang.customer_not_linked'),
                ];

            }

            return [
                'success' => 1,
            ];
        }

        return [
            'success' => 2,
        ];
    }


    public function MKamel_store555($data)
    {
        $request = $data['request'];

        $input = $data['input'];

        $customer_linked = $data['customer_linked'];

        $user_id = $data['user_id'];

        $sell_return = $data['sell_return'];

        $business_id = $request->session()->get('user.business_id');

        $tree_accs = AccountingUtil::checkTreeOfAccountsIsHere();

        if($tree_accs)
        {

            $sell = Transaction::where('business_id', $business_id)
                ->with(['sell_lines', 'sell_lines.sub_unit'])
                ->findOrFail($input['transaction_id']);

            $output_amounts = AccountingUtil::getAmountsAcounts2($sell->sell_lines);

            $recs = [];

            //customer
            $_rec1['accounting_account_id'] = $customer_linked->id;
            $_rec1['amount'] = $output_amounts["customer"];
            $_rec1['type'] = 'credit';

            $recs[] = $_rec1;

            if ($output_amounts["cost_of_goods"] != 0) {
                //cost_of_goods
                $_rec2['accounting_account_id'] = AccountingUtil::getAccountingAccountID(5101, $business_id);
                $_rec2['amount'] = $output_amounts["cost_of_goods"];
                $_rec2['type'] = 'credit';

                $recs[] = $_rec2;
            }

            if ($output_amounts["discount"] != 0) {
                //discount
                $_rec3['accounting_account_id'] = AccountingUtil::getAccountingAccountID(4102, $business_id);
                $_rec3['amount'] = $output_amounts["discount"];
                $_rec3['type'] = 'credit';

                $recs[] = $_rec3;
            }

            if ($output_amounts["stock"] != 0) {
                //stock
                $_rec4['accounting_account_id'] = AccountingUtil::getAccountingAccountID(1106, $business_id);
                $_rec4['amount'] = $output_amounts["stock"];
                $_rec4['type'] = 'debit';

                $recs[] = $_rec4;
            }

            //revenue_of_products
            $_rec5['accounting_account_id'] = AccountingUtil::getAccountingAccountID(4101, $business_id);
            $_rec5['amount'] = $output_amounts["revenue_of_products"];
            $_rec5['type'] = 'debit';

            $recs[] = $_rec5;

            if ($output_amounts["tax"] != 0) {
                //tax
                $_rec6['accounting_account_id'] = AccountingUtil::getAccountingAccountID(2105, $business_id);
                $_rec6['amount'] = $output_amounts["tax"];
                $_rec6['type'] = 'debit';

                $recs[] = $_rec6;
            }

            AccountingUtil::createJournalEntry(
                'journal_entry',
                $business_id,
                $sell_return->location_id,
                $user_id,
                $sell_return->transaction_date,
                'transactions',
                $sell_return->id,
                $recs
            );

        }
    }

    public function MKamel_store666($data)
    {
        $request = $data['request'];

        $products = $data['products'];

        $stock_adjustment = $data['stock_adjustment'];

        $business_id = $request->session()->get('user.business_id');

        $tree_accs = AccountingUtil::checkTreeOfAccountsIsHere();

        if($tree_accs)
        {
            $output_amounts = AccountingUtil::getAmountsAcounts3($products);

            $recs = [];

            //stock
            $_rec1['accounting_account_id'] =  AccountingUtil::getAccountingAccountID(1106, $business_id);
            $_rec1['amount'] = $output_amounts["stock"];
            $_rec1['type'] = 'credit';

            $recs[] = $_rec1;

            //Retained Earnings Or Losses
            $_rec2['accounting_account_id'] =  AccountingUtil::getAccountingAccountID(34, $business_id);
            $_rec2['amount'] = $output_amounts["stock"];
            $_rec2['type'] = 'debit';

            $recs[] = $_rec2;

            AccountingUtil::createJournalEntry(
                'journal_entry',
                $business_id,
                $stock_adjustment->location_id,
                $stock_adjustment->created_by,
                $stock_adjustment->transaction_date,
                'transactions',
                $stock_adjustment->id,
                $recs,
                $request->input('additional_notes')
            );
        }
    }

    public function MKamel_store777($data)
    {
        $request = $data['request'];

        $products = $data['products'];

        $purchase_transfer = $data['purchase_transfer'];

        $status = $data['status'];

        $business_id = $request->session()->get('user.business_id');

        $tree_accs = AccountingUtil::checkTreeOfAccountsIsHere();

        if($tree_accs)
        {

            if( $status == 'completed')
            {

                $output_amounts = AccountingUtil::getAmountsAcounts3($products);

                $recs = [];

                //stock
                $_rec1['accounting_account_id'] =  AccountingUtil::getAccountingAccountID(1106, $business_id);
                $_rec1['amount'] = $output_amounts["stock"];
                $_rec1['location_id'] = Transaction::find($purchase_transfer->transfer_parent_id)->location_id;
                $_rec1['type'] = 'credit';

                $recs[] = $_rec1;

                //stock
                $_rec2['accounting_account_id'] =  AccountingUtil::getAccountingAccountID(1106, $business_id);
                $_rec2['amount'] = $output_amounts["stock"];
                $_rec2['location_id'] = $purchase_transfer->location_id;
                $_rec2['type'] = 'debit';

                $recs[] = $_rec2;

                AccountingUtil::createJournalEntry(
                    'journal_entry',
                    $business_id,
                    $purchase_transfer->location_id,
                    $purchase_transfer->created_by,
                    $purchase_transfer->transaction_date,
                    'transactions',
                    $purchase_transfer->id,
                    $recs,
                    $request->input('additional_notes')
                );
            }

        }
    }

    public function MKamel_check888($data)
    {

        $request = $data['request'];

        $transaction = $data['transaction'];

        $business_id = $request->session()->get('user.business_id');

        $tree_accs =  AccountingUtil::checkTreeOfAccountsIsHere();

        if($tree_accs && $request->input('method') != 'advance')
        {
            $contact_linked =  AccountingUtil::getLinkedWithAccountingAccount($business_id,"contacts",$transaction->contact_id);

            if($contact_linked == null)
            {
                return ['success' => 0,
                    'msg' => __('accounting::lang.supplier_not_linked'),
                ];
            }

            $account_linked =  AccountingUtil::getLinkedWithAccountingAccount($business_id,"accounts",$request->input('account_id'));

            if($account_linked == null)
            {
                return ['success' => 0,
                    'msg' => __('accounting::lang.account_not_linked'),
                ];

            }

            return ['success' => 1,
                'contact_linked' => $contact_linked,
                'account_linked' => $account_linked,
            ];
        }

        return ['success' => 2,
        ];
    }

    public function MKamel_store888($data)
    {
        $request = $data['request'];

        $contact_linked = $data['contact_linked'];

        $account_linked = $data['account_linked'];

        $transaction = $data['transaction'];

        $tp = $data['tp'];

        $business_id = $request->session()->get('user.business_id');

        $tree_accs =  AccountingUtil::checkTreeOfAccountsIsHere();

        if($tree_accs && $request->input('method') != 'advance')
        {
            if ($transaction->type == "purchase") {
                $recs = [];

                //supplier
                $_rec1['accounting_account_id'] = $contact_linked->id;
                $_rec1['amount'] = $tp->amount;
                $_rec1['type'] = 'debit';

                $recs[] = $_rec1;

                //cash or banck account
                $_rec2['accounting_account_id'] = $account_linked->id;
                $_rec2['amount'] = $tp->amount;
                $_rec2['type'] = 'credit';

                $recs[] = $_rec2;

                AccountingUtil::createJournalEntry(
                    'expense',
                    $business_id,
                    $transaction->location_id,
                    $tp->created_by,
                    $tp->paid_on,
                    'transaction_payments',
                    $tp->id,
                    $recs
                );
            }

            if ($transaction->type == "purchase_return") {
                $recs = [];

                //supplier
                $_rec1['accounting_account_id'] = $contact_linked->id;
                $_rec1['amount'] = $tp->amount;
                $_rec1['type'] = 'credit';

                $recs[] = $_rec1;

                //cash or banck account
                $_rec2['accounting_account_id'] = $account_linked->id;
                $_rec2['amount'] = $tp->amount;
                $_rec2['type'] = 'debit';

                $recs[] = $_rec2;

                AccountingUtil::createJournalEntry(
                    'receipt',
                    $business_id,
                    $transaction->location_id,
                    $tp->created_by,
                    $tp->paid_on,
                    'transaction_payments',
                    $tp->id,
                    $recs
                );
            }

            if ($transaction->type == "sell") {
                $recs = [];

                //cash or banck account
                $_rec1['accounting_account_id'] = $account_linked->id;
                $_rec1['amount'] = $tp->amount;
                $_rec1['type'] = 'debit';

                $recs[] = $_rec1;

                //customer
                $_rec2['accounting_account_id'] = $contact_linked->id;
                $_rec2['amount'] = $tp->amount;
                $_rec2['type'] = 'credit';

                $recs[] = $_rec2;

                AccountingUtil::createJournalEntry(
                    'receipt',
                    $business_id,
                    $transaction->location_id,
                    $tp->created_by,
                    $tp->paid_on,
                    'transaction_payments',
                    $tp->id,
                    $recs
                );
            }

            if ($transaction->type == "sell_return") {
                $recs = [];

                //cash or banck account
                $_rec1['accounting_account_id'] = $account_linked->id;
                $_rec1['amount'] = $tp->amount;
                $_rec1['type'] = 'credit';

                $recs[] = $_rec1;

                //customer
                $_rec2['accounting_account_id'] = $contact_linked->id;
                $_rec2['amount'] = $tp->amount;
                $_rec2['type'] = 'debit';

                $recs[] = $_rec2;

                AccountingUtil::createJournalEntry(
                    'expense',
                    $business_id,
                    $transaction->location_id,
                    $tp->created_by,
                    $tp->paid_on,
                    'transaction_payments',
                    $tp->id,
                    $recs
                );
            }
        }
    }

    public function MKamel_check_2_888($data)
    {
        $request = $data['request'];

        $business_id = $request->session()->get('user.business_id');

        $tree_accs =  AccountingUtil::checkTreeOfAccountsIsHere();

        if($tree_accs)
        {
            $contact_linked = AccountingUtil::getLinkedWithAccountingAccount($business_id,"contacts", $request->input('contact_id'));

            if($contact_linked == null)
            {
                return ['success' => 0,
                    'msg' => __('accounting::lang.supplier_not_linked'),
                ];

            }

            $account_linked = AccountingUtil::getLinkedWithAccountingAccount($business_id,"accounts",$request->input('account_id'));

            if($account_linked == null)
            {
                return ['success' => 0,
                    'msg' => __('accounting::lang.account_not_linked'),
                ];

            }

            return ['success' => 1,
                'contact_linked' => $contact_linked,
                'account_linked' => $account_linked,
            ];
        }

        return ['success' => 2,
        ];
    }

    public function MKamel_store_2_888($data)
{
    $request = $data['request'];
    $contact_linked = $data['contact_linked'];
    $account_linked = $data['account_linked'];
    $tp = $data['tp'];
    $business_id = $request->session()->get('user.business_id');
    $tree_accs = AccountingUtil::checkTreeOfAccountsIsHere();

    if ($tree_accs) {
        $contact = Contact::where('business_id', $business_id)
            ->findOrFail($request->input('contact_id'));

        $recs = [];
        $entry_type = '';

        // ✅ Handling Supplier Transactions
        if ($contact->type == 'supplier') {
            if ($tp->amount > 0) { 
                // ✅ Paying a Supplier (Business Pays Supplier)
                $recs[] = [
                    'accounting_account_id' => $contact_linked->id,
                    'amount' => $tp->amount,
                    'type' => 'debit'  
                ];
                $recs[] = [
                    'accounting_account_id' => $account_linked->id,
                    'amount' => $tp->amount,
                    'type' => 'credit' 
                ];
                $entry_type = 'expense';
            } else { 
                // ✅ Receiving Money from Supplier (Refund)
                $recs[] = [
                    'accounting_account_id' => $contact_linked->id,
                    'amount' => abs($tp->amount),
                    'type' => 'credit'  
                ];
                $recs[] = [
                    'accounting_account_id' => $account_linked->id,
                    'amount' => abs($tp->amount),
                    'type' => 'debit' 
                ];
                $entry_type = 'supplier_refund';
            }
        }

        // ✅ Handling Customer Transactions
        elseif ($contact->type == 'customer') {
            if ($tp->amount > 0) { 
                // ✅ Receiving Payment from Customer
                $recs[] = [
                    'accounting_account_id' => $account_linked->id,
                    'amount' => $tp->amount,
                    'type' => 'debit' 
                ];
                $recs[] = [
                    'accounting_account_id' => $contact_linked->id,
                    'amount' => $tp->amount,
                    'type' => 'credit' 
                ];
                $entry_type = 'receipt';
            } else { 
                // ✅ Paying Money to a Customer
                $recs[] = [
                    'accounting_account_id' => $contact_linked->id,
                    'amount' => abs($tp->amount),
                    'type' => 'debit'  
                ];
                $recs[] = [
                    'accounting_account_id' => $account_linked->id,
                    'amount' => abs($tp->amount),
                    'type' => 'credit' 
                ];
                $entry_type = 'customer_payment';
            }
        }

        // ✅ Create Journal Entry
        AccountingUtil::createJournalEntry(
            $entry_type, 
            $business_id,
            AccountingUtil::getLcationID($business_id),
            $tp->created_by,
            $tp->paid_on,
            null,
            null,
            $recs,
            $tp->payment_ref_no
        );
    }
}

    
}
