<?php
namespace \FW\Trace;

class Trace {
    static $data = array();
	static $start = 0;
	static $dateformat = 'Y-m-d H:i:s'; // '' empty for Unix Timestamp

    static function set() {
		$info = debug_backtrace();
		self::$data[] = array(
			'time' => time(),
			'microtime' => microtime(true),
			'text' => (count($info[0]['args']) ? $info[0]['args'] : '' ),
			'file' => $info[0]['file'],
			'line' => $info[0]['line'],
		);
    }

    static function get($stop = false) {
		$res = '<table border="1" cellspacing="0" cellpadding="2" bordercolor="#CCCCCC"><tr><td colspan="5"><h3 align="left">Trace it:</h3></td></tr>
		<tr>
		  <th>Time</th>
		  <th>Delay</th>
		  <th>Information</th>
		  <th>File</th>
		  <th>Line</th>
		</tr>';
		if(count(self::$data)) {
			$start = self::$data[0]['microtime'];
			foreach(self::$data as $v) {
				$res .= '
				  <tr>
					<td>'.(empty(self::$dateformat) ? $v['time'] : date(self::$dateformat,$v['time'])).'</td>
					<td><div align="center">'.($v['microtime'] - $start).'</div></td>
					<td><div align="left">'.(is_array($v['text']) ? '<pre>'.print_r($v['text'],1).'</pre>' : $v['text']).'</div></td>
					<td>'.$v['file'].'</td>
					<td>'.$v['line'].'</td>
				  </tr>
				';
			}
		}
		return $res;
    }
}
