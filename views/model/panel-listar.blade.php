<div class="panel" id="panel-lista-<?php echo $Model->stlHandleTable(); ?>">
<div class="panel-heading">
	<strong>Lista de {{ $Model->getName() }}</strong>
	<div class="pull-right">
	@if(isset($panelClosable) && $panelClosable)
	<a class="btn btn-xs btn-default btn-action btn-close-panel"><i class="fa fa-times"></i> Cerrar</a>
	@endif
	@if(!(isset($can_add) && !$can_add))
	<a class="btn btn-xs btn-success btn-action btn-agregar-<?php echo $Model->stlHandleTable(); ?>" id=""> Agregar <i class="fa fa-plus"></i></a>			
	@endif
	</div>
</div>
<table class="table panel-body" id="tb-<?php echo $Model->stlHandleTable(); ?>">
	<thead>
		<tr>
			@foreach($Model->getVisible() as $column)
			<th class="text-center th-{{$column}}">{{ $Model->getLabel($column) }}</th>
			@endforeach
			@if( !(isset($can_remove) && !$can_remove) || !(isset($can_edit) && !$can_edit) )
			<th class="th-actions"></th>
			@endif
			@if($extra_cols && count($extra_cols)>0)
			@foreach($extra_cols as $buttons)
			<th></th>
			@endforeach
			@endif
		</tr>  
	</thead>
	<tbody>
		@foreach($Lista as $Item)
		<tr 
			<?php foreach($Model->getPrimaryKeys() as $col){
				echo 'data-'. strtolower($col).'="'.$Item->$col.'" ';
			}?>
		>
			@foreach($Model->getVisible() as $column)
			<td class="{{$column}} {{$Model->tdAlign($column)}}" data-value="{{$Item->$column}}">{{ $Model->normalize($Item->$column,$column) }}</td>						
			@endforeach
			@if( !(isset($can_remove) && !$can_remove) || !(isset($can_edit) && !$can_edit) )
			<td class="text-right">
				@if(!(isset($can_remove) && !$can_remove))
				<a class="btn btn-xs btn-danger btn-action tooltips btn-borrar-<?php echo $Model->stlHandleTable(); ?>" data-placement="top" title="Borrar"
					<?php foreach($Model->getPrimaryKeys() as $col){
						echo 'data-'. strtolower($col).'="'.$Item->$col.'" ';
					}?>
				>
				<i class="fa fa-trash-o"></i>
				</a>
				@endif
				@if(!(isset($can_edit) && !$can_edit))
				<a class="btn btn-xs btn-warning btn-action tooltips btn-editar-<?php echo $Model->stlHandleTable(); ?>" data-placement="top" title="Editar"
					<?php foreach($Model->getPrimaryKeys() as $col){
						echo 'data-'. strtolower($col).'="'.$Item->$col.'" ';
					}?>
				><i class="fa fa-pencil"></i></a>	
				@endif					
			</td>
			@endif
			@if($extra_cols && count($extra_cols)>0)
			@foreach($extra_cols as $buttons)
			<td class="text-right">
				@foreach($buttons as $button)
				<a class="{{$button['class']}} btn-action tooltips" title="{{$button['title']}}"
					<?php foreach($Model->getPrimaryKeys() as $col){
						echo 'data-'. strtolower($col).'="'.$Item->$col.'" ';
					}?>
				>
				@if(isset($button['fa']))
				<i class="fa {{$button['fa']}}"></i>
				@endif
				</a>
				@endforeach
			</td>
			@endforeach
			@endif
		</tr>
		@endforeach
	</tbody>
</table>
<div class="panel-footer">
		Hay <strong class="nfilas">{{ count($Lista) }} </strong> Registro(s)
	</div>
</div>