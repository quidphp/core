<?php
declare(strict_types=1);
namespace Quid\Core\Test;
use Quid\Test;
use Quid\Core;
use Quid\Base;

// roles
class Roles extends Base\Test
{
	// trigger
	public static function trigger(array $data):bool
	{
		// prepare
		$user = new Test\Role\User();
		$roles = new Core\Roles([Core\Role::class,Test\Role::class]);
		$roles2 = new Core\Roles();
		
		// main
		assert($roles->nobody() instanceof Core\Role\Nobody);

		// classe
		assert($roles->not($roles)->add($roles) !== $roles);
		assert($roles->not($roles)->add($roles)->isCount(3));
		assert($roles->not(1)->count() === 2);
		assert($roles->not($roles)->isEmpty());
		assert($roles->pair('name')[1] === 'nobody');
		assert($roles->pair('label','%:','fr')[80] === 'Administrateur:');
		assert($roles->filter(['permission'=>80])->isCount(1));
		assert($roles->filter(['permission'=>80]) !== $roles);
		assert($roles->filter(['canDb'=>true],'select')->isCount(3));
		assert($roles->filter(['canDb'=>true],'insert')->isCount(1));
		assert(count($roles->group('name')) === 3);
		assert($roles->sortBy('permission',false) !== $roles);
		assert(is_a($roles->sortBy('permission',false)->first(),Core\Role\Admin::class,true));
		assert(is_a($roles->sortDefault()->first(),Core\Role\Nobody::class,true));
		assert($roles->getObject(20) instanceof Test\Role\User);
		
		// map
		$user2 = $roles->get(20);
		assert(is_a($roles->get(20),Test\Role\User::class,true));
		assert($roles->get($user) !== $user);
		assert($roles->get(Test\Role\User::class) === $user2);
		assert(!$roles->in($user));
		assert($roles->in($user2));
		assert(!$roles->in(2));
		assert($roles->in(Test\Role\User::class));
		assert(!$roles->in(new Test\Role\User));
		assert($roles->exists($user2));
		assert($roles->exists($user));
		assert($roles->exists(20));
		assert($roles->exists(Test\Role\User::class));
		assert($roles->exists(new Test\Role\User));
		
		return true;
	}
}
?>