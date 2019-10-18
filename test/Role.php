<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Test\Core;
use Quid\Base;
use Quid\Core;
use Quid\Main;
use Quid\Suite;

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

        // isShared
        assert(Core\Role\Shared::isShared());

        // isAdmin
        assert(!Core\Role\Nobody::isAdmin());
        assert($admin->isAdmin());

        // isCli
        assert(Core\Role\Cli::isCli());

        // validateReplace

        // permission
        assert(Core\Role\Nobody::permission() === 1);

        // name
        assert(Core\Role\Nobody::name() === 'nobody');

        // label
        assert(Core\Role\Nobody::label() === 'Nobody');
        assert(Core\Role\Admin::label('%:','fr') === 'Administrateur:');

        // labelPermission
        assert(Core\Role\Nobody::labelPermission() === 'Nobody (1)');

        // description
        assert(Core\Role\Nobody::description() === null);

        // getOverloadKeyPrepend
        assert(Core\Role::getOverloadKeyPrepend() === null);
        assert(Core\Role\Nobody::getOverloadKeyPrepend() === 'Role');

        // main
        $x = clone $admin;
        assert($x !== $admin);
        assert($admin->useAlso() === null);
        assert(count($admin->toArray()) === 4);
        assert($admin->_cast() === 80);
        assert(is_string($x = serialize($admin)));
        assert(unserialize($x) instanceof Core\Role\Admin);
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
        
        // roles
        $user = new Suite\Role\User();
        $roles = new Main\Roles([Core\Role::class,Suite\Role::class]);
        $roles2 = new Main\Roles();
        assert($roles->nobody() instanceof Core\Role\Nobody);
        assert($roles->not($roles)->add($roles) !== $roles);
        assert($roles->not($roles)->add($roles)->isCount(5));
        assert($roles->not(1)->count() === 4);
        assert($roles->not($roles)->isEmpty());
        assert($roles->pair('name')[1] === 'nobody');
        assert($roles->pair('label','%:','fr')[80] === 'Administrateur:');
        assert($roles->filter(['permission'=>80])->isCount(1));
        assert($roles->filter(['permission'=>80]) !== $roles);
        assert(count($roles->group('name')) === 5);
        assert($roles->sortBy('permission',false) !== $roles);
        assert(is_a($roles->sortBy('permission',false)->first(),Core\Role\Cli::class,true));
        assert(is_a($roles->sortDefault()->first(),Core\Role\Nobody::class,true));
        assert($roles->getObject(20) instanceof Suite\Role\User);
        $user2 = $roles->get(20);
        assert(is_a($roles->get(20),Suite\Role\User::class,true));
        assert($roles->get($user) !== $user);
        assert($roles->get(Suite\Role\User::class) === $user2);
        assert(!$roles->in($user));
        assert($roles->in($user2));
        assert(!$roles->in(2));
        assert($roles->in(Suite\Role\User::class));
        assert(!$roles->in(new Suite\Role\User()));
        assert($roles->exists($user2));
        assert($roles->exists($user));
        assert($roles->exists(20));
        assert($roles->exists(Suite\Role\User::class));
        assert($roles->exists(new Suite\Role\User()));
        
        return true;
    }
}
?>