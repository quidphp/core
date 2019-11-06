<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Route;
use Quid\Base;
use Quid\Base\Cli;
use Quid\Core;

// cliVersion
// abstract class for the version route, accessible via the cli
abstract class CliVersion extends Core\RouteAlias
{
    // config
    public static $config = [
        'path'=>['-v','-version','-about'] // plusieurs chemins pour la route
    ];


    // cliWrap
    // enrobe l'appel à la méthode cli
    abstract protected function cliWrap();


    // cli
    // génère le output du cli
    protected function cli(bool $cli)
    {
        $boot = static::boot();
        $art = static::asciiArt();

        if($cli === false)
        $art = Base\Debug::printr($art,true);

        Cli::flush($art);
        Cli::pos($boot->label());
        Cli::pos($boot->typeLabel());
        Cli::pos($boot->version());

        return;
    }


    // asciiArt
    // retourne le ascii art pour le cli
    public static function asciiArt():string
    {
return '
 .d88888b.           d8b      888 8888888b.  888    888 8888888b.  
d88P" "Y88b          Y8P      888 888   Y88b 888    888 888   Y88b 
888     888                   888 888    888 888    888 888    888 
888     888 888  888 888  .d88888 888   d88P 8888888888 888   d88P 
888     888 888  888 888 d88" 888 8888888P"  888    888 8888888P"  
888 Y8b 888 888  888 888 888  888 888        888    888 888        
Y88b.Y8b88P Y88b 888 888 Y88b 888 888        888    888 888        
 "Y888888"   "Y88888 888  "Y88888 888        888    888 888        
       Y8b
';
    }
}

// init
CliVersion::__init();
?>