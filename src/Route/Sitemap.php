<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Route;
use Quid\Core;
use Quid\Main;

// sitemap
// abstract class for automated sitemap.xml route
abstract class Sitemap extends Core\RouteAlias
{
    // config
    protected static array $config = [
        'path'=>'sitemap.xml',
        'priority'=>5010,
        'sitemap'=>false,
        'group'=>'seo'
    ];


    // trigger
    // lance la route sitemap et génère toutes les uris accessible à l'utilisateur de la session courante
    public function trigger()
    {
        $xml = Main\Xml::newOverload('sitemap');
        $lang = $this->lang();
        $routes = static::routes();
        $uris = $routes->sitemap($lang->allLang());

        if(!empty($uris))
        $xml->sitemap(...$uris);

        return $xml->output();
    }
}

// init
Sitemap::__init();
?>