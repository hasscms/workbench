<?php
/**
 * HassCMS (http://www.hassium.org/)
 *
 * @link      http://github.com/hasscms for the canonical source repository
 * @copyright Copyright (c) 2014-2099  Hassium  Software LLC.
 * @license   http://www.hassium.org/license/new-bsd New BSD License
 */

namespace  hasscms\workbench\helps;

/**
 *
 *
 * @author zhepama <zhepama@gmail.com>
 * @date 2014-9-20 下午10:58:05
 * @since 1.0
 */
class PackageCreator {
	/**
	 * The filesystem instance.
	 *
	 * @var \hasscms\workbench\helps\Filesystem
	 */
	protected $files;
	/**
	 * The basic building blocks of the package.
	 *
	 * @param  array
	 */
	protected $basicBlocks = array(
		'SupportFiles',
		'TestDirectory',
		'ModuleFile',
	);

	/**
	 * The building blocks of the package.
	 *
	 * @param  array
	 */
	protected $blocks = array(
		'SupportFiles',
		'SupportDirectories',
		'TestDirectory',
		'ModuleFile',
	);

	public $resourceDir;

	/**
	 * Create a new package creator instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->resourceDir = dirname(__DIR__)."/stubs";
		$this->files = new Filesystem();
	}


	/**
	 * Create a new package stub.
	 *
	 * @param   \hasscms\workbench\helps\Package  $package
	 * @param  string  $path
	 * @param  bool    $plain
	 * @return string
	 */
	public function create(Package $package, $path, $plain = true)
	{
		$directory = $this->createDirectory($package, $path);

		// To create the package, we will spin through a list of building blocks that
		// make up each package. We'll then call the method to build that block on
		// the class, which keeps the actual building of stuff nice and cleaned.
		foreach ($this->getBlocks($plain) as $block)
		{
			$this->{"write{$block}"}($package, $directory, $plain);
		}

		return $directory;
	}

	/**
	 * Get the blocks for a given package.
	 *
	 * @param  bool $plain
	 * @return array
	 */
	protected function getBlocks($plain)
	{
		return $plain ? $this->basicBlocks : $this->blocks;
	}

	/**
	 * Write the support files to the package root.
	 *
	 * @param   \hasscms\workbench\helps\Package  $package
	 * @param  string  $directory
	 * @param  bool    $plain
	 * @return void
	 */
	public function writeSupportFiles(Package $package, $directory, $plain)
	{
		foreach (array('PhpUnit', 'Travis', 'Composer', 'Ignore') as $file)
		{
			$this->{"write{$file}File"}($package, $directory, $plain);
		}
	}

	/**
	 * Write the PHPUnit stub file.
	 *
	 * @param   \hasscms\workbench\helps\Package  $package
	 * @param  string  $directory
	 * @return void
	 */
	protected function writePhpUnitFile(Package $package, $directory)
	{
		$stub = $this->resourceDir .'/phpunit.xml';

		$this->files->copy($stub, $directory.'/phpunit.xml');
	}

	/**
	 * Write the Travis stub file.
	 *
	 * @param   \hasscms\workbench\helps\Package  $package
	 * @param  string  $directory
	 * @return void
	 */
	protected function writeTravisFile(Package $package, $directory)
	{
		$stub = $this->resourceDir .'/.travis.yml';

		$this->files->copy($stub, $directory.'/.travis.yml');
	}

	/**
	 * Write the Composer.json stub file.
	 *
	 * @param   \hasscms\workbench\helps\Package  $package
	 * @param  string  $directory
	 * @param  bool    $plain
	 * @return void
	 */
	protected function writeComposerFile(Package $package, $directory, $plain)
	{
		$stub = $this->getComposerStub($plain);

		$stub = $this->formatPackageStub($package, $stub);

		$this->files->put($directory.'/composer.json', $stub);
	}

