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
        // prepare
        $boot = Core\Boot::inst();
        $roles = $boot->roles();
        $nobody = $roles->nobody();
        $admin = $roles->get(80);
        $cli = $roles->main();

        // isShared
        assert(!$admin->isShared());

        // isAdmin
        assert($admin->isAdmin());
        assert($cli->isAdmin());

        // isCli
        assert(!$admin->isCli());
        assert($cli->isCli());

        // validate
        assert($admin->validate(['='=>'admin']));

        assert($admin->validate([80]));
        assert($admin->validate(['admin']));
        assert(!$admin->validate([70]));
        assert(!$admin->validate(['bla']));
        assert($admin->validate(['>'=>'nobody']));
        assert(!$admin->validate(['>'=>'whatnobody']));
        assert(!$admin->validate(['<'=>'nobody']));
        assert($admin->validate('admin'));
        assert(!$admin->validate('nobody'));
        assert($admin->validate(['admin','nobody']));
        assert(!$admin->validate(['adminz','nobody']));
        assert($admin->validate(['='=>'admin']));
        assert(!$admin->validate(['!='=>'admin']));
        assert($admin->validate(['!='=>'nobody']));

        // validateReplace

        // label
        assert($nobody->label() === 'Nobody');
        assert($admin->label('%:','fr') === 'Administrateur:');

        // labelPermission
        assert($nobody->labelPermission() === 'Nobody (1)');

        // description
        assert($nobody->description() === null);

        return true;
    }
}
?>