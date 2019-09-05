<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/test/blob/master/LICENSE
 */

namespace Quid\Test\Core;
use Quid\TestSuite;
use Quid\Core;
use Quid\Base;

// routes
// class for testing Quid\Core\Routes
class Routes extends Base\Test
{
	// trigger
	public static function trigger(array $data):bool
	{
		// prepare
		$boot = $data['boot'];
		$type = $boot->type();
		$app = $boot->routes($type);
		$request = Core\Request::live();
		$login = Core\Cms\Login::class;
		$loginSubmit = Core\Cms\LoginSubmit::class;
		$routes = new Core\Routes([Core\Cms::class,TestSuite\Cms::class]);
		$routes->init('cms');

		// routing
		assert($app->count() === 5);
		assert($routes->type() === 'cms');
		assert($routes->keyParent()[Core\Cms\LoginSubmit::class] === Core\Cms\Login::class);
		assert(count($routes->hierarchy()) === 10);
		assert(count($routes->childsRecursive($login)) === 5);
		assert($routes->tops()->isCount(10));
		assert($routes->tops() !== $routes);
		assert($routes->top($loginSubmit) === $login);
		assert($routes->parents($loginSubmit)->isCount(1));
		assert($routes->breadcrumb($loginSubmit)->isCount(2));
		assert($routes->breadcrumb($loginSubmit)->last() === $loginSubmit);
		assert($routes->siblings($loginSubmit)->isCount(4));
		assert($routes->childs($login)->isCount(5));
		assert($routes->withSegment()->count() > 5);
		assert($routes->withoutSegment()->count() > 5);
		assert($routes->active()->count() !== $routes->count());
		assert($routes::makeBreadcrumbs('/',null,$login::make(),$loginSubmit::make()) === "<a href='/'>Login</a>/<a href='/en/login' hreflang='en'>Login - Submit</a>");
		assert($routes::makeBreadcrumbs('/',5,$login::make(),$loginSubmit::make()) === "<a href='/'>Login</a>/<a href='/en/login' hreflang='en'>Lo...</a>");

		// classe
		assert($routes->not($routes)->add($routes)->count() > 20);
		assert($routes->not('Home') !== $routes);
		assert($routes->not('Home')->count() === ($routes->count() - 1));
		assert($routes->not($routes)->isEmpty());
		assert($routes->not($routes->not('Home'))->count() === 1);
		assert($routes->pair('priority')['Home'] === 1);
		assert(is_numeric($routes->pairStr('priority')));
		assert($routes->pair('path','en')['LoginSubmit'] === 'login');
		assert($routes->pair('label','%:',null,['error'=>false])['Home'] === 'Home:');
		assert($routes->filter(['group'=>'home'])->isCount(1));
		assert($routes->first(['group'=>'home']) === Core\Cms\Home::class);
		assert($routes->filter(['group'=>'error','priority'=>992])->isEmpty());
		assert($routes->filter(['group'=>'error','priority'=>999])->isCount(1));
		assert(count($routes->group('group')) === 9);
		assert($routes->sortBy('name',false)->index(1) === Core\Cms\SpecificUserWelcome::class);
		assert($routes->sortBy('name',false) !== $routes);
		assert($routes->sortDefault()->index(0) === Core\Cms\Home::class);
		assert($routes->sortDefault() === $routes);

		// map
		assert($routes->isCount(36));
		assert($routes->get('Sitemap') === Core\Cms\Sitemap::class);
		assert($routes->get(Core\Cms\Sitemap::class) === Core\Cms\Sitemap::class);
		assert(!$routes->in('Sitemap'));
		assert($routes->in(Core\Cms\Sitemap::class));
		assert($routes->exists('Sitemap'));
		assert($routes->exists(Core\Cms\Sitemap::class));
		assert($routes->unset('Sitemap')->isCount(35));
		assert($routes->add(Core\Cms\Sitemap::class)->isCount(36));

		return true;
	}
}
?>