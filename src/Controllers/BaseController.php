<?php namespace DxsRavel\Essentials\Controllers;

use Illuminate\Foundation\Bus\DispatchesCommands;
use Illuminate\Routing\Controller as LaravelController;
use Illuminate\Foundation\Validation\ValidatesRequests;

abstract class BaseController extends LaravelController {
	protected $response = ['error'=>false,'title'=>'','message'=>''];
	use DispatchesCommands, ValidatesRequests;
	
	function setResponseTitle($title){ $this->response['title'] = $title; }
	function getResponseTitle(){ return $this->response['title']; }

	function setResponseMessage($message){ $this->response['message'] = $message; }
	function getResponseMessage(){ return $this->response['message']; }

	protected function setAjaxMessages($arr){
		$this->response['message'] = $arr['message'];
		$this->response['title'] = $arr['title'];
	}
	
	protected function toJSON(){
		if(!isset($this->response['error'])) $this->response['error'] = false;
		if(!isset($this->response['message'])) $this->response['message'] = '';
		if(!isset($this->response['title'])) $this->response['title'] = '';
		echo json_encode($this->response);exit;
	}
	static function datetime(){
		return date('Y-m-d H:i:s');
	}
	protected function upperKeysArray($arr){ $arr2 = [];
		foreach($arr as $c => $v){ $arr2[strtoupper($c)] = $v; }
		return $arr2;
	}
}
