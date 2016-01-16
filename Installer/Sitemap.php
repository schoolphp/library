<?php
namespace FW\Installer;

class Sitemap
{
	static $installDir = '/install';
	static $i = 0;
	static $basedir = '';

	static function generateMap($POST) {
		$temp = [];
		foreach($POST as $v) {
			if(empty($v['name']) || !isset($v['page']) || !count($v['page'])) {
				continue;
			}
			$temp[$v['name']] = [];
			if(isset($v['options-config'])) {
				$temp[$v['name']]['/Options']['config'] = true;
				unset($v['options-config']);
			}
			if(isset($v['options-controller'])) {
				$temp[$v['name']]['/Options']['controller'] = true;
				unset($v['options-controller']);
			}
			if(isset($v['options-allpages'])) {
				$temp[$v['name']]['/Options']['allpages'] = true;
				unset($v['options-allpages']);
			}
			if(isset($v['options-before'])) {
				$temp[$v['name']]['/Options']['before'] = true;
				unset($v['options-before']);
			}
			if(isset($v['options-after'])) {
				$temp[$v['name']]['/Options']['after'] = true;
				unset($v['options-after']);
			}
			if(isset($v['options-sitemap'])) {
				$temp[$v['name']]['/Options']['sitemap'] = true;
				unset($v['options-sitemap']);
			}
			foreach($v['page'] as $v2) {
				$pagename = $v2['name'];
				unset($v2['name']);
				if(count($v2)) {
					foreach($v2['get'] as $v3) {
						$getname = $v3['name'];
						unset($v3['name']);
						if(count($v3)) {
							foreach($v3['param'] as $v4) {
								$getparam = $v4['select'];
								unset($v4['select']);
								$temp[$v['name']][$pagename][$getname][$getparam] = $v4['input'];
							}
						} else {
							$temp[$v['name']][$pagename][$getname] = true;
						}
					}
				} else {
					$temp[$v['name']][$pagename] = [];
				}
			}
		}
		foreach($temp as $k=>$v) {
			foreach($temp[$k] as $k2=>$v2) {
				if(is_array($temp[$k][$k2])) {
					foreach($temp[$k][$k2] as $k3 => $v3) {
						if(empty($k3)) {
							unset($temp[$k][$k2][$k3]);
						}
					}
				}
			}
		}
		return $temp;
	}

