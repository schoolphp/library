<?php
namespace FW\Uploader;

class UploaderVideo implements UploaderInterface
{
	use UploaderLibrary, UploaderError;

	function __construct($file, $options) {
		$this->filename = $file['file_name'];
		$this->destination = $file['tmp_destination'];

	}

	/**
	 * @param bool $to
	 * @param array $options
	 * @return $this
	 * @throws \Exception
	 */
	function save($to = false, $options = []) {
		if(!function_exists('exec')) {
			$this->setError('exec is disabled');
		}

		if(!isset($options['video_type'])) {
			$options['video_type'] = 'all';
		}

		if($to === false) {
			$to = $this->directory;
		}

		$name = pathinfo($this->filename, PATHINFO_FILENAME);
		$this->filename = $name.'.mp4';

		if(!is_dir(\Core::$ROOT.'/'.$to.'/mp4/')) {
			mkdir(\Core::$ROOT.'/'.$to.'/mp4/', 0777, true);
		}

		exec($this->ffmpeg_path.' -i '.$this->destination.' -q:v 5 '.\Core::$ROOT.'/'.$to.'/mp4/'.$name.'.mp4 > /dev/null 2>/dev/null &');

		if(in_array($options['video_type'],['all','webm'])) {
			if(!is_dir(\Core::$ROOT.'/'.$to.'/webm/')) {
				mkdir(\Core::$ROOT.'/'.$to.'/webm/', 0777, true);
			}

			exec($this->ffmpeg_path.' -i '.$this->destination.' -q:v 5 '.\Core::$ROOT.'/'.$to.'/webm/'.$name.'.webm > /dev/null 2>/dev/null &');
		}

		if(in_array($options['video_type'],['all','ogg'])) {
			if(!is_dir(\Core::$ROOT.'/'.$to.'/ogg/')) {
				mkdir(\Core::$ROOT.'/'.$to.'/ogg/', 0777, true);
			}

			exec($this->ffmpeg_path.' -i '.$this->destination.' -q:v 5 '.\Core::$ROOT.'/'.$to.'/ogg/'.$name.'.ogg > /dev/null 2>/dev/null &');
		}

		return $this;
	}
}