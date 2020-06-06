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
use Quid\Orm;

// error
// class for a column that manages an error object
class Error extends Core\ColAlias
{
    // config
    protected static array $config = [
        'general'=>true,
        'editable'=>false,
        'complex'=>'div',
        'onComplex'=>true,
        'check'=>['kind'=>'text']
    ];


    // onGet
    // sur onGet recrée l'objet error si c'est du json
    protected function onGet($return,?Orm\Cell $cell=null,array $option)
    {
        if(is_scalar($return))
        {
            $json = Base\Json::decode($return);

            if(!empty($json))
            $return = Core\Error::newOverload($json);
        }

        return $return;
    }
}

// init
Error::__init();
?>