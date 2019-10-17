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

        // canLogin
        assert($admin->canLogin());
        assert(!Core\Role\Nobody::canLogin());

        // db
        assert(Base\Arrs::is($admin->db()));
        assert($admin->db('update')['log'] === false);
        assert($admin->db('update')['ormCell'] === true);

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
        assert(count($admin->toArray()) === 5);
        assert($admin->_cast() === 80);
        assert(is_string($x = serialize($admin)));
        assert(unserialize($x) instanceof Core\Role\Admin);
        assert(Core\Role\Admin::can('login/assert'));
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