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
use Quid\Orm;

// phone
// class for a column managing phone numbers, automatically formats the value
class Phone extends Core\ColAlias
{
    // config
    public static $config = [
        'tag'=>'inputText',
        'search'=>false,
        'validate'=>[1=>'phone'],
        'onComplex'=>true,
        'check'=>['kind'=>'char'],
        'phone'=>null // custom
    ];


    // onGet
    // ramène le numéro de téléphone dans un format nord-américain
    public function onGet($return,array $option)
    {
        $return = $this->value($return);

        if(!empty($return))
        $return = Base\Number::phoneFormat($return,null,$this->attr('phone'));

        return $return;
    }


    // onSet
    // gère la logique onSet pour un téléphone
    // enlève tous les caractères non numérique
    public function onSet($return,array $row,?Orm\Cell $cell=null,array $option)
    {
        if(is_string($return))
        $return = Base\Str::keepNumber($return);

        return $return;
    }
}

// config
Phone::__config();
?>