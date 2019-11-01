<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
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
    public static $config = [
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
            'colGroup'=>[ // classe pour colonne selon le group
                'primary'=>Col\Primary::class,
                'integer'=>Col\Integer::class,
                'float'=>Col\Floating::class],
            'colAttr'=>[ // classe pour colonne selon un attribut
                'media'=>Col\Media::class,
                'set'=>Col\Set::class,
                'enum'=>Col\Enum::class]],
        'tables'=>[], // paramètre par défaut pour les tables
        'cols'=>[ // paramètre par défaut pour les colonnes
            'active'=>['class'=>Col\Active::class,'general'=>true],
            'featured'=>['class'=>Col\Yes::class,'general'=>true],
            'menu'=>['class'=>Col\Yes::class,'general'=>true],
            'code'=>['required'=>true,'general'=>true],
            'date'=>['class'=>Col\Date::class,'required'=>true],
            'dateAdd'=>['class'=>Col\DateAdd::class],
            'dateEnd'=>['class'=>Col\Date::class,'compare'=>['>=','dateStart']],
            'dateLogin'=>['class'=>Col\DateLogin::class],
            'dateModify'=>['class'=>Col\DateModify::class],
            'dateStart'=>['class'=>Col\Date::class,'compare'=>['<='=>'dateEnd']],
            'datetime'=>['class'=>Col\Date::class,'required'=>true,'date'=>'dateToMinute'],
            'datetimeEnd'=>['class'=>Col\Date::class,'date'=>'dateToMinute','compare'=>['>='=>'datetimeStart']],
            'datetimeStart'=>['class'=>Col\Date::class,'date'=>'dateToMinute','required'=>true,'default'=>true,'compare'=>['<='=>'datetimeEnd']],
            'email'=>['class'=>Col\Email::class],
            'enum'=>['class'=>Col\Enum::class],
            'fax'=>['search'=>false],
            'firstName'=>['required'=>true],
            'fullName'=>['general'=>true],
            'json_en'=>['class'=>Col\Json::class],
            'json_fr'=>['class'=>Col\Json::class],
            'json'=>['class'=>Col\Json::class],
            'icon'=>['class'=>Col\Media::class,'extension'=>['png','svg']],
            'icons'=>['class'=>Col\Medias::class,'extension'=>['png','svg']],
            'key_en'=>['required'=>true],
            'key_fr'=>['required'=>true],
            'key'=>['general'=>true,'required'=>true],
            'lastName'=>['required'=>true],
            'media'=>['class'=>Col\Media::class],
            'media_fr'=>['class'=>Col\Media::class,'panel'=>'fr'],
            'media_en'=>['class'=>Col\Media::class,'panel'=>'en'],
            'medias'=>['class'=>Col\Medias::class],
            'media_id'=>['excerpt'=>null],
            'media_ids'=>['excerpt'=>null],
            'metaImage_fr'=>['class'=>Col\Media::class,'panel'=>'fr','general'=>false,'version'=>['large'=>[80,'jpg','crop',1200,630]],'extension'=>['jpg','png']],
            'metaImage_en'=>['class'=>Col\Media::class,'panel'=>'en','general'=>false,'version'=>['large'=>[80,'jpg','crop',1200,630]],'extension'=>['jpg','png']],
            'name_en'=>['general'=>[Col::class,'generalCurrentLang'],'required'=>true],
            'name_fr'=>['general'=>[Col::class,'generalCurrentLang'],'required'=>true],
            'name'=>['general'=>true,'required'=>true],
            'order'=>['class'=>Col\Enum::class,'complex'=>'select','general'=>true,'order'=>true,'relation'=>20],
            'session_id'=>['class'=>Col\Session::class],
            'set'=>['class'=>Col\Set::class],
            'status'=>['general'=>true,'required'=>true],
            'storage'=>['class'=>Col\Media::class,'path'=>'[storagePrivate]','extension'=>'pdf'],
            'storage_fr'=>['class'=>Col\Media::class,'panel'=>'fr','path'=>'[storagePrivate]','extension'=>'pdf'],
            'storage_en'=>['class'=>Col\Media::class,'panel'=>'en','path'=>'[storagePrivate]','extension'=>'pdf'],
            'storages'=>['class'=>Col\Medias::class,'path'=>'[storagePrivate]','extension'=>'pdf'],
            'timezone'=>['class'=>Col\Timezone::class],
            'type'=>['general'=>true,'required'=>true],
            'userAdd'=>['class'=>Col\UserAdd::class],
            'userCommit'=>['class'=>Col\UserCommit::class],
            'userModify'=>['class'=>Col\UserModify::class],
            'username'=>['class'=>Col\Username::class],
            'visible'=>['class'=>Col\Yes::class,'general'=>false],
            'background'=>['class'=>Col\Media::class,'extension'=>'jpg'],
            'video'=>['class'=>Col\Media::class,'extension'=>'mp4'],
            'thumbnail'=>['class'=>Col\Media::class,'general'=>true,'extension'=>['jpg','png']],
            'pointer'=>['class'=>Col\Pointer::class],
            'uri_fr'=>['class'=>Col\Uri::class],
            'uri_en'=>['class'=>Col\Uri::class],
            'website'=>['class'=>Col\UriAbsolute::class]]
    ];
}

// init
Db::__init();
?>