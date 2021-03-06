<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\File;
use Quid\Core;
use Quid\Main;

// js
// class for a js file
class Js extends Main\File\Js
{
    // config
    protected static array $config = [
        'concatenateService'=>Core\Service\JShrink::class
    ];


    // getConcatenatorOption
    // retourne les options pour le concatenateur
    protected function getConcatenatorOption(array $values,array $option):?array
    {
        $return = parent::getConcatenatorOption($values,$option);

        if(array_key_exists('compress',$option) && $option['compress'] === true)
        $return['callable'] = fn(string $value) => $this->concatenateService()::staticTrigger($value,$option);

        return $return;
    }
}

// init
Js::__init();
?>