<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('api/departamentos/{id}','DepartamentoController@getDepartamentos');
Route::get('api/estados/{id}','EstadoController@getEstados');

Route::get('api/municipios/{id}','MunicipioController@getMunicipios');
Route::get('api/preparatoriaProcedencia/{municipio_id}','AlumnoController@preparatoriaProcedencia')->name('api/preparatoriaProcedencia');



// Candidatos Route
Route::get("/", 'CandidatosPrimerIngresoController@index');
Route::resource('candidatos_primer_ingreso','CandidatosPrimerIngresoController');
Route::get('api/candidatos_primer_ingreso','CandidatosPrimerIngresoController@list')->name('api/candidatosPrimerIngreso');
Route::get('candidatos_primer_ingreso/preregistro/{candidatoId}',
    'CandidatosPrimerIngresoController@preregistro')->name('candidatos_primer_ingreso/preregistro');

Route::get('api/programaByCampus/{ubicacion_id}','CandidatosPrimerIngresoController@getProgramasByCampus');
    
    

Route::get('api/programaByCampus/{ubicacion_id}','CandidatosPrimerIngresoController@getProgramasByCampus');
