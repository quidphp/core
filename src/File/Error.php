<?php
declare(strict_types=1);
namespace Quid\Core\File;
use Quid\Main;

// error
class Error extends DumpAlias implements Main\Contract\Log, Main\Contract\FileStorage
{
	// trait
	use Main\File\_log;
	
	
	// config
	public static $config = array(
		'dirname'=>'[storage]/error',
		'deleteTrim'=>500
	);
	
	
	// isStorageDataValid
	// retourne vrai si les datas fournis sont valides pour logError
	public static function isStorageDataValid(...$values):bool 
	{
		return (!empty($values) && $values[0] instanceof Main\Error)? true:false;
	}
	
	
	// storageData
	// retourne les données à mettre dans le fichier logError
	public static function storageData(...$values)
	{
		return (!empty($values[0]))? $values[0]->toArray():array();
	}
	
	
	// storageFilename
	// retourne le filename de l'error, par défaut utilise le id de l'erreur
	public static function storageFilename(...$values):string 
	{
		return (static::isStorageDataValid(...$values))? $values[0]->id(static::$config['inc']):null;
	}
}

// config
Error::__config();
?>