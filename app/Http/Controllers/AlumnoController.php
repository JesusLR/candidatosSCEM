<?php

namespace App\Http\Controllers;

use URL;
use Validator;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Models\PreparatoriaProcedencia;


class AlumnoController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }



    public function preparatoriaProcedencia (Request $request)
    {
        return PreparatoriaProcedencia::where("municipio_id", "=", $request->municipio_id)
            ->where("prepHomologada", "=", "SI")
            ->orderBy("prepNombre")
        ->get();
    }



}