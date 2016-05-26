<?php namespace DxsRavel\Essentials\Traits;

use DB;

trait ServiceCRUD{
	//protected $Model;	
	function setModel($Model){
		$this->Model = $Model;
	}
	function getModel(){
		return $this->Model;
	}
	function clearLastModel(){
		$this->LastModel = false;
	}
	function listar($where = []){
		$Query = DB::table( $this->Model->getTable() );
		foreach($where as $key => $val){
			$Query->where($key,$val);
		}
		return $Query->get();
		return DB::table($this->Model->getTable())
				 //->whereNull('FECHA_HORA_BORRADO')
				 //->orderBy($this->Model->getStatusColumn())
				 ->get();
	}
	function listarNoBorrados(){
		return DB::table($this->Model->getTable())
				 ->whereNull('FECHA_HORA_BORRADO')
				 ->orderBy($this->Model->getStatusColumn())
				 ->get();
	}
	function listarNoNulos(){
		return DB::table($this->Model->getTable())
				 ->whereNull('FECHA_HORA_BORRADO')
				 ->orderBy($this->Model->getStatusColumn())
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
	function listarArray($where = [],$dummy = false){
		$Lista = $this->listar($where);
		$ret = array(); 
		if($dummy) $ret['0'] = $dummy;
		foreach($Lista as $Model){
			$ret[ $this->Model->getHandleKeys($Model) ] =  $this->Model->getHandleInformatives($Model);
		}
		return $ret;
	}
	function listarAgrupadoArray($val){
		$id = $val['id'];$label = $val['label'];
		$Lista = $this->listar(); //dd($Lista);
		$ret = array();
		foreach($Lista as $Model){
			$ret[$Model->$id]['label'] = $Model->$label;
			$ret[$Model->$id]['values'][ $this->Model->getHandleKeys($Model) ] = $this->Model->getHandleInformatives($Model);
		}
		return $ret;
	}
	public function existe($new){
		$pKeys = $this->Model->getPrimaryKeys();		
		if(count($pKeys) == 1){
			$pk = array_shift($pKeys);
			//dd($new[$pk]);
			if(!isset($new[$pk])) return false;
			return $this->Model->find($new[$pk]);					
		}		
		$Query = DB::table( $this->Model->getTable() );
		foreach( $pKeys as $column){
			if(!isset($new[$column])) return false;
			$Query->where($column,$new[$column]);
		}
		return $Query->first();
	}
	protected function puedeAgregar($new){ return true; }
	function agregar($new){
		$Model = clone $this->Model;
		if($this->puedeAgregar($new)){
			if($this->LastModel = $this->existe($new)){
				$this->title = 'Registro ya Existente';
				if(isset($this->LastModel->FECHA_HORA_BORRADO) && $this->LastModel->FECHA_HORA_BORRADO){				
					$this->message = 'Puede reactivar el Registro';
				}else{ $this->LastModel = false;}
				return false;
			}
			foreach($Model->getFillable() as $column){
				if(isset($new[$column])) $Model->$column = $new[$column];
			}
			try{
				$Model->save();
				$this->title = 'Agregado Correctamente.';
				return $Model;
			}catch(PDOException $e){	
				$this->title = 'ERROR EN BD';		
				$this->message = 'Error al Agregar un Registro de '.$this->Model->getTable();
				return false;
			}	
		}
		return false;	
	}
	protected function puedeEditar($old,$new){ return true; }
	function editar($old,$new){
		if($this->puedeEditar($old,$new)){
			$update_arr = array();			
			foreach($this->Model->getFillable() as $column){
				if(isset($new[$column])) $update_arr[$column] = $new[$column];			
			}
			try{				
				$Query = DB::table( $this->Model->getTable() );
				foreach($this->Model->getPrimaryKeys() as $column){
					$Query->where($column,$old[$column]);
				}
				$Query->update($update_arr);
				$this->title = 'Actualizado Correctamente.';
				return true;
			}catch(PDOException $e){			
				return false;
			}
		}
		return false;
	}
	function borrarLogico($old){
		$update_arr = array();
		try{
			$Query = DB::table( $this->Model->getTable() );
			foreach($this->Model->getPrimaryKeys() as $column){
				$Query->where($column,$old[$column]);
			}
			//$Query->delete();
			$Query->update(['FECHA_HORA_BORRADO'=> date('Y-m-d H:i:s')]);
			$this->title = 'Borrado Correctamente.';
			return true;
		}catch(PDOException $e){			
			return false;
		}
	}
	function borrarFisico($old){
		$update_arr = array();
		try{
			$Query = DB::table( $this->Model->getTable() );
			foreach($this->Model->getPrimaryKeys() as $column){
				$Query->where($column,$old[$column]);
			}
			$Query->delete();
			//$Query->update(['FECHA_HORA_BORRADO'=> date('Y-m-d H:i:s')]);
			$this->title = 'Borrado Correctamente.';
			return true;
		}catch(PDOException $e){			
			return false;
		}
	}
	protected function puedeBorrar($old){ return true; }
	function borrar($old){ 
		if($this->puedeBorrar($old)){
			return $this->borrarLogico($old); 
		}
		return false;
	}
	function editarOcrear($old,$new){
		if(! $this->existe($old) ){
			$new2 = $new;
			if(is_array($old) && count($old)>0){
				//$new2 = array_merge($new,$old);
				foreach($old as $k => $v){
					$new2[$k] = $v;
				}
			}
			return $this->agregar($new2);
		}else{
			return $this->editar($old,$new);
		}
	}
	function reactivar($old){
		$update_arr = array();
		try{
			$Query = DB::table( $this->Model->getTable() );
			foreach($this->Model->getPrimaryKeys() as $column){
				$Query->where($column,$old[$column]);
			}
			//$Query->delete();
			$Query->update(['FECHA_HORA_BORRADO'=> DB::Raw('NULL')]);
			$this->title = 'Borrado Correctamente.';
			return true;
		}catch(PDOException $e){			
			return false;
		}
	}
}