<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

use App\Http\Helpers\GenerarLogs;


class PreparatoriaProcedencia extends Model
{

    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'preparatorias';


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
        'municipio_id',
        'prepNombre',
        'prepHomologada',
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



   public function municipio()
   {
       return $this->belongsTo(Municipio::class);
   }

}
