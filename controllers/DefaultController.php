<?php
/**
 * HassCMS (http://www.hassium.org/)
 *
 * @link      http://github.com/hasscms for the canonical source repository
 * @copyright Copyright (c) 2014-2099  Hassium  Software LLC.
 * @license   http://www.hassium.org/license/new-bsd New BSD License
 */

namespace hasscms\workbench\controllers;
use hasscms\workbench\helps\PackageCreator;
use hasscms\workbench\helps\Package;
use yii\helpers\FileHelper;

/**
 *
 *
 * @author zhepama <zhepama@gmail.com>
 * @date 2014-9-20 下午10:55:33
 * @since 1.0
 */
class DefaultController extends \yii\console\Controller {

	/**
	 * The package creator instance.
	 *
	 * @var \hasscms\workbench\helps\PackageCreator
	 */
	protected $creator;
	public function init(){
		$this->creator = new PackageCreator();
	}
	public function actionIndex($package,$plain= false)
	{
		$path = $this->creator->create( $this->buildPackage($package), \Yii::$app->params["workBenchPath"], $plain);
		echo 'Package workbench created!';
		chdir($path);
		passthru('composer install --dev');
	}

	protected function buildPackage($package)
	{
		list($vendor, $name) = array_map(function ($value)
		{
			$value = str_replace(array('-', '_'), ' ', $value);
			return str_replace(' ', '', $value);
		}, explode('/', $package, 2));
		$author = \Yii::$app->params["packageAuthor"];
		$email = \Yii::$app->params["packageEmail"];
		return new Package($vendor, $name, $author, $email);
	}

	public function actionAutoload()
	{
		$dir = FileHelper::normalizePath(rtrim(\Yii::$app->params["workBenchPath"]), DIRECTORY_SEPARATOR);

		$composers = FileHelper::findFiles($dir,["only"=>["composer.json"],"filter"=>function($file) use($dir){
			if(substr_count($file,DIRECTORY_SEPARATOR)-3 > substr_count($dir,DIRECTORY_SEPARATOR))
			{
				return false;
			}
		}]);

		foreach ($composers as $file)
		{
			chdir(dirname($file));
			passthru('composer dump-autoload --optimize');
		}
		echo 'dump-autoload package!';
	}

}