<?php namespace DxsRavel\Essentials\Auth;

use RuntimeException;
use Illuminate\Contracts\Hashing\Hasher as HasherContract;

class Md5Hasher implements HasherContract {

	protected $rounds = 10;

	public function info($hashedValue) {
		return [];
	}

	public function make($value, array $options = array()){
		return md5($value);
	}
	public function check($value, $hashedValue, array $options = array()){
		return md5($value) == $hashedValue;
	}
	public function needsRehash($hashedValue, array $options = array()){		
		return md5($hashedValue);
	}
	
}