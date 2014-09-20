<?php

/**
 * HassCMS (http://www.hassium.org/)
 *
 * @link      http://github.com/hasscms for the canonical source repository
 * @copyright Copyright (c) 2014-2099  Hassium  Software LLC.
 * @license   http://www.hassium.org/license/new-bsd New BSD License
 */

namespace hasscms\workbench\helps;

use yii\helpers\FileHelper;

/**
 *
 *
 * @author zhepama <zhepama@gmail.com>
 * @date 2014-9-20 下午10:57:22
 * @since 1.0
 */
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