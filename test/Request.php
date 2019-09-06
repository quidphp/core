<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Test\Core;
use Quid\Core;
use Quid\Base;

// request
// class for testing Quid\Core\Request
class Request extends Base\Test
{
	// trigger
	public static function trigger(array $data):bool
	{
		// prepare
		$boot = $data['boot'];
		$routes = $boot->routes();
		$specific = new Core\Request('/en/table/user/1');
		$uri = 'http://google.com/lavieestlaide?get=laol#lastchance';
		$r = new Core\Request($uri);

		// routing
		assert(is_array($specific->match($routes)));
		assert(is_string($specific->matchOne($routes)));
		assert($specific->route($routes) instanceof Core\Route);

		// main
		$inst = Core\Boot::inst()->request();
		$instClone = $inst->clone();
		assert($inst !== $instClone);
		assert($instClone->isLive());
		assert($instClone->setAjax(false) === $instClone);

		// inst
		$bootRequest = Core\Boot::inst()->request();
		$bootRequest->unsetInst();
		assert(!$r->isReadOnly());
		assert($r->setInst() === $r);
		assert($r->isReadOnly());
		assert($r->unsetInst() === $r);
		assert(!$r->isReadOnly());
		$bootRequest->setInst();

		return true;
	}
}
?>