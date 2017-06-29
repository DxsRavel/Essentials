<?php namespace DxsRavel\Essentials\Services;

use DxsRavel\Essentials\Services\BaseService;
use DxsRavel\Essentials\Traits\ServiceCRUD;
use DB;
use Exception;

class MantenedorService extends BaseService{
	use ServiceCRUD {listarArray as listarArrayTrait;}
	protected $Model;
	protected $LastModel;

	function listarArray($where=[],$col = [],$all=false,$order=[],$dummy = false){
		//if(!$col) $col = $this->Model->getColumnInformative();
		$Lista = $all?$this->listar($where,[],$order):$this->listarActivos($order);

		$primaryKeys = $this->Model->getPrimaryKeys();
		
		$ret = array();
		if($dummy) $ret['0'] = $dummy;
		foreach($Lista as $Row){
			$id = array();
			foreach($primaryKeys as $pk){
				$id[] = $Row->$pk;
			}
			//$ret[implode('-',$id)] = $Row->$col;
			$ret[implode('-',$id)] = $this->Model->getHandleInformatives($Row,$col);
		}
		return $ret;
	}
	function listarActivos($order = []){
		if($this->Model->hasSoftDeleteColumn()){
			return DB::table($this->Model->getTable())
				->whereNull($this->Model->getSoftDeleteColumn())
				->where($this->Model->getStatusColumn(),'A')
				->get();
		}else{
			return DB::table($this->Model->getTable())				
				->where($this->Model->getStatusColumn(),'A')
				->get();
		}		
	}
	function getLastModel(){
		return $this->LastModel;
	}	
}