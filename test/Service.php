<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Test\Core;
use Quid\Base;

// service
// class for testing Quid\Core\Service
class Service extends Base\Test
{
	// trigger
	public static function trigger(array $data):bool
	{
		// prepare
		$boot = $data['boot'];

		// getLangCode

		// getOverloadKeyPrepend

		// classUpload

		// jShrink

		// ldap
		$ldap = $boot->service('ldap');
		assert($ldap->host() === 'james.com');
		assert($ldap->port() === 388);
		assert(is_resource($ldap->res()));

		// phpConcatenator

		// scssPhp

		return true;
	}
}
?>