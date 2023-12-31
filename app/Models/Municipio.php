<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class Municipio extends Model
{

    use SoftDeletes;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'municipios';

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
        'estado_id',
        'munNombre',
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

   public function ubicaciones()
    {
        return $this->hasMany(Ubicacion::class);
    }

    public function estado()
    {
        return $this->belongsTo(Estado::class);
    }

    public function persona()
    {
        return $this->hasOne(Persona::class);
    }

    public static function municipios($id){
        return  Municipio::where('estado_id','=',$id)
        ->whereNotIn( 'id', [268])
        ->orderBy('munNombre')->get();
    }

}
