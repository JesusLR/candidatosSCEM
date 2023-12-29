<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

use App\Http\Helpers\GenerarLogs;


class Persona extends Model
{

    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'personas';


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
        'municipio_id',
        'perSexo',
        'perCorreo1',
        'perTelefono1',
        'perTelefono2',
        'perDirCP',
        'perDirCalle',
        'perDirNumInt',
        'perDirNumExt',
        'perDirColonia',
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

    public function empleado()
    {
        return $this->hasOne(Empleado::class);
    }

    public function municipio()
    {
        return $this->belongsTo(Municipio::class);
    }



}
