<div class="panel panel-default" id="panel-agregar" style="display:;">
  <div class="panel-heading"><strong>Agregar</strong></div>
  <div class="panel-body">
	{!! Form::open(array('method'=>'POST','id'=>'fagregar','class'=>'form-horizontal form-xs fagregar','onsubmit'=>'return sbmtAgregar();')) !!}
	@foreach($Model->getFillable() as $column)
	<?php $Input = $Model->getInput($column);?>
	<div class="form-group">
		<label class="col-md-{{ $Input['label-col'] }}">{{ $Model->getLabel($column) }}:</label>
		<div class="col-md-{{ $Input['input-col'] }}">
			@if($Input['type'] == 'text')					
			{!! Form::text($column,$Model->$column,array_merge( ['class'=>'form-control new '.$column], $Input['tags']) ) !!}
			@endif
			@if($Input['type'] == 'number')					
			{!! Form::number($column,$Model->$column,array_merge( ['class'=>'form-control new '.$column], $Input['tags']) ) !!}
			@endif
			@if($Input['type'] == 'color')					
			{!! Form::text($column,$Model->$column,array_merge( ['class'=>'form-control new colorpicker '.$column], $Input['tags']) ) !!}
			@endif			
			@if($Input['type'] == 'select')
			{!! Form::select($column,$Input['values'],$Model->$column,array_merge( ['class'=>'form-control new '.$column], $Input['tags']) ) !!}	
			@endif
			@if($Input['type'] == 'email')					
			{!! Form::email($column,$Model->$column,array_merge( ['class'=>'form-control new '.$column], $Input['tags']) ) !!}
			@endif
			@if($Input['type'] == 'password')					
			{!! Form::password($column,array_merge( ['class'=>'form-control new '.$column], $Input['tags']) ) !!}
			@endif
			@if($Input['type'] == 'select-grouped')
			{!! Form::selectGrouped($column,$Input['values'],$Model->$column, $Input['tags']) !!}	
			@endif
			@if($Input['type'] == 'group-list')
			{!! Form::groupList($column,$Input['pivot'],$Input['list'],$Input['model'],$Model->$column, array_merge( ['class'=>'form-control new '.$column], $Input['tags']) ,$Input['dummy']) !!}	
			@endif
		</div>
	</div>
	@endforeach
	<div class="pull-right">
		{{--<a class="btn btn-xs btn-default " id="btn-cancelar-agregar"> Cancelar</a>--}}
		<button type="submit" class="btn btn-xs btn-success sbmt-btn" id="sbmt-agregar"> 
		{{ Config::has('dxsravel.maintainer-label.add-submit')?Config::get('dxsravel.maintainer-label.add-submit'):'Agregar' }} 
		<i class="fa fa-plus"></i></button>
	</div>
	{!! Form::close() !!}
  </div>
</div>