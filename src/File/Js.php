<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\File;
use Quid\Base;
use Quid\Core;
use Quid\Main;

// js
// class for a js file
class Js extends TextAlias
{
    // config
    public static $config = [
        'group'=>'js',
        'service'=>Core\Service\JShrink::class
    ];


    // concatenateFrom
    // écrit dans le fichier js le contenu d'un ou plusieurs dossiers contenant du javascript
    // utilise la classe main/concatenator
    public function concatenateFrom($values,?array $option=null):self
    {
        $option = Base\Arr::plus(['extension'=>$this->extension(),'separator'=>PHP_EOL.PHP_EOL,'compress'=>true],$option);

        $concatenatorOption = [];
        if($option['compress'] === true)
        $concatenatorOption['callable'] = [static::getServiceClass(),'staticTrigger'];

        $concatenator = Main\Concatenator::newOverload($concatenatorOption);

        if(!is_array($values))
        $values = (array) $values;
        ksort($values);

        foreach ($values as $value)
        {
            if(!empty($value))
            $concatenator->add($value,$option);
        }

        $concatenator->triggerWrite($this);

        return $this;
    }


    // getServiceClass
    // retourne la classe du service
    public static function getServiceClass():string
    {
        return static::$config['service']::getOverloadClass();
    }


    // concatenate
    // permet de concatener un ou plusieurs dossiers avec fichiers js
    // possible aussi de minifier
    public static function concatenateMany(array $value,?array $option=null):Core\Files
    {
        $return = Core\Files::newOverload();

        foreach ($value as $to => $from)
        {
            if(is_string($to) && !empty($to) && !empty($from))
            {
                if(Base\Dir::isOlderThanFrom($to,$from,['visible'=>true,'extension'=>'js']))
                {
                    $to = Core\File::newCreate($to);

                    if($to instanceof self)
                    $to->concatenateFrom($from,$option);

                    $return->add($to);
                }
            }
        }

        return $return;
    }
}

// init
Js::__init();
?>