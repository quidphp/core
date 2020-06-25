<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core;
use Quid\Orm;

// _dbAccess
// trait that provides a method to access the current database
trait _dbAccess
{
    // db
    // retourne l'objet db de boot
    final public static function db():Orm\Db
    {
        return static::boot()->db();
    }
}
?>