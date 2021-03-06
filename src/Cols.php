<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core;
use Quid\Orm;

// cols
// extended class for a collection of many columns within a same table
class Cols extends Orm\Cols
{
    // trait
    use _accessAlias;


    // config
    protected static array $config = [];


    // tableFromFqcn
    // retourne l'objet table à partir du fqcn de la classe
    // envoie une erreur si la table n'existe pas
    final public static function tableFromFqcn():Table
    {
        $value = (static::class !== self::class)? static::boot()->db()->table(static::class):null;
        return static::typecheck($value,Table::class);
    }
}
?>