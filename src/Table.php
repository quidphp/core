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

// table
// extended class to represent an existing table within a database
class Table extends Orm\Table
{
    // trait
    use _routeAttr;
    use _accessAlias;


    // config
    public static $config = [
        'route'=>null, // permet de lier une classe de route à la table
        'permission'=>array(
            'admin'=>array(
                'insert'=>true,
                'update'=>true,
                'delete'=>true,
                'create'=>true,
                'alter'=>true,
                'truncate'=>true,
                'drop'=>true,
                'nullPlaceholder'=>true),
            'cli'=>array(
                'insert'=>true,
                'update'=>true,
                'delete'=>true,
                'create'=>true,
                'alter'=>true,
                'truncate'=>true,
                'drop'=>true)),
        '@prod'=>[
            'colsExists'=>false]
    ];


    // tableFromFqcn
    // retourne l'objet table à partir du fqcn de la classe
    // utilise boot
    // envoie une erreur si la table n'existe pas
    public static function tableFromFqcn():self
    {
        $return = (static::class !== self::class)? static::boot()->db()->table(static::class):null;

        if(!$return instanceof self)
        static::throw();

        return $return;
    }


    // getOverloadKeyPrepend
    // retourne le prepend de la clé à utiliser pour le tableau overload
    public static function getOverloadKeyPrepend():?string
    {
        return (static::class !== self::class && !Base\Fqcn::sameName(static::class,self::class))? 'Table':null;
    }
}

// init
Table::__init();
?>