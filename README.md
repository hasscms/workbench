workbench
=========



if (is_dir ( $workbench = dirname ( dirname ( __DIR__ ) ) . '/workbench' )) {

	if (is_dir ( $workbench . '/hasscms/workbench' )) {
		$loader = new \Composer\Autoload\ClassLoader ();
		$map = array (
				'hasscms\\workbench\\' => array (
						$workbench . '/hasscms/workbench'
				)
		);
		foreach ( $map as $namespace => $path ) {
			$loader->setPsr4 ( $namespace, $path );
		}
		$loader->register ( true );
	}
	Event::on ( 'yii\console\Application', yii\base\Application::EVENT_BEFORE_REQUEST, [
			'hasscms\workbench\Starter',
			'registerCommend'
	], [
			"author" => "zhepama",
			"email" => "zhepama@gmail.com"
	] );

	hasscms\workbench\Starter::start ( $workbench );
}
