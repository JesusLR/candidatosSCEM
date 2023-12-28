@extends('layouts.dashboard')

@section('template_title')
    Candidato
@endsection

@section('head')

@endsection



@section('content')
<div class="row">
  <div class="col s12">
    <div style="display:block; text-align:center;">
      <img width="300px; margin: 0 auto;" src="{{asset("images/logo.png")}}" alt="">
    </div>
    <div style="display:block; text-align:center;">

      <h4 style="text-align:center;">Tus datos se han guardado correctamente.</h4>
      <h5 style="text-align:center;">La institución se pondrá en contacto contigo para el siguiente trámite.</h5>

      <a style="margin: 0 auto; text-align:center;" href="https://www.unimodelo.edu.mx/">
        <button type="submit" class="btn btn-success">
          Salir
        </button>
      </a>
    </div>

  </div>

</div>

@endsection







