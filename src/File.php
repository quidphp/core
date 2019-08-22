<?php
declare(strict_types=1);
namespace Quid\Core;
use Quid\Main;
use Quid\Base;

// file
class File extends Main\File
{
	// trait
	use _fullAccess;
	
	
	// config
	public static $config = array(
		'types'=>array( // défini les options à mettre selon le type
			'dump'=>array(
				'write'=>array(
					'callback'=>array(Base\Debug::class,'varGet'))),
			'json'=>array(
				'read'=>array(
					'callback'=>array(Base\Json::class,'decode')),
				'write'=>array(
					'callback'=>array(Base\Json::class,'encodePretty'))),
			'serialize'=>array(
				'read'=>array(
					'callback'=>array(Base\Crypt::class,'unserialize')),
				'write'=>array(
					'callback'=>array(Base\Crypt::class,'serialize')))),
		'storageClass'=>array( // défini les classes storages, un dirname dans celui défini de la classe doit utilisé un objet particulier
			'cache'=>File\Cache::class,
			'error'=>File\Error::class,
			'log'=>File\Log::class,
			'queue'=>File\Queue::class,
			'session'=>File\Session::class),
		'utilClass'=>array( // défini les classes utilités
			'dump'=>File\Dump::class,
			'serialize'=>File\Serialize::class,
			'email'=>File\Email::class),
		'groupClass'=>array( // défini la classe à utiliser selon le mimeGroup du fichier
			'audio'=>File\Audio::class,
			'calendar'=>File\Calendar::class,
			'css'=>File\Css::class,
			'csv'=>File\Csv::class,
			'doc'=>File\Doc::class,
			'font'=>File\Font::class,
			'html'=>File\Html::class,
			'imageRaster'=>File\ImageRaster::class,
			'imageVector'=>File\ImageVector::class,
			'js'=>File\Js::class,
			'json'=>File\Json::class,
			'pdf'=>File\Pdf::class,
			'php'=>File\Php::class,
			'txt'=>File\Txt::class,
			'video'=>File\Video::class,
			'xml'=>File\Xml::class,
			'zip'=>File\Zip::class)
	);
	
	
	// getOverloadKeyPrepend
	// retourne le prepend de la clé à utiliser pour le tableau overload
	public static function getOverloadKeyPrepend():?string 
	{
		return (static::class !== self::class && !Base\Fqcn::sameName(static::class,self::class))? 'File':null;
	}
}

// config
File::__config();
?>