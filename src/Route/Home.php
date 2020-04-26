<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 * Readme: https://github.com/quidphp/core/blob/master/README.md
 */

namespace Quid\Core\Route;
use Quid\Core;

// home
// abstract class for a home route
abstract class Home extends Core\RouteAlias
{
    // config
    public static array $config = [
        'path'=>[
            '',
            'en'=>'home',
            'fr'=>'accueil'],
        'group'=>'home',
        'priority'=>1
    ];


    // onReplace
    // comme titre, met le bootLabel
    protected function onReplace(array $return):array
    {
        $return = parent::onReplace($return);
        $return['title'] = $return['bootLabel'];

        return $return;
    }
}

// init
Home::__init();
?>