<div class="panel panel-success panel-action" id="panel-agregar-<?php echo $Model->stlHandleTable(); ?>" style="display:none;">
<div class="panel-heading">
	Agregar {{ $Model->getName() }}	
</div>
<div class="panel-body">
	<form class="form-xs form-horizontal" id="fagregar-<?php echo $Model->stlHandleTable(); ?>" onsubmit="return sbmtAgregar<?php echo $Model->ucwHandleTable();?>();">		
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
				{!! Form::text($column,'', $tags ) !!}
				@endif
				@if($Input['type'] == 'select')
				{!! Form::select($column,$Input['values'],'', $tags) !!}	
				@endif
				@if($Input['type'] == 'select-grouped')
				{!! Form::selectGrouped($column,$Input['values'],'', $tags) !!}	
				@endif
			</div>
		</div>
		@endif
		@endforeach
		<div class="form-group">
			<div class="text-right col-md-12">
				<a class="btn btn-xs btn-default btn-close-panel"><i class="fa fa-times"></i> Cancelar</a>
				<button class="btn btn-xs btn-success" id="sbmt-agregar-<?php echo $Model->stlHandle(); ?>">Agregar <i class="fa fa-plus"></i></button>
			</div>
		</div>
	</form>
</div>
</div>