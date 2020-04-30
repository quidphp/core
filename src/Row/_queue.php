<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 * Readme: https://github.com/quidphp/core/blob/master/README.md
 */

namespace Quid\Core\Row;
use Quid\Main;

// _queue
// trait that adds queuing-related methods to a row
trait _queue
{
    // trait
    use Main\_queue;


    // getQueued
    // retourne un objet rows avec toutes les rows queued
    abstract public static function getQueued(?int $limit=null):?Main\Map;


    // prepareQueueData
    // méthode abstraite pour préparer les datas de la queue
    abstract protected static function prepareQueueData():?array;


    // queue
    // crée une nouvelle entrée du queue
    final public static function queue(...$values):?Main\Contract\Queue
    {
        $return = null;
        $data = static::prepareQueueData(...$values);

        if($data !== null)
        $return = static::safeInsert($data);

        return $return;
    }
}
?>