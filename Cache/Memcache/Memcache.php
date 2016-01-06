<?php
namespace FW\Cache\Memcache;

class Memcache {
	
	private $expire = 0;
	private $host = 'localhost';
	private $port = 11211;
	private $connect; 

	public function __construct($options = array()) {
		foreach($options as $k=>$v) {
			if(property_exists($this, $k)) {
				if(gettype($v) === gettype($this->$k)) {
					$this->$k = $v;
				}
			}
		}
		$this->connect = memcache_connect($this->host, $this->port);
		$this->clearExpire();
	}
	
	public function set($key,$value,$expire = 0) {
		if(!$expire) {
			$expire = $this->expire;
		}
		return $this->connect->set($key,$value,0,$expire);
	}

	public function get($key) {
		return $this->connect->get($key);
	}

	public function delete($key) {
		return $this->connect->delete($key);
		return true;
	}

	public function delete_all() {
		return $this->connect->flush();
		return true;
	}
	
	public function clearExpire() {
		return true;
	}
}
