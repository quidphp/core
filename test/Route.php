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
use Quid\Routing;
use Quid\Test\Suite;

// route
// class for testing Quid\Core\Route
class Route extends Base\Test
{
    // trigger
    final public static function trigger(array $data):bool
    {
        // prepare
        $boot = Core\Boot::inst();
        $contact = Suite\Assert\Contact::class;
        $contactMake = $contact::make();
        $admin = $boot->roles()->get(80);

        // type
        assert($contact::type() === 'assert');

        // getBaseReplace

        // prepareTitle

        // prepareDocServices

        // rowExists
        assert($contactMake->rowExists() === false);

        // row
        assert($contactMake->row() === null);

        // getOtherMeta

        // host
        assert(is_string($contact::host()));

        // schemeHost
        assert(is_string($contact::schemeHost()));

        // routes
        assert($contact::routes() instanceof Routing\Routes);

        // tableSegment

        // rowClass
        assert($contact::rowClass() === Suite\Row\OrmCol::class);

        // tableFromRowClass
        assert($contact::tableFromRowClass() instanceof Core\Table);

        // routeBaseClasses
        assert(count($contact::routeBaseClasses()) === 2);

        // routing
        assert(is_array($contactMake->getPermission($admin)));
        assert($contact::inSitemap());
        assert($contact::isRedirectable());

        // request
        $class = new class() extends Core\Route\Robots { };
        assert(count(Routing\Request::fromRoute($class)) === 3);

        return true;
    }
}
?>