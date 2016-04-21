<div class="panel panel-danger" id="panel-borrar" style="display:none;">
  <div class="panel-heading"><strong>Borrar</strong></div>
  <div class="panel-body">
	{!! Form::open(array('method'=>'POST','id'=>'fborrar','class'=>'form-horizontal form-xs fborrar','onsubmit'=>'return sbmtBorrar();')) !!}				
	@foreach($Model->getFillableAndKeys() as $column)
	<?php $Input = $Model->getInput($column); 
	$old = $Model->isPrimaryKey($column)?'old':''; 
	$tagsInput = (isset($Input['tags']))?$Input['tags']:[];
	$tags = array_merge( ['class'=>"form-control $old $column",'readonly'=>'','disabled'=>''], $tagsInput);
	?>
	@if($Input['type'] == 'hidden')
	{!! Form::hidden($column,'', $tags ) !!}
	@else
	<div class="form-group">
		<label class="col-md-{{ $Input['label-col'] }}">{{ $Model->getLabel($column) }}:</label>
		<div class="col-md-{{ $Input['input-col'] }}">
			@if($Input['type'] == 'text')					
			{!! Form::text($column,'', $tags ) !!}
			@endif
			@if($Input['type'] == 'color')					
			{!! Form::text($column,'', $tags ) !!}
			@endif
			@if($Input['type'] == 'select')
			{!! Form::select($column,$Input['values'],'', $tags ) !!}	
			@endif
			@if($Input['type'] == 'email')					
			{!! Form::email($column,'', $tags ) !!}
			@endif
			@if($Input['type'] == 'password')					
			{!! Form::password($column,$tags ) !!}
			@endif
			@if($Input['type'] == 'group-list')
			{!! Form::groupList($column,$Input['pivot'],$Input['list'],$Input['model'],'', $tags) !!}	
			@endif
		</div>
	</div>
	@endif
	@endforeach
	<div class="pull-right">
		<a class="btn btn-xs btn-default" id="btn-cancelar-borrar"><i class="fa fa-times"></i> Cancelar</a> 
		<button class="btn btn-xs btn-danger" id="sbmt-borrar">Borrar <i class="fa fa-trash-o"></i></button>
	</div>
	{!! Form::close() !!}
  </div>			  			  
</div>