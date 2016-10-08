<?php
namespace FW\Cache\File;

class File {
	
	private $expire = 2678400;

	public function __construct($options = []) {
		foreach($options as $k=>$v) {
			if(property_exists($this, $k)) {
				if(gettype($v) === gettype($this->$k)) {
					$this->$k = $v;
				}
			}
		}
		$this->clearExpire();
	}
	
	public function set($key,$value,$expire = 0) {
		if(!$expire) {
			$expire = $this->expire;
		}
		file_put_contents('./cache/file/'.$key,serialize(['data'=>$value,'expire'=>time()+$expire]));
		return true;
	}

	public function get($key) {
		$file = './cache/file/'.$key;
		if(!file_exists($file)) {
			return false;
		}
		$data = @file_get_contents($file);
		if($data === false) {
			return false;
		}
		$data = unserialize($data);
		if(time() > $data['expire']) {
			@unlink($file);
			return false;
		}
		return $data['data'];
	}

	public function delete($key) {
		$file = './cache/file/'.$key;
		if(file_exists($file)) {
			@unlink($file);
		}
		return true;
	}

	public function delete_all() {
		$files = scandir('./cache/file/');
		unset($files[0],$files[1]);
		foreach($files as $v) {
			@unlink('./cache/file/'.$v);
		}
		return true;
	}
	
	public function clearExpire() {
		$files = scandir('./cache/file/');
		unset($files[0],$files[1]);
		foreach($files as $v) {
			$data = @file_get_contents('./cache/file/'.$v);
			if($data === false) {
				@unlink('./cache/file/'.$v);
				continue;
			}
			$data = unserialize($data);
			if(time() > $data['expire']) {
				@unlink('./cache/file/'.$v);
			}
		}
		return true;
	}
}
