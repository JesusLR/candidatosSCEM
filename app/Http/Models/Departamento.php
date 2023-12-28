<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class Departamento extends Model
{

    use SoftDeletes;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'departamentos';


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
        'ubicacion_id',
        'depNivel',
        'depClave',
        'depNombre',
        'depNombreCorto',
        'depClaveOficial',
        'depNombreOficial',
        'perActual',
        'perAnte',
        'perSig',
        'depCalMinAprob',
        'depCupoGpo',
        'depTituloDoc',
        'depNombreDoc',
        'depPuestoDoc',
        'depIncorporadoA',
        'usuario_at'
    ];

    protected $dates = [
        'deleted_at',
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

    public function escuelas()
    {
        return $this->hasMany(Escuela::class);
    }

    // public function periodos()
    // {
    //     return $this->hasMany(Periodo::class);
    // }

    // public function periodoAnterior() {
    //     return $this->belongsTo(Periodo::class,'perAnte');
    // }

    // public function periodoActual() {
    //     return $this->belongsTo(Periodo::class,'perActual');
    // }

    // public function periodoSiguiente() {
    //     return $this->belongsTo(Periodo::class,'perSig');
    // }

}