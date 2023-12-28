<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class Programa extends Model
{

    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'programas';


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
        'escuela_id',
        'empleado_id',
        'progClave',
        'progNombre',
        'progNombreCorto',
        'progClaveSegey',
        'progClaveEgre',
        'progTituloOficial',
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
        return $this->belongsTo(Empleado::class);
    }

    public function escuela()
    {
        return $this->belongsTo(Escuela::class);
    }

    public function planes()
    {
        return $this->hasMany(Plan::class);
    }


}