<?php

namespace Modules\CodeCraftCurrencyAdd\Http\Controllers;

use App\System; 
use Composer\Semver\Comparator;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class InstallController extends Controller
{

    
    public function __construct()
    {
        $this->module_name = 'codecraftcurrencyadd';
        $this->appVersion =config('codecraftcurrencyadd.module_version');
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if (!auth()->user()->can('superadmin')) {
            abort(403, 'Unauthorized action.');
        }

        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '512M');

        $this->installSettings();

        //Check if installed or not.
        $is_installed = System::getProperty($this->module_name . '_version');


        if (!empty($is_installed)) {
            abort(404);
        }

        $this->installSettings();
        $this->install();
        return redirect()
            ->action('\App\Http\Controllers\Install\ModulesController@index')
            ->with('status', $output);
    }

    
    private function installSettings()
    {
        config(['app.debug' => true]);
        Artisan::call('config:clear');
        Artisan::call('cache:clear');
    }
    public function install()
    {
        $is_installed = System::getProperty($this->module_name . '_version');
        if (!empty($is_installed)) {
            abort(404);
        }

        Artisan::call('module:migrate-reset', ['module' => "codecraftcurrencyadd"]);
        Artisan::call('module:migrate', ['module' => "codecraftcurrencyadd"]);

        DB::statement('SET default_storage_engine=INNODB;');


        System::addProperty($this->module_name . '_version', $this->appVersion);

        DB::commit();

        $output = ['success' => 1,
            'msg' => 'Configurador de monedas correctamente instalado'
        ];

        return redirect()
            ->action('\App\Http\Controllers\Install\ModulesController@index')
            ->with('status', $output);
    }

    public function uninstall()
    {
        if (!auth()->user()->can('superadmin')) {
            abort(403, 'Unauthorized action.');
        }

        try {
            System::removeProperty($this->module_name . '_version');

            $output = ['success' => true,
                'msg' => __("lang_v1.success")
            ];
        } catch (\Exception $e) {
            $output = ['success' => false,
                'msg' => $e->getMessage()
            ];
        }

        return redirect()->back()->with(['status' => $output]);
    }

    public function update()
{
    if (!auth()->user()->can('superadmin')) {
        abort(403, 'Unauthorized action.');
    }

    try {
        DB::beginTransaction();

        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '512M');

        $version = System::getProperty($this->module_name . '_version');

        if (Comparator::greaterThan($this->appVersion, $version)) {
            $this->installSettings();
            

            DB::statement('SET default_storage_engine=INNODB;');
            Artisan::call('module:migrate', ['module' => "codecraftcurrencyadd"]);
            System::setProperty($this->module_name . '_version', $this->appVersion);
        } else {
            DB::rollBack();
            abort(404, 'No requiere Actualizacion.');
        }

        DB::commit();

        $output = [
            'success' => 1,
            'msg' => 'Actualizamos su modulo a la version: ' . $this->appVersion . ' !!'
        ];

        return redirect()->back()->with(['status' => $output]);
    } catch (Exception $e) {
        DB::rollBack();
        return redirect()->back()->withErrors(['error' => $e->getMessage()]);
    }
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
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
