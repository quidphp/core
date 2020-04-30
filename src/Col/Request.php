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

// request
// class for a column that manages a request object
class Request extends Core\ColAlias
{
    // config
    protected static array $config = [
        'general'=>true,
        'editable'=>false,
        'complex'=>'div',
        'onComplex'=>true,
        'required'=>true,
        'check'=>['kind'=>'text']
    ];


    // onInsert
    // retourner la requête en json sur insertion
    final protected function onInsert($value,array $row,array $option):?string
    {
        $return = null;
        $boot = static::bootReady();

        if(!empty($boot))
        $return = $boot->request()->toJson();

        return $return;
    }


    // onGet
    // sur onGet recrée l'objet request
    protected function onGet($return,?Orm\Cell $cell=null,array $option)
    {
        $return = parent::onGet($return,$cell,$option);

        if(is_scalar($return))
        {
            $json = Base\Json::decode($return);

            if(!empty($json))
            $return = Core\Request::newOverload($json);
        }

        return $return;
    }
}

// init
Request::__init();
?>