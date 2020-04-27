<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 * Readme: https://github.com/quidphp/core/blob/master/README.md
 */

namespace Quid\Core\Cell;
use Quid\Base;
use Quid\Core;

// floating
// class to work with a cell containing a floating value
class Floating extends Core\CellAlias
{
    // config
    protected static array $config = [];


    // pair
    // retourne la date formatté
    // sinon renvoie à parent
    final public function pair($value=null,...$args)
    {
        $return = $this;

        if($value === '$')
        $return = $this->moneyFormat(...$args);

        elseif($value !== null)
        $return = parent::pair($value,...$args);

        return $return;
    }


    // moneyFormat
    // format le nombre flottant en argent
    final public function moneyFormat(?string $lang=null,?array $option=null):?string
    {
        return Base\Num::moneyFormat($this->value(),$lang,$option);
    }
}

// init
Floating::__init();
?>