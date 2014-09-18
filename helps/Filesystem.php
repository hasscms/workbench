<?php

namespace hasscms\workbench\helps;

use yii\helpers\FileHelper;

class Filesystem {
	public function isDirectory($filename) {
		return is_dir ( $filename );
	}
	public function makeDirectory($path, $mode = 0775, $recursive = true) {
		return FileHelper::createDirectory ( $path, $mode, $recursive );
	}
	public function copy($source, $dest, $context = null) {
		if (is_file ( $source )) {
			$this->makeDirectory ( dirname ( $dest ), 0777, true );
			return copy ( $source, $dest );
		}
		return false;
	}
	public function put($filename, $data, $context = null) {
		return file_put_contents ( $filename, $data, $context );
	}
	public function get($filename) {
		return file_get_contents ( $filename);
	}
}

?>