	/**
	 * Get the Composer.json stub file contents.
	 *
	 * @param  bool  $plain
	 * @return string
	 */
	protected function getComposerStub($plain)
	{
		if ($plain) return $this->files->get($this->resourceDir .'/plain.composer.json');

		return $this->files->get($this->resourceDir .'/composer.json');
	}

	/**
	 * Write the stub .gitignore file for the package.
	 *
	 * @param   \hasscms\workbench\helps\Package  $package
	 * @param  string  $directory
	 * @param  bool    $plain
	 * @return void
	 */
	public function writeIgnoreFile(Package $package, $directory, $plain)
	{
		$this->files->copy($this->resourceDir .'/gitignore.txt', $directory.'/.gitignore');
	}

	/**
	 * Create the support directories for a package.
	 *
	 * @param   \hasscms\workbench\helps\Package  $package
	 * @param  string  $directory
	 * @return void
	 */
	public function writeSupportDirectories(Package $package, $directory)
	{
		foreach (array('components','config', 'controllers','models', 'views') as $support)
		{
			$this->writeSupportDirectory($package, $support, $directory);
		}
	}

	/**
	 * Write a specific support directory for the package.
	 *
	 * @param   \hasscms\workbench\helps\Package  $package
	 * @param  string  $support
	 * @param  string  $directory
	 * @return void
	 */
	protected function writeSupportDirectory(Package $package, $support, $directory)
	{
		// Once we create the source directory, we will write an empty file to the
		// directory so that it will be kept in source control allowing the dev
		// to go ahead and push these components to GitHub right on creation.
		$path = $directory.'/'.$support;

		$this->files->makeDirectory($path, 0777, true);

		$this->files->put($path.'/.gitkeep', '');
	}

	/**
	 * Create the test directory for the package.
	 *
	 * @param   \hasscms\workbench\helps\Package  $package
	 * @param  string  $directory
	 * @return void
	 */
	public function writeTestDirectory(Package $package, $directory)
	{
		$this->files->makeDirectory($directory.'/tests');

		$this->files->put($directory.'/tests/.gitkeep', '');
	}


	/**
	 * Write the stub ServiceModule for the package.
	 *
	 * @param   \hasscms\workbench\helps\Package  $package
	 * @param  string  $directory
	 * @param  bool    $plain
	 * @return void
	 */
	public function writeModuleFile(Package $package, $directory, $plain)
	{
		// Once we have the service Module stub, we will need to format it and make
		// the necessary replacements to the class, namespaces, etc. Then we'll be
		// able to write it out into the package's workbench directory for them.
		$stub =$this->formatPackageStub($package, $this->getModuleFile($plain));

		$file = $directory.'/Module.php';

		$this->files->put($file, $stub);
	}

	/**
	 * Load the raw service Module file.
	 *
	 * @param  bool  $plain
	 * @return string
	 */
	protected function getModuleFile($plain)
	{
		if ($plain)
		{
			return $this->files->get($this->resourceDir .'/plain.module.stub');
		}
		else
		{
			return $this->files->get($this->resourceDir .'/module.stub');
		}
	}


	/**
	 * Format a generic package stub file.
	 *
	 * @param   \hasscms\workbench\helps\Package  $package
	 * @param  string  $stub
	 * @return string
	 */
	protected function formatPackageStub(Package $package, $stub)
	{
		foreach (get_object_vars($package) as $key => $value)
		{
			$stub = str_replace('{{'.Package::snake_case($key).'}}', $value, $stub);
		}

		return $stub;
	}

	/**
	 * Create a workbench directory for the package.
	 *
	 * @param   \hasscms\workbench\helps\Package  $package
	 * @param  string  $path
	 * @return string
	 *
	 */
	protected function createDirectory(Package $package, $path)
	{
		$fullPath = $path.'/'.$package->getFullName();

		// If the directory doesn't exist, we will go ahead and create the package
		// directory in the workbench location. We will use this entire package
		// name when creating the directory to avoid any potential conflicts.
		if ( ! $this->files->isDirectory($fullPath))
		{
			$this->files->makeDirectory($fullPath, 0777, true);

			return $fullPath;
		}

	}

}
