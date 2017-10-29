<?php
namespace FW\Form;

class FileSystem {
	public static function delTree($dir):void {
		if(is_dir($dir)) {
			$files = array_diff(scandir($dir), ['.','..']);
			foreach ($files as $file) { 
				(is_dir($dir.'/'.$file)) ? self::delTree($dir.'/'.$file) : unlink($dir.'/'.$file);
			} 
			rmdir($dir);
		}
	}
}


