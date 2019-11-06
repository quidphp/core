<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Route;
use Quid\Base\Cli;
use Quid\Core;

// cliSessionGc
// abstract class for the cli route to remove expired sessions
abstract class CliSessionGc extends Core\RouteAlias
{
    // config
    public static $config = [
        'path'=>['-sessiongc']
    ];


    // cliWrap
    // enrobe l'appel à la méthode cli
    abstract protected function cliWrap();


    // cli
    // méthode pour effacer les sesssions non valides
    protected function cli(bool $cli)
    {
        Cli::neutral(static::label());
        $return = $this->sessionGc();

        return $return;
    }


    // sessionGc
    // efface les sessions non valides pour tous les types
    protected function sessionGc():array
    {
        $return = [];
        $type = static::boot()->type();
        $session = static::session();
        $gc = $session->garbageCollect();
        $method = (is_int($gc))? 'pos':'neg';
        $value = "$type: $gc";

        Cli::$method($value);
        $return[] = [$method=>$value];

        return $return;
    }
}

// init
CliSessionGc::__init();
?>