<?php
namespace FW\Uploader;

trait UploaderError
{
	/**
	 * @param string $error
	 * @throws \Exception
	 * @return bool
	 */
	protected function setError(String $error)
	{
		throw new \Exception($error);
		return false;
	}
}