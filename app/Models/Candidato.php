<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

use App\Http\Helpers\GenerarLogs;


class Candidato extends Model
{

    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'candidatos';


    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'perCurp',
        'perApellido1',
        'perApellido2',
        'perNombre',
        'perFechaNac',
        'perLugarNac',
        'perSexo',
        'perCorreo1',
        'perTelefono1',
        'perFoto',
        'curExani',
        'preparatoria_id',
        'ubicacion_id',
        'ubiClave',
        'ubiNombre',
        'departamento_id',
        'escuela_id',
        'director_id',
        'director_correo',
        'programa_id',
        'progClave',
        'progNombre',
        'coordinador_id',
        'coordinador_correo',
        'candidatoPreinscrito',
        'esExtranjero',
        'usuario_at'
    ];

    protected $dates = [
        'deleted_at',
    ];

    protected $casts = [
        'aluClave' => 'integer',
    ];

    /**
   * Override parent boot and Call deleting event
   *
   * @return void
   */
   protected static function boot()
   {
     parent::boot();


   }

    public function ubicacion()
    {
        return $this->belongsTo(Ubicacion::class);
    }

    public function departaments()
    {
        return $this->hasMany(Departamento::class);
    }

    public function escuelas()
    {
        return $this->hasMany(Escuela::class);
    }


    public function director()
    {
        return $this->hasMany(Empleado::class);
    }


    public function programas(){
        return $this->hasMany(Programa::class);
    }

    public function coordinador(){
        return $this->hasMany(Empleado::class);
    }

}
