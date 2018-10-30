<?php
namespace FW\Pagination;
use \Core;

class Pagination {
	static $onpage = 10;
	static $pages = 1;
	static $curpage = 1;
	static $start = 0;
	static $options = [
		'begin' => true,
		'end' => true,
		'before' => true,
		'next' => true,
		'trash' => true,
		'move' => 3,
	];
	static function q($query) {
		self::$start = (self::$curpage-1)*self::$onpage;
		if(self::$start < 0) {
			self::$start = 0;
		}

		$res = q(preg_replace('#^\s*SELECT#usU','SELECT SQL_CALC_FOUND_ROWS',$query).' LIMIT '.self::$start.','.self::$onpage);
		$res_count = q("SELECT FOUND_ROWS()");
		$row_count = $res_count->fetch_row();
		$res_count->close();

		self::$pages = ceil($row_count[0]/self::$onpage);

		if(self::$curpage < 1 || self::$curpage > self::$pages) {
			header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");
			self::$curpage = 1;
		}
		return $res;
	}

	static function nav($clearget = true) {
		if($clearget !== true && $clearget !== false) {
			$url = $clearget;
		}

		if(file_exists(__DIR__.'/language/'.Core::$LANGUAGE['lang'].'.php')) {
			$text = include __DIR__.'/language/'.Core::$LANGUAGE['lang'].'.php';
		} else {
			$text = include __DIR__.'/language/ru.php';
		}

		$nav = '<ul class="pagination">';
		
		if(self::$curpage-self::$options['move'] > 1 && self::$options['begin']) {
			if(self::$options['begin']) $nav .= '<li class="page-item"><a class="page-link" href="'.(isset($url) ? str_replace('{page}','1',$url) : createUrl(['page'=>1],$clearget)).'">'.$text['begin'].'</a></li>';
			if(self::$options['before']) $nav .= '<li class="page-item"><a class="page-link" href="'.(isset($url) ? str_replace('{page}',(self::$curpage-self::$options['move'] - 1),$url) :createUrl(['page' => (self::$curpage-self::$options['move'] - 1)],$clearget)).'">'.$text['before'].'</a></li>';
			if(self::$options['trash']) $nav .= '<li class="page-item"><span class="page-link">...</span></li>';
		}
		
		for($i = self::$curpage-self::$options['move']; $i <= self::$curpage+self::$options['move']; ++$i) {
			if($i == self::$curpage) {
				$nav .= '<li class="page-item active"><span class="page-link">'.self::$curpage.'</span></li>';
			} elseif($i > 0 && $i <= self::$pages) {
				$nav .= '<li class="page-item"><a class="page-link" href="'.(isset($url) ? str_replace('{page}',$i,$url) : createUrl(['page' => $i],$clearget)).'">'.$i.'</a></li>';
			}
		}
		
		if(self::$curpage - 1 > 0 && empty(Core::$META['prev'])) {
			Core::$META['prev'] = createUrl(['page' => self::$curpage - 1],$clearget);
		}
		if(self::$curpage + 1 <= self::$pages && empty(Core::$META['next'])) {
			Core::$META['next'] = createUrl(['page' => self::$curpage + 1],$clearget);
		}

		if(self::$curpage+self::$options['move'] < self::$pages) {
			if(self::$options['trash']) $nav .= '<li class="page-item"><span class="page-link">...</span></li>';
			if(self::$options['next']) $nav .= '<li class="page-item"><a class="page-link" href="'.(isset($url) ? str_replace('{page}',(self::$curpage+self::$options['move'] + 1),$url) : createUrl(['page' => (self::$curpage+self::$options['move'] + 1)],$clearget)).'">'.$text['next'].'</a></li>';
			if(self::$options['end']) $nav .= '<li class="page-item"><a class="page-link" href="'.(isset($url) ? str_replace('{page}',self::$pages,$url) : createUrl(['page' => self::$pages],$clearget)).'">'.$text['end'].'</a></li>';
		}

		$nav .= '</ul>';
		return $nav;
	}
}
