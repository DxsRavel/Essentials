<?php namespace DxsRavel\Essentials\Services;

use DB;
use URL;

class UrlService{	
	public static function isCurrent($handle,$ret = false){
		if( URL::to($handle) == URL::current() ){
			if($ret === false) return true;
			return $ret;
		}
		if($ret === false) return false;
		return '';
	}
}