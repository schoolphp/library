<?php
namespace FW\Uploader;

class UploaderDoc
{
	use UploaderLibrary, UploaderError;

	function __construct($file, $options) {
		$this->filename = $file['file_name'];
		$this->destination = $file['tmp_destination'];

		/*
		if(in_array($file['real_ext'],['zip','rar'])) {
			$bytes = file_get_contents($file['destination'], false, null, 0, 7);

			if($file['real_ext'] == 'rar' && bin2hex($bytes) !== '526172211a0700') {
				$this->setError('It`s not a RAR archive');
			}

			if($file['real_ext'] == 'zip' && substr($bytes, 0, 2) !== 'PK') {
				$this->setError('It`s not a ZIP archive');
			}
		}
		*/
	}

	function save($to):bool {
		if(!is_dir(\Core::$ROOT.$to)) {
			mkdir(\Core::$ROOT.$to, 0777, true);
		}

		copy($this->destination,\Core::$ROOT.$to.$this->filename);
		return true;
	}

}