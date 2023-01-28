<?php

namespace Gsdk\Grid\Concerns;

trait HasExtensions
{

	private static array $extendedNamespaces = [];

	private static array $extendedColumns = [];

	public static function registerNamespace(string $namespace): void
	{
		self::$extendedNamespaces[] = $namespace;
	}

	public static function extend(string $type, string $class): void
	{
		self::$extendedColumns[$type] = $class;
	}

	protected static function columnFactory($name, $type, $options)
	{
		if (isset(self::$extendedColumns[$type])) {
			$class = self::$extendedColumns[$type];
		}
		else {
			$class = null;

			foreach (self::$extendedNamespaces as $ns) {
				$tmp = $ns . '\\' . ucfirst($type);
				if (!class_exists($tmp)) {
					continue;
				}

				self::extend($type, $tmp);
				$class = $tmp;
				break;
			}

			if (null === $class) {
				$class = 'Gsdk\Grid\Column\\' . ucfirst($type);
			}
		}

		return new $class($name, $options);
	}

}
