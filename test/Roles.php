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

// roles
// class for testing Quid\Core\Roles
class Roles extends Base\Test
{
	// trigger
	public static function trigger(array $data):bool
	{
		// prepare
		$user = new TestSuite\Role\User();
		$roles = new Core\Roles([Core\Role::class,TestSuite\Role::class]);
		$roles2 = new Core\Roles();

		// main
		assert($roles->nobody() instanceof Core\Role\Nobody);

		// classe
		assert($roles->not($roles)->add($roles) !== $roles);
		assert($roles->not($roles)->add($roles)->isCount(5));
		assert($roles->not(1)->count() === 4);
		assert($roles->not($roles)->isEmpty());
		assert($roles->pair('name')[1] === 'nobody');
		assert($roles->pair('label','%:','fr')[80] === 'Administrateur:');
		assert($roles->filter(['permission'=>80])->isCount(1));
		assert($roles->filter(['permission'=>80]) !== $roles);
		assert($roles->filter(['canDb'=>true],'select')->isCount(5));
		assert($roles->filter(['canDb'=>true],'insert')->isCount(2));
		assert(count($roles->group('name')) === 5);
		assert($roles->sortBy('permission',false) !== $roles);
		assert(is_a($roles->sortBy('permission',false)->first(),Core\Role\Admin::class,true));
		assert(is_a($roles->sortDefault()->first(),Core\Role\Nobody::class,true));
		assert($roles->getObject(20) instanceof TestSuite\Role\User);

		// map
		$user2 = $roles->get(20);
		assert(is_a($roles->get(20),TestSuite\Role\User::class,true));
		assert($roles->get($user) !== $user);
		assert($roles->get(TestSuite\Role\User::class) === $user2);
		assert(!$roles->in($user));
		assert($roles->in($user2));
		assert(!$roles->in(2));
		assert($roles->in(TestSuite\Role\User::class));
		assert(!$roles->in(new TestSuite\Role\User()));
		assert($roles->exists($user2));
		assert($roles->exists($user));
		assert($roles->exists(20));
		assert($roles->exists(TestSuite\Role\User::class));
		assert($roles->exists(new TestSuite\Role\User()));

		return true;
	}
}
?>