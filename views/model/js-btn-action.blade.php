var btn_editar;
var btn_agregar;
var btn_borrar;
var btn_action;
@foreach($Models as $Model)
$('.btn-agregar-{{$Model->stlHandleTable()}}').click(function(){
	if( btn_action == this){ 
		$('#panel-agregar-{{$Model->stlHandleTable()}}').slideToggle();
		return true; 
	}else{
		$('#panel-agregar-{{$Model->stlHandleTable()}}').hide();
		$('.panel-action').hide();	
	}
	btn_action = this;		
	$('#panel-agregar-{{$Model->stlHandleTable()}}').slideDown();
});		
$('#fagregar-{{$Model->stlHandleTable()}} .new').resetInput();

$(document).on('click','.btn-editar-{{$Model->stlHandleTable()}}',function(){				
	if( btn_action == this){ $('#panel-editar-{{$Model->stlHandleTable()}}').slideToggle();return; }else{
		$('#panel-editar-{{$Model->stlHandleTable()}}').hide();
		$('.panel-action').hide();	
	}
	btn_action = this; 			
	var tr = $(this).closest('tr');		
	$.each(columns['{{$Model->getTable()}}'],function(id,column){
		$('#feditar-{{$Model->stlHandleTable()}} .'+column).val( $('td.'+column,tr).data('value') );	
	});
	$('.old','#feditar-{{$Model->stlHandleTable()}}').each(function(id,dom){
		var name = $(this).attr('name');
		$(this).val( $(btn_action).data( name.toLowerCase() ) );
	});
	$('#panel-editar-{{$Model->stlHandleTable()}}').slideDown();
});	

$(document).on('click','.btn-borrar-{{$Model->stlHandleTable()}}',function(){ 		
	if( btn_action == this){ $('#panel-borrar-{{$Model->stlHandleTable()}}').slideToggle();return; }else{
		$('#panel-borrar-{{$Model->stlHandleTable()}}').hide();
		$('.panel-action').hide();	
	}
	btn_action = this;
	var tr = $(this).closest('tr');
	$.each(columns['{{$Model->getTable()}}'],function(id,column){
		$('#fborrar-{{$Model->stlHandleTable()}} .'+column).val( $('td.'+column,tr).data('value') );	
	});
	$('.old','#fborrar-{{$Model->stlHandleTable()}}').each(function(id,dom){
		var name = $(this).attr('name');
		$(this).val( $(btn_action).data( name.toLowerCase() ) );
	});
	$('#panel-borrar-{{$Model->stlHandleTable()}}').slideDown();			
});
@endforeach