<?php
declare(strict_types=1);
namespace Quid\Core\Test;
use Quid\Core;
use Quid\Base;

// request
class Request extends Base\Test
{
	// trigger
	public static function trigger(array $data):bool
	{
		// prepare
		$boot = $data['boot'];
		$routes = $boot->routes();
		$specific = new Core\Request('/en/table/user/1');
		$uri = "http://google.com/lavieestlaide?get=laol#lastchance";
		$r = new Core\Request($uri);

		// match
		assert(is_array($specific->match($routes)));

		// matchOne
		assert(is_string($specific->matchOne($routes)));

		// route
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