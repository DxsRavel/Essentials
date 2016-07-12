<?php namespace DxsRavel\Essentials\Auth;

use RuntimeException;
use Illuminate\Contracts\Hashing\Hasher as HasherContract;

class PlainHasher implements HasherContract {	

	public function make($value, array $options = array()){
		return ($value);
	}
	public function check($value, $hashedValue, array $options = array()){
		return $value == $hashedValue;
	}
	public function needsRehash($hashedValue, array $options = array()){		
		return $hashedValue;
	}
	
}