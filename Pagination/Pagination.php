<?php
namespace FW\Pagination;
use \Core;

class Pagination {
	static $onpage = 10;
	static $pages = 1;
	static $curpage = 1;
	static $start = 0;
	static $options = array(
		'begin' => true,
		'end' => true,
		'before' => true,
		'next' => true,
		'trash' => true,
		'move' => 3,
	);
	static function q($query) {
		$query_count = preg_replace('#^\s*SELECT.*FROM#usU','SELECT COUNT(*) FROM',$query);
		$res_count = q($query_count);
		$row_count = $res_count->fetch_row();
		$res_count->close();

		self::$pages = ceil($row_count[0]/self::$onpage);

		if(self::$curpage < 1 || self::$curpage > self::$pages) {
			header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");
			self::$curpage = 1;
		}

		self::$start = (self::$curpage-1)*self::$onpage;

		$query = $query.' LIMIT '.self::$start.','.self::$onpage;
		return q($query);
	}
	
	static function nav($clearget = true) {
		include __DIR__.'/language/'.Core::$LANGUAGE['lang'].'.php';

		$nav = '<ul class="pagination">';
		
		if(self::$curpage-self::$options['move'] > 1 && self::$options['begin']) {
			if(self::$options['begin']) $nav .= '<li><a href="'.createUrl(array('page'=>1),$clearget).'">'.$text['begin'].'</a></li>';
			if(self::$options['before']) $nav .= '<li><a href="'.createUrl(array('page' => (self::$curpage-self::$options['move'] - 1)),$clearget).'">'.$text['before'].'</a></li>';
			if(self::$options['trash']) $nav .= '<li><span>...</span></li>';
		}
		
		for($i = self::$curpage-self::$options['move']; $i <= self::$curpage+self::$options['move']; ++$i) {
			if($i == self::$curpage) {
				$nav .= '<li class="active"><span>'.self::$curpage.'</span></li>';
			} elseif($i > 0 && $i <= self::$pages) {
				$nav .= '<li><a href="'.createUrl(array('page' => $i),$clearget).'">'.$i.'</a></li>';
			}
		}
		
		if(self::$curpage - 1 > 0 && empty(Core::$META['prev'])) {
			Core::$META['prev'] = createUrl(array('page' => self::$curpage - 1),$clearget);
		}
		if(self::$curpage + 1 <= self::$pages && empty(Core::$META['next'])) {
			Core::$META['next'] = createUrl(array('page' => self::$curpage + 1),$clearget);
		}

		if(self::$curpage+self::$options['move'] < self::$pages) {
			if(self::$options['trash']) $nav .= '<li><span>...</span></li>';
			if(self::$options['next']) $nav .= '<li><a href="'.createUrl(array('page' => (self::$curpage+self::$options['move'] + 1)),$clearget).'">'.$text['next'].'</a></li>';
			if(self::$options['end']) $nav .= '<li><a href="'.createUrl(array('page' => self::$pages),$clearget).'">'.$text['end'].'</a></li>';
		}

		$nav .= '</ul>';
		return $nav;
	}
}
