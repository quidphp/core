<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core;
use Quid\Base;
use Quid\Orm;

// cells
// extended class for a collection of many cells within a same row
class Cells extends Orm\Cells
{
    // trait
    use _accessAlias;


    // config
    public static $config = [];


    // tableFromFqcn
    // retourne l'objet table à partir du fqcn de la classe
    // envoie une erreur si la table n'existe pas
    public static function tableFromFqcn():Table
    {
        $return = (static::class !== self::class)? static::boot()->db()->table(static::class):null;

        if(!$return instanceof Table)
        static::throw();

        return $return;
    }


    // getOverloadKeyPrepend
    // retourne le prepend de la clé à utiliser pour le tableau overload
    public static function getOverloadKeyPrepend():?string
    {
        return (static::class !== self::class && !Base\Fqcn::sameName(static::class,self::class))? 'Cells':null;
    }


    // keyClassExtends
    // retourne un tableau utilisé par onPrepareKey
    public static function keyClassExtends():array
    {
        return [Cell::class,Col::class];
    }
}
?>