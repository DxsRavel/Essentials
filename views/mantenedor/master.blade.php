@extends('base')

@section('title')
{{ Config::has('dxsravel.maintainer-title')?Config::get('dxsravel.maintainer-title'):'Mantenedor' }} | {{ $Model->getName() }}
@stop

@section('head_tags')    
    {!! Html::style('assets/toastr-master/toastr.css') !!}        
    {!! Html::style('assets/bootstrap-colorpicker/css/colorpicker.css') !!}
@stop

@section('main-content')
<div class="row row-mantenedor row-cabecera">
  <div class="col-md-7">
      <!--breadcrumbs start -->
      <ul class="breadcrumb" style="">
          <li><a href="{{URL::to('/inicio')}}"><i class="fa fa-home"></i> Inicio</a></li>
          <li>{{ Config::has('dxsravel.maintainer-title')?Config::get('dxsravel.maintainer-title'):'Mantenedor' }}</li>
          <li class="active">{{ $Model->getName() }}</li>
      </ul>    
      <!--breadcrumbs end -->
  </div>
  <div class="col-md-5">
  	<!--<div class="panel">
  	<div class="panel-body">-->
  		<div class="pull-left">
			<div class="btn-group btn-group-xs" role="group">				
		  		<a href="{{ URL::to('/inicio') }}" class="btn btn-xs btn-default"><i class="fa fa-arrow-left"></i></a>
		  		<a href="{{ URL::to('/inicio') }}" class="btn btn-xs btn-white">Volver</a>		  		
		  	</div>
  			<!--<a href="{{ URL::to('/recepcion') }}" class="btn btn-xs btn-danger pull-right"><i class="fa fa-arrow-left"></i> Volver</a>-->
  		</div>
  		<div class="pull-right">
	  	<!--
	  	<div class="btn-group btn-group-xs" role="group">
	  		<a class="btn btn-xs btn-info"><i class="fa fa-print"></i></a>
	  		<a class="btn btn-xs btn-default">Imprimir Etiqueta</a>
	  	</div>
	  	-->
      	</div>
  	<!--</div>
  	</div>-->
  </div>
</div>
@yield('row-middle')
<div class="row row-mantenedor row-panel">    
	<div class="col-md-7 panel-lista">
		@include('DxsRavel::mantenedor.panel-listar')
	</div>
	<div class="col-md-5 paneles-accion">		
		@if($puede['borrar'])
		@include('DxsRavel::mantenedor.panel-borrar')
		@endif
		
		@if($puede['editar'])
		@include('DxsRavel::mantenedor.panel-editar')
		@endif
		
		@if($puede['agregar'])
		@include('DxsRavel::mantenedor.panel-agregar')
		@include('DxsRavel::mantenedor.panel-reactivar')
		@endif		
	</div>
</div>
@stop	
@section('script_tags')
	@parent		
	{!! Html::script('assets/toastr-master/toastr.js') !!}	

	{!! Html::script('assets/seahorse/seahorse-1.2.js') !!}
  	{!! Html::script('assets/seahorse/seahorse.jquery-1.2.js') !!}

  	{!! Html::script('assets/bootstrap-colorpicker/js/bootstrap-colorpicker.js') !!}
  	<script>
  	$(function(){
  		$('.colorpicker').colorpicker();
  	});
  	</script>
@stop
@section('body_scripts')
@parent
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
			if( btn_editar == this){ $('#panel-editar,#panel-agregar').slideToggle();return; }
			btn_editar = this; 			
			$('.paneles-accion .panel').hide();
			$('#panel-agregar').slideUp();
			var tr = $(this).closest('tr');
			$.each(columns,function(id,column){				
				$.each(visibles,function(id,column){
				var type = $('#feditar .'+column).attr('type');
				if(type!='password') $('#feditar .'+column).val( $('td.'+column,tr).data('value') );	
			});	
			});											
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
@stop
