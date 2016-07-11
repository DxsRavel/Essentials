<script>
/*
$(document).bind('DOMNodeInserted', '.tooltips',function(e) {
var element = e.target;
$(element).tooltip();
});
*/
$.fn.faspin = function(icon) {    
  $('<i/>',{class:'fa '+ icon +' fa-spin'}).prependTo(this);
};  
$.fn.disableBtn = function(){
$(this).attr('disabled',true);
}
$.fn.enableBtn = function(){
$(this).attr('disabled',true);
}
$.fn.lockBtn = function(){
$(this).faspin('fa-refresh');
$(this).attr('disabled',true);
};
$.fn.unlockBtn = function(){
$('.fa-spin',this).remove();
$(this).attr('disabled',false);
};
$.fn.spinIcon = function(sp){
	if(!sp) sp = 'refresh';
	var i = $('i',this);
	var c = $(i).attr('class');
	$(i).data('fa',c);
	$(i).attr('class','fa fa-spin fa-'+sp);
	$(this).attr('disabled',true);
}
$.fn.unspinIcon = function(){
	var i = $('i',this);
	var c = $(i).data('fa');
	$(i).attr('class',c);
	$(this).attr('disabled',false);
}
$.fn.resetInput = function(){
$(this).each(function(){var node = this;
  var d = $(node).data('default');
  if( d!=='undefined' ){
    $(node).val(d);
  }else{
    var tagName = node.tagName;   
    if(tagName == 'INPUT'){ 
      var type = $(this).attr('type');
      if(type == 'text') $(node).val(''); 
    }
    if(tagName == 'SELECT'){
      if(!$(node).prop('disabled')){
        $(node).val( $('option:enabled:first',node).val() );
        $(node).trigger('change');
      }
    }
  }
});
};
$.fn.dataOld = function(){
var f = this;
var data_old = {};
$.each( $('.old',f) , function(id,input){
  var name = $(input).attr('name');data_old[name] = $(input).val();
});
return data_old;
};
$.fn.dataNew = function(){
var f = this;
var data_new = {};
$.each( $('.new',f) , function(id,input){
  var name = $(input).attr('name');
  data_new[name] = $(input).val();
});
return data_new;
}
$.fn.dataNewSetDefault = function(slctd,callback){
var f = this;
var data_new = {};
$.each( $('.new',f) , function(id,input){		      
  	var node = this;
  	var tagName = node.tagName;   
  	var def = '';
    if(tagName == 'INPUT'){ 
    	def = $(input).val();
    }
    if(tagName == 'SELECT'){

      if( $('option[selected]',node).length > 0){
      	def = $('option[selected]',node).attr('value');
      }
      if(slctd){
       def = $('option:selected',node).attr('value');
       $('option:selected',node).attr('selected',true);
      }
    }

    $(input).data('default',def);
});
if(callback) callback();
}
$.fn.newNoChange = function(){
	var f = this;		    
var ret = true;
$.each( $('.new',f) , function(id,input){
  var name = $(input).attr('name');
  var v = $(input).val();
  var d = $(input).data('default');
  if(d!=='undefined' && (v!=d)) ret = false;
});
return ret;
}
$.fn.cardCollapse = function(){
	materialadmin.AppCard.cardCollapse($(this));
}
$.fn.cardExpand = function(){
	materialadmin.AppCard.cardExpand($(this));
}
</script>