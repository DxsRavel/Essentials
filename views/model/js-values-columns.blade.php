var values = {};
var td_align = {};
var columns = {};
@foreach($Models as $Model)
values['{{$Model->getTable()}}'] = {};
td_align['{{$Model->getTable()}}'] = {};
@foreach($Model->getVisible() as $column)
	<?php $Input = $Model->getInput($column);?>
	@if(isset($Input['values']))
		values['{{$Model->getTable()}}']['{{$column}}'] = {};
		@foreach($Input['values'] as $k => $v)
		values['{{$Model->getTable()}}']['{{$column}}']['{{$k}}'] = '{{$v}}';
		@endforeach
	@endif
	@if( isset($Input['td-align']) )
		td_align['{{$Model->getTable()}}']['{{$column}}'] = '{{$Input["td-align"]}}';
	@endif
@endforeach
columns['{{$Model->getTable()}}'] = ['{!! implode("','",$Model->getVisible()) !!}'];
@endforeach
