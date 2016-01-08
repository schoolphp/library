<?php
namespace FW\DB;
use \Core;
/*
ALIAS:
q(); Запрос
es(); mysqli_real_escape_string

РАБОТА С ОБЪЕКТОМ ВЫБОРКИ
$res = q(); // Запрос с возвратом результата
$res->num_rows; // Количество возвращенных строк - mysqli_num_rows();
$res->fetch_assoc(); // достаём запись - mysqli_fetch_assoc();
$res->close(); // Очищаем результат выборки

РАБОТА С ПОДКЛЮЧЕННОЙ MYSQL
\FW\DB\DB::_()->affected_rows; // Количество изменённых записей
\FW\DB\DB::_()->insert_id; // Последний ID вставки
\FW\DB\DB::_()->real_escape_string(); // аналог es();
\FW\DB\DB::_()->query(); // аналог q
\FW\DB\DB::_()->multi_ query(); // Множественные запросы

\FW\DB\DB::close(); // Закрываем соединение с БД
*/

class DB {
	static public $mysqli = array();
	static public $connect = array();

	/**
	 * @param int $key
	 * @return \mysqli;
	 */
	static public function _($key = 0) {
		if(!isset(self::$mysqli[$key])) {
			if(!isset(self::$connect['server']))
				self::$connect['server'] = Core::$DB_LOCAL;
			if(!isset(self::$connect['user']))
				self::$connect['user'] = Core::$DB_LOGIN;
			if(!isset(self::$connect['pass']))
				self::$connect['pass'] = Core::$DB_PASS;
			if(!isset(self::$connect['db']))
				self::$connect['db'] = Core::$DB_NAME;

			self::$mysqli[$key] = @new \mysqli(self::$connect['server'],self::$connect['user'],self::$connect['pass'],self::$connect['db']); // WARNING
			if (mysqli_connect_errno()) {
				echo 'Не удалось подключиться к Базе Данных';
				exit;
			}
			if(!self::$mysqli[$key]->set_charset("utf8")) {
				echo 'Ошибка при загрузке набора символов utf8:'.self::$mysqli[$key]->error;
				exit;
			}
			if(!empty(Core::$DB_TIME_ZONE)) {
				self::$mysqli[$key]->query("set time_zone = '".es(Core::$DB_TIME_ZONE)."'");
			}
		}
		return self::$mysqli[$key];
	}
	static public function close($key = 0) {
		self::$mysqli[$key]->close();
		unset(self::$mysqli[$key]);
	}
	static public function result($res) {
		$row = $res->fetch_row();
		return $row[0];
	}
	static public function multi_query($res,$key = 0) {
		return self::$mysqli[$key]->multi_query($res);
	}

	static public function begin_transaction($key = 0) {}
	static public function commit($key = 0) {}
	static public function rollback($key = 0) {}
}
