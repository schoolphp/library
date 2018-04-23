<?php
namespace FW\Uploader;

class UploaderAudio
{
	use UploaderLibrary, UploaderError;

	public $quality = 80;
	public $ffmpeg_path = 'ffmpeg';
	public $destination = '';

	public function __construct($file,$options) {
		$this->filename = $file['file_name'];
		$this->destination = $file['tmp_destination'];
	}

	public function save($to = '/uploads', $types = 'all'):bool {
		if(!function_exists('exec')) {
			$this->setError('exec is disabled');
		}

		if(in_array($types,['all','mp3'])) {
			exec($this->ffmpeg_path.' -i '.$this->destination.' -vn -ar 22050 -ac 2 -ab 48 -f mp3 '.\Core::$ROOT.$to.preg_replace('#\.[a-z0-9]+$#','.mp3', $this->filename));
		}

		if(in_array($types,['all','ogg'])) {
			exec($this->ffmpeg_path.' -i '.$this->destination.' -vn -ar 22050 -ac 2 -f ogg '.\Core::$ROOT.$to.'ogg/'.preg_replace('#\.[a-z0-9]+$#','.ogg', $this->filename));
		}

		return true;
	}
}
