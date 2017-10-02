<?php

namespace App\Util;

class ApplicationVersion
{
	const MAJOR = 1;
	const MINOR = 2;
	const PATCH = 0;

	private static function isEnabled($func) {
		return is_callable($func) && false === stripos(ini_get('disable_functions'), $func);
	}

	public static function get()
	{
		if (self::isEnabled('exec')) {
			$commitHash = trim(exec('git log --pretty="%h" -n1 HEAD'));
			$commitDate = new \DateTime(trim(exec('git log -n1 --pretty=%ci HEAD')));
			$commitDate->setTimezone(new \DateTimeZone('UTC'));
			return sprintf('v%s.%s.%s-dev.%s (%s)', self::MAJOR, self::MINOR, self::PATCH, $commitHash, $commitDate->format('Y-m-d H:m:s'));
		}
		return sprintf('v%s.%s.%s', self::MAJOR, self::MINOR, self::PATCH);
	}
}
