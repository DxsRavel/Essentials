<?php namespace DxsRavel\Essentials\Traits;

trait PostManager{
	function post($accion){ //dd($accion);
		$x = explode('-',$accion);
		$metodo = $x[0];unset($x[0]);
		if(count($x)>0){
			foreach($x as $w){
				$metodo.= ucwords($w);
			}
		}
		//dd($metodo);
		return $this->$metodo();
	}
}