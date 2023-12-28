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
use App\Http\Models\Municipio;

class MunicipioController extends Controller
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
     * Show municipios.
     *
     * @return \Illuminate\Http\Response
     */
    public function getmunicipios(Request $request, $id)
    {
        if ($request->ajax()) {
            $municipios = DB::table("municipios")
            ->where("estado_id","=", $id)
            ->whereNotIn('id', [268])
            ->orderBy('munNombre')
            ->get();
            return response()->json($municipios);
        }
    }

}