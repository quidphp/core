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

// request
// class for a column that manages a request object as a value
class Request extends Core\ColAlias
{
    // config
    public static $config = [
        'general'=>true,
        'editable'=>false,
        'complex'=>'div',
        'onComplex'=>true,
        'required'=>true,
        'check'=>['kind'=>'text']
    ];


    // onInsert
    // retourner la requête en json sur insertion
    public function onInsert($value,array $row,array $option):?string
    {
        $return = null;
        $boot = static::bootReady();

        if(!empty($boot))
        $return = $boot->request()->toJson();

        return $return;
    }


    // onGet
    // sur onGet recrée l'objet request
    public function onGet($return,array $option)
    {
        if(!$return instanceof Core\Request)
        {
            $return = $this->value($return);

            if(is_scalar($return))
            {
                $return = Base\Json::decode($return);
                $return = Core\Request::newOverload($return);
            }
        }

        return $return;
    }
}

// init
Request::__init();
?>