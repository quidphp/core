<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core;
use Quid\Orm;
use Quid\Routing;

// table
// extended class to represent an existing table within a database
class Table extends Orm\Table
{
    // trait
    use _accessAlias;
    use Routing\_attrRoute;


    // config
    public static $config = [
        'route'=>null, // permet de lier une classe de route à la table
        'permission'=>[
            '*'=>[
                'view'=>true, // pouvoir voir le contenu de la table
                'mediaDownload'=>true, // pouvoir télécharger un média
                'mediaDelete'=>true, // permettre d'effacer un média
                'mediaRegenerate'=>false], // permettre de regénérer un média
            'admin'=>[
                'insert'=>true,
                'update'=>true,
                'delete'=>true,
                'create'=>true,
                'alter'=>true,
                'truncate'=>true,
                'drop'=>true,
                'mediaRegenerate'=>true,
                'nullPlaceholder'=>true],
            'cli'=>[
                'insert'=>true,
                'update'=>true,
                'delete'=>true,
                'create'=>true,
                'alter'=>true,
                'truncate'=>true,
                'drop'=>true,
                'mediaRegenerate'=>true]],
        '@prod'=>[
            'colsExists'=>false]
    ];


    // tableFromFqcn
    // retourne l'objet table à partir du fqcn de la classe
    // utilise boot
    // envoie une erreur si la table n'existe pas
    final public static function tableFromFqcn():self
    {
        $return = (static::class !== self::class)? static::boot()->db()->table(static::class):null;

        if(!$return instanceof self)
        static::throw();

        return $return;
    }
}

// init
Table::__init();
?>