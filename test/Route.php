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
        $priority = Suite\Assert\Priority::class;
        $priorityMake = $priority::make();
        $admin = $boot->roles()->get(80);

        // type
        assert($priority::type() === 'assert');

        // getBaseReplace

        // prepareTitle

        // prepareDocServices

        // rowExists
        assert($priorityMake->rowExists() === false);

        // row
        assert($priorityMake->row() === null);

        // getOtherMeta

        // host
        assert(is_string($priority::host()));

        // schemeHost
        assert(is_string($priority::schemeHost()));

        // routes
        assert($priority::routes() instanceof Routing\Routes);

        // tableSegment

        // rowClass
        assert($priority::rowClass() === Suite\Row\OrmCol::class);

        // tableFromRowClass
        assert($priority::tableFromRowClass() instanceof Core\Table);

        // routeBaseClasses
        assert(count($priority::routeBaseClasses()) === 2);

        // routing
        assert(is_array($priorityMake->getPermission($admin)));
        assert($priority::inSitemap());
        assert($priority::isRedirectable());

        // request
        $class = new class() extends Core\Route\Robots { };
        assert(count(Routing\Request::fromRoute($class)) === 3);

        return true;
    }
}
?>