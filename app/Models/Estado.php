<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class Estado extends Model
{
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'estados';

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
        'pais_id',
        'edoNombre',
        'edoAbrevia',
        'edoRenapo',
        'edoISO',
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

    public function pais()
    {
        return $this->belongsTo(Pais::class);
    }

    public function municipios()
    {
        return $this->hasMany(Municipio::class);
    }

    public static function estados($id){
        return  Estado::where('pais_id','=',$id)->orderBy("edoNombre")->get();
    }
}
