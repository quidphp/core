<?php
declare(strict_types=1);
namespace Quid\Core;
use Quid\Orm;

// db
class Db extends Orm\Db
{
	// trait
	use _bootAccess;
	
	
	// config
	public static $config = array(
		'option'=>array( // tableau d'options
			'logClass'=>array( // classe à utiliser pour logger ces différents types de requêtes
				'select'=>null,
				'show'=>null,
				'insert'=>Row\LogSql::class,
				'update'=>Row\LogSql::class,
				'delete'=>Row\LogSql::class,
				'create'=>Row\LogSql::class,
				'alter'=>Row\LogSql::class,
				'truncate'=>Row\LogSql::class,
				'drop'=>Row\LogSql::class),
			'classe'=>array( // option pour l'objet classe
				'default'=>array( // classe par défaut
					'table'=>Table::class,
					'col'=>Col::class,
					'row'=>Row::class,
					'cell'=>Cell::class,
					'cols'=>Cols::class,
					'rows'=>Rows::class,
					'cells'=>Cells::class),
				'colGroup'=>array( // classe pour colonne selon le group
					'primary'=>Col\Primary::class,
					'integer'=>Col\Integer::class,
					'float'=>Col\Floating::class),
				'colAttr'=>array( // classe pour colonne selon un attribut
					'media'=>Col\Media::class,
					'set'=>Col\Set::class,
					'enum'=>Col\Enum::class)),
			'tables'=>array(), // paramètre par défaut pour les tables
			'cols'=>array( // paramètre par défaut pour les colonnes
				'active'=>array('class'=>Col\Active::class,'general'=>true),
				'featured'=>array('class'=>Col\Yes::class,'general'=>true),
				'menu'=>array('class'=>Col\Yes::class,'general'=>true),
				'code'=>array('required'=>true,'general'=>true),
				'excerpt_en'=>array('class'=>Col\Excerpt::class),
				'excerpt_fr'=>array('class'=>Col\Excerpt::class),
				'content_en'=>array('class'=>Col\Textarea::class),
				'content_fr'=>array('class'=>Col\Textarea::class),
				'content'=>array('class'=>Col\Textarea::class),
				'date'=>array('class'=>Col\Date::class,'required'=>true),
				'dateAdd'=>array('class'=>Col\DateAdd::class),
				'dateEnd'=>array('class'=>Col\Date::class,'compare'=>array('>=','dateStart')),
				'dateLogin'=>array('class'=>Col\DateLogin::class),
				'dateModify'=>array('class'=>Col\DateModify::class),
				'dateStart'=>array('class'=>Col\Date::class,'compare'=>array('<='=>'dateEnd')),
				'datetime'=>array('class'=>Col\Date::class,'required'=>true,'date'=>'dateToMinute'),
				'datetimeEnd'=>array('class'=>Col\Date::class,'date'=>'dateToMinute','compare'=>array('>='=>'datetimeStart')),
				'datetimeStart'=>array('class'=>Col\Date::class,'date'=>'dateToMinute','required'=>true,'default'=>true,'compare'=>array('<='=>'datetimeEnd')),
				'email'=>array('class'=>Col\Email::class),
				'enum'=>array('class'=>Col\Enum::class),
				'fax'=>array('search'=>false),
				'firstName'=>array('required'=>true),
				'fullName'=>array('general'=>true),
				'json_en'=>array('class'=>Col\Json::class),
				'json_fr'=>array('class'=>Col\Json::class),
				'json'=>array('class'=>Col\Json::class),
				'icon'=>array('class'=>Col\Media::class,'extension'=>array('png','svg')),
				'icons'=>array('class'=>Col\Medias::class,'extension'=>array('png','svg')),
				'key_en'=>array('required'=>true),
				'key_fr'=>array('required'=>true),
				'key'=>array('general'=>true,'required'=>true),
				'lastName'=>array('required'=>true),
				'media'=>array('class'=>Col\Media::class),
				'media_fr'=>array('class'=>Col\Media::class,'panel'=>'fr'),
				'media_en'=>array('class'=>Col\Media::class,'panel'=>'en'),
				'medias'=>array('class'=>Col\Medias::class),
				'media_id'=>array('excerpt'=>null),
				'media_ids'=>array('excerpt'=>null),
				'metaImage_fr'=>array('class'=>Col\Media::class,'panel'=>'fr','general'=>false,'version'=>array('large'=>array(80,'jpg','crop',1200,630)),'extension'=>array('jpg','png')),
				'metaImage_en'=>array('class'=>Col\Media::class,'panel'=>'en','general'=>false,'version'=>array('large'=>array(80,'jpg','crop',1200,630)),'extension'=>array('jpg','png')),
				'metaSearch_fr'=>array('class'=>Col\Auto::class),
				'metaSearch_en'=>array('class'=>Col\Auto::class),
				'name_en'=>array('general'=>true,'required'=>true),
				'name_fr'=>array('general'=>true,'required'=>true),
				'name'=>array('general'=>true,'required'=>true),
				'order'=>array('class'=>Col\Enum::class,'complex'=>'select','general'=>true,'order'=>true,'relation'=>20),
				'phone'=>array('class'=>Col\Phone::class,'search'=>false),
				'session_id'=>array('class'=>Col\Session::class),
				'set'=>array('class'=>Col\Set::class),
				'slug_en'=>array('class'=>Col\Slug::class),
				'slug_fr'=>array('class'=>Col\Slug::class),
				'slugPath_en'=>array('class'=>Col\SlugPath::class),
				'slugPath_fr'=>array('class'=>Col\SlugPath::class),
				'fragment_en'=>array('class'=>Col\Fragment::class),
				'fragment_fr'=>array('class'=>Col\Fragment::class),
				'slug'=>array('class'=>Col\Slug::class),
				'status'=>array('general'=>true,'required'=>true),
				'storage'=>array('class'=>Col\Media::class,'path'=>'[storagePrivate]/storage','extension'=>'pdf'),
				'storage_fr'=>array('class'=>Col\Media::class,'panel'=>'fr','path'=>'[storagePrivate]/storage','extension'=>'pdf'),
				'storage_en'=>array('class'=>Col\Media::class,'panel'=>'en','path'=>'[storagePrivate]/storage','extension'=>'pdf'),
				'storages'=>array('class'=>Col\Medias::class,'path'=>'[storagePrivate]/storage','extension'=>'pdf'),
				'timezone'=>array('class'=>Col\Timezone::class),
				'type'=>array('general'=>true,'required'=>true),
				'userAdd'=>array('class'=>Col\UserAdd::class),
				'userCommit'=>array('class'=>Col\UserCommit::class),
				'userModify'=>array('class'=>Col\UserModify::class),
				'username'=>array('class'=>Col\Username::class),
				'visible'=>array('class'=>Col\Yes::class,'general'=>false),
				'background'=>array('class'=>Col\Media::class,'extension'=>'jpg'),
				'video'=>array('class'=>Col\Media::class,'extension'=>'mp4'),
				'thumbnail'=>array('class'=>Col\Media::class,'general'=>true,'extension'=>array('jpg','png')),
				'pointer'=>array('class'=>Col\Pointer::class)))
	);
}

// config
Db::__config();
?>