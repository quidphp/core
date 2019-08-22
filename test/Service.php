<?php
declare(strict_types=1);
namespace Quid\Core\Test;
use Quid\Base;

// service
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