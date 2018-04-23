<?php
namespace FW\Uploader;

class UploaderVideo
{
	use UploaderLibrary, UploaderError;

	function __construct($file, $options) {
		$this->filename = $file['file_name'];
		$this->destination = $file['tmp_destination'];

	}

	function save($to, $types = 'all'):bool {
		if(in_array($types,['all','mp4'])) {
			exec($this->ffmpeg_path.' -i '.$this->destination.' -q:v 5 '.\Core::$ROOT.$to.'mp4/'.preg_replace('#\.[a-z0-9]+$#', '.mp4', $this->filename).' > /dev/null 2>/dev/null &');
		}

		if(in_array($types,['all','webm'])) {
			exec($this->ffmpeg_path.' -i '.$this->destination.' -q:v 5 '.\Core::$ROOT.$to.'webm/'.preg_replace('#\.[a-z0-9]+$#', '.webm', $this->filename).' > /dev/null 2>/dev/null &');
		}

		if(in_array($types,['all','ogg'])) {
			exec($this->ffmpeg_path.' -i '.$this->destination.' -q:v 5 '.\Core::$ROOT.$to.'ogg/'.preg_replace('#\.[a-z0-9]+$#', '.ogg', $this->filename).' > /dev/null 2>/dev/null &');
		}

		return true;
	}
}