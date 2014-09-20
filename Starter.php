<?php
/**
 * HassCMS (http://www.hassium.org/)
 *
 * @link      http://github.com/hasscms for the canonical source repository
 * @copyright Copyright (c) 2014-2099  Hassium  Software LLC.
 * @license   http://www.hassium.org/license/new-bsd New BSD License
 */
namespace hasscms\workbench;
use yii\helpers\FileHelper;

/**
 *
 *
 * @author zhepama <zhepama@gmail.com>
 * @date 2014-9-20 下午10:58:51
 * @since 1.0
 */
class Starter {

	public static function start($path)
	{

		$dir = FileHelper::normalizePath(rtrim($path, DIRECTORY_SEPARATOR));

		$autoloads = FileHelper::findFiles($dir,["only"=>["autoload.php"],"filter"=>function($file) use($dir){
			if(substr_count($file,DIRECTORY_SEPARATOR)-4 > substr_count($dir,DIRECTORY_SEPARATOR))
			{
				return false;
			}
		}]);

		foreach ($autoloads as $path)
		{
			require_once $path;
		}
	}

	/**
	 *
	 * @param \yii\base\Event $event
	 */
	public static function registerCommend($event)
	{
		$app = $event->sender;
		/*@var $app \yii\console\Application */
		$app->controllerMap['workbench'] = 'hasscms\workbench\controllers\DefaultController';
		$app->params["packageAuthor"] = $event->data["author"];
		$app->params["packageEmail"]=$event->data["email"];
		$app->params["workBenchPath"]=$event->data["workBenchPath"];
	}
}
