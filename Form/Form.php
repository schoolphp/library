<?php
namespace FW\Form;

class Form extends FormSettings {
	
	public $error = '';
	public $content = [];
	private $view = '';
	private $REQUEST = [];
	private $tpl = [];
	
	function __construct($name = 'basic') {
		try {
			$this->settings['name'] = $name;
			$this->settings['full_path'] = $this->settings['path'].$this->settings['name'].'/';
			
			if($this->settings['method'] == 'POST') {
				$this->REQUEST = $_POST;
			} else {
				$this->REQUEST = $_GET;
			}

			if(!is_dir($this->settings['full_path'])) {
				$this->install();
				$this->settings['generate_form'] = true;
			}
			
		} catch(\Exception $e) {
			$this->myException($e);
		}
	}
	
	function install() {
		// RELOAD UNINSTALL
		if($this->settings['reload']) {
			FileSystem::delTree($this->settings['full_path']);
		}

		// INSTALL
		if(!is_dir($this->settings['full_path'])) {
			$files = scandir(__DIR__.'/'.$this->settings['origin_type'].'/');
			unset($files[0],$files[1]);

			if(!mkdir($this->settings['full_path'],$this->settings['file_rights'],true)) {
				$this->error('ErrorCreateDir',$this->settings['full_path']);	
			}
			
			foreach($files as $v) {
				copy(__DIR__.'/'.$this->settings['origin_type'].'/'.$v,$this->settings['full_path'].$v);
			}
		}
	}
	
	function error($err,$info) {
		if($this->settings['mode'] == 0) {
			$error = $this->language['ErrorForm'];
		} else {
			$error = $this->language[$err].($this->settings['mode'] == 2 ? ': '.$info : '');
		}
		throw new \Exception($error);
	}
	
	function myException($e) {
		if($this->settings['mode'] == 0) {
			echo $this->language['ErrorForm'];
		} else {
			wtf($e);
		}
	}
	
	function create($form) {
		try {
			if(!isset($_SESSION['antixsrf-form-'.$this->settings['name']])) {
				$_SESSION['antixsrf-form-'.$this->settings['name']] = md5($_SERVER['REMOTE_ADDR'].'xs'.$this->settings['name']);
			} else {
				$xsrfkey = $_SESSION['antixsrf-form-'.$this->settings['name']];
			}
			$form['antixsrf'] = array(
				'title' => '',
				'type' => 'hidden',
				'value' => $_SESSION['antixsrf-form-'.$this->settings['name']],
				'rules' => array(
					'antixsrf',
				),
			);
			foreach($form as $k=>$v) {
				if(!is_array($v)) {
					if(!isset($this->template[$k]))
						throw new \Exception('NoTemplateInput');

					$form[$k] = $this->template[$k];
				}
				$form[$k]['error'] = '';
				if(!isset($form[$k]['attr']['id'])) {
					$form[$k]['attr']['id'] = 'form-'.$this->settings['name'].'-'.$k;
				}
				if(!isset($form[$k]['type'])) {
					$form[$k]['type'] = 'text';
				}
				if(!isset($form[$k]['title'])) {
					$form[$k]['title'] = 'TITLE';
				}
				if(!isset($form[$k]['text'])) {
					$form[$k]['text'] = 'bonus text';
				}
				
				$form[$k]['name'] = $k;
				
				$form[$k]['attrs'] = '';
				if(isset($form[$k]['attr'])) {
					foreach($form[$k]['attr'] as $k2=>$v2) {
						$form[$k]['attrs'] .= ' '.$k2.'="'.$v2.'"';
					}
				}
				
				// НЕ ДОДЕЛАНО
				if(isset($this->REQUEST[$k])) {
					$form[$k]['value'] = hc($this->REQUEST[$k]);
				} elseif(!isset($form[$k]['value'])) {
					$form[$k]['value'] = '';
				}
				// ТУТ КОНЕЦ!
			}
			$this->content = $form;
		} catch(\Exception $e) {
			$this->myException($e);
		}
	}

	function issend() {
		if($_SERVER['REQUEST_METHOD'] == $this->settings['method']) {
			$err = 0;
			foreach($this->content as $k=>$v) {
				/*
				if(!isset($this->REQUEST[$k]) && $this->content[$k]['type'] != 'checkbox') {
					$this->content[$k]['error'] = $this->language['CheckEmpty'];
					$err = 1;
				} else
				*/
				if(isset($this->content[$k]['rules'])) {
					foreach($this->content[$k]['rules'] as $rule=>$value) {
						if(is_int($rule)) {
							$rule = $value;
						}
						if($error = $this->ruleCheck($rule,$value,(isset($this->REQUEST[$k]) ? $this->REQUEST[$k] : ''))) {
							$this->content[$k]['error'] = preg_replace_callback('#%(.+)%#',array($this,'myReplaceCallback'),$error);
							$this->error = $k.': '.$this->content[$k]['error'];
							$err = 1;
						}
					}
				}
			}
			if(!$err) {
				return true;
			}
		}
		return false;
	}
	
	function myReplaceCallback($matches) {
		return $this->language[$matches[1]];
	}
	
