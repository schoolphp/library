<?php
namespace FW\Uploader;

class UploaderImage implements UploaderInterface
{
	use UploaderLibrary, UploaderError;

	public $watermark = '/uploads/watermark.png';
	public $minwidth = 200;
	public $minheight = 150;

	public function __construct($file) {
		$this->img = getimagesize($file['tmp_destination']);
		if('image/jpeg' === $this->img['mime']) {
			$this->source = imagecreatefromjpeg($file['tmp_destination']);
		} elseif('image/png' === $this->img['mime']) {
			$this->source = imagecreatefrompng($file['tmp_destination']);
		} elseif('image/gif' === $this->img['mime']) {
			$this->source = imagecreatefromgif($file['tmp_destination']);
		} else {
			$this->setError('Incorrect file mime type');
		}
		$this->filename = $file['file_name'];
		$this->prop = $this->img[0]/$this->img[1];
		if($this->prop > 5 || $this->prop < 0.2) {
			$this->setError('Incorrect file proportion');
		}

		if($this->img[0] < $this->minwidth) {
			$this->setError('Width of file is too small. Minimum require '.$this->minwidth.' px');
		} elseif($this->img[1] < $this->minheight) {
			$this->setError('Height of file is too small. Minimum require '.$this->minheight.' px');
		}
	}

	public function upload():bool
	{


		return true;
	}

	public function save($width,$height,$to = '/uploads',$watermark = false):bool {
		$beginwidth = $width;
		if(empty($this->filename) || empty($this->prop) || !is_array($this->img) || !$this->source) {
			$this->setError('File is not included to resize');
		}

		$tmp = $width/$this->prop;
		if($tmp > $height) {
			$width = round($height*$this->prop);
		} else {
			$height = round($tmp);
		}

		$thumb = imagecreatetruecolor($width, $height);
		imagecopyresampled($thumb, $this->source, 0, 0, 0, 0, $width, $height, $this->img[0], $this->img[1]);

		if($watermark) {
			$stamp = imagecreatefrompng(\Core::$ROOT.$this->watermark);
			$watermarksize = getimagesize(\Core::$ROOT.$this->watermark);
			$watermarkprop = 600/$beginwidth;
			if($watermarkprop != 1) {
				$targetImage = imagecreatetruecolor( $watermarksize[0]/$watermarkprop, $watermarksize[1]/$watermarkprop );
				imagealphablending( $targetImage, false );
				imagesavealpha( $targetImage, true );

				imagecopyresampled( $targetImage, $stamp,
					0, 0,
					0, 0,
					$watermarksize[0]/$watermarkprop, $watermarksize[1]/$watermarkprop,
					$watermarksize[0], $watermarksize[1]);
				$stamp = $targetImage;
			}
			$marge_right = 5;
			$marge_bottom = 5;
			$sx = imagesx($stamp);
			$sy = imagesy($stamp);
			imagecopy($thumb, $stamp, $width - $sx - $marge_right, $height - $sy - $marge_bottom, 0, 0, $sx, $sy);
		}

		imagejpeg($thumb,\Core::$ROOT.$to.'/'.$this->filename,100);
		imagedestroy($thumb);
		return true;
	}
}
