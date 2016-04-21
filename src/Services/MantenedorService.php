<?php namespace DxsRavel\Essentials\Services;

use DxsRavel\Essentials\Services\BaseService;

use DB;
use Exception;

class MantenedorService extends BaseService{
	protected $Model;
	protected $LastModel;
	function listar($where = [],$select=[],$order=[]){
		$Query = DB::table($this->Model->getTable());
				 //->whereNull('FECHA_HORA_BORRADO')
		foreach($where as $col => $val){
			$Query = $Query->where($col,$val);
		}
		foreach($select as $col){
			$Query = $Query->addSelect($col);
		}
		foreach($order as $c => $t){
			$Query = $Query->orderBy($c,$t);
		}
		$Query = $Query->orderBy($this->Model->getStatusColumn());		
		return $Query->get();
	}
	function listarNoBorrados(){
		return DB::table($this->Model->getTable())
				 ->whereNull('FECHA_HORA_BORRADO')
				 ->orderBy($this->Model->getStatusColumn())
				 ->get();
	}
	function listarActivos($order = []){
		return DB::table($this->Model->getTable())
				->whereNull('FECHA_HORA_BORRADO')
				->where($this->Model->getStatusColumn(),'A')
				->get();
	}
	function listarLista($where = []){
		$Lista = $this->listar($where);
		$ret = array(); 
		foreach($Lista as $Model){
			$ret[ $this->Model->getHandleKeys($Model) ] =  $Model;
		}
		return $ret;
	}
	function listarArray($where=[],$col = false,$all=false,$order=[],$dummy = false){
		if(!$col) $col = $this->Model->getColumnInformative();
		$Lista = $all?$this->listar($where,[],$order):$this->listarActivos($order);

		$primaryKeys = $this->Model->getPrimaryKeys();
		
		$ret = array();
		if($dummy) $ret['0'] = $dummy;
		foreach($Lista as $Row){
			$id = array();
			foreach($primaryKeys as $pk){
				$id[] = $Row->$pk;
			}
			$ret[implode('-',$id)] = $Row->$col;
		}
		return $ret;
	}
	function getLastModel(){
		return $this->LastModel;
	}	
	function existe($new){
		$Query = DB::table( $this->Model->getTable() );
		foreach($this->Model->getPrimaryKeys() as $column){
			$Query->where($column,$new[$column]);
		}
		return $Query->first();
	}
	function agregar($new){		
		$Model = $this->Model;
		if($this->LastModel = $this->existe($new)){
			$this->title = 'Registro ya Existente';
			if($this->LastModel->FECHA_HORA_BORRADO){				
				$this->message = 'Puede reactivar el Registro';
			}else{ $this->LastModel = false;}
			return false;
		}
		foreach($Model->getFillable() as $column){
			if(isset($new[$column])) $Model->$column = $new[$column];
		}
		try{
			//$Model->toUpperCase($column);
			$Model->save();
			$this->title = 'Agregado Correctamente.';
			return true;
		}catch(Exception $e){	
			
			throw new Exception($e);
				
			$this->title = 'Error en BD';
			return false;
		}		
	}
	function editar($old,$new){
		$update_arr = array();			
		foreach($this->Model->getFillable() as $column){
			if(isset($new[$column])) $update_arr[$column] = $new[$column];			
		}
		try{
			$Query = DB::table( $this->Model->getTable() );
			foreach($this->Model->getPrimaryKeys() as $column){
				$Query = $Query->where($column,$old[$column]);
			}
			$Query->update($update_arr);
			$this->title = 'Actualizado Correctamente.';
			return true;
		}catch(Exception $e){			
			$this->title = 'Error en BD';
			return false;
		}
	}
	function borrar($old){
		$update_arr = array();
		try{
			$Query = DB::table( $this->Model->getTable() );
			foreach($this->Model->getPrimaryKeys() as $column){
				$Query = $Query->where($column,$old[$column]);
			}
			//$Query->delete();
			$Query->update(['FECHA_HORA_BORRADO'=> date('Y-m-d H:i:s')]);
			$this->title = 'Borrado Correctamente.';
			return true;
		}catch(Exception $e){			
			$this->title = 'Error en BD';
			return false;
		}
	}
	function reactivar($old){
		$update_arr = array();
		try{
			$Query = DB::table( $this->Model->getTable() );
			foreach($this->Model->getPrimaryKeys() as $column){
				$Query = $Query->where($column,$old[$column]);
			}
			//$Query->delete();
			$Query->update(['FECHA_HORA_BORRADO'=> DB::Raw('NULL')]);
			$this->title = 'Borrado Correctamente.';
			return true;
		}catch(Exception $e){			
			$this->title = 'Error en BD';
			return false;
		}
	}
}