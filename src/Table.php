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
use Quid\Routing;

// table
// extended class to represent an existing table within a database
class Table extends Orm\Table
{
    // trait
    use _accessAlias;
    use Routing\_attrRoute;


    // config
    protected static array $config = [
        'active'=>'active', // colonne(s) utilisé pour déterminer si une ligne est active
        'key'=>['key',0], // colonne(s) utilisé pour key
        'name'=>['name_[lang]','name','id',0], // colonne(s) utilisé pour le nom d'une ligne
        'content'=>['content_[lang]','content'], // colonne(s) utilisé pour le contenu d'une ligne
        'dateCommit'=>['dateAdd'=>'userAdd','dateModify'=>'userModify'], // crée une relation entre un nom de colonne pour la date et un pour le user, le user peut être vide
        'owner'=>['user_id','userAdd','userModify'], // champs qui définissent le ou les propriétaires d'une ligne
        'order'=>['order'=>'asc','date'=>'desc','name_[lang]'=>'asc','key'=>'asc','id'=>'desc'], // ordre et direction à utiliser par défaut, prend la première qui existe
        'route'=>null, // permet de lier une classe de route à la table
        'inRelation'=>true, // active ou non la validation que la valeur des relations sont dans la relation
        'permission'=>[
            '*'=>[
                'download'=>true, // pouvoir télécharger un média
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