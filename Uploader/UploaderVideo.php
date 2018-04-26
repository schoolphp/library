<?php
namespace FW\Uploader;

class UploaderVideo implements UploaderInterface
{
	use UploaderLibrary, UploaderError;

	function __construct($file, $options) {
		$this->filename = $file['file_name'];
		$this->destination = $file['tmp_destination'];

	}

	function save($to, $options = []):bool {
		if(!function_exists('exec')) {
			$this->setError('exec is disabled');
		}

		if(!isset($options['video_type'])) {
			$options['video_type'] = 'all';
		}

		if(in_array($options['video_type'],['all','mp4'])) {
			exec($this->ffmpeg_path.' -i '.$this->destination.' -q:v 5 '.\Core::$ROOT.$to.'mp4/'.preg_replace('#\.[a-z0-9]+$#', '.mp4', $this->filename).' > /dev/null 2>/dev/null &');
		}

		if(in_array($options['video_type'],['all','webm'])) {
			exec($this->ffmpeg_path.' -i '.$this->destination.' -q:v 5 '.\Core::$ROOT.$to.'webm/'.preg_replace('#\.[a-z0-9]+$#', '.webm', $this->filename).' > /dev/null 2>/dev/null &');
		}

		if(in_array($options['video_type'],['all','ogg'])) {
			exec($this->ffmpeg_path.' -i '.$this->destination.' -q:v 5 '.\Core::$ROOT.$to.'ogg/'.preg_replace('#\.[a-z0-9]+$#', '.ogg', $this->filename).' > /dev/null 2>/dev/null &');
		}

		return true;
	}
}