$.fn.showData = function(name,rows,cols,values,td_align,extra){
		if(!extra) extra = [];
		if(!td_align) td_align = [];		
		var scope = this;
		$('tbody',scope).empty();
		$.each(rows,function(i,row){
			var tr = '<tr ';
				$('#feditar-'+name+' .old').each(function(){ var n = $(this).attr('name');
					tr+= 'data-'+ n +'="' + row[n] + '" ';	
				});
				tr+= '>';				
				console.log(row);
				$.each(cols,function(id,col){
					var align = (col in td_align)?('text-'+td_align[col]):'';
					if(col in values){ console.log('col ['+col+'] esta ');console.log(values);}
					//console.log('row:'+row+' col:'+col+ ' '+ row[col]);
					tr+= '<td class="'+ col +' '+ align +'" data-value="'+row[col]+'">'+ ( (col in values)?( (row[col] in values[col])?values[col][row[col]]:'' ):row[col]) +'</td>';
				});			
				@if(!(isset($can_remove) && !$can_remove) || !(isset($can_edit) && !$can_edit))			
				tr+= '<td class="text-right">';
				@if(!(isset($can_remove) && !$can_remove))
				tr+= '<a class="btn btn-xs btn-danger btn-action tooltips btn-borrar-'+ name +'" data-placement="top" title="Borrar"';
				$('#fborrar-'+name+' .old').each(function(){ var n = $(this).attr('name');
					tr+= 'data-'+ n +'="' + row[n] + '" ';	
				});
				tr+= '><i class="fa fa-trash-o"></i></a> ';
				@endif
				@if(!(isset($can_edit) && !$can_edit))
				tr+= '<a class="btn btn-xs btn-warning btn-action tooltips btn-editar-'+ name +'" data-placement="top" title="Editar"';
				$('#feditar-'+name+' .old').each(function(){ var n = $(this).attr('name');
					tr+= 'data-'+ n +'="' + row[n] + '" ';	
				});
				tr+= '><i class="fa fa-pencil"></i></a>';
				@endif
				@endif
				tr+= '</td>';
				if(extra.length>0){
					$.each(extra,function(id,buttons){
						tr+= '<td class="text-right">';
						$.each(buttons,function(id,button){
							tr+= '<a class="'+ button.class +' btn-action tooltips" data-placement="top" title="'+ button.title +'"';
							$('#feditar-'+name+' .old').each(function(){ var n = $(this).attr('name');
								tr+= 'data-'+ n +'="' + row[n] + '" ';	
							});
							tr+= '>';
							if(button.fa) tr+= '<i class="fa '+ button.fa +'"></i>';
							tr+= '</a>';
						});
						tr+= '</td>';
					});
				}
				tr+= '</tr>';
			$('tbody',scope).append(tr);			
			$('.tooltips',scope).tooltip();
		});
		$(scope).trigger('table-updated',scope,rows);
};