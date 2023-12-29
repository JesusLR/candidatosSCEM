<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Helpers\Utils;
use Illuminate\Database\QueryException;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Auth;
use URL;
use Validator;
use Debugbar;

use App\Models\Departamento;

class DepartamentoController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('permisos:departamento',['except' => ['index','show','list','getDepartamentos']]);
    }

     /**
     * Show departamentos.
     *
     * @return \Illuminate\Http\Response
     */
    public function getDepartamentos(Request $request, $id)
    {
        if($request->ajax()){
            $departamentos = Departamento::with('ubicacion')->where('ubicacion_id','=',$id)->get();
            return response()->json($departamentos);
        }
    }

}
