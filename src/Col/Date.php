<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Col;
use Quid\Core;
use Quid\Base;

// date
// class for a date column, supports many date formats
class Date extends Core\ColAlias
{
	// config
	public static $config = [
		'cell'=>Core\Cell\Date::class,
		'tag'=>'inputText',
		'filter'=>true,
		'filterMethod'=>'or|month',
		'date'=>'dateToDay',
		'pattern'=>false,
		'onComplex'=>true,
		'check'=>['kind'=>'int'],
		'calendarFormat'=>'dateToDay', // custom
		'formats'=>[true,'dateToDay','dateToMinute','dateToSecond'],
		'route'=>array('calendar'=>null) // route à ajouter
	];


	// onMakeAttr
	// callback lors du set des attr
	// le format spécifié dans config est utilisé comme argument pour les callbacks
	// peut envoyer une exception si le format de date est invalide
	protected function onMakeAttr(array $return):array
	{
		$format = static::makeDateFormat($return['date'] ?? true);

		if(!is_string($format))
		static::throw($this->table(),$this->name(),'invalidDateFormat');

		$allowedFormats = static::allowedFormats();

		if(empty($return['onGet']))
		$return['onGet'] = [[Base\Date::class,'onGet'],$format];

		if(empty($return['onSet']))
		$return['onSet'] = [[Base\Date::class,'onSet'],$format];

		if(!array_key_exists('preValidate',$return) && in_array($format,$allowedFormats,true))
		$return['preValidate'] = $format;

		if(array_key_exists('default',$return) && $return['default'] === true)
		$return['default'] = Base\Date::timestamp();

		return $return;
	}


	// showDetailsMaxLength
	// n'affiche pas le détail sur le maxLength de la colonne
	public function showDetailsMaxLength():bool
	{
		return false;
	}


	// format
	// format une valeur à partir du format de date de la colonne
	public function format($value)
	{
		return Base\Date::onSet($value,$this->date());
	}


	// checkFormatCalendar
	// envoie une exception si le format n'est pas compatible avec un calendrier
	// envoie aussi une exception ai aucun placeholder pour le format
	public function checkFormatCalendar():self
	{
		$format = $this->date();

		if(!in_array($format,static::allowedFormats(),true))
		static::throw('invalidDateFormat',$format);

		$placeholder = Base\Date::placeholder($format);
		if(empty($placeholder))
		static::throw('noDatePlaceholderFor',$format);

		return $this;
	}


	// formComplex
	// génère le formulaire complex pour date
	// un petit calendrier apparaît en popup
	public function formComplex($value=true,?array $attr=null,?array $option=null):string
	{
		$return = '';
		$tag = $this->complexTag($attr);

		if(Base\Html::isFormTag($tag,true))
		{
			$this->checkFormatCalendar();
			$value = $this->valueComplex($value);
			$format = $this->date(true);
			$placeholder = Base\Date::placeholder($format);
			$timestamp = Base\Date::timestamp();

			if(is_int($value))
			$timestamp = $value;

			elseif(is_string($value))
			{
				$v = Base\Date::time($value,$format);
				if(is_int($v))
				$timestamp = $v;
			}

			$route = static::route('calendar',['timestamp'=>true,'format'=>$format]);

			$formatCalendar = strtolower(Base\Date::placeholder(static::$config['calendarFormat']));
			$placeholderMaxLength = strlen($placeholder);
			$attr = Base\Attr::append($attr,['placeholder'=>$placeholder,'maxlength'=>$placeholderMaxLength]);
			$return .= $this->form($value,$attr,$option);
			$return .= Base\Html::divOp('popup');
			$data = ['char'=>$route::getReplaceSegment(),'format'=>$formatCalendar,'current'=>$timestamp,'href'=>$route];
			$return .= Base\Html::div(null,['calendar','data'=>$data]);
			$return .= Base\Html::divCl();
		}

		else
		$return .= parent::formComplex($value,$attr,$option);

		return $return;
	}


	// dateMin
	// retourne la date la plus petite de la colonne dans la table
	public function dateMin():?int
	{
		return $this->db()->selectColumn($this,$this->table(),[[$this->name(),true]],[$this->name()=>'asc'],1);
	}


	// dateMax
	// retourne la date la plus grande de la colonne dans la table
	public function dateMax():?int
	{
		return $this->db()->selectColumn($this,$this->table(),[[$this->name(),true]],[$this->name()=>'desc'],1);
	}


	// date
	// retourne le format de la date si disponible
	public function date(bool $make=false)
	{
		$return = $this->attr('date');

		if($make === true)
		$return = static::makeDateFormat($return);

		return $return;
	}


	// makeDateFormat
	// retourne le format de date en string, gère la valeur est un true (donc par défaut)
	public static function makeDateFormat($value):?string
	{
		$return = null;

		if($value === true)
		{
			$lang = static::lang()->currentLang();
			$value = Base\Date::getFormat($value,$lang);
		}

		if(is_string($value))
		$return = $value;

		return $return;
	}


	// allowedFormats
	// retourne les formats de date permis
	public static function allowedFormats():array
	{
		return static::$config['formats'];
	}
}

// config
Date::__config();
?>