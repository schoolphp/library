<?php
namespace FW\Uploader;

trait UploaderError
{
	/**
	 * @param string $error
	 * @throws \Exception
	 */
	static function setError(string $error)
	{
		throw new \Exception(hc($this->basefilename) . '. ' . $error);
	}
}