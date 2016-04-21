<?php namespace DxsRavel\Essentials\Controllers;

use DxsRavel\Essentials\Controllers\BaseController;

use Input;

class MantenedorController extends BaseController {
	protected $Service;
	protected $Model;
	function post($accion){
		return $this->$accion();
	}
	function index(){
		$Lista = $this->Service->listar();
		return view('DxsRavel.mantenedor.master')->with('Lista',$Lista)->with('Model',$this->Model);
	}	
	function agregar(){
		$new = Input::get('new');
		$this->response['error'] = $err = !$this->Service->agregar($new);
		$this->setAjaxMessages( $this->Service->getAjaxMessages() );
		if(!$err){ $this->response['rows'] = $this->Service->listarNoBorrados(); }else{
			$this->response['data'] = $this->Service->getLastModel();
		}
		$this->toJSON();
	}
	function editar(){
		$old = Input::get('old');
		$new = Input::get('new');
		$this->response['error'] = $err = !$this->Service->editar($old,$new);
		$this->setAjaxMessages( $this->Service->getAjaxMessages() );
		//$this->response['query'] = $this->Service->getLastQuery();
		if(!$err){ $this->response['rows'] = $this->Service->listarNoBorrados(); }
		$this->toJSON();
	}
	function borrar(){
		$old = Input::get('old');
		$this->response['error'] = $err = !$this->Service->borrar($old);
		$this->setAjaxMessages( $this->Service->getAjaxMessages() );		
		if(!$err){ $this->response['rows'] = $this->Service->listarNoBorrados(); }
		$this->toJSON();
	}
	function reactivar(){
		$old = Input::get('old');
		$this->response['error'] = $err = !$this->Service->reactivar($old);
		$this->setAjaxMessages( $this->Service->getAjaxMessages() );		
		if(!$err){ $this->response['rows'] = $this->Service->listarNoBorrados(); }
		$this->toJSON();
	}
}