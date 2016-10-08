<?php
namespace FW\Timer;

class Timer {
	static $start = [];
	static $step = [];
	static $last = [];
	
	static function start($key = 0,$title = '') {
		if(empty($key)) {
			$key = (isset(self::$last['key']) ? self::$last['key'] : 0);
		}
		self::$start[$key] = microtime(true);
		self::$step[$key] = [];
		self::$last = ['key'=>$key,'title'=>$title,'time'=>self::$start[$key],'different'=>0];
		self::step($key,$title);
	}
	static function step($key = 0,$title = '') {
		if(empty($key)) {
			$key = (isset(self::$last['key']) ? self::$last['key'] : 0);
		}
		
		$correct = microtime(true);
		self::$last = ['key'=>$key,'title'=>$title,'time'=>$correct,'different'=>($correct - self::$start[$key])];
		self::$step[$key][] = self::$last;
	}
	
	static function result() {
		$res = '<table cellpadding="5" cellspacing="0" border="1"><tr><th>TITLE</th><th>TIME</th><th>DELAY</th></tr>';
		foreach(self::$step as $k=>$v) {
			$res .= '<tr><td colspan=3>'.htmlspecialchars($k).'</td></tr>';
			foreach($v as $k2=>$v2) {
				$res .= '<tr>
				  <td>'.$v2['title'].'</td>
				  <td>'.$v2['time'].'</td>
				  <td>'.$v2['different'].'</td>
				</tr>';
			}
		}
		$res .= '</table>';
		return $res;
	}
}
