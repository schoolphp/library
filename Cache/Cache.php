<?php
namespace FW\Cache;

class Cache {
	static $instance = [];
	static $driver = 'Mysql';
	static $driver_allow = ['Mysql','Memcache','File'];
	static $block_opt = [
		'key' => 'default',
		'expire' => 36000,
		'fast' => false,
		'safe' => false,
		'compress' => false,
	];
	static $php_tags = [
		'fake' => ['%%<php%','%php>%%'],
		'real' => ['<?php', '?>'],
	];
	static function connect($driver = false, $options = []) {
		if($driver) {
			if(!in_array($driver,self::$driver_allow)) {
				throw new \Exception('Недопустимый драйвер');
			}
			self::$driver = $driver;
		}
		if(!isset(self::$instance[self::$driver])) {
			$class = '\\'.__NAMESPACE__.'\\'.self::$driver.'\\'.self::$driver;
			self::$instance[self::$driver] = new $class($options);
		}
		return self::$instance[self::$driver];
	}

	static function set($key,$value,$time = 0) {
		return self::connect()->set($key,$value,$time);
	}

	static function get($key) {
		return self::connect()->get($key);
	}

	static function delete($key) {
		return self::connect()->delete($key);
	}

	static function delete_all() {
		return self::connect()->delete_all();
	}
	
	static function beginCache($key,$options = []) {
		self::$block_opt['key'] = $key;
		foreach($options as $k=>$v) {
			if(isset(self::$block_opt[$k])) {
				self::$block_opt[$k] = $v;
			}
		}

		if(($data = self::get(self::getName(self::$block_opt['key'],'block_'))) !== false) {
			if(self::$block_opt['fast']) {
				echo $data;
			} else {
				eval(' ?>'.$data.'<?php ');
			}
			return false;
		}

		ob_start();
		return true;
	}

	static function endCache() {
		$res = ob_get_clean();
		$before = ['<?', '?>', '<%', '%>', '<script language="php">'];
		$after = ['&lt;?','?&gt;','&lt;%','%&gt;','&lt;script language=&quot;php&quot;&gt;'];
		$res = str_ireplace($before,$after,$res);
		if(self::$block_opt['safe']) {
			$res = str_ireplace(self::$php_tags['fake'],self::$php_tags['real'],$res);
		}


		if(self::$block_opt['compress']) {
			$res = self::compress($res);
		}

		self::set(self::getName(self::$block_opt['key'],'block_'),$res,self::$block_opt['expire']);
		if(self::$block_opt['fast']) {
			echo $res;
		} else {
			eval(' ?>'.$res.'<?php ');
		}
	}

	static function noCache($str) {
		if(!self::$block_opt['safe']) {
			echo $str;
		} else {
			echo str_replace(self::$php_tags['real'],self::$php_tags['fake'],$str);
		}
	}

	static function getName($name,$prefix = '') {
		return $prefix.$name;
	}

	static function compress($str) {
		$str = str_replace(["\r","\n","\t"],' ',$str);
		$str = preg_replace('#\s{2,}#',' ',$str);
		return $str;
	}
}
