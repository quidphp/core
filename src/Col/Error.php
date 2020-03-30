<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 * Readme: https://github.com/quidphp/core/blob/master/README.md
 */

namespace Quid\Core\Col;
use Quid\Base;
use Quid\Core;

// error
// class for a column that manages an error object
class Error extends Core\ColAlias
{
    // config
    public static $config = [
        'general'=>true,
        'editable'=>false,
        'complex'=>'div',
        'onComplex'=>true,
        'check'=>['kind'=>'text']
    ];


    // onGet
    // sur onGet recrée l'objet error si c'est du json
    protected function onGet($return,array $option)
    {
        if(!$return instanceof Core\Error)
        {
            $return = $this->value($return);

            if(is_scalar($return))
            {
                $return = Base\Json::decode($return);
                $return = Core\Error::newOverload($return);
            }
        }

        return $return;
    }
}

// init
Error::__init();
?>