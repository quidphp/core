<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/lemur/blob/master/LICENSE
 */

namespace Quid\Core\Route;
use Quid\Base;
use Quid\Base\Cli;
use Quid\Core;

// cliClearCache
// class for the cli route to remove all cached data
class CliClearCache extends Core\RouteAlias
{
    // config
    public static $config = [
        'path'=>['-clearcache'],
        'clear'=>['[storageCache]']
    ];


    // cli
    // méthode pour vider les caches
    final protected function cli(bool $cli)
    {
        Cli::neutral(static::label());
        $return = $this->clearCache();

        return $return;
    }


    // clearCache
    // vide le dossier de cache
    final protected function clearCache():array
    {
        $return = [];

        foreach ($this->getAttr('clear') as $value)
        {
            ['method'=>$method,'value'=>$value] = $this->clearValue($value);
            Cli::$method($value);
            $return[] = [$method=>$value];
        }

        return $return;
    }
}

// init
CliClearCache::__init();
?>