<?php namespace DxsRavel\Essentials\Services;

use DB;

class BaseService{
	protected $message;
	protected $title;
	protected $lastQuery;
	protected $Model;
	protected $LastModel;
	
	//function puedeAgregar($new){return true;}
	//function puedeEditar($old,$new){return true;}
	//function puedeBorrar($old){return true;}

	function getMessage(){
		return $this->message;
	}
	function setMessage($message){
		return $this->message = $message;
	}
	function getAjaxMessages(){
		return array('message'=>$this->message,'title'=>$this->title);
	}
	protected function setAjaxMessages($arr){
		$this->message = $arr['message'];
		$this->title = $arr['title'];
	}
	protected function appendAjaxMessages($arr){
		if(strlen($arr['message'])>0) $this->message .= ', '.$arr['message'];		
	}
	static function getLastQuery(){ 			
		$queries = DB::getQueryLog();
 		return last( $queries );
	}
	function getLastModel(){
		return $this->LastModel;
	}
	static function datetime(){
		return date('Y-m-d H:i:s');
	}
	function lastInsertId(){
		$seq_name = $this->Model->getSequenceName();
		if(!$seq_name) return DB::getPdo()->lastInsertId();
		return DB::getPdo()->lastInsertId($seq_name);
	}
}