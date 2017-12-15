<?php namespace DxsRavel\Essentials\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Builder;

class BaseModel extends Model{
	protected $primaryKeys;
	protected $labels;
	protected $name;
	protected $inputs;
	protected $original_table;
	protected $sequence_name = false;
	protected $results_per_page = 2;

	public function setTable($table){
		$this->original_table = $this->table;
		$this->table = $table;
	}
	public function recoveryTable(){
		$this->table = $this->original_table;
	}
	public function getSequenceName(){
		return $this->sequence_name;
	}
	public function getResultsPerPage(){
		return $this->results_per_page;
	}
	public function stlHandle(){ // string to lowe handle(table)
		return strtolower($this->getTable());
	}
	public function stlHandleTable(){ // string to lowe handle(table)
		$table = $this->getTable();
		if( $prefix = $this->getPrefix() ){
			$table = str_replace($prefix, '', $table);
		}
		return strtolower($table);
	}
	public function ucwHandle(){ // camelize handle(table)
		$name = $this->getTable(); //return ($name);
		$x = explode('_',$name);
		$str = '';
		foreach($x as $w){ $str.= ucwords(strtolower($w));}
		return $str;

	}
	public function ucwHandleTable(){ // string to lowe handle(table)
		$name = $this->getTable();
		if( $prefix = $this->getPrefix() ){
			$name = str_replace($prefix, '', $name);
		}
		$x = explode('_',$name);
		$str = '';
		foreach($x as $w){ $str.= ucwords(strtolower($w));}
		return $str;
	}
	public function getInformatives(){
		if(!isset($this->informative)){ 
			throw new \Exception("No hay columna informativa especificada para modelo", 1);
		}
		return $this->informative;
	}
	public function getColumnInformative(){
		if($cols = $this->getInformatives() ){
			//if(count($cols)>1){ throw new \Exception("Más de una columna informativa especificada para modelo", 1); }
			$col = array_shift($cols);			
			return $col;
		}
		return false;
	}
	public function getHandleInformatives($Model,$cols = []){
		$infs = array();
		$informatives = (count($cols)>0)?$cols:$this->getInformatives();
		foreach($informatives as $col){
			if( property_exists($Model,$col) ){
				$infs[] = $Model->$col;
			}else{
				$infs[] = $col;
			}
		}	
		return implode('',$infs);
	}
	public function getPrimaryKeys(){
		if(!isset($this->primaryKeys)) return false;
		return $this->primaryKeys;
	}
	public function getPrimaryKey(){
		return $this->primaryKey;
	}
	public function getHandleKeys($Model){
		$keys = array();
		foreach($this->getPrimaryKeys() as $col){
			$keys[] = $Model->$col;
		}
		return implode('-',$keys);

	}
	public function getPkValue(){
		if($pks = $this->getPrimaryKeys() ){
			if(count($pks)==1){
				$pk = array_shift($pks);
				return $this->$pk;
			}
			$vals = [];
			foreach($pks as $pk){
				$vals[$pk] = $this->$pk;			
			}
			return $vals;
		}
		return false;
	}
	public function getPkNameValue(){
		$vals = [];
		if($pks = $this->getPrimaryKeys() ){
			foreach($pks as $pk){
				$vals[$pk] = $this->$pk;			
			}			
		}
		return $vals;
	}
	public function getLabel($col){
		if(isset($this->labels[$col])) return $this->labels[$col];
		return '{'.$col.'}';
	}
	public function getPrefix(){
		if(!isset($this->prefix)) return false;
		return $this->prefix;
	}
	public function getName(){
		return $this->name;
	}
	public function getInput($col){
		if(!isset($this->inputs[$col])) return false;
		return $this->inputs[$col];
	}
	function isPrimaryKey($col){
		return in_array($col, $this->primaryKeys);
	}
	function getFillableAndKeys(){
		return array_unique(array_merge($this->fillable,$this->primaryKeys));
	}
	protected function setValues($col,$val){
		if(!isset($this->inputs[$col])) $this->inputs[$col] = [];
		$this->inputs[$col]['values'] = $val;
	}
	protected function values($col){
		$Input = $this->getInput($col);
		if(!isset($Input['values']) ) return false;
		return $Input['values'];

	}
	function normalize($val,$col){
		if( ($values = $this->values($col)) && isset($values[$val]) ) return $values[$val];
		return $val;
	}	
	function tdAlign($col){
		$Input = $this->getInput($col);
		if(!isset($Input['td-align']) ) return '';
		return 'text-'.$Input['td-align'];
	}
	function hasStatusColumn(){
		if( $this->statusColumn && $this->statusColumn !== null){ return true; }
		return false;
	}
	function getStatusColumn(){
		$statusColumn = $this->statusColumn;
		if(!$statusColumn) return $this->fillable[ count($this->fillable) -1];
		return $statusColumn;
	}
	function hasSoftDeleteColumn(){
		if($this->softDeleteColumn && $this->softDeleteColumn !== null){return true;}
	}
	function getSoftDeleteColumn(){
		if(!$this->softDeleteColumn){return null;}
		return $this->softDeleteColumn;
	}
	function getSeaBehavior($column){
		if(!isset($this->inputs[$column])) return false;
		if(!isset($this->inputs[$column]['seaBehavior'])) return false;
		return $this->inputs[$column]['seaBehavior'];
	}

	/**
     * Set the keys for a save update query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function setKeysForSaveQuery(Builder $query)
    {
    	$pKeys = $this->getPrimaryKeys();

    	if( is_array($this->getKeyName()) || count($pKeys) > 1){    		
    		foreach ($pKeys as $key) {
	            // UPDATE: Added isset() per devflow's comment.
	            if (isset($this->$key))
	                $query->where($key, '=', $this->$key);
	            else
	                throw new Exception(__METHOD__ . 'Missing part of the primary key: ' . $key);
	        }
	        return $query;
    	}else{    		
    		// From \Illuminate\Database\Eloquent\Model
    		$query->where($this->getKeyName(), '=', $this->getKeyForSaveQuery());
        	return $query;
    	}
    }

    /**
     * Execute a query for a single record by ID.
     *
     * @param  array  $ids Array of keys, like [column => value].
     * @param  array  $columns
     * @return mixed|static
     */
    public function find($id, $columns = ['*'])
    {
		$pKeys = $this->getPrimaryKeys();

        if( is_array($this->getKeyName()) || count($pKeys) > 1){
        	$me = new self;
	        $query = $me->newQuery();
	        if (!is_array($id)) {
	        	throw new Exception('find input must to be an array');
	        }
	        foreach ($this->getKeyName() as $key) {
	            $this->query->where($key, '=', $id[$key]);
	        }
	        return $query->first($columns);
        }else{
        	// From \Illuminate\Database\Eloquent\Builder
        	if (is_array($id)) {
            	return $this->findMany($id, $columns);
	        }
	        $this->query->where($this->model->getQualifiedKeyName(), '=', $id);
	        return $this->first($columns);
        }
        
    }
}
