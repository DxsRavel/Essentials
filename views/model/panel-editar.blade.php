<div class="panel panel-warning panel-action" id="panel-editar-<?php echo $Model->stlHandleTable(); ?>" style="display:none;">
<div class="panel-heading"><strong>Editar</strong></div>
<div class="panel-body">
{!! Form::open(array('method'=>'POST','id'=>'feditar-'.$Model->stlHandleTable(),'class'=>'form-horizontal form-xs','onsubmit'=>'return sbmtEditar'.$Model->ucwHandleTable().'();')) !!}
@foreach($Model->getPrimaryKeys() as $column)
<input type="hidden" class="form-control old {{ $column }}" name="{{ $column }}">
@endforeach
@foreach($Model->getFillable() as $column)
<?php $Input = $Model->getInput($column);
$def_tag = ['class'=>"form-control new $column"];
$tags = isset($Input['tags'])?array_merge($def_tag, $Input['tags']):$def_tag;
?>
@if(! (isset($Input['hidden']) && $Input['hidden']) )
<div class="form-group {{$column}}">
	<label class="col-md-{{ $Input['label-col'] }}">{{ $Model->getLabel($column) }}:</label>
	<div class="col-md-{{ $Input['input-col'] }}">
		@if($Input['type'] == 'text')					
		{!! Form::text($column,'', $tags) !!}
		@endif
		@if($Input['type'] == 'select')
		{!! Form::select($column,$Input['values'],'', $tags ) !!}	
		@endif
		@if($Input['type'] == 'select-grouped')
		{!! Form::selectGrouped($column,$Input['values'],'',array_merge( ['class'=>'form-control new '.$column], $tags) ) !!}	
		@endif
	</div>
</div>
@endif
@endforeach
<div class="pull-right">
	<a class="btn btn-xs btn-default btn-close-panel"><i class="fa fa-times"></i> Cancelar</a> 
	<button class="btn btn-xs btn-warning" id="sbmt-editar-<?php echo $Model->stlHandle(); ?>">Editar <i class="fa fa-pencil"></i></button>
</div>
{!! Form::close() !!}
</div>
</div>