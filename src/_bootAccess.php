<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 * Readme: https://github.com/quidphp/core/blob/master/README.md
 */

namespace Quid\Core;
use Quid\Routing;

// _bootAccess
// trait that provides methods to access the Boot object
trait _bootAccess
{
    // boot
    // retourne l'objet boot
    final public static function boot():Boot
    {
        return Boot::inst();
    }


    // bootSafe
    // retourne l'objet boot si existant, n'envoie pas d'exception
    final public static function bootSafe():?Boot
    {
        return Boot::instSafe();
    }


    // bootReady
    // retourne l'objet boot si prêt, n'envoie pas d'exception
    final public static function bootReady():?Boot
    {
        return Boot::instReady();
    }


    // routeException
    // lance une exception de route
    // ceci force à passer à la prochaine route
    final public static function routeException(?array $option=null,...$values):void
    {
        static::throwCommon(Routing\Exception::class,$values,$option);
    }


    // routeBreakException
    // lance une exception de route
    // ceci brise le loop des route
    final public static function routeBreakException(?array $option=null,...$values):void
    {
        static::throwCommon(Routing\BreakException::class,$values,$option);
    }
}
?>