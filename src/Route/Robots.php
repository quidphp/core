<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Route;
use Quid\Core;

// robots
// abstract class for a robots.txt route
abstract class Robots extends Core\RouteAlias
{
    // config
    protected static array $config = [
        'path'=>'robots.txt',
        'priority'=>5000,
        'sitemap'=>false,
        'group'=>'seo',
        'response'=>[
            'contentType'=>'txt'],
        'allow'=>true
    ];


    // trigger
    // lance la route robots
    final public function trigger()
    {
        $r = '';
        $allow = $this->getAttr('allow');

        if($allow === true)
        $r .= $this->robotsAllow();

        else
        $r .= $this->robotsDeny();

        return $r;
    }


    // robotsAllow
    // contenu si la navigation par les bots est permise
    final public function robotsAllow():string
    {
        $r = "User-agent: *\n";
        $r .= 'Allow: /';

        return $r;
    }


    // robotsDeny
    // contenu si la navigation par les bots est interdite
    final public function robotsDeny():string
    {
        $r = "User-agent: *\n";
        $r .= 'Disallow: /';

        return $r;
    }
}

// init
Robots::__init();
?>