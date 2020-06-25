<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core;
use Quid\Orm;

// db
// extended class used to query the database
class Db extends Orm\Db
{
    // trait
    use _bootAccess;


    // config
    protected static array $config = [
        'logClass'=>[ // classe à utiliser pour logger ces différents types de requêtes
            'select'=>null,
            'show'=>null,
            'insert'=>Row\LogSql::class,
            'update'=>Row\LogSql::class,
            'delete'=>Row\LogSql::class,
            'create'=>Row\LogSql::class,
            'alter'=>Row\LogSql::class,
            'truncate'=>Row\LogSql::class,
            'drop'=>Row\LogSql::class],
        'classe'=>[ // option pour l'objet classe
            'default'=>[ // classe par défaut
                'table'=>Table::class,
                'col'=>Col::class,
                'row'=>Row::class,
                'cell'=>Cell::class,
                'cols'=>Cols::class,
                'rows'=>Rows::class,
                'cells'=>Cells::class],
            'colKind'=>[ // classe pour colonne selon le kind
                'int'=>Col\Integer::class,
                'float'=>Col\Floating::class],
            'colAttr'=>[ // classe pour colonne selon un attribut
                'media'=>Col\Media::class,
                'set'=>Col\Set::class,
                'enum'=>Col\Enum::class]],
        'tables'=>[], // paramètre par défaut pour les tables
        'cols'=>[ // paramètre par défaut pour les colonnes
            'id'=>['class'=>Col\Primary::class],
            'enum'=>['class'=>Col\Enum::class],
            'set'=>['class'=>Col\Set::class],
            'active'=>['class'=>Col\Active::class,'general'=>true],
            'dateAdd'=>['class'=>Col\DateAdd::class],
            'dateLogin'=>['class'=>Col\DateLogin::class],
            'dateModify'=>['class'=>Col\DateModify::class],
            'email'=>['class'=>Col\Email::class],
            'json'=>['class'=>Col\Json::class],
            'name'=>['general'=>true,'required'=>true],
            'session_id'=>['class'=>Col\Session::class],
            'timezone'=>['class'=>Col\Timezone::class],
            'type'=>['general'=>true,'required'=>true],
            'key'=>['general'=>true,'required'=>true],
            'status'=>['general'=>true,'required'=>true],
            'userAdd'=>['class'=>Col\UserAdd::class],
            'userModify'=>['class'=>Col\UserModify::class],
            'userCommit'=>['class'=>Col\UserCommit::class],
            'username'=>['class'=>Col\Username::class],
            'content'=>['tag'=>'textarea'],
            'media'=>['class'=>Col\Media::class],
            'medias'=>['class'=>Col\Medias::class],
            'storage'=>['class'=>Col\Media::class,'path'=>'[storagePrivate]','extension'=>'pdf'],
            'storages'=>['class'=>Col\Medias::class,'path'=>'[storagePrivate]','extension'=>'pdf'],
            'code'=>['required'=>true,'general'=>true],
            'date'=>['class'=>Col\Date::class,'required'=>true],
            'dateEnd'=>['class'=>Col\Date::class,'compare'=>['>=','dateStart']],
            'dateStart'=>['class'=>Col\Date::class,'compare'=>['<='=>'dateEnd']],
            'datetime'=>['class'=>Col\Date::class,'required'=>true,'date'=>'dateToMinute'],
            'datetimeEnd'=>['class'=>Col\Date::class,'date'=>'dateToMinute','compare'=>['>='=>'datetimeStart']],
            'datetimeStart'=>['class'=>Col\Date::class,'date'=>'dateToMinute','required'=>true,'default'=>true,'compare'=>['<='=>'datetimeEnd']],
            'website'=>['class'=>Col\UriAbsolute::class],
            'pointer'=>['class'=>Col\Pointer::class]]
    ];
}

// init
Db::__init();
?>