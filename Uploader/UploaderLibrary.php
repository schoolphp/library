<?php
namespace FW\Uploader;

trait UploaderLibrary
{
	private $file;
	private $basefilename = '';
	private $img;
	private $prop;
	private $uploaded = false;
	private $source;
	public $ffmpeg_path = 'ffmpeg';

	protected $filename = '';
	protected $destination = '';

	public $subdir = '';
	public $tmpfilename = '';

	public $error = '';
	public $notuploaded = false;

	public $save_origin = true;

	protected $directory = 'uploads/';

	/**
	 * @param $to
	 * @return $this
	 */
	public function setDirectory($to) {
		$this->directory = $to;
		return $this;
	}

	public function __construct($file)
	{
		$this->file = $file;
	}

	public function getFilename():string {
		return $this->filename;
	}

	/**
	 * @param string $filename
	 * @return $this
	 */
	public function setFilename(string $filename) {
		$filename = basename($filename);
		$filename = $this->translit($filename);
		$filename = preg_replace('#[\s_]+#ui', '-', $filename);
		$filename = preg_replace('#\-{2,}#ui', '-', $filename);

		$tmp = pathinfo($filename);

		$classname = get_class($this);
		$sub_dir = '';
		if($classname === 'FW\Uploader\UploaderVideo') {
			$tmp['extension'] = 'mp4';
			$sub_dir = 'mp4/';
		} elseif($classname === 'FW\Uploader\UploaderAudio') {
			$tmp['extension'] = 'mp3';
			$sub_dir = 'mp3/';
		}

		$i = 0;
		do {
			$filename = $tmp['filename'].(!empty($i) ? '-'.$i : '').'.'.$tmp['extension'];
			if(!file_exists(\Core::$ROOT.'/'.$this->directory.'/'.$sub_dir.$filename)) {
				break;
			}
			++$i;
		} while(true);

		$this->filename = $filename;
		return $this;
	}

	public function __destruct()
	{
		if(!$this->save_origin) {
			unlink($this->file['tmp_name']);
		}
	}

	/**
	 * @param string $str
	 * @return string
	 */
	protected function translit(string $str):string
	{
		$rus = ['А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь', 'Э', 'Ю', 'Я', 'а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я'];
		$lat = ['A', 'B', 'V', 'G', 'D', 'E', 'E', 'Gh', 'Z', 'I', 'Y', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'F', 'H', 'C', 'Ch', 'Sh', 'Sch', 'Y', 'Y', 'Y', 'E', 'Yu', 'Ya', 'a', 'b', 'v', 'g', 'd', 'e', 'e', 'gh', 'z', 'i', 'y', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'h', 'c', 'ch', 'sh', 'sch', 'y', 'y', 'y', 'e', 'yu', 'ya'];
		return str_replace($rus, $lat, $str);
	}
}