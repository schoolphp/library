<?php
namespace FW\Uploader;

class UploaderImage implements UploaderInterface
{
	use UploaderLibrary, UploaderError;

	public $watermark = '/uploads/watermark.png';
	public $minwidth = 200;
	public $minheight = 150;
	public $quality = 80;
	public $prop_min = 0.1;
	public $prop_max = 5;
	private $real_ext;

	public function __construct($file,$options) {
		if(isset($options['photo_minwidth'])) {
			$this->minwidth = $options['photo_minwidth'];
		}
		if(isset($options['photo_minheight'])) {
			$this->minheight = $options['photo_minheight'];
		}
		if(isset($options['photo_quality'])) {
			$this->quality = $options['photo_quality'];
		}
		if(isset($options['photo_prop_min'])) {
			$this->prop_min = $options['photo_prop_min'];
		}
		if(isset($options['photo_prop_max'])) {
			$this->prop_max = $options['photo_prop_max'];
		}

		$this->real_ext = $file['real_ext'];

		$this->img = getimagesize($file['tmp_destination']);
		if(in_array($file['real_ext'], ['jpg','jpeg'])) {
			$this->source = imagecreatefromjpeg($file['tmp_destination']);
		} elseif($file['real_ext'] === 'png') {
			$this->source = imagecreatefrompng($file['tmp_destination']);
		} elseif($file['real_ext'] === 'gif') {
			$this->source = imagecreatefromgif($file['tmp_destination']);
		} elseif($file['real_ext'] === 'bmp') {
			$this->source = imagecreatefrombmp($file['tmp_destination']);
		} else {
			$this->setError('Incorrect file mime type: '.$this->img['mime']);
		}

		$this->filename = $file['file_name'];
		$this->destination = $file['tmp_destination'];

		$this->prop = $this->img[0]/$this->img[1];
		if($this->prop > $this->prop_max || $this->prop < $this->prop_min) {
			$this->setError('Incorrect file proportion');
		}

		if($this->img[0] < $this->minwidth) {
			$this->setError('Width of file is too small. Minimum require '.$this->minwidth.' px');
		} elseif($this->img[1] < $this->minheight) {
			$this->setError('Height of file is too small. Minimum require '.$this->minheight.' px');
		}
	}

	/**
	 * @param bool $to
	 * @param array $options
	 * @return $this
	 * @throws \Exception
	 */
	public function save($to = false, $options = []) {
		if($to === false) {
			$to = $this->directory;
		}

		if(!is_dir(\Core::$ROOT.'/'.$to)) {
			mkdir(\Core::$ROOT.'/'.$to, 0777, true);
		}

		if(isset($options['photo_no_modify'])) {
			copy($this->destination,\Core::$ROOT.'/'.$to.'/'.$this->filename);
			return $this;
		}

		if(empty($this->filename) || empty($this->prop) || !is_array($this->img) || !$this->source) {
			$this->setError('File is not included to resize');
		}

		$width = $this->img[0];
		$height = $this->img[1];
		if((isset($options['photo_width']) && $options['photo_width'] < $this->img[0]) || (isset($options['photo_height']) && $options['photo_height'] < $this->img[1])) {

			if(isset($options['photo_width']) && $options['photo_width'] < $this->img[0]) {
				$width = round($options['photo_width']);
				$height = round($options['photo_width'] / $this->prop);
			}

			if(isset($options['photo_height']) && $options['photo_height'] < $this->img[1]) {
				$height = round($options['photo_height']);
				$width = round($options['photo_height'] * $this->prop);
			}
		}

		$thumb = imagecreatetruecolor($width, $height);
		if(in_array($this->real_ext, ['png','gif']) && !isset($options['photo_force_jpg'])) {
			imagecolortransparent($thumb, imagecolorallocatealpha($thumb, 0, 0, 0, 127));
			imagealphablending($thumb, false);
			imagesavealpha($thumb, true);
		}

		imagecopyresampled($thumb, $this->source, 0, 0, 0, 0, $width, $height, $this->img[0], $this->img[1]);

		if(!empty($options['photo_watermark'])) {
			$stamp = imagecreatefrompng(\Core::$ROOT.$this->watermark);

			$marge_right = 10;
			$marge_bottom = 10;
			$sx = imagesx($stamp);
			$sy = imagesy($stamp);

			imagecopy($thumb, $stamp, $width - $sx - $marge_right, $height - $sy - $marge_bottom, 0, 0, imagesx($stamp), imagesy($stamp));
		}

		$name = pathinfo($this->filename, PATHINFO_FILENAME);
		if(in_array($this->real_ext, ['png','gif']) && !isset($options['photo_force_jpg'])) {
			$this->filename = $name.'.png';
			imagepng($thumb, \Core::$ROOT.'/'.$to.'/'.$this->filename);
		} else {
			$this->filename = $name.'.jpg';
			imagejpeg($thumb, \Core::$ROOT.'/'.$to.'/'.$this->filename, $this->quality);
		}
		imagedestroy($thumb);
		return $this;
	}
}
