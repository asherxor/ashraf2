<?php

namespace Modules\CodeCraftCurrencyAdd\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Menu;
use App\Utils\ModuleUtil;

class DataController extends Controller
{

    public function superadmin_package()
    {
        return [
            [
                'name' => 'codecraftcurrencyadd_module',
                'label' => __('Configurador de moneda'),
                'default' => false
            ]
        ];
    }

    public function modifyAdminMenu()
    {


        $business_id = session()->get('user.business_id');
        $module_util = new ModuleUtil();
        $is_codecraftcurrencyadd_enabled = (boolean)$module_util->hasThePermissionInSubscription($business_id, 'codecraftcurrencyadd_module', 'superadmin_package');
       
        if($is_codecraftcurrencyadd_enabled){
            $menu = Menu::instance('admin-sidebar-menu');
            if (auth()->user()->can('codecraftcurrencyadd.view')) {
                $menu->dropdown(
                    __('codecraftcurrencyadd::lang.codecraftcurrencyadd'),
                    function ($sub) {
                        if (auth()->user()->can('superadmin')) {
                            $sub->url(
                                action('\Modules\CodeCraftCurrencyAdd\Http\Controllers\CodeCraftCurrencyAddController@index'),
                                __('codecraftcurrencyadd::lang.list'),
                                ['icon' => '', 'active' => request()->segment(1) == 'codecraftcurrencyadd' ]
                            );
                        }
                        $sub->url(
                            action('\Modules\CodeCraftCurrencyAdd\Http\Controllers\CodeCraftCurrencyAddController@tazas'),
                            __('codecraftcurrencyadd::lang.tazas'),
                            ['icon' => '', 'active' => request()->segment(1) == 'codecraftcurrencyadd' ]
                        );
                    },
					['icon' => 'fa fas fa-dollar-sign', 'style' => 'background-color: orange !important;']
    
                )->order(86);
    
            }
        }
    }


    public function user_permissions()
    {
        return [

            [
                'value' => 'codecraftcurrencyadd.view',
                'label' =>  __('codecraftcurrencyadd::lang.view'),
                'default' => false
            ],

            [
                'value' => 'codecraftcurrencyadd.create',
                'label' =>  __('codecraftcurrencyadd::lang.create'),
                'default' => false
            ],
            [
                'value' => 'codecraftcurrencyadd.edit',
                'label' => __('codecraftcurrencyadd::lang.edit'),
                'default' => false
            ],
            [
                'value' => 'codecraftcurrencyadd.delete',
                'label' =>  __('codecraftcurrencyadd::lang.delete'),
                'default' => false
            ],
            [
                'value' => 'codecraftcurrencyadd.taza',
                'label' =>  __('codecraftcurrencyadd::lang.taza_create'),
                'default' => false
            ],



        ];
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('codecraftcurrencyadd::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('codecraftcurrencyadd::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('codecraftcurrencyadd::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('codecraftcurrencyadd::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
