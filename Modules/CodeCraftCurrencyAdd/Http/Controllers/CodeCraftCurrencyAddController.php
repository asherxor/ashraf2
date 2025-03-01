<?php

namespace Modules\CodeCraftCurrencyAdd\Http\Controllers;

use App\Business;
use App\Product;
use App\Variation;
use App\Utils\ProductUtil;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use App\VariationValueTemplate;
use App\VariationTemplate;
use App\ProductVariation;
use App\Media;
use Modules\CodeCraftCurrencyAdd\Entities\Currency;
use Modules\CodeCraftCurrencyAdd\Entities\Taza;

class CodeCraftCurrencyAddController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if (request()->ajax()) {
            $currency_set = Currency::all();
    
            return datatables()->of($currency_set)->addColumn('action', function ($row) {
                return '<button class="btn btn-primary">Editar</button>';
            })
            ->addColumn(
                'action',
                '
                @can("codecraftcurrencyadd.delete")
                <button data-href="{{ route(\'destroy\', [$id]) }}" class="btn btn-xs btn-danger delete_currency_button"><i class="glyphicon glyphicon-trash"></i> @lang("messages.delete")</button>
                @endcan
                '
            )
            ->rawColumns(['action'])->make(true);
        }
    
        return view('codecraftcurrencyadd::index');
    }

    public function taza_s()
    {
        if (request()->ajax()) {
            $business_id = request()->session()->get('user.business_id');
            $bussines_location = Business::find($business_id);
            $bussines_currency = $bussines_location->currency_id;
            $currency_set = Currency::find($bussines_currency);
            $name_currency = $currency_set->symbol .'1 (' . $currency_set->code.')';
    
            $taza_set = Taza::where('business_id', $business_id)->get();
    
            return datatables()->of($taza_set)
                ->addColumn('currency_location', function ($taza) use ($name_currency) {
                    return $name_currency;
                })
                ->addColumn('country_currency', function ($taza) {
                    $currency = Currency::find($taza->currency_id);
                    return $currency->country . ' - ' . $currency->currency;
                })
                ->addColumn('value_tax', function ($taza) use ($name_currency) {
                    $currency_data = Currency::find($taza->currency_id);
                    return $currency_data->code ." " .$currency_data->symbol. $taza->value . ' = ' . $name_currency;
                })
                ->addColumn('action', function ($taza) {
                    return '<button data-href="' . route('destroy_taza', [$taza->id]) . '" class="btn btn-xs btn-danger delete_taza_button"><i class="glyphicon glyphicon-trash"></i> ' . __('messages.delete') . '</button>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    
        return view('codecraftcurrencyadd::tazas');
    }
    public function currency_row($currency_id)
    {
        if (request()->ajax()) {
            $business_id = request()->session()->get('user.business_id');

            $currency = Currency::find($currency_id);

            return view('codecraftcurrencyadd::row_currencys')
                ->with(compact('currency'));
        }
    }

    public function getCurrency()
    {
        if (request()->ajax()) {
            $term = request()->input('term', '');

            
            // Inicializar la consulta
            $query = Currency::query();

            // Incluir búsqueda
            if (!empty($term)) {
                $query->where(function ($q) use ($term) {
                    $q->where('country', 'like', '%'.$term.'%')
                    ->orWhere('currency', 'like', '%'.$term.'%');
                });
            }

            // Obtener los resultados con las columnas especificadas
            $products = $query->select('id', 'currency', 'country')->get();

            return response()->json($products);
        }
    }

    public function create_currency()
    {
        if (! auth()->user()->can('codecraftcurrencyadd.create')) {
            abort(403, 'Unauthorized action.');
        }

        return view('codecraftcurrencyadd::create_currency');
    }

    public function create_tazas()
    {
        if (! auth()->user()->can('codecraftcurrencyadd.create')) {
            abort(403, 'Unauthorized action.');
        }

        return view('codecraftcurrencyadd::create_tazas');
    }

    public function tazas()
    {
        if (request()->ajax()) {
            $currency_set = Currency::all();
    
            return datatables()->of($currency_set)->addColumn('action', function ($row) {
                return '<button class="btn btn-primary">Editar</button>';
            })
            ->addColumn(
                'action',
                '
                @can("codecraftcurrencyadd.delete")
                <button data-href="{{ route(\'destroy\', [$id]) }}" class="btn btn-xs btn-danger delete_currency_button"><i class="glyphicon glyphicon-trash"></i> @lang("messages.delete")</button>
                @endcan
                '
            )
            ->rawColumns(['action'])->make(true);
        }
        return view('codecraftcurrencyadd::tazas');
    }


    public function store_tz(Request $request)
    {
        try {
            // Verificar si el usuario tiene permiso para crear una moneda
            if (! auth()->user()->can('currency.create')) {
                abort(403, 'Unauthorized action.');
            }
    
            // Obtener todos los datos de entrada del request
            $input = $request->all();
            $business_id = request()->session()->get('user.business_id');
            
    
            // Iniciar una transacción de base de datos
            DB::beginTransaction();
            foreach ($input['currency_product'] as $key => $currency_product) {
                // Preparar los datos para crear una nueva fila en la tabla 'tazas'
                $currency_data = [
                    'business_id' => $business_id,
                    'currency_id' => $currency_product,
                    'value' => $input['currency_taza'][$key],
                    'alias' => $input['currency_alias'][$key],
                ];
    
                // Crear un nuevo registro de 'taza'
                Taza::create($currency_data);
            }
            // Confirmar la transacción
            DB::commit();
    
            return redirect()->route('tazas')->with('success', 'Moneda creada con éxito.');

            } catch (\Exception $e) {
                } catch (\Exception $e) {
                    // Revertir la transacción si ocurre un error
                    DB::rollBack();
                    // Registrar los detalles del error
                    \Log::emergency('File:'.$e->getFile().' Line:'.$e->getLine().' Message:'.$e->getMessage());
                    return back()->with('error', 'Ocurrió un error al crear la moneda. Por favor, inténtalo de nuevo.');
            
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
    public function store()
    {
        return view('codecraftcurrencyadd::index');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store_currency(Request $request)
    {
        try {
            // Verificar si el usuario tiene permiso para crear una moneda
            if (! auth()->user()->can('currency.create')) {
                abort(403, 'Unauthorized action.');
            }
    
            // Obtener todos los datos de entrada del request
            $input = $request->all();
    
            // Preparar los datos para crear una nueva moneda
            $currency_data = [
                'country' => $input['country'],
                'currency' => $input['currency'],
                'code' => $input['code'],
                'symbol' => $input['symbol'],
                'thousand_separator' => $input['thousand_separator'],
                'decimal_separator' => $input['decimal_separator'],
            ];
    
            // Iniciar una transacción de base de datos
            DB::beginTransaction();
            // Crear un nuevo registro de moneda
            $currency = Currency::create($currency_data);
    
            // Confirmar la transacción
            DB::commit();
    
            return redirect()->route('tazas')->with('success', 'Moneda creada con éxito.');

    } catch (\Exception $e) {
        } catch (\Exception $e) {
            // Revertir la transacción si ocurre un error
            DB::rollBack();
            // Registrar los detalles del error
            \Log::emergency('File:'.$e->getFile().' Line:'.$e->getLine().' Message:'.$e->getMessage());
            return back()->with('error', 'Ocurrió un error al crear la moneda. Por favor, inténtalo de nuevo.');
    
        }
    
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
    public function destroy($id, Request $request)
    {
        if (! auth()->user()->can('codecraftcurrencyadd.delete')) {
            abort(403, 'Unauthorized action.');
        }

        try {
            DB::beginTransaction();
            $business_id = $request->session()->get('user.business_id');

            Currency::where('id', $id)
                ->delete();

            DB::commit();

            $output = ['success' => 1, 'msg' => __('lang_v1.deleted_success')];
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::emergency('File:'.$e->getFile().'Line:'.$e->getLine().'Message:'.$e->getMessage());

            $output = ['success' => 0, 'msg' => __('messages.something_went_wrong')];
        }

        return $output;
    }
    public function destroy_taza($id, Request $request)
    {
        if (! auth()->user()->can('codecraftcurrencyadd.delete')) {
            abort(403, 'Unauthorized action.');
        }

        try {
            DB::beginTransaction();
            $business_id = $request->session()->get('user.business_id');

            Taza::where('id', $id)
                ->delete();

            DB::commit();

            $output = ['success' => 1, 'msg' => __('lang_v1.deleted_success')];
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::emergency('File:'.$e->getFile().'Line:'.$e->getLine().'Message:'.$e->getMessage());

            $output = ['success' => 0, 'msg' => __('messages.something_went_wrong')];
        }

        return $output;
    }
}
