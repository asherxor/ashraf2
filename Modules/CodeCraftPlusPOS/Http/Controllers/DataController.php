<?php

namespace Modules\CodeCraftPlusPOS\Http\Controllers;

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
                'name' => 'codecraftpluspos_module',
                'label' => __('POS PLUS'),
                'default' => false
            ]
        ];
    }

    public function modifyAdminMenu()
    {


        $business_id = session()->get('user.business_id');
        $module_util = new ModuleUtil();
        $is_codecraftpluspos_enabled = (boolean)$module_util->hasThePermissionInSubscription($business_id, 'codecraftpluspos_module', 'superadmin_package');
       
        if($is_codecraftpluspos_enabled){
            $menu = Menu::instance('admin-sidebar-menu');
            if (auth()->user()->can('codecraftpluspos.view')) {
                $menu
                ->url(
                    action([\Modules\CodeCraftPlusPOS\Http\Controllers\SellPosController::class, 'create']),
                    __('codecraftpluspos::lang.list'),
                    ['icon' => 'fa fas fa-shopping-cart', 'style' => 'background-color: cyan !important;']
                
                )->order(86);
    
            }
        }
    }


    public function user_permissions()
    {
        return [

            [
                'value' => 'codecraftpluspos.view',
                'label' =>  __('codecraftpluspos::lang.view'),
                'default' => false
            ],

            [
                'value' => 'codecraftpluspos.create',
                'label' =>  __('codecraftpluspos::lang.create'),
                'default' => false
            ],
            [
                'value' => 'codecraftpluspos.edit',
                'label' => __('codecraftpluspos::lang.edit'),
                'default' => false
            ],
            [
                'value' => 'codecraftpluspos.delete',
                'label' =>  __('codecraftpluspos::lang.delete'),
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
        return view('codecraftpluspos::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('codecraftpluspos::create');
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
        return view('codecraftpluspos::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('codecraftpluspos::edit');
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
