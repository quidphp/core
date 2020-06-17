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
use Quid\Orm;

// cells
// extended class for a collection of many cells within a same row
class Cells extends Orm\Cells
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
        return static::checkClass($value,Table::class);
    }
}
?>