<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/core/blob/master/LICENSE
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


    // queue
    // crée une nouvelle entrée du queue
    final public static function queue(...$values):?Main\Contract\Queue
    {
        $data = static::prepareQueueData(...$values);
        return ($data !== null)? static::safeInsert($data):null;
    }
}
?>