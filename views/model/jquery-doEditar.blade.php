$.fn.doEditar = function(url,name,cols,vals,td_align,extra_cols,callback){
	var f = this;
	var table = $('#tb-'+name);
	var panel = $('#panel-editar-'+name);
	var panell = $(table).closest('.panel');
	var btn = $('#sbmt-editar-'+name);

	$(btn).lockBtn();
	var data = {}
	var data_old = {};
	$.each( $('.old',f) , function(id,input){
		var name = $(input).attr('name');
		data_old[name] = $(input).val();
	});
	var data_new = {};
	$.each( $('.new',f) , function(id,input){
		var name = $(input).attr('name');
		data_new[name] = $(input).val();
	});
	data['old'] = data_old;
	data['new'] = data_new;
	console.log(data);
	$.post(url,data,function(resp){ console.log(resp);
		$(btn).unlockBtn();
		toastr[ (resp.error)?'error':'success' ](resp.message,resp.title);
		if(!resp.error){				
			$(table).showData(name,resp.rows,cols,vals,td_align,extra_cols);
			$('.nfilas',panell).html(resp.rows.length);					
			$(panel).slideUp();
			$('.new',f).resetInput();
			$('.panel-action').slideUp();
			if(callback) callback(data['old'],data['new']);
		}
	},'json').fail(function(xhr){
		toastr['error'](xhr.status + ' (' + xhr.statusText + ')',"ERROR AL ENVIAR/RECIBIR DATOS" );				
		$(btn).unlockBtn();
	});	
	return false;
};