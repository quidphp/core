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
use Quid\TestSuite;

// route
// class for testing Quid\Core\Route
class Route extends Base\Test
{
    // trigger
    public static function trigger(array $data):bool
    {
        // prepare
        $contact = TestSuite\Assert\Contact::class;
        $contactMake = $contact::make();

        // type
        assert($contact::type() === 'assert');

        // getBaseReplace

        // prepareTitle

        // prepareDocServices

        // context
        assert(count($contactMake->context()) === 4);

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
        assert($contact::routes() instanceof Core\Routes);

        // tableSegment

        // rowClass
        assert($contact::rowClass() === TestSuite\Row\OrmCol::class);

        // tableFromRowClass
        assert($contact::tableFromRowClass() instanceof Core\Table);

        // routeBaseClasses
        assert(count($contact::routeBaseClasses()) === 2);

        // getOverloadKeyPrepend

        // routing
        assert($contact::inSitemap());
        assert($contact::isRedirectable());

        return true;
    }
}
?>