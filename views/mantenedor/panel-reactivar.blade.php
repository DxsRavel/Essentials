<div class="panel panel-default" id="panel-reactivar" style="display:none;">
	  <div class="panel-heading"><strong>Reactivar</strong></div>
	  <div class="panel-body">
		{!! Form::open(array('method'=>'POST','id'=>'freactivar','class'=>'form-horizontal form-xs fagregar','onsubmit'=>'return sbmtReactivar();')) !!}
		@foreach($Model->getFillableAndKeys() as $column)
		<?php $Input = $Model->getInput($column); 
		$old = $Model->isPrimaryKey($column)?'old':''; 
		$tags = array_merge( ['class'=>"form-control $old $column",'readonly'=>'','disabled'=>''], (isset($Input['tags'])?$Input['tags']:[]));
		?>
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
				{!! Form::password($column, $tags ) !!}
				@endif
				@if($Input['type'] == 'group-list')
				{!! Form::groupList($column,$Input['pivot'],$Input['list'],$Input['model'],'', $tags) !!}	
				@endif
			</div>
		</div>
		@endforeach
		<div class="pull-right">
			<a class="btn btn-xs btn-default " id="btn-cancelar-reactivar"><i class="fa fa-times"></i> 
			{{ Config::has('dxsravel.maintainer-label.reactivate-cancel')?Config::get('dxsravel.maintainer-label.reactivate-cancel'):'Cancelar' }} 
			</a>
			<button type="submit" class="btn btn-xs btn-warning sbmt-btn" id="sbmt-reactivar"> 
			{{ Config::has('dxsravel.maintainer-label.reactivate-submit')?Config::get('dxsravel.maintainer-label.reactivate-submit'):'Reactivar' }} 
			<i class="fa fa-exclamation-triangle"></i></button>
		</div>
		{!! Form::close() !!}
	  </div>
</div>