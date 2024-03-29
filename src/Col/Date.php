<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Col;
use Quid\Base;
use Quid\Core;
use Quid\Orm;

// date
// class for a date column, supports many date formats
class Date extends Core\ColAlias
{
    // config
    protected static array $config = [
        'cell'=>Core\Cell\Date::class,
        'group'=>'date',
        'tag'=>'inputText',
        'filter'=>true,
        'filterMethod'=>[self::class,'autoFilterMethod'],
        'date'=>'dateToDay',
        'pattern'=>false,
        'keyboard'=>'numeric',
        'check'=>['kind'=>'int'],
        'detailMaxLength'=>false,
        'filterFormat'=>[ // format, méthodes et maximum pour le filter
            'day'=>['format'=>0,'filter'=>'or|day','method'=>'days','max'=>100],
            'month'=>['format'=>2,'filter'=>'or|month','method'=>'months','max'=>1500],
            'year'=>['format'=>'Y','filter'=>'or|year','method'=>'years']],
        'formats'=>[true,'dateToDay','dateToMinute','dateToSecond','sql'],
        'route'=>['calendar'=>null] // route à ajouter
    ];


    // onMakeAttr
    // callback lors du set des attr
    // le format spécifié dans config est utilisé comme argument pour les callbacks
    // peut envoyer une exception si le format de date est invalide
    final protected function onAttr(array $return):array
    {
        $format = static::makeDateFormat($return['date'] ?? true);

        if(!is_string($format))
        static::throw($this->table(),$this->name(),'invalidDateFormat');

        $allowedFormats = $return['formats'] ?? [];

        if($return['preValidate'] !== false && in_array($format,$allowedFormats,true))
        $return['preValidate'] = $format;

        if(array_key_exists('default',$return) && $return['default'] === true)
        $return['default'] = fn() => Base\Datetime::now();

        return $return;
    }


    // isDate
    // retourne vrai comme la colonne est de type date
    final public function isDate():bool
    {
        return true;
    }


    // onGet
    // gère l'affichage de la date onGet
    protected function onGet($return,?Orm\Cell $cell,array $option)
    {
        $format = $this->date(true);

        if(is_int($return) && !empty($format))
        $return = Base\Datetime::format($format,$return);

        return $return;
    }


    // onSet
    // gère l'écriture de la date onSet
    final protected function onSet($return,?Orm\Cell $cell,array $row,array $option)
    {
        $format = $this->date(true);

        if(is_string($return) && !empty($format))
        $return = Base\Datetime::time($return,$format);

        return $return;
    }


    // format
    // format une valeur à partir du format de date de la colonne
    final public function format($value)
    {
        return (is_string($value))? Base\Datetime::time($value,$this->date()):$value;
    }


    // parse
    // retourne un timestamp à partir d'une date formattée
    final public function parse(string $value):?int
    {
        $format = $this->date(true);
        return (!empty($format))? Base\Datetime::time($value,$format):null;
    }


    // checkFormatCalendar
    // envoie une exception si le format n'est pas compatible avec un calendrier
    // envoie aussi une exception ai aucun placeholder pour le format
    final public function checkFormatCalendar():self
    {
        $format = $this->date();

        if(!in_array($format,$this->allowedFormats(),true))
        static::throw('invalidDateFormat',$format);

        $placeholder = Base\Datetime::placeholder($format);
        if(empty($placeholder))
        static::throw('noDatePlaceholderFor',$format);

        return $this;
    }


    // dateMin
    // retourne la date la plus petite de la colonne dans la table
    final public function dateMin(bool $cache=true):?int
    {
        return $this->cache(__METHOD__,function() {
            return $this->db()->selectColumn($this,$this->table(),[[$this->name(),true]],[$this->name()=>'asc'],1);
        },$cache);
    }


    // dateMax
    // retourne la date la plus grande de la colonne dans la table
    final public function dateMax(bool $cache=true):?int
    {
        return $this->cache(__METHOD__,function() {
            return $this->db()->selectColumn($this,$this->table(),[[$this->name(),true]],[$this->name()=>'desc'],1);
        },$cache);
    }


