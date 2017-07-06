<?php namespace DxsRavel\Essentials\Traits;

use DB;
use Event;
use Exception;

use DxsRavel\Essentials\Events\ModelCreatedEvent;
use DxsRavel\Essentials\Events\ModelUpdatedEvent;
use DxsRavel\Essentials\Events\ModelDeletedEvent;

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
	function primero($where = []){
		$Query = DB::table( $this->Model->getTable() );
		foreach($where as $key => $val){
			$Query->where($key,$val);
		}
		return $Query->first();
	}
	function listar($where = [],$order=[]){
		$Query = DB::table( $this->Model->getTable() );
		foreach($where as $key => $val){
			$Query->where($key,$val);
		}
		foreach($order as $c => $t){
			$Query->orderBy($c,$t);
		}
		return $Query->get();
	}
	function listarNoBorrados(){
		$Query = DB::table($this->Model->getTable());
		if( $this->Model->hasSoftDeleteColumn() ){
			$Query = $Query->whereNull($this->Model->getSoftDeleteColumn());
		}
		if( $this->Model->hasStatusColumn() ){
			$Query = $Query->orderBy($this->Model->getStatusColumn());
		}
		return $Query->get();
	}
	function listarNoNulos(){
		$Query = DB::table($this->Model->getTable());
		if( $this->Model->hasSoftDeleteColumn() ){
			$Query = $Query->whereNull($this->Model->getSoftDeleteColumn());
		}
		if( $this->Model->hasStatusColumn() ){
			$Query = $Query->orderBy($this->Model->getStatusColumn());
		}
		return $Query->get();
	}
	function listarLista($where = [],$order=[]){
		$Lista = $this->listar($where,$order);
		$ret = array(); 
		foreach($Lista as $Model){
			$ret[ $this->Model->getHandleKeys($Model) ] =  $Model;
		}
		return $ret;
	}
	function listarArray($where = [],$dummy = false,$order = []){
		$Lista = $this->listar($where,$order);
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
		$this->ModelQuery = clone $this->Model;
		foreach( $pKeys as $column){
			if(!isset($new[$column])) return false;
			$Query = $Query->where($column,$new[$column]);
			$this->ModelQuery = $this->ModelQuery->where($column,$new[$column]);
		}
		$this->LastModel = $this->ModelQuery->first();
		return $this->LastModel;
		return $Query->first();
	}
	protected function puedeAgregar($new){ return true; }
	function agregar($new){
		$Model = clone $this->Model;
		if($this->puedeAgregar($new)){
			if($this->LastModel = $this->existe($new)){
				$this->title = 'Registro ya Existente';				
				$SoftDeleteColumn = $this->Model->getSoftDeleteColumn();				
				if( $Model->hasSoftDeleteColumn() &&
					//isset($this->LastModel->$SoftDeleteColumn) && 
					$this->LastModel->$SoftDeleteColumn !== null
				){				
					$this->message = 'Puede reactivar el Registro';
				}else{ 
					$this->LastModel = false;
				}
				return false;
			}
			foreach($Model->getFillable() as $column){
				if(isset($new[$column])) $Model->$column = $new[$column];
			}
			try{
				$pKeys = $this->Model->getPrimaryKeys();
				$this->LastModel = clone $Model;
				$Model->save(); // Cuando hace save() se borran los atributos pk				
				
				if(count($pKeys) == 1){
					$fillable_arr = $Model->getFillable();
					$pk = array_shift($pKeys);
					if(!isset($new[$pk]) && !in_array($pk, $fillable_arr)){
						try{							
							$Model->$pk = $this->lastInsertId();
							$this->LastModel->$pk = $this->lastInsertId();
						}catch(\Exception $e){

						}
					}
				}				
				
				$this->title = 'Agregado Correctamente.';
				Event::fire(new ModelCreatedEvent($this->LastModel));
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
			if( $this->LastModel = $this->existe($old) ){
				$update_arr = array();
				$this->OldModel = clone $this->LastModel;
				foreach($this->Model->getFillable() as $column){
					if(isset($new[$column])){ $update_arr[$column] = $new[$column]; }
					$this->LastModel->$column = $new[$column];
				}
				try{				
					$Query = DB::table( $this->Model->getTable() );
					foreach($this->Model->getPrimaryKeys() as $column){
						$Query->where($column,$old[$column]);
					}
					//$Query->update($update_arr);
					$this->LastModel->save();
					$this->title = 'Actualizado Correctamente.';
					Event::fire(new ModelUpdatedEvent($this->OldModel,$this->LastModel));
					return true;
				}catch(PDOException $e){			
					return false;
				}
			}else{
				$this->message = 'Entidad no encontrada';
				return false;
			}
		}
		return false;
	}
	function borrarLogico($old){
		$update_arr = array();
		try{
			if(!$this->Model->hasSoftDeleteColumn()){
				$this->title = 'Error al hacer un Borrado Logico.';
				$this->message = 'Se necesita especificar un campo para control de Borrado Logico.';
				throw new Exception("Soft Delete Column is Mandatory for Soft Delete", 1);				
			}

			if( $this->LastModel = $this->existe($old) ){
				$this->OldModel = clone $this->LastModel;
				$Query = DB::table( $this->Model->getTable() );
				foreach($this->Model->getPrimaryKeys() as $column){
					$Query->where($column,$old[$column]);
				}
				//$Query->delete();
				$date = date('Y-m-d H:i:s');
				
				$softDeleteColumn = $this->Model->getSoftDeleteColumn();
				$this->LastModel->$softDeleteColumn = $date;

				//$Query->update([ $softDeleteColumn => $date]);
				$this->LastModel->save();
				
				Event::fire(new ModelUpdatedEvent($this->OldModel,$this->LastModel));

				$this->title = 'Borrado Correctamente.';
				return true;
			}else{
				$this->message = 'Entidad no encontrada';
				return false;
			}
		}catch(Exception $e){
			return false;
		}catch(PDOException $e){			
			return false;
		}
	}
	function borrarFisico($old){
		$update_arr = array();
		try{
			if( $this->LastModel = $this->existe($old) ){
				$this->OldModel = clone $this->LastModel;
				$Query = DB::table( $this->Model->getTable() );
				foreach($this->Model->getPrimaryKeys() as $column){
					$Query->where($column,$old[$column]);
				}
				//$Query->delete();
				$this->LastModel->delete();				
				Event::fire(new ModelDeletedEvent($this->Model));
				$this->LastModel = null;
				$this->title = 'Borrado Correctamente.';
				return true;
			}else{
				$this->message = 'Entidad no encontrada';
				return false;
			}
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
			if( $this->LastModel = $this->existe($old) ){
				if(!$this->Model->hasSoftDeleteColumn()){
					$this->title = 'Error al hacer un Borrado Logico.';
					$this->message = 'Se necesita especificar un campo para control de Borrado Logico.';
					throw new Exception("Soft Delete Column is Mandatory for Soft Delete", 1);				
				}
				$this->OldModel = clone $this->LastModel;
				$Query = DB::table( $this->Model->getTable() );
				foreach($this->Model->getPrimaryKeys() as $column){
					$Query->where($column,$old[$column]);
				}
				//$Query->delete();
				$softDeleteColumn = $this->Model->getSoftDeleteColumn();
				//$Query->update([ $this->Model->getSoftDeleteColumn() => DB::Raw('NULL')]);
				$this->LastModel->$softDeleteColumn = DB::Raw('NULL');
				$this->LastModel->save();

				Event::fire(new ModelUpdatedEvent($this->OldModel,$this->LastModel));
				$this->title = 'Reactivado Correctamente.';
				return true;
			}else{
				$this->message = 'Entidad no encontrada';
				return false;
			}
		}catch(PDOException $e){			
			return false;
		}
	}
}