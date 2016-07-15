<script>
var values = {};
var td_align = {};
@foreach($Model->getVisible() as $column)
	<?php $Input = $Model->getInput($column);?>
	@if(isset($Input['values']))
		values['{{$column}}'] = {};
		@foreach($Input['values'] as $k => $v)
		values['{{$column}}']['{{$k}}'] = '{{$v}}';
		@endforeach
	@endif
	@if( isset($Input['td-align']) )
		td_align['{{$column}}'] = '{{$Input["td-align"]}}';
	@endif
@endforeach
</script>
<script>
$(function(){
	$('.panel-lista .btn-panel-full-width').click(function(){
		$(this).closest('.panel-lista').colWidthFull();
		$(this).parent().children('.btn-panel-back-width').show();
		$(this).hide();
	});
	$('.panel-lista .btn-panel-back-width').click(function(){
		$(this).closest('.panel-lista').colWidthBack();
		$(this).parent().children('.btn-panel-full-width').show();
		$(this).hide();
	});
	$('.dxscheck-for-password').click(function(){
		if( $(this).is(':checked') ){
			$(this).parent().parent().children('div').children('input[type=password]').attr('disabled',false).addClass('new').val('');
		}else{
			$(this).parent().parent().children('div').children('input[type=password]').attr('disabled',true).removeClass('new');
		}
	});
	$('#feditar').on('do-editar-success',function(){
		if( $('.dxscheck-for-password').is(':checked') ){
			$('.dxscheck-for-password').trigger('click');
		}
	})
});
</script>
<script>	
	$.fn.showData = function(rows,cols){ //console.log(cols);
		var scope = this;
		$('tbody',scope).empty();
		$.each(rows,function(i,row){
			var tr = '<tr>';				
				$.each(cols,function(id,col){
					var align = (col in td_align)?('text-'+td_align[col]):'';
					tr+= '<td class="'+ col +' '+ align +'" data-value="'+row[col]+'">'+ ( (col in values)?(values[col][row[col]]):row[col]) +'</td>';
				});						
				tr+= '<td class="text-right">';
				tr+= '<a class="btn btn-xs btn-danger tooltips btn-borrar" data-placement="top" title="Borrar"><i class="fa fa-trash-o"></i></a> ';
				tr+= '<a class="btn btn-xs btn-warning tooltips btn-editar" data-placement="top" title="Editar"><i class="fa fa-pencil"></i></a>';
				tr+= '</td>';
				tr+= '</tr>';
			$('tbody',scope).append(tr);
			$('.tooltips',scope).tooltip();
		});
	};
	$.fn.doAgregar = function(cols,visibles){ if(!visibles) visibles = cols;
		var scope = this;
		var btn = $('#sbmt-agregar');
		var data = {'_token':'<?php echo csrf_token();?>'};
		var data_new = {};
		var error = false;
		$.each( $('.new',scope) , function(id,input){
			var name = $(input).attr('name');
			var val = $(input).val();
			var required = $(input).attr('required');
			data_new[name] = val;
			if(required && val==''){error = true;}
		});			
		if(error) return false;

		$(btn).lockBtn();
		data['new']	= data_new;
		console.log(data);
		$.post('<?php echo URL::current();?>/agregar',data,function(resp){ //console.log(resp);
			$(btn).unlockBtn();
			toastr[ (resp.error)?'error':'success' ](resp.message,resp.title);
			if(!resp.error){				
				$('#tblista').showData(resp.rows,visibles);
				$('#nfilas').html(resp.rows.length);				
				//$('#panel-agregar').slideUp();
				$(scope).trigger('do-agregar-success',[resp.rows]);
			}else if(resp.data){
				$('#panel-agregar').slideUp();				
				$.each(columns,function(id,column){
					$('#freactivar .'+column).val( resp.data[column] );	
				});											
				$('#panel-reactivar').slideDown();
			}
			$('input',scope).resetInput();
			if(resp.Model){
				$.each(resp.Model,function(col,val){
					$('.'+col,scope).val(val);
				});
			}
		},'json').fail(function(xhr){
			toastr['error'](xhr.status + ' (' + xhr.statusText + ')',"ERROR AL ENVIAR/RECIBIR DATOS" );			
			$(btn).unlockBtn();
		});
	};
	$.fn.doEditar = function(cols,visibles){ if(!visibles) visibles = cols;
		var scope = this;
		var btn = $('#sbmt-editar'); $(btn).lockBtn();
		var data = {'_token':'<?php echo csrf_token();?>'};
		var data_old = {};
		$.each( $('.old',scope) , function(id,input){
			var name = $(input).attr('name');
			data_old[name] = $(input).val();
		});
		var data_new = {};
		$.each( $('.new',scope) , function(id,input){
			var name = $(input).attr('name');
			data_new[name] = $(input).val();
		});
		data['old'] = data_old;
		data['new'] = data_new;
		console.log(data);
		$.post('<?php echo URL::current();?>/editar',data,function(resp){ console.log(resp);
			$(btn).unlockBtn();
			toastr[ (resp.error)?'error':'success' ](resp.message,resp.title);
			if(!resp.error){				
				$('#tblista').showData(resp.rows,visibles);
				$('#nfilas').html(resp.rows.length);
				$('input',scope).val('');
				$('#panel-editar').slideUp();
				$('#panel-agregar').slideDown();
				$(scope).trigger('do-editar-success',[resp.rows]);				
			}
		},'json').fail(function(xhr){
			toastr['error'](xhr.status + ' (' + xhr.statusText + ')',"ERROR AL ENVIAR/RECIBIR DATOS" );				
			$(btn).unlockBtn();
		});
	}
	$.fn.doBorrar = function(cols,visibles){ if(!visibles) visibles = cols;
		var scope = this;
		var btn = $('#sbmt-borrar'); $(btn).lockBtn();
		var data = {'_token':'<?php echo csrf_token();?>'};
		var data_old = {};
		$.each( $('.old',scope) , function(id,input){
			var name = $(input).attr('name');
			data_old[name] = $(input).val();
		});
		data['old'] = data_old;
		console.log(data);
		$.post('<?php echo URL::current();?>/borrar',data,function(resp){ console.log(resp);
			$(btn).unlockBtn();
			toastr[ (resp.error)?'error':'success' ](resp.message,resp.title);
			if(!resp.error){				
				$('#tblista').showData(resp.rows,visibles);
				$('#nfilas').html(resp.rows.length);
				$('input',scope).val('');
				$('#panel-borrar').slideUp();
				$('#panel-agregar').slideDown();
				$(scope).trigger('do-borrar-success',[resp.rows]);
			}
		},'json').fail(function(xhr){
			toastr['error'](xhr.status + ' (' + xhr.statusText + ')',"ERROR AL ENVIAR/RECIBIR DATOS" );
			$(btn).unlockBtn();
		});
	}
	$.fn.doReactivar = function(cols,visibles){ if(!visibles) visibles = cols;
		var scope = this;
		var btn = $('#sbmt-reactivar'); $(btn).lockBtn();
		var data = {'_token':'<?php echo csrf_token();?>'};
		var data_old = {};
		$.each( $('.old',scope) , function(id,input){
			var name = $(input).attr('name');
			data_old[name] = $(input).val();
		});
		data['old'] = data_old;
		console.log(data);
		$.post('<?php echo URL::current();?>/reactivar',data,function(resp){ console.log(resp);
			$(btn).unlockBtn();
			toastr[ (resp.error)?'error':'success' ](resp.message,resp.title);
			if(!resp.error){				
				$('#tblista').showData(resp.rows,visibles);
				$('#nfilas').html(resp.rows.length);
				$('input',scope).val('');
				$('#panel-reactivar').slideUp();
				$('#panel-agregar').slideDown();
			}
		},'json').fail(function(xhr){
			toastr['error'](xhr.status + ' (' + xhr.statusText + ')',"ERROR AL ENVIAR/RECIBIR DATOS" );			
			$(btn).unlockBtn();
		});
	}

	toastr.options = {
	  "closeButton": true,
	  "debug": false,
	  "progressBar": true,
	  "positionClass": "toast-bottom-right",	  	  	  	  	  
	  "showEasing": "swing",
	  "hideEasing": "linear",
	  "showMethod": "fadeIn",
	  "hideMethod": "fadeOut",
	  "showDuration": "100",
  	  "hideDuration": "100",
  	  "timeOut": "1500",
  	  "extendedTimeOut": "1000",
	}
	var columns = ['{!! implode("','",$Model->getFillable()) !!}'];
	var pks = ['{!! implode("','",$Model->getPrimaryKeys()) !!}'];
	var visibles = ['{!! implode("','",$Model->getVisible()) !!}'];
	var btn_editar;
	var btn_borrar;

	function sbmtAgregar(){ $('#fagregar').doAgregar(columns,visibles); return false;}
	function sbmtEditar(){ $('#feditar').doEditar(columns,visibles); return false;}
	function sbmtBorrar(){ $('#fborrar').doBorrar(columns,visibles); return false;}
	function sbmtReactivar(){ $('#freactivar').doReactivar(columns,visibles); return false;}
	$(function(){
		$(document).on('click','.btn-editar',function(){
			$('#panel-borrar').slideUp();
			$('#panel-reactivar').slideUp();

			if( btn_editar == this){ 
				if( $('#panel-editar').is(':visible')){					
					$('#panel-editar').slideUp();
					$('#panel-agregar').slideDown();
					//$('#panel-agregar').show();
					return; 
				}
			}
			btn_editar = this; 			
			$('.paneles-accion .panel').hide();				
			var tr = $(this).closest('tr');
			$.each(columns,function(id,column){				
				$.each(visibles,function(id,column){
					var type = $('#feditar .'+column).attr('type');
					if(type!='password') $('#feditar .'+column).val( $('td.'+column,tr).data('value') );	
				});	
			});
			$('#panel-agregar').slideUp();		
			$('#panel-editar').slideDown();
		});
		$('#btn-cancelar-editar').click(function(){			
			$('#panel-editar').slideUp();
			$('#panel-agregar').slideDown();
		});

		$(document).on('click','.btn-borrar',function(){ 
			$('#panel-editar').slideUp();
			$('#panel-reactivar').slideUp();			
			if( btn_borrar == this){ $('#panel-borrar,#panel-agregar').slideToggle();return; }
			btn_borrar = this; 			
			$('.paneles-accion .panel').hide();
			$('#panel-agregar').slideUp();
			var tr = $(this).closest('tr');
			$.each(columns,function(id,column){
				$('#fborrar .'+column).val( $('td.'+column,tr).data('value') );	
			});	
			$('#panel-borrar').slideDown();			
		});
		$('#btn-cancelar-borrar').click(function(){						
			$('#panel-borrar').slideUp();
			$('#panel-agregar').slideDown();
		});

		$('#btn-cancelar-reactivar').click(function(){						
			$('#panel-reactivar').slideUp();
			$('#panel-agregar').slideDown();
		});
		
		@foreach($Model->getFillable() as $column)
			@if($seaBehavior = $Model->getSeaBehavior($column))
			$('.<?php echo $column;?>').seaBehavior('<?php echo $seaBehavior['type'];?>',{
				<?php $i = 0; foreach($seaBehavior['opts'] as $key => $val){
					if($i>0) echo ',';
					echo "'$key':";
					echo $seaBehavior['type']=='regex'?$val:"'$val'";
					$i++;
				}?>
				},{'callbackFunction': function(e,v){validateInputTooltip(e,v);}});	    	
			@endif
		@endforeach
	});
</script>