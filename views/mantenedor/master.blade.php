@extends($extendsView)

<?php 
$_class_col_left = isset($class_col_left)?$class_col_left:'col-md-7';
$_class_col_right = isset($class_col_right)?$class_col_right:'col-md-5';
?>

@section('title')
{{ Config::has('dxsravel.maintainer-title')?Config::get('dxsravel.maintainer-title'):'Mantenedor' }} | {{ $Model->getName() }}
@stop

@section('head_link')    
    @parent
    @include('DxsRavel::mantenedor.head-tags')
@stop

@section('main-content-wrapper')
<div class="row row-mantenedor row-cabecera">
  <div class="<?php echo $_class_col_left;?>">
      <!--breadcrumbs start -->
      <ul class="breadcrumb" style="">
          <li><a href="{{URL::to('/inicio')}}"><i class="fa fa-home"></i> Inicio</a></li>
          <li>{{ Config::has('dxsravel.maintainer-title')?Config::get('dxsravel.maintainer-title'):'Mantenedor' }}</li>
          <li class="active">{{ $Model->getName() }}</li>
      </ul>    
      <!--breadcrumbs end -->
  </div>
  <div class="<?php echo $_class_col_right;?>">
  	<!--<div class="panel">
  	<div class="panel-body">-->
  		<div class="pull-left">
  			<!--
			<div class="btn-group btn-group-xs" role="group">				
		  		<a href="{{ URL::to('/inicio') }}" class="btn btn-xs btn-default"><i class="fa fa-arrow-left"></i></a>
		  		<a href="{{ URL::to('/inicio') }}" class="btn btn-xs btn-white">Volver</a>		  		
		  	</div>  
		  	-->			
  		</div>
  		<div class="pull-right">
	  	<!--
	  	<div class="btn-group btn-group-xs" role="group">
	  		<a class="btn btn-xs btn-info"><i class="fa fa-print"></i></a>
	  		<a class="btn btn-xs btn-default">Imprimir Etiqueta</a>
	  	</div>
	  	-->
      	</div>
  	<!--</div>
  	</div>-->
  </div>
</div>
@yield('row-middle')
<div class="row row-mantenedor row-panel dxs-main-panel">    
	<div class="<?php echo $_class_col_left;?> panel-lista">
		@include('DxsRavel::mantenedor.panel-listar')
	</div>
	<div class="<?php echo $_class_col_right;?> paneles-accion">		
		@if($puede['borrar'])
		@include('DxsRavel::mantenedor.panel-borrar')
		@endif
		
		@if($puede['editar'])
		@include('DxsRavel::mantenedor.panel-editar')
		@endif
		
		@if($puede['agregar'])
		@include('DxsRavel::mantenedor.panel-agregar')
		@include('DxsRavel::mantenedor.panel-reactivar')
		@endif		
	</div>
</div>
@stop	
@section('body_pre_script')
	@parent		
	@include('DxsRavel::mantenedor.script-tags')
@stop
@section('body_script')
	@parent
	@include('DxsRavel::mantenedor.scripts')
@stop
