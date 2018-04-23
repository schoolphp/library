<?php
namespace FW\Uploader;

class UploaderAudio
{
	use UploaderLibrary, UploaderError;

	public $quality = 80;
	public $ffmpeg_path = '/usr/local/bin/ffmpeg';
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
			exec($this->ffmpeg_path.' -i '.$this->destination.' -vn -ar 44100 -ac 2 -ab 192k -f mp3 '.\Core::$ROOT.$to.preg_replace('#\.[a-z]+$#','.mp3', $this->filename));
		}

		if(in_array($types,['all','ogg'])) {
			exec($this->ffmpeg_path.' -i '.$this->destination.' -vn -ar 44100 -ac 2 -ab 192k '.\Core::$ROOT.$to.preg_replace('#\.[a-z]+$#','.ogg', $this->filename));
		}

		return true;
	}
}
