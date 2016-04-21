<div class="panel panel-default">
	<div class="panel-heading">
		<strong>Mantenedor - {{ $Model->getName() }}</strong>
		{{--<a class="btn btn-xs btn-success pull-right" id="btn-agregar-item"><i class="fa fa-plus"></i> Agregar</a>--}}				
	</div>
	<table class="panel-body table table" id="tblista">
		<thead>
		<tr>
			@foreach($Model->getVisible() as $column)
			<th class="text-center">{{ $Model->getLabel($column) }}</th>
			@endforeach
			<th></th>
		</tr>  
		</thead>
		<tbody>
		@foreach($Lista as $Item)
			<tr>
				@foreach($Model->getVisible() as $column)
				<td class="{{$column}} {{$Model->tdAlign($column)}}" data-value="{{$Item->$column}}">{{ $Model->normalize($Item->$column,$column) }}</td>						
				@endforeach
				<td class="text-right">
					@if($puede['borrar'])
					<a class="btn btn-xs btn-danger tooltips btn-borrar" data-placement="top" title="Borrar"><i class="fa fa-trash-o"></i></a>
					@endif
					@if($puede['editar'])
					<a class="btn btn-xs btn-warning tooltips btn-editar" data-placement="top" title="Editar"><i class="fa fa-pencil"></i></a>
					@endif
				</td>
			</tr>
		@endforeach
		</tbody>	
	</table>
	<div class="panel-footer">
		Hay <strong id="nfilas">{{ count($Lista) }} </strong> Registro(s)
	</div>
</div>