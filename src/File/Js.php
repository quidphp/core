<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 * Readme: https://github.com/quidphp/core/blob/master/README.md
 */

namespace Quid\Core\File;
use Quid\Core;
use Quid\Main;

// js
// class for a js file
class Js extends Main\File\Js
{
    // config
    public static $config = [
        'service'=>Core\Service\JShrink::class
    ];


    // getConcatenatorOption
    // retourne les options pour le concatenateur
    protected function getConcatenatorOption(array $values,array $option):?array
    {
        $return = parent::getConcatenatorOption($values,$option);

        if(array_key_exists('compress',$option) && $option['compress'] === true)
        {
            $return['callable'] = function(string $value) use($option) {
                $service = $this->getServiceClass();
                return $service::staticTrigger($value,$option);
            };
        }

        return $return;
    }
}

// init
Js::__init();
?>