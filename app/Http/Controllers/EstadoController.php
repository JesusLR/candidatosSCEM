<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Helpers\Utils;
use Illuminate\Database\QueryException;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Auth;
use URL;
use Validator;
use Debugbar;

use App\Http\Models\Estado;
use App\Http\Models\Pais;

class EstadoController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }


    /**
     * Show list.
     *
     * @return \Illuminate\Http\Response
     */
    public function list()
    {
        $estados = Estado::select('estados.id as estado_id','estados.edoNombre','estados.edoAbrevia','estados.edoRenapo'
        ,'estados.edoISO','paises.paisNombre')->join('paises','estados.pais_id','paises.id')->where('estados.id','!=',0)->get();
      
        return Datatables::of($estados)
        ->addColumn('action',function($query){
            return '<a href="estados/'.$query->estado_id.'" class="button button--icon js-button js-ripple-effect" title="Ver">
                <i class="material-icons">visibility</i>
            </a>
            <a href="estados/'.$query->estado_id.'/edit" class="button button--icon js-button js-ripple-effect" title="Editar">
                <i class="material-icons">edit</i>
            </a>';
        })->make(true);
    }

    /**
     * Show estados.
     *
     * @return \Illuminate\Http\Response
     */
    public function getEstados(Request $request, $id)
    {
        if($request->ajax()){
            $estados = Estado::estados($id);
            return response()->json($estados);
        }
    }


}