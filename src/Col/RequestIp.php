<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Col;
use Quid\Core;

// requestIp
// class for a column which applies the current request ip as value on commit
class RequestIp extends Core\ColAlias
{
    // config
    protected static array $config = [
        'general'=>true
    ];


    // onCommit
    // donne le ip de la requête courante lors d'un insert ou un update
    final protected function onCommit($value,?Core\Cell $cell=null,array $row,array $option):?string
    {
        $return = null;
        $boot = static::bootReady();

        if(!empty($boot))
        $return = $boot->request()->ip();

        return $return;
    }
}

// init
RequestIp::__init();
?>