    // dateDaysDiff
    // retourne la différence de jour entre la date minimum et maximum
    final public function dateDaysDiff(bool $cache=true):?int
    {
        $min = $this->dateMin($cache);
        $max = $this->dateMax($cache);

        return (is_int($min) && is_int($max))? Base\Datetime::daysDiff($min,$max):null;
    }


    // dateDaysDiffFilterMethod
    // retourne la méthode de filtre à utiliser selon la différence de jours
    final public function dateDaysDiffFilterMethod(bool $cache=true):string
    {
        $return = null;
        $diff = $this->dateDaysDiff($cache) ?? 0;
        $filterFormat = $this->getAttr('filterFormat');

        foreach ($filterFormat as $array)
        {
            $method = $array['filter'] ?? null;
            $max = $array['max'] ?? null;

            if($max === null || $diff <= $max)
            {
                $return = $method;
                break;
            }
        }

        return $return;
    }


    // date
    // retourne le format de la date si disponible
    final public function date(bool $make=false)
    {
        $return = $this->getAttr('date');
        return ($make === true)? static::makeDateFormat($return):$return;
    }


    // daysMonthsIn
    // méthode protégé utilisé par daysIn et monthsIn
    final protected function daysMonthsIn(string $key):array
    {
        $return = [];
        $filterFormat = $this->getAttr('filterFormat');

        if(array_key_exists($key,$filterFormat) && is_array($filterFormat[$key]))
        {
            $method = $filterFormat[$key]['method'] ?? null;
            $format = $filterFormat[$key]['format'] ?? null;

            if(!is_string($method) || $format === null)
            static::throw($filterFormat[$key]);

            $min = $this->dateMin();
            $max = $this->dateMax();

            if(is_int($min) && is_int($max))
            $return = Base\Datetime::$method($max,$min,1,$format);
        }

        return $return;
    }


    // daysIn
    // retourne un tableau de tous les jours compris entre la date minimale et maximale de la colonne
    final public function daysIn():array
    {
        return $this->daysMonthsIn('day');
    }


    // monthsIn
    // retourne un tableau de tous les mois compris entre la date minimale et maximale de la colonne
    final public function monthsIn():array
    {
        return $this->daysMonthsIn('month');
    }


    // yearsIn
    // retourne un tableau de toutes les années compris entre la date minimale et maximale de la colonne
    final public function yearsIn():array
    {
        return $this->daysMonthsIn('year');
    }


    // filterMethodDateType
    // retourne le type de date à partir de la méthode filter
    // envoie une exception si null
    final public function filterMethodDateType():string
    {
        $return = null;
        $filterMethod = $this->filterMethod();
        $filterFormat = $this->getAttr('filterFormat');

        if(is_string($filterMethod))
        {
            $closure = fn($value) => (is_array($value) && array_key_exists('filter',$value) && $value['filter'] === $filterMethod);
            $return = Base\Arr::findKey($filterFormat,$closure);
        }

        return $return ?? static::throw('invalidFilterMethod',$filterMethod);
    }


    // dateRelation
    // retourne le tableau de la relation de date
    // se base sur le filterMethod de la colonne
    final public function dateRelation():array
    {
        return $this->daysMonthsIn($this->filterMethodDateType());
    }


    // allowedFormats
    // retourne les formats de date permis
    final public function allowedFormats():array
    {
        return $this->getAttr('formats');
    }


    // makeDateFormat
    // retourne le format de date en string, gère la valeur est un true (donc par défaut)
    final public static function makeDateFormat($value):?string
    {
        if($value === true)
        {
            $lang = static::lang()->currentLang();
            $value = Base\Datetime::getFormat($value,$lang);
        }

        return (is_string($value))? $value:null;
    }


    // autoFilterMethod
    // génère une méthode de filtre automatiquement, selon la différence de jour entre la date minimale et maximale
    final public static function autoFilterMethod(self $col):string
    {
        return $col->cache(__METHOD__,fn() => $col->dateDaysDiffFilterMethod());
    }
}

// init
Date::__init();
?>