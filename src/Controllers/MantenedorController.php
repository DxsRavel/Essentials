<?php namespace DxsRavel\Essentials\Controllers;

use DxsRavel\Essentials\Controllers\BaseController;
use Illuminate\Http\Request;

class MantenedorController extends BaseController {
	protected $Service;
	protected $Model;
	protected $puede = ['agregar'=>true,'editar'=>true,'borrar'=>true];
	protected $extendsView = 'base';
	protected $assetsDir = 'assets';

	function post(Request $request, $accion){
		return $this->$accion($request);
	}
	function index(){
		$view = (isset($this->view)?$this->view:'DxsRavel::mantenedor.master');
		$Lista = $this->Service->listarNoBorrados();
		return 
			view($view)
			->with('Lista',$Lista)
			->with('Model',$this->Model)
			->with('puede',$this->puede)
			->with('extendsView',$this->extendsView)
			->with('assetsDir',$this->assetsDir)
			;
	}
	function indexLegacy(){
		$view = (isset($this->view)?$this->view:'DxsRavel::mantenedor-legacy.master');
		$Lista = $this->Service->listar();
		return 
			view($view)
			->with('Lista',$Lista)
			->with('Model',$this->Model)
			->with('puede',$this->puede)
			->with('extendsView',$this->extendsView)
			->with('assetsDir',$this->assetsDir)
			;
	}	
	function agregar(Request $request){
		$new = $request->input('new');
		$this->response['error'] = $err = !$this->Service->agregar($new);
		$this->setAjaxMessages( $this->Service->getAjaxMessages() );
		if(!$err){ 
			$this->response['rows'] = $this->Service->listarNoBorrados(); 			
		}else{
			$this->response['data'] = $this->Service->getLastModel();
		}		
		$this->response['Model'] = $this->Model->newInstance();
		$this->toJSON();
	}
	function editar(Request $request){
		$old = $request->input('old');
		$new = $request->input('new');
		$this->response['error'] = $err = !$this->Service->editar($old,$new);
		$this->setAjaxMessages( $this->Service->getAjaxMessages() );
		//$this->response['query'] = $this->Service->getLastQuery();
		if(!$err){ $this->response['rows'] = $this->Service->listarNoBorrados(); }
		$this->toJSON();
	}
	function borrar(Request $request){
		$old = $request->input('old');
		$this->response['error'] = $err = !$this->Service->borrar($old);
		$this->setAjaxMessages( $this->Service->getAjaxMessages() );		
		if(!$err){ $this->response['rows'] = $this->Service->listarNoBorrados(); }
		$this->toJSON();
	}
	function reactivar(Request $request){
		$old = $request->input('old');
		$this->response['error'] = $err = !$this->Service->reactivar($old);
		$this->setAjaxMessages( $this->Service->getAjaxMessages() );		
		if(!$err){ $this->response['rows'] = $this->Service->listarNoBorrados(); }
		$this->toJSON();
	}
}