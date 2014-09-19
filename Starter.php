<?php
namespace hasscms\workbench;

use hasscms;
use yii\helpers\FileHelper;
/**
 * 引入workbench下所有的类
 * 创建package
 * @author zhepama
 *
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
