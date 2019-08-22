<?php
declare(strict_types=1);
namespace Quid\Core\Test;
use Quid\Core;
use Quid\Base;

// nav
class Nav extends Base\Test
{
	// trigger
	public static function trigger(array $data):bool
	{
		// prepare
		$nav = new Core\Nav();

		// route

		// map
		assert($nav->set('ok','ok') === $nav);
		assert($nav->get('ok') === 'ok');
		
		return true;
	}
}
?>