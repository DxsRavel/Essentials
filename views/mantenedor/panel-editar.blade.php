<div class="panel panel-warning" id="panel-editar" style="display:none;">
  <div class="panel-heading"><strong>Editar</strong></div>
  <div class="panel-body">
	{!! Form::open(array('method'=>'POST','id'=>'feditar','class'=>'form-horizontal form-xs feditar','onsubmit'=>'return sbmtEditar();')) !!}				
	@foreach($Model->getPrimaryKeys() as $column)
	<input type="hidden" class="form-control old {{ $column }}" name="{{ $column }}">
	@endforeach
	@foreach($Model->getFillable() as $column)
	<?php $Input = $Model->getInput($column);
	$base_tags = (isset($Input['check-for-enable']) && $Input['check-for-enable'] == true)?['class'=>"form-control $column",'disabled'=>'']:['class'=>"form-control new $column"];
	$tags = array_merge( $base_tags , (isset($Input['tags'])?$Input['tags']:[]) );
	?>
	<div class="form-group">
		<label class="col-md-{{ $Input['label-col'] }}">
		{{ $Model->getLabel($column) }}:
		@if( isset($Input['check-for-enable']) && $Input['check-for-enable'] == true )
		<input type="checkbox" class="dxscheck-for-password">
		@endif
		</label>
		<div class="col-md-{{ $Input['input-col'] }}">
			@if($Input['type'] == 'text')					
			{!! Form::text($column,'', $tags) !!}
			@endif
			@if($Input['type'] == 'number')
			{!! Form::number($column,'', $tags) !!}
			@endif
			@if($Input['type'] == 'color')	
			<?php $tags['class'] = $tags['class'].' colorpicker';?>
			{!! Form::text($column,'', $tags) !!}
			@endif
			@if($Input['type'] == 'select')
			{!! Form::select($column,$Input['values'],'', $tags ) !!}	
			@endif
			@if($Input['type'] == 'email')
			{!! Form::email($column,'', $tags) !!}
			@endif					
			@if($Input['type'] == 'password')
			{!! Form::password($column,$tags) !!}
			@endif
			@if($Input['type'] == 'group-list')
			{!! Form::groupList($column,$Input['pivot'],$Input['list'],$Input['model'],'', $tags) !!}	
			@endif
		</div>
	</div>
	@endforeach
	<div class="pull-right">
		<a class="btn btn-xs btn-default" id="btn-cancelar-editar"><i class="fa fa-times"></i>		
		{{ Config::has('dxsravel.maintainer-label.edit-cancel')?Config::get('dxsravel.maintainer-label.edit-cancel'):'Cancelar' }}
		</a> 
		<button class="btn btn-xs btn-warning" id="sbmt-editar">
		{{ Config::has('dxsravel.maintainer-label.edit-submit')?Config::get('dxsravel.maintainer-label.edit-submit'):'Editar' }}
		<i class="fa fa-pencil"></i></button>
	</div>
	{!! Form::close() !!}
  </div>
</div>