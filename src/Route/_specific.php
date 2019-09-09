<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Route;
use Quid\Core;

// _specific
// trait that provides most methods used for a specific route
trait _specific
{
    // trait
    use _specificNav;


    // config
    public static $configGeneral = [
        'group'=>'specific'
    ];


    // selectedUri
    public function selectedUri():array
    {
        return static::makeParent()->selectedUri();
    }


    // general
    public function general():Core\Route
    {
        return $this->cache(__METHOD__,function() {
            $return = null;
            $parent = static::parent();

            if(!empty($parent))
            $return = $parent::makeGeneral();

            return $return;
        });
    }


    // getBreadcrumbs
    public function getBreadcrumbs():array
    {
        return [static::makeParent(),$this];
    }
}
?>