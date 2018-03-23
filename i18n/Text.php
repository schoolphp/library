<?php
declare(strict_types=1);

namespace FW\i18n;

class Text
{
	static $lang = '';
	static $replaceArray = ['{$x}','{$y}','{$z}'];
	static function get(string $source, string $group, string $key, ...$replace):string {
		if(empty(self::$lang) || !in_array(self::$lang, \Core::$LANGUAGE['allow'])) {
			$lang = \Core::$LANGUAGE['lang'];
		}

		$res = \q("
			SELECT `text-".\es($lang)."`
			FROM `fw_i18n_text`
			WHERE `source` = '".\es($source)."'
			  AND `group` = '".\es($group)."'
			  AND `key` = '".\es($key)."'
		");
		if(!$res->num_rows) {
			return '';
		}

		$text = $res->fetch_assoc()['text-'.$lang];

		if(isset($replace) && is_array($replace) && count($replace)) {
			foreach($replace as $k => $v) {
				if(isset(self::$replaceArray[$k])) {
					$text = str_replace(self::$replaceArray[$k], $v, $text);
				}
			}
		}

		return $text;
	}
}
