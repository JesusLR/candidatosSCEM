<?php

namespace App\Http\Controllers;

use DB;
use Debugbar;
use Validator;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Pais;

use App\Models\Plan;
use App\Http\Helpers\Utils;
use App\Models\Acuerdo;
use App\Models\Escuela;
use Illuminate\Http\Request;
use App\Models\Programa;
use App\Models\Candidato;
use App\Models\Municipio;
use App\Models\Ubicacion;
use App\Models\Departamento;
use PHPMailer\PHPMailer\PHPMailer;
use Illuminate\Support\Facades\Mail;
use Illuminate\Database\QueryException;
use Yajra\DataTables\Facades\DataTables;
use App\Models\PreparatoriaProcedencia;

class CandidatosPrimerIngresoController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

        // $this->middleware('auth');
        // $this->middleware('permisos:CandidatosPrimerIngreso',['except' => [
        //     'index','show','list',
        //     'getProgramasByCampus',
        //     'preparatoriaProcedenciaCandidatos'
        // ]]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $departamentos = Departamento::whereIn("depNivel", [1,2,3,4,5,6,7])->get()->unique("depClave");
        $paises = Pais::get();
        $ubicaciones = Ubicacion::all();
        //$ubicaciones = Ubicacion::where("id", "=", 1)->first();
        //$ubicaciones = Ubicacion::find(1);


        return View('candidatos.create', compact("departamentos", "paises", "ubicaciones"));
    }

    /**
     * Show user list.
     *
     */
    public function list()
    {
        $candidatos = DB::table("candidatos")
        ->select('candidatos.id as id','candidatos.perCurp','candidatos.perApellido1','candidatos.perApellido2','candidatos.perNombre',
        'candidatos.perCorreo1', 'candidatos.ubiClave', 'candidatos.progClave', 'candidatos.coordinador_correo')->orderBy("candidatos.id", "desc");

        return Datatables::of($candidatos)->addColumn('action',function($query) {
            return '<a href="candidatos_primer_ingreso/' . $query->id . '" class="button button--icon js-button js-ripple-effect" title="Ver">
                <i class="material-icons">visibility</i>
            </a>';
        })->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $departamentos = Departamento::whereIn("depNivel", [1,2,3,4,5,6,7])->get()->unique("depClave");
        $paises = Pais::get();
        $ubicaciones = Ubicacion::all();
        //$ubicaciones = Ubicacion::where("id", "=", 1)->first();
        //$ubicaciones = Ubicacion::find(1);


        return View('candidatos.create', compact("departamentos", "paises", "ubicaciones"));
    }


    public function getProgramasByCampus(Request $request, $ubicacion_id)
    {
        if($request->ajax()){

            /*
            $programas = DB::table("planes")
                ->select("escuelas.escNombre", "departamentos.depClave",
                "departamentos.ubicacion_id", "planes.planEstado",
                "programas.id", "programas.progNombre")
                ->join('programas', 'programas.id', '=', 'planes.id')
                ->join('escuelas', 'escuelas.id', '=', 'programas.escuela_id')
                ->join('departamentos', 'departamentos.id', '=', 'escuelas.departamento_id')
                ->join('ubicacion', 'ubicacion.id', '=', 'departamentos.ubicacion_id')

                ->where("escuelas.escNombre", "like", "ESCUELA%")
                ->where("departamentos.depClave", "=", "SUP")
                ->where("ubicacion.id", "=", $ubicacion_id)
                ->where("planes.planEstado", "=", "N")
                ->orderBy("programas.progNombre")
            ->get();
            */

            $programas = DB::table("programas")
                ->select("escuelas.escNombre", "departamentos.depClave",
                    "departamentos.ubicacion_id", "planes.planEstado",
                    "programas.id", "programas.progNombre")
                ->join('planes', 'planes.programa_id', '=', 'programas.id')
                ->join('escuelas', 'escuelas.id', '=', 'programas.escuela_id')
                ->join('departamentos', 'departamentos.id', '=', 'escuelas.departamento_id')
                ->join('ubicacion', 'ubicacion.id', '=', 'departamentos.ubicacion_id')

                ->where("escuelas.escNombre", "like", "ESCUELA%")
                ->where("departamentos.depClave", "=", "SUP")
                ->where("ubicacion.id", "=", $ubicacion_id)
                ->where("planes.planEstado", "=", "N")
                ->orderBy("programas.progNombre")
                ->distinct()
                ->get();




            return response()->json($programas);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {



        // dd($request->perFechaNac);



        $prepaRequired = "required";
        if ($request->noEncuentraPrepa) {
            $prepaRequired = "";
        }

        $perCurpRequired = "required|max:18";
        $esCurpValida = "accepted";

        if ($request->noSoyMexicano) {
            $perCurpRequired = "max:18";
            $esCurpValida = "";
        }



        if (Carbon::parse($request->perFechaNac)->age < 15) {
            alert()->error('error', 'No se puede registrar a menores de 15 años.')->showConfirmButton();
            return redirect()->back()->withInput();
        }


        $validator = Validator::make($request->all(),
            [
                'image' => 'mimes:jpeg,jpg,png,pdf|file|max:10000',
                'perNombre'     => ['required','max:40', 'regex:/^[A-ZÄËÏÖÜÑ ]+$/'],
                'perApellido1'  => ['required','max:30', 'regex:/^[A-ZÄËÏÖÜÑ ]+$/'],
                'perApellido2'  => ['nullable','max:30', 'regex:/^[A-ZÄËÏÖÜÑ ]+$/'],
                'perCurp'      => $perCurpRequired,
                'esCurpValida'  => $esCurpValida,

                'perSexo'      => 'required',
                'perFechaNac'  => 'required',

                //perLugarNac
                'municipio_id' => 'required',
                'perTelefono1' => 'required|min:10|max:10',
                'perCorreo1'   => 'required',

                'preparatoria_id' => $prepaRequired,
                'ubicacion_id'    => 'required',
                'programa_id'     => 'required',
                'passwordEscuela' => 'required'
            ],
            [
                'image.mimes' => "El archivo solo puede ser de tipo jpeg, jpg, png y pdf",
                'image.max'   => "El archivo no debe de pesar más de 10 Megas",
                'perNombre.required' => 'El nombre es obligatorio',
                'perNombre.regex' => 'Los nombres no deben contener tildes ni caracteres especiales, solo se permiten Ñ,Ä,Ë,Ï,Ö,Ü',
                'perApellido1.required' => 'El apellido paterno es obligatorio',
                'perApellido1.regex' => 'El apellido paterno no deben contener tildes ni caracteres especiales, solo se permiten Ñ,Ä,Ë,Ï,Ö,Ü',
                'perApellido2.regex' => 'El apellido materno no deben contener tildes ni caracteres especiales, solo se permiten Ñ,Ä,Ë,Ï,Ö,Ü',

                "perCurp.required"      => "Favor de poner la CURP",
                "perCurp.max"           => "La CURP debe tener un maximo de 18 caracteres.",
                "esCurpValida.accepted" => "La CURP debe ser un formato válido.",

                "perSexo.required"      => "Favor de seleccionar sexo",
                "perFechaNac.required"  => "Favor de poner fecha de nacimiento",
                'municipio_id.required' => "Favor de seleccionar municipio",
                'perTelefono1.required' => "Favor de poner su telefono",
                'perTelefono1.min' => "Numero de telefono solo puede ser de 10 dígitos",
                'perTelefono1.max' => "Numero de telefono solo puede ser de 10 dígitos",
                'perCorreo1.required'   => "Favor de poner su correo",
                'preparatoria_id.required' => "Favor de seleccionar preparatoria de procedencia",
                'ubicacion_id.required'    => "Favor de seleccionar campus",
                'programa_id.required'     => "Favor de seleccionar carrera",
                'passwordEscuela.required' => "Favor de poner la clave de escuela",
            ]
        );

        $encuentraClave = DB::table("candidatosclaves")->where("claveunica", "=", $request->passwordEscuela)
        ->whereNull('deleted_at')->first();


        if (!$encuentraClave) {
            alert()->error('error', 'La clave proporcionada es inválida.')->showConfirmButton();
            return redirect()->back()->withInput();
        }


        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {



            $ubicacion = Ubicacion::where("id", "=", $request->input('ubicacion_id'))->first();

            $departamento = Departamento::where("ubicacion_id", "=", $ubicacion->id)
                ->where("depClave", "=", "SUP")
            ->first();

            $programa = Programa::where("id", "=", $request->input('programa_id'))->first();
            $escuela  = Escuela::where("id", "=", $programa->escuela_id)->first();


            if ($request->noSoyMexicano && $request->perSexo == "M") {
                $perCurp = "XEXX010101MNEXXXA4";
            }
            if ($request->noSoyMexicano && $request->perSexo == "F") {
                $perCurp = "XEXX010101MNEXXXA8";
            }

            $imageName = "";
            if ($request->image) {
                $imageName = time().'.'.request()->image->getClientOriginalExtension();
                $path = $request->image->move(env("PROJECT_PATH"), $imageName);
            }

            try {
                $candidato = Candidato::create([
                    'perCurp'      => $request->noSoyMexicano ? $perCurp : $request->input('perCurp'),
                    'perApellido1' => $request->input('perApellido1'),
                    'perApellido2' => $request->input('perApellido2'),
                    'perNombre'    => $request->input('perNombre'),
                    'perFechaNac'  => $request->input('perFechaNac'),
                    'perLugarNac'  => $request->input('municipio_id'),
                    'perSexo'      => $request->input('perSexo'),
                    'perCorreo1'   => $request->input('perCorreo1'),
                    'perTelefono1' => $request->input('perTelefono1'),
                    'perFoto'      => $imageName,
                    'curExani'     => $request->input('curExani'),
                    'preparatoria_id' => $request->noEncuentraPrepa ? 0: $request->input('preparatoria_id'),
                    'ubicacion_id'    => $request->input('ubicacion_id'),
                    'ubiClave'        => $ubicacion ? $ubicacion->ubiClave: "",
                    'ubiNombre'       => $ubicacion ? $ubicacion->ubiNombre: "",
                    'departamento_id' => $departamento ? $departamento->id: "",
                    'escuela_id'      => $escuela->id,
                    'director_id'     => $escuela->empleado_id,
                    'director_correo' => $escuela->empleado->empCorreo1 ? $escuela->empleado->empCorreo1: "",
                    'programa_id'     => $request->input('programa_id'),
                    'progClave'       => $programa->progClave,
                    'progNombre'      => $programa->progNombre,
                    'coordinador_id'  => $programa->empleado_id,
                    'coordinador_correo'   => $programa->empleado->empCorreo1 ? $programa->empleado->empCorreo1: "",
                    'candidatoPreinscrito' => "NO",
                    'esExtranjero'         => ($request->noSoyMexicano) ? 1: 0,
                    'usuario_at' =>  1
                ]);

                if ($candidato) {
                    DB::table("candidatosclaves")
                        ->where("claveunica", "=", $request->passwordEscuela)
                        ->whereNull('deleted_at')
                        ->update(['deleted_at' => Carbon::now()]);
                }


                $municipio = Municipio::where("id", "=", $request->input('municipio_id'))->first();
                $perLugarNac = $municipio ?
                    $municipio->munNombre . ", ". $municipio->estado->edoNombre . ", " . $municipio->estado->pais->paisNombre
                : "";


                $preparatoriaId          = $request->noEncuentraPrepa ? 0: $request->input('preparatoria_id');
                $preparatoriaProcedencia = PreparatoriaProcedencia::where("id", "=", $preparatoriaId)->first();
                $preparatoriaProcedencia = $preparatoriaProcedencia && ($preparatoriaId != 0) ?
                    $preparatoriaProcedencia->prepNombre
                        . ", ". $preparatoriaProcedencia->municipio->munNombre
                        . ", ". $preparatoriaProcedencia->municipio->estado->edoNombre
                        . ", ". $preparatoriaProcedencia->municipio->estado->pais->paisNombre
                : "";


                // dd($programa->empleado->empCorreo1);
                if ($programa->empleado->empCorreo1) {
                    $empleadoSeguimiento = DB::table("empleadosseguimiento")
                        ->where("prog_id", "=", $programa->id)
                        ->where('modulo', 'CANDIDATOS')
                    ->get();
                    // if(!$empleadoSeguimiento) {
                    //     $empleadoSeguimiento = DB::table("empleadosseguimiento")
                    //         ->where("escuela_id", "=", $programa->escuela_id)
                    //     ->first();
                    // }

                    $nombreCandidato = $request->input('perNombre')
                        . " " . $request->input('perApellido1')
                        . " ". $request->input('perApellido2');

                    $to_name  = $programa ? $programa->empleado->persona->perNombre
                        . " " . $programa->empleado->persona->perApellido1
                        . " " . $programa->empleado->persona->perApellido2: "";

                    $to_email = $programa->empleado->empCorreo1 ? $programa->empleado->empCorreo1: "";


                    $cc_name =  $escuela ? $escuela->empleado->persona->perNombre
                        . " " . $escuela->empleado->persona->perApellido1
                        . " " . $escuela->empleado->persona->perApellido2: "";

                    $cc_email = $escuela->empleado->empCorreo1 ? $escuela->empleado->empCorreo1: "";




                    $nombreCandidato   = $nombreCandidato;
                    $mailCandidato     = $request->input('perCorreo1');
                    $telefonoCandidato = $request->input('perTelefono1');
                    $carreraCandidato  = $programa->progNombre;

                    $mail = new PHPMailer(true);
                    // Server settings
                    $mail->CharSet = "UTF-8";
                    $mail->Encoding = 'base64';

                    $mail->SMTPDebug = 0;                         // Enable verbose debug output
                    $mail->isSMTP();                              // Set mailer to use SMTP
                    $mail->Host = 'smtp.office365.com';           // Specify main and backup SMTP servers
                    $mail->SMTPAuth = true;                       // Enable SMTP authentication
                    $mail->Username = 'candidatos@modelo.edu.mx'; // SMTP username
                    $mail->Password = 'Tuv72389';                 // SMTP password
                    $mail->SMTPSecure = 'tls';                    // Enable TLS encryption, `ssl` also accepted
                    $mail->Port = 587;                            // TCP port to connect to
                    $mail->setFrom('candidatos@modelo.edu.mx', 'Universidad Modelo');

                    $mail->addAddress($to_email, $to_name);
                    if($empleadoSeguimiento->isNotEmpty()) {
                        $empleadoSeguimiento->each(static function($empleado) use ($mail) {
                            $mail->addAddress($empleado->empCorreo1);
                        });
                    }
                    $mail->addCC($cc_email);
                    //$mail->addCC($cc_email);

                    $mail->isHTML(true);                          // Set email format to HTML
                    $mail->Subject = "Candidato a 1er Ingreso: " . $request->input('perNombre')
                        . " " . $request->input('perApellido1')
                        . " ". $request->input('perApellido2');


                    $nosoymexicano = $request->noSoyMexicano ? $perCurp : $request->input('perCurp');
                    $body = "<p>Buen día: Se ha recibido una solicitud por parte de ". $nombreCandidato." para ingresar al primer semestre"
                    ." de la " . $carreraCandidato . "</p>
                    <p>Favor de realizar el seguimiento oportuno y adecuado mediante los datos de contacto:</p>
                    <p>CURP: " . $nosoymexicano . "</p>
                    <p>Fecha de nacimiento: " . Carbon::parse($request->input('perFechaNac'))->format("d-m-Y") . "</p>
                    <p>Lugar de nacimiento: ". $perLugarNac."</p>
                    <p>Preparatoria de procedencia: " . $preparatoriaProcedencia . "</p>
                    <p>Calificación exani: " . $request->input('curExani') . "</p>
                    <p>Email: ".$mailCandidato  ."</p>
                    <p>Teléfono: " . $telefonoCandidato. "</p>".
                    "<p><b><i>Este es un correo automatizado, favor de no responder a esta cuenta de correo electrónico.</i></b></p>";

                    $mail->Body  = $body;
                    $mail->send();
                }

                $nosoymexicano = $request->noSoyMexicano ? $perCurp : $request->input('perCurp');
                DB::update("update candidatos c, personas p set  c.candidatoPreinscrito = 'SI' where c.perCurp = p.perCurp
 and c.perCurp <> 'XEXX010101MNEXXXA8' and c.perCurp <> 'XEXX010101MNEXXXA4' and LENGTH(ltrim(rtrim(c.perCurp))) > 0
 and p.deleted_at is null and p.perCurp = ?", [$nosoymexicano]);

                return view("exito");


                alert('Escuela Modelo', 'El candidato se ha creado con éxito','success')->showConfirmButton();
                return redirect()->back();
            } catch (QueryException $e) {
                $errorCode = $e->errorInfo[1];
                $errorMessage = $e->errorInfo[2];
                alert()->error('Ups...' . $errorCode, $errorMessage)->showConfirmButton();
                return redirect()->back()->withInput();
            }
        }
    }



    public function preregistro(Request $request)
    {
        $departamentos = Departamento::whereIn("depNivel", [5,6])->get()->unique("depClave");
        $paises = Pais::get();

        $candidato = Candidato::where("id", "=", $request->candidatoId)->first();

        $municipio = Municipio::where("id", "=", $candidato->perLugarNac)->first();


        $preparatoriaProcedencia = PreparatoriaProcedencia::where("id", "=", $candidato->preparatoria_id)->first();

        return view('alumno.create', compact('departamentos', 'paises', 'candidato', 'municipio', 'preparatoriaProcedencia'));
    }


    public function preparatoriaProcedenciaCandidatos (Request $request)
    {
        return PreparatoriaProcedencia::where("municipio_id", "=", $request->municipio_id)
            ->where("prepHomologada", "=", "SI")
            ->orderBy("prepNombre")
        ->get();
    }
}
