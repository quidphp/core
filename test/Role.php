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

// role
// class for testing Quid\Core\Role
class Role extends Base\Test
{
	// trigger
	public static function trigger(array $data):bool
	{
		// construct
		$admin = new Core\Role\Admin();

		// isNobody
		assert(Core\Role\Nobody::isNobody());
		assert(!Core\Role\Admin::isNobody());
		assert(!$admin->isNobody());

		// isSomebody
		assert(!Core\Role\Nobody::isSomebody());
		assert($admin->isSomebody());

		// isAdmin
		assert(!Core\Role\Nobody::isAdmin());
		assert($admin->isAdmin());

		// validateReplace

		// canLogin
		assert($admin->canLogin());
		assert(!Core\Role\Nobody::canLogin());

		// canDb
		assert($admin->canDb('select'));
		assert($admin->canDb('insert','user'));
		assert($admin->canDb('truncate','ormDb'));

		// getDb
		assert($admin->getDb('select'));

		// table
		assert(Core\Role\Nobody::table()['insert'] === false);
		assert(count($admin->table('user')) >= 10);
		assert(Core\Role\Nobody::table('log')['insert'] === true);

		// permission
		assert(Core\Role\Nobody::permission() === 1);

		// name
		assert(Core\Role\Nobody::name() === 'nobody');

		// label
		assert(Core\Role\Nobody::label() === 'Nobody');
		assert(Core\Role\Admin::label('%:','fr') === 'Administrateur:');

		// description
		assert(Core\Role\Nobody::description() === null);

		// getOverloadKeyPrepend
		assert(Core\Role::getOverloadKeyPrepend() === null);
		assert(Core\Role\Nobody::getOverloadKeyPrepend() === 'Role');

		// main
		$x = clone $admin;
		assert($x !== $admin);
		assert(count($admin->toArray()) === 7);
		assert($admin->_cast() === 80);
		assert(is_string($x = serialize($admin)));
		assert(unserialize($x) instanceof Core\Role\Admin);
		assert(Core\Role\Admin::can('login/cms'));
		assert(Core\Role\Admin::validate(['>'=>70]));
		assert(Core\Role\Admin::validate(['<'=>90]));
		assert(!Core\Role\Admin::validate(['<'=>8]));
		assert(!Core\Role\Admin::validate(['>'=>90]));
		assert(Core\Role\Admin::validate(['>'=>'nobody']));
		assert(!Core\Role\Admin::validate(['<'=>'nobody']));
		assert(Core\Role\Admin::validate('admin'));
		assert(!Core\Role\Admin::validate('nobody'));
		assert(Core\Role\Admin::validate(Core\Role\Admin::class));
		assert(Core\Role\Admin::validate(['admin','nobody']));
		assert(!Core\Role\Admin::validate(['adminz','nobody']));
		assert(Core\Role\Admin::validate(['='=>'admin']));
		assert(!Core\Role\Admin::validate(['!='=>'admin']));
		assert(Core\Role\Admin::validate(['!='=>'nobody']));
		assert(Core\Role\Admin::validate('admin'));

		return true;
	}
}
?>