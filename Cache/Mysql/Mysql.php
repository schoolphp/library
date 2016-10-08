<?php
namespace FW\Cache\Mysql;

class Mysql {
	
	private $expire = 2678400;
	private $delstep = 10000;

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
		q("
			INSERT INTO `fw_cache_data` (`key`,`value`,`expire`) 
			VALUES ('".es($key)."','".es(serialize($value))."',NOW() + INTERVAL ".(int)$expire." SECOND)
			ON DUPLICATE KEY UPDATE `value`= '".es(serialize($value))."', `expire` = NOW() + INTERVAL ".(int)$expire." SECOND
		");
		return true;
	}

	public function get($key) {
		$res = q("
			SELECT `value`
			FROM `fw_cache_data`
			WHERE `key` = '".es($key)."'
		");
		if(!$res->num_rows) {
			return false;
		}

		$row = $res->fetch_assoc();
		$res->close();
		return unserialize($row['value']);
	}

	public function delete($key) {
		q("
			DELETE FROM `fw_cache_data`
			WHERE `key` = '".es($key)."'
		");
		return true;
	}

	public function delete_all() {
		$this->delstep = (int)$this->delstep;
		if($this->delstep < 0) {
			$this->delstep = 1000;
		}
		$i = 1;
		do {
			if($i++ > 1000) {
				return false;
			}
			q("
				DELETE FROM `fw_cache_data`
				LIMIT ".$this->delstep."
			");

		} while(\FW\DB\DB::_()->affected_rows);
		return true;
	}
	
	public function clearExpire() {
		q("
			DELETE FROM `fw_cache_data`
			WHERE `expire` < NOW()
		");
		return true;
	}
}
