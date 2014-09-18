<?php

namespace  hasscms\workbench\helps;
class Package {

	/**
	 * The vendor name of the package.
	 *
	 * @var string
	 */
	public $vendor;

	/**
	 * The snake-cased version of the vendor.
	 *
	 * @var string
	 */
	public $lowerVendor;

	/**
	 * The name of the package.
	 *
	 * @var string
	 */
	public $name;

	/**
	 * The snake-cased version of the package.
	 *
	 * @var string
	 */
	public $lowerName;

	/**
	 * The name of the author.
	 *
	 * @var string
	 */
	public $author;

	/**
	 * The email address of the author.
	 *
	 * @var string
	 */
	public $email;

	/**
	 * Create a new package instance.
	 *
	 * @param  string  $vendor
	 * @param  string  $name
	 * @param  string  $author
	 * @param  string  $email
	 * @return void
	 */
	public function __construct($vendor, $name, $author, $email)
	{
		$this->name = $name;
		$this->email = $email;
		$this->vendor = $vendor;
		$this->author = $author;
		$this->lowerName = static::snake_case($name, '-');
		$this->lowerVendor =  static::snake_case($vendor, '-');
	}

	/**
	 * Get the full package name.
	 *
	 * @return string
	 */
	public function getFullName()
	{
		return $this->lowerVendor.'/'.$this->lowerName;
	}

	public static function snake_case($value, $delimiter = '_')
	{
		$replace = '$1'.$delimiter.'$2';

		return ctype_lower($value) ? $value : strtolower(preg_replace('/(.)([A-Z])/', $replace, $value));
	}

}
