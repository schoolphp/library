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

	public function __construct($file)
	{
		$this->file = $file;
	}

	public function getFilename():string {
		return $this->filename;
	}

	public function setFilename(string $filename):void {
		$this->filename = basename($filename);
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
		$rus = array('А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь', 'Э', 'Ю', 'Я', 'а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я');
		$lat = array('A', 'B', 'V', 'G', 'D', 'E', 'E', 'Gh', 'Z', 'I', 'Y', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'F', 'H', 'C', 'Ch', 'Sh', 'Sch', 'Y', 'Y', 'Y', 'E', 'Yu', 'Ya', 'a', 'b', 'v', 'g', 'd', 'e', 'e', 'gh', 'z', 'i', 'y', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'h', 'c', 'ch', 'sh', 'sch', 'y', 'y', 'y', 'e', 'yu', 'ya');
		return str_replace($rus, $lat, $str);
	}

	/**
	 * @return bool
	 */
	public function generateFileName()
	{
		$input = str_replace($this->file['ext'], '', $this->basefilename);
		$input = $this->translit($input);
		$input = preg_replace('/[\s_]+/ui', '-', $input);
		$input = preg_replace('/^-*|[^\da-z-]+|-{2,}|-\.*$/ui', '', $input);
		$this->tmpfilename = $input . '(' . rand(10000, 99999) . ').' . $this->file['ext'];
		//$this->tmpfilename = date('U') . 'upl' . rand(1000, 9999) . '.' . $this->ext;

		$this->tmpfilepath = $this->dir . $this->tmpfilename;
		return true;
	}

}