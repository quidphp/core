<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
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
    protected static array $config = [
        'path'=>['-v','-version','-about'], // plusieurs chemins pour la route
        'priority'=>8000
    ];


    // cliWrap
    // enrobe l'appel à la méthode cli
    abstract protected function cliWrap();


    // cli
    // génère le output du cli
    final protected function cli(bool $cli)
    {
        $boot = static::boot();
        $art = static::asciiArt();

        if($cli === false)
        $art = Base\Debug::printr($art,true);

        $value = [$boot->label(),$boot->typeLabel(),$boot->version(true)];
        $this->cliWrite('pos',$value);
        $this->cliWrite('eol',1);
        $this->cliWrite(null,$art,false);
        $this->cliWrite('eol',1);
        $this->cliWrite('pos',$boot::quidCredit(),false);
    }


    // asciiArt
    // retourne le ascii art pour le cli
    final public static function asciiArt():string
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