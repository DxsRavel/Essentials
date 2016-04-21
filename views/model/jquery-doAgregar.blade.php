$.fn.doAgregar = function(url,name,cols,vals,td_align,extra_cols,callback){
	//var name = 'flujo';
	//var fagregar = $('#fagregar-flujo');
	var f = this;
	var table = $('#tb-'+name);
	var panel = $('#panel-agregar-'+name);	
	var panell = $(table).closest('.panel');
	var btn = $('#sbmt-agregar-'+name);
	//var cols = columns['FLUJO'];
	//var vals = values['FLUJO'];
	//var extra_cols = [[{'class':'btn btn-xs btn-info btn-ver-nodos','title':'Ver Nodos','fa':'fa-eye'}]];
	
	$(btn).lockBtn();
	var data = {};
	var data_new = {};
	$.each( $('.new',f) , function(id,input){
		var name = $(input).attr('name');
		data_new[name] = $(input).val();
	});			
	data['new']	= data_new; //console.log(data);
	$.post(url,data,function(resp){ //console.log(resp);
		$(btn).unlockBtn();
		toastr[ (resp.error)?'error':'success' ](resp.message,resp.title);
		if(!resp.error){				
			$(table).showData(name,resp.rows,cols,vals,td_align,extra_cols);
			$('.nfilas',panell).html(resp.rows.length);					
			$(panel).slideUp();
			$('.new',f).resetInput();
			$('.panel-action').slideUp();
			if(callback) callback(data['new']);
		}else if(resp.data){
			/*
			$(panel).slideUp();				
			$.each(columns,function(id,column){
				$('#freactivar .'+column).val( resp.data[column] );	
			});											
			$(panel_hide).slideDown();
			*/
		}
		$('input',f).resetInput();
	},'json').fail(function(xhr){
		toastr['error'](xhr.status + ' (' + xhr.statusText + ')',"ERROR AL ENVIAR/RECIBIR DATOS" );				
		$(btn).unlockBtn();
	});
	return false;
};