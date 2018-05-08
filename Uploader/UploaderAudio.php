<?php
namespace FW\Uploader;

class UploaderAudio
{
	use UploaderLibrary, UploaderError;

	public $quality = 80;

	public function __construct($file,$options) {
		$this->filename = $file['file_name'];
		$this->destination = $file['tmp_destination'];
	}

	public function save($to = '/uploads', $options = []):bool {
		if(!function_exists('exec')) {
			$this->setError('exec is disabled');
		}

		if(!isset($options['audio_type'])) {
			$options['audio_type'] = 'all';
		}

		if(in_array($options['audio_type'],['all','mp3'])) {
			if(!is_dir(\Core::$ROOT.$to.'mp3/')) {
				mkdir(\Core::$ROOT.$to.'mp3/', 0777, true);
			}

			exec($this->ffmpeg_path.' -i '.$this->destination.' -vn -ar 22050 -ac 2 -ab 48 -f mp3 '.\Core::$ROOT.$to.'mp3/'.preg_replace('#\.[a-z0-9]+$#','.mp3', $this->filename));
		}

		if(in_array($options['audio_type'],['all','ogg'])) {
			if(!is_dir(\Core::$ROOT.$to.'ogg/')) {
				mkdir(\Core::$ROOT.$to.'ogg/', 0777, true);
			}

			exec($this->ffmpeg_path.' -i '.$this->destination.' -vn -ar 22050 -ac 2 -f ogg '.\Core::$ROOT.$to.'ogg/'.preg_replace('#\.[a-z0-9]+$#','.ogg', $this->filename));
		}

		return true;
	}
}
