<?php
namespace FW\SessionMysqlHandler;

class SessionMysqlHandler implements SessionHandlerInterface {
    public $table = 'fw_sessions';
	public $expires = '24 MINUTE';
	public $engine = 'InnoDB'; // Alternative - MEMORY
	public $control = array(
		'ip' => true,
		'useragent' => true, 
	);

	public function install() {
        q("
			CREATE TABLE IF NOT EXISTS `".$this->table."` (
			  `id` varchar(32) NOT NULL,
			  `expires` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			  `ip` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
			  `useragent` text COLLATE utf8_unicode_ci NOT NULL,
			  `data` text COLLATE utf8_unicode_ci NOT NULL,
			  PRIMARY KEY (`id`),
			  KEY `ip` (`ip`)
			) ENGINE=".$this->engine." DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1
		");
		return true;
	}
	
	public function __construct($install = false) {
		if($install) {
			$this->install();
		}
	}
	
    public function open($savePath, $sessionName) {
		return true;
    }

    public function close() {
        return true;
    }

    public function read($id) {
		$res = q("
			SELECT *
			FROM `".$this->table."`
			WHERE `id` = '".es($id)."'
		");
		if(!$res->num_rows) {
			return false;
		}

		$row = $res->fetch_assoc();
		if($this->control['ip'] === true && (!isset($_SERVER['REMOTE_ADDR']) || $row['ip'] != $_SERVER['REMOTE_ADDR'])) {
			$this->destroy($id);
			return false;
		} elseif($this->control['useragent'] === true && (!isset($_SERVER['HTTP_USER_AGENT']) || $row['useragent'] != $_SERVER['HTTP_USER_AGENT'])) {
			$this->destroy($id);
			return false;
		}
		return $row['data'];
    }

    public function write($id, $data) {
		$res = q("
			SELECT `ip`,`useragent`
			FROM `".$this->table."`
			WHERE `id` = '".es($id)."'
		");
		if($res->num_rows) {
			$row = $res->fetch_assoc();
			if($this->control['ip'] === true && (!isset($_SERVER['REMOTE_ADDR']) || $row['ip'] != $_SERVER['REMOTE_ADDR'])) {
				$this->destroy($id);
				return false;
			} elseif($this->control['useragent'] === true && (!isset($_SERVER['HTTP_USER_AGENT']) || $row['useragent'] != $_SERVER['HTTP_USER_AGENT'])) {
				destroy($id);
				return false;
			}
			q("
				UPDATE `".$this->table."` SET
				`data` = '".es($data)."',
				`expires` = NOW() + INTERVAL ".$this->expires."
				WHERE `id` = ".(int)$id."
			");
		} else {
			q("
				INSERT INTO `".$this->table."` SET
				`id` = '".es($id)."',
				`data` = '".es($data)."',
				`expires` = NOW() + INTERVAL ".$this->expires.",
				`ip` = '".es(isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '')."',
				`useragent` = '".es(isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '')."'
			");
		}
		return true;

    }

    public function destroy($id) {
        q("
			DELETE FROM `".$this->table."`
			WHERE `id` = '".es($id)."'
		");
        return true;
    }

    public function gc($maxlifetime) {
        q("
			DELETE FROM `".$this->table."`
			WHERE `expires` < NOW()
		");
        return true;
    }
}
