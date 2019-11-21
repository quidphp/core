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

// cliClearLog
// class for the cli route to remove all log data
class CliClearLog extends Core\RouteAlias
{
    // config
    public static $config = [
        'path'=>['-clearlog'],
        'clear'=>[
            '[storageLog]',
            '[storageError]',
            Core\Row\Log::class,
            Core\Row\LogCron::class,
            Core\Row\LogEmail::class,
            Core\Row\LogError::class,
            Core\Row\LogHttp::class,
            Core\Row\LogSql::class]
    ];


    // cli
    // méthode pour vider les logs
    final protected function cli(bool $cli)
    {
        Cli::neutral(static::label());
        $return = $this->clearLog();

        return $return;
    }


    // clearLog
    // vide les logs
    final protected function clearLog():array
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
CliClearLog::__init();
?>