	static function generateDiv($sitemap) {
		if(count($sitemap)) {
			foreach($sitemap as $k => $v) {
				if(!file_exists(self::$basedir.'/modules/'.$k.'/view')) {
					mkdir(self::$basedir.'/modules/'.$k.'/view', 0775, true);
				}

				if(isset($v['/Options']['before']) && !file_exists(self::$basedir.'/modules/'.$k.'/view/_before.tpl')) {
					file_put_contents(self::$basedir.'/modules/'.$k.'/view/_before.tpl', '');
				} elseif(!isset($v['/Options']['before']) && file_exists(self::$basedir.'/modules/'.$k.'/view/_before.tpl')) {
					unlink(self::$basedir.'/modules/'.$k.'/view/_before.tpl');
				}

				if(isset($v['/Options']['after']) && !file_exists(self::$basedir.'/modules/'.$k.'/view/_after.tpl')) {
					file_put_contents(self::$basedir.'/modules/'.$k.'/view/_after.tpl', '');
				} elseif(!isset($v['/Options']['after']) && file_exists(self::$basedir.'/modules/'.$k.'/view/_after.tpl')){
					unlink(self::$basedir.'/modules/'.$k.'/view/_after.tpl');
				}

				if(isset($v['/Options']['controller']) && !file_exists(self::$basedir.'/modules/'.$k.'/controller/controller.php')) {
					if(!file_exists(self::$basedir.'/modules/'.$k.'/controller')) {
						mkdir(self::$basedir.'/modules/'.$k.'/controller', 0775);
					}
					file_put_contents(self::$basedir.'/modules/'.$k.'/controller/controller.php', '');
				} elseif(!isset($v['/Options']['controller']) && file_exists(self::$basedir.'/modules/'.$k.'/controller')) {
					\FW\Form\FileSystem::delTree(self::$basedir.'/modules/'.$k.'/controller');
				}

				if(isset($v['/Options']['config']) && !file_exists(self::$basedir.'/modules/'.$k.'/config/config.php')) {
					if(!file_exists(self::$basedir.'/modules/'.$k.'/config')) {
						mkdir(self::$basedir.'/modules/'.$k.'/config', 0775);
					}
					file_put_contents(self::$basedir.'/modules/'.$k.'/config/config.php', '');
				} elseif(!isset($v['/Options']['config']) && file_exists(self::$basedir.'/modules/'.$k.'/config/config.php')) {
					unlink(self::$basedir.'/modules/'.$k.'/config/config.php');
				}

				if(isset($v['/Options']['allpages']) && !file_exists(self::$basedir.'/modules/'.$k.'/_allpages.php')) {
					file_put_contents(self::$basedir.'/modules/'.$k.'/_allpages.php', '');
				} elseif(!isset($v['/Options']['allpages']) && file_exists(self::$basedir.'/modules/'.$k.'/_allpages.php')) {
					unlink(self::$basedir.'/modules/'.$k.'/_allpages.php');
				}

				if(isset($v['/Options']['sitemap'])) {
					unset($v['/Options']['sitemap']);
					unset($sitemap[$k]);
					if(!file_exists(self::$basedir.'/modules/'.$k.'/sitemap')) {
						mkdir(self::$basedir.'/modules/'.$k.'/sitemap', 0775);
					}

					$temp = '<?php
return [
  \''.$k.'\' => '.preg_replace('#\[\s+\]#iu', '[]', preg_replace('#\=\>\s*array \(#iu', '=> [', str_replace('),', '],', var_export($v, 1)))).'
];';
					file_put_contents(self::$basedir.'/modules/'.$k.'/sitemap/sitemap.php', $temp);
				}

				unset($v['/Options']);
				foreach($v as $k2 => $v2) {
					file_put_contents(self::$basedir.'/modules/'.$k.'/'.$k2.'.php', '');
					file_put_contents(self::$basedir.'/modules/'.$k.'/view/'.$k2.'.tpl', '');
				}
			}
			if(file_put_contents(self::$basedir.'/config/sitemap.php', '<?php return '.preg_replace('#\[\s+\]#iu', '[]', preg_replace('#\=\>\s*array \(#iu', '=> [', str_replace('),', '],', var_export($sitemap, 1)))).';') === false) {
				Installer::addLog('error', '`/config/sitemap.php` не удалось создать!');
			}
			else {
				Installer::addLog('success', '`/config/sitemap.php` создан!');
			}
		} else {
			file_put_contents(self::$basedir.'/config/sitemap.php', '<?php return [];');
		}
		return true;
		//echo '<pre>'.preg_replace('#\[\s+\]#iu','[]',preg_replace('#\=\>\s*array \(#iu','=> [',str_replace('),','],',var_export($sitemap,1))));
	}

	static function copyModule($module,$files) {
		mkdir(self::$basedir.'/modules/'.$module.'/view',0775, true);
		foreach($files as $v) {
			if(file_exists(self::$basedir.self::$installDir.'/modules/'.$module.'/sitemap/sitemap.php')) {
				file_put_contents(self::$basedir.'/modules/'.$module.'/sitemap/sitemap.php', file_get_contents(self::$basedir.self::$installDir.'/modules/'.$module.'/sitemap/sitemap.php'));
			}
			file_put_contents(self::$basedir.'/modules/'.$module.'/'.$v.'.php', file_get_contents(self::$basedir.self::$installDir.'/modules/'.$module.'/'.$v.'.php'));
			file_put_contents(self::$basedir.'/modules/'.$module.'/view/'.$v.'.tpl', file_get_contents(self::$basedir.self::$installDir.'/modules/'.$module.'/view/'.$v.'.tpl'));
		}

	}
}