<?php
declare(strict_types=1);
namespace Quid\Core\Row;
use Quid\Core;
use Quid\Main;

// email
class Email extends Core\RowAlias implements Main\Contract\Email
{
	// trait
	use Main\_email;
	
		
	// config
	public static $config = array(
		'panel'=>false,
		'priority'=>951,
		'parent'=>'system',
		'cols'=>array(
			'active'=>true,
			'type'=>array('relation'=>'emailType'),
			'key'=>true,
			'name_fr'=>false,
			'name_en'=>false,
			'content_fr'=>array('class'=>Core\Col\Textarea::class,'exists'=>false),
			'content_en'=>array('class'=>Core\Col\Textarea::class,'exists'=>false))
	);
	
	
	// contentType
	// retourne le type de contenu du email, retourne int
	public function contentType():int
	{
		return $this->cell('type')->get();
	}
	
	
	// subject
	// retourne le sujet du email
	public function subject():string
	{
		return $this->cell('name_[lang]')->get();
	}
	
	
	// body
	// retourne le body du email
	public function body():string
	{
		return $this->cell('content_[lang]')->get();
	}
	
	
	// find
	// retourne un objet email, à partir d'une clé
	public static function find(string $key):?Self 
	{
		return static::tableFromFqcn()->rowVisible($key);
	}
}

// config
Email::__config();
?>