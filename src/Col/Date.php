<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Col;
use Quid\Base;
use Quid\Core;

// date
// class for a date column, supports many date formats
class Date extends Core\ColAlias
{
    // config
    public static $config = [
        'cell'=>Core\Cell\Date::class,
        'tag'=>'inputText',
        'filter'=>true,
        'filterMethod'=>[self::class,'autoFilterMethod'],
        'date'=>'dateToDay',
        'pattern'=>false,
        'check'=>['kind'=>'int'],
        'calendarFormat'=>'dateToDay', // custom
        'filterFormat'=>[ // format, méthodes et maximum pour le filter
            'day'=>['format'=>0,'filter'=>'or|day','method'=>'days','max'=>100],
            'month'=>['format'=>2,'filter'=>'or|month','method'=>'months','max'=>1500],
            'year'=>['format'=>'Y','filter'=>'or|year','method'=>'years']],
        'formats'=>[true,'dateToDay','dateToMinute','dateToSecond'],
        'route'=>['calendar'=>null] // route à ajouter
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

        $allowedFormats = $return['formats'] ?? [];

        if(empty($return['onGet']))
        $return['onGet'] = [[Base\Date::class,'onGet'],$format];

        if(empty($return['onSet']))
        $return['onSet'] = [[Base\Date::class,'onSet'],$format];

        if($return['preValidate'] !== false && in_array($format,$allowedFormats,true))
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

        if(!in_array($format,$this->allowedFormats(),true))
        static::throw('invalidDateFormat',$format);

        $placeholder = Base\Date::placeholder($format);
        if(empty($placeholder))
        static::throw('noDatePlaceholderFor',$format);

        return $this;
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


    // dateDaysDiff
    // retourne la différence de jour entre la date minimum et maximum
    public function dateDaysDiff():?int
    {
        $return = null;
        $min = $this->dateMin();
        $max = $this->dateMax();

        if(is_int($min) && is_int($max))
        $return = Base\Date::daysDiff($min,$max);

        return $return;
    }


    // dateDaysDiffFilterMethod
    // retourne la méthode de filtre à utiliser selon la différence de jours
    public function dateDaysDiffFilterMethod():string
    {
        $return = null;
        $diff = $this->dateDaysDiff() ?? 0;
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
    public function date(bool $make=false)
    {
        $return = $this->getAttr('date');

        if($make === true)
        $return = static::makeDateFormat($return);

        return $return;
    }


    // daysMonthsIn
    // méthode protégé utilisé par daysIn et monthsIn
    protected function daysMonthsIn(string $key):array
    {
        $return = [];
        $filterFormat = $this->getAttr('filterFormat');

        if(array_key_exists($key,$filterFormat) && is_array($filterFormat[$key]))
        {
            $method = $filterFormat[$key]['method'] ?? null;
            $format = $filterFormat[$key]['format'] ?? null;

            if(is_string($method) && $format !== null)
            {
                $min = $this->dateMin();
                $max = $this->dateMax();

                if(is_int($min) && is_int($max))
                $return = Base\Date::$method($max,$min,1,$format);
            }

            else
            static::throw($filterFormat[$key]);
        }

        return $return;
    }


    // daysIn
    // retourne un tableau de tous les jours compris entre la date minimale et maximale de la colonne
    public function daysIn():array
    {
        return $this->daysMonthsIn('day');
    }


    // monthsIn
    // retourne un tableau de tous les mois compris entre la date minimale et maximale de la colonne
    public function monthsIn():array
    {
        return $this->daysMonthsIn('month');
    }


    // yearsIn
    // retourne un tableau de toutes les années compris entre la date minimale et maximale de la colonne
    public function yearsIn():array
    {
        return $this->daysMonthsIn('year');
    }


    // filterMethodDateType
    // retourne le type de date à partir de la méthode filter
    // envoie une exception si null
    public function filterMethodDateType():string
    {
        $return = null;
        $filterMethod = $this->filterMethod();
        $filterFormat = $this->getAttr('filterFormat');

        if(is_string($filterMethod))
        {
            foreach ($filterFormat as $key => $value)
            {
                if(is_array($value) && array_key_exists('filter',$value) && $value['filter'] === $filterMethod)
                {
                    $return = $key;
                    break;
                }
            }
        }

        if($return === null)
        static::throw('invalidFilterMethod',$filterMethod);

        return $return;
    }


    // dateRelation
    // retourne le tableau de la relation de date
    // se base sur le filterMethod de la colonne
    public function dateRelation():array
    {
        return $this->daysMonthsIn($this->filterMethodDateType());
    }


    // allowedFormats
    // retourne les formats de date permis
    public function allowedFormats():array
    {
        return $this->getAttr('formats');
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


    // autoFilterMethod
    // génère une méthode de filtre automatiquement, selon la différence de jour entre la date minimale et maximale
    public static function autoFilterMethod(self $col):string
    {
        return $col->cache(__METHOD__,function() use($col) {
            return $col->dateDaysDiffFilterMethod();
        });
    }
}

// init
Date::__init();
?>