<?php
namespace DxsRavel\Essentials\Migrations;

use DxsRavel\Essentials\Controllers\BaseController;
use Illuminate\Database\Schema\Builder;

use View, Session, Redirect, DB, Validator, Schema, Closure;

class SchemaConsole extends Builder{	
	
	public static function createTable($table, Closure $callback)
    {
        //$tb_name = 'tb_encuesta';
		if (!Schema::hasTable($table)) {
			try{
				Schema::create($table,$callback);
				echo 'Tabla '.$table.' creada correctamente.';
			}catch(\Exception $e){
				echo 'Hubo un error al crear la tabla.';
			}
		}else{
			echo 'Tabla '.$table.' ya existe, no se pudo crear.';
		}
    }

    public static function deleteTable($table){    	
		if (Schema::hasTable($table)) {
			try{
				Schema::drop($table);
				echo 'Tabla '.$table.' borrada correctamente.';
			}catch(\Exception $e){
				echo 'Hubo un error al borrada la tabla.';
			}
		}else{
			echo 'Tabla '.$table.' no existe, no se pudo borrar.';
		}
    }
}