<?php
declare(strict_types=1);
namespace Quid\Core\Col;
use Quid\Base\Html;
use Quid\Core;
use Quid\Orm;
use Quid\Main;
use Quid\Base;

// video
abstract class Video extends Core\ColAlias
{
	// config
	public static $config = [
		'tag'=>'inputText',
		'search'=>false,
		'onGet'=>[Base\Json::class,'onGet'],
		'preValidate'=>['uriAbsolute'],
		'check'=>['kind'=>'text'],
		'excerpt'=>500, // custom, longueur maximale de la description
		'service'=>null // clé du service utilisé, à spécifier
	];
	
	
	// getService
	// retourne le service à utiliser
	public function getService():Main\Service 
	{
		return $this->service(static::$config['service']);
	}
	
	
	// showDetailsMaxLength
	// n'affiche pas le détail sur le maxLength de la colonne
	public function showDetailsMaxLength():bool 
	{
		return false;
	}
	
	
	// onGet
	// sur onGet retourne l'objet video s'il y a une valeur
	public function onGet($return,array $option) 
	{
		if(!$return instanceof Main\Video)
		{
			$return = $this->value($return);
			
			if(is_string($return) && Base\Json::is($return))
			{
				$service = $this->getService();
				$return = $service::makeVideo($return);
			}
		}
		
		if(!empty($option['context']) && $option['context'] === 'cms:general' && $return instanceof Main\Video)
		$return = Base\Html::a($return->absolute(),true);

		return $return;
	}
	
	
	// onSet
	// gère la logique onSet pour la vidéo
	public function onSet($return,array $row,?Orm\Cell $cell=null,array $option) 
	{
		$hasChanged = true;
		
		if(!empty($cell))
		{
			$video = $cell->get();
			
			if($video instanceof Main\Video && $video->absolute() === $return)
			{
				$return = $video;
				$hasChanged = false;
			}
		}
		
		if(!empty($return) && $hasChanged === true)
		{
			$service = $this->getService();
			$return = $service->query($return);
		}
		
		return $return;
	}
	
	
	// formComplex
	// génère le formComplex pour la vidéo
	// le html du video sera ajouté au-dessus du champ
	public function formComplex($value=true,?array $attr=null,?array $option=null):string
	{
		$return = '';
		$value = $this->value($value);
		$value = $this->onGet($value,(array) $option);
		
		if($value instanceof Main\Video)
		{
			$return .= $this->html($value);
			$value = $value->absolute();
			$return .= parent::formComplex($value,$attr,$option);
		}
		
		else
		$return .= parent::formComplex($value,$attr,$option);
		
		return $return;
	}
	
	
	// html
	// fait le html video à d'un objet video
	public function html(Main\Video $value):?string
	{
		$return = '';
		$excerpt = static::$config['excerpt'];
		$date = $value->date(0);
		$excerpt = $value->description($excerpt);
		$html = $value->html();
		$content = '';
		
		if(!empty($date))
		$content = Html::span($date);
		
		if(!empty($excerpt))
		{
			if(strlen($content))
			$content .= ' - ';
			
			$content .= Html::span($excerpt);
		}
		
		$return .= Html::div($html,'html');
		$return .= Html::divCond($content,'content');
		
		return $return;
	}
}

// config
Video::__config();
?>