	function ruleCheck($rule,$value,$text) {
		try {
			switch($rule) {
				case 'int':
					if(!is_numeric($text)) {
						return '%CheckInt%';
					}
					break;
				case 'length':
					if(!preg_match('#^.{'.$value.'}$#iu',$text)) {
						$temp = explode(',',$value);
						return '%CheckLength%: '.$value;
					}
					break;
				case 'empty':
					if(empty($text)) {
						return '%CheckEmpty%';
					}
					break;
				case 'email':
					if(!filter_var($text, FILTER_VALIDATE_EMAIL)) {
						return '%CheckEmail%';
					}
					break;
				case 'captcha':
					$key = 'key'.(isset($_GET['captchakey']) ? $_GET['captchakey'] : '');
					if(!isset($_SESSION[$key]) || mb_strtolower($text) != mb_strtolower($_SESSION[$key])) {
						return '%CheckCaptcha%';
					}
					break;
				case 'antixsrf':
					if($text != $_SESSION['antixsrf-form-'.$this->settings['name']]) {
						return '%CheckXSRF%';
					}
					break;
				case 'unique':
					if(is_array($value)) {
						$table = (isset($value['table']) ? $value['table'] : 'users');
						$cell = (isset($value['cell']) ? $value['cell'] : 'login');
					} else {
						$table = 'users';
						$cell = $value;
					}
					if($this->checkUnique(array($cell=>$text),$table)) {
						return '%CheckUnique%';
					}
					break;
				default:
					throw new \Exception('%Wrong Rule%: '.$rule);
					break;
			}
			return false;
		} catch(\Exception $e) {
			$this->myException($e);
		}
	}

	function view() {
		try {
			if($this->settings['mode'] == 2 || ($this->settings['mode'] == 1 && $this->settings['generate_form'])) {
				$this->createForm();
			}

			include $this->settings['full_path'].'template.tpl';
		} catch(\Exception $e) {
			$this->myException($e);
		}
	}
	
	function getTpl($link) {
		try {
			if(!isset($this->tpl[$link])) {
				if(!file_exists($this->settings['full_path'].$link.'.tpl')) {
					throw new \Exception('%NoTplFile%: '.$this->settings['full_path'].$link.'.tpl');
				}
				$this->tpl[$link] = file_get_contents($this->settings['full_path'].$link.'.tpl');
			}
			return $this->tpl[$link];

		} catch(\Exception $e) {
			$this->myException($e);
		}
	}

	function createForm() {
		try {
			$form = '';

			foreach(array('item_input','item_textarea','start','end') as $v) {
				if(!file_exists($this->settings['full_path'].$v.'.tpl')) {
					throw new \Exception('NoItemFile');
				}
			}

			$form .= '<form action="'.$this->settings['action'].'" method="'.$this->settings['method'].'" class="form-'.$this->settings['name'].'" enctype="multipart/form-data">';
			$form .= $this->getTpl('start');

			$hidden = '';

			foreach($this->content as $k=>$v) {
				switch($v['type']) {
					case 'text': case 'password': case 'email':
						$item = $this->getTpl('item_input');
						break;
					case 'hidden':
						$item = '';
						$hidden_item = $this->getTpl('item_hidden');
						$hidden .= str_replace('$key',"'".$k."'",$hidden_item);
						break;
					case 'textarea':
						$item = $this->getTpl('item_textarea');
						break;
					case 'submit':
						$item = $this->getTpl('item_submit');
						break;
					case 'checkbox':
						$item = $this->getTpl('item_checkbox');
						break;
					case 'radio':
						$item = $this->getTpl('item_radio');
						break;
					case 'captcha':
						$item = $this->getTpl('item_captcha');
						break;
					default:
						ob_get_clean();
						throw new \Exception('%NoItemType%: '.$v['type']);
						break;
				}
				$item = str_replace('$key',"'".$k."'",$item);
				$form .= $item;
			}
			$form .= $this->getTpl('end');
			$form .= $hidden;
			$form .= '</form>';
			file_put_contents($this->settings['full_path'].'template.tpl',$form);
			return true;

		} catch(\Exception $e) {
			$this->myException($e);
		}
	}

	function createItem($name,$data) {
		try {
			$attr = '';

			// НЕ ДОДЕЛАНО
			$value = '';
			if(isset($this->REQUEST[$name])) {
				$value = htmlspecialchars($this->REQUEST[$name]);
			}
			// ТУТ КОНЕЦ!

			foreach($data['attr'] as $k=>$v) {
				$attr .= ' '.$k.'="'.$v.'"';
			}

			ob_start();
			switch($data['type']) {
				case 'text': case 'password': case 'email':
					include $this->settings['full_path'].'item_input.tpl';
					break;
				case 'textarea':
					include $this->settings['full_path'].'item_textarea.tpl';
					break;
				default:
					ob_get_clean();
					throw new \Exception('NoItemType');
					break;
			}
			return ob_get_clean();
		} catch(\Exception $e) {
			$this->myException($e);
		}
	}
	
	function checkUnique($var,$table = 'users') {
		if(!is_array($var) || !count($var)) {
			throw new \Exception('Wrond CheckUnique Data');
		}

		$temp = array();
		foreach($var as $k=>$v)
			$temp[] = "`".es($k)."` = '".es($v)."'";

		$where = implode(' AND ',$temp);
			
		$res = q("
			SELECT `id`
			FROM `".es($table)."`
			WHERE ".$where."
			LIMIT 1
		");
		if($res->num_rows)
			return TRUE;
		else
			return FALSE;
	}

}
