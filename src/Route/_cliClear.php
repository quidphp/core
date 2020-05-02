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
use Quid\Base;
use Quid\Base\Cli;
use Quid\Core;

// cliClear
// trait for a cli route to remove log files and truncate tables
trait _cliClear
{
    // cli
    // méthode pour vider les logs
    final protected function cli(bool $cli)
    {
        Cli::neutral(static::label());
        $return = $this->clear();

        return $return;
    }


    // clear
    // lance la route
    final protected function clear():array
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


    // clearValue
    // utilisé par clearCache et clearLog pour effacer une valeur
    // peut être un fichier, directoire, symlink ou table de données
    final protected function clearValue(string $value):array
    {
        $return = ['method'=>'neg','value'=>null];
        $value = Base\Finder::shortcut($value);
        $return['value'] = "? $value";

        if(is_a($value,Core\Row::class,true))
        {
            $db = static::db();

            if($db->hasTable($value))
            {
                $table = $db->table($value);
                $option = ['log'=>false];

                if($table->truncate($option) === true)
                $return = ['method'=>'pos','value'=>"x $value"];
            }
        }

        else
        {
            if(Base\Symlink::is($value) && Base\Symlink::unset($value))
            $return = ['method'=>'pos','value'=>"- $value"];

            elseif(Base\Dir::is($value) && Base\Dir::emptyAndUnlink($value))
            $return = ['method'=>'pos','value'=>"x $value"];
        }

        return $return;
    }
}
?>