<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Row;
use Quid\Core;
use Quid\Main;

// cacheRoute
// class to store rendered route caches
class CacheRoute extends Core\RowAlias implements Main\Contract\Cache
{
    // config
    protected static array $config = [
        'panel'=>false,
        'priority'=>953,
        'parent'=>'system',
        'cols'=>[
            'context'=>['class'=>Core\Col\Json::class],
            'data'=>['class'=>Core\Col\Json::class]],
        'permission'=>[
            '*'=>['insert'=>true,'delete'=>true],
            'nobody'=>['insert'=>true],
            'admin'=>['update'=>false]],
    ];


    // getData
    // retourne le tableau des donnés de la cache
    final public function getData():array
    {
        return $this['data']->get() ?? [];
    }


    // getContent
    // retourne les donnés de la cache sous forme de string
    final public function getContent():string
    {
        return $this->getData()['html'] ?? '';
    }


    // getDate
    // retourne la date de création de la cache
    final public function getDate():int
    {
        return $this['dateAdd']->value();
    }


    // store
    // enregistre une nouvelle entrée de cache
    final public static function store(array $context,string $data):?int
    {
        $table = static::tableFromFqcn();
        $data = ['html'=>$data];
        $set = ['context'=>$context,'data'=>$data];

        return $table->insert($set,['row'=>false]);
    }


    // clearAll
    // vide la cache de toutes ses entrées
    // la méthode est safe, donc pas d'erreur si la table n'existe pas
    public static function clearAll():void
    {
        $boot = static::bootReady();

        if(!empty($boot) && $boot->hasDb())
        {
            $db = $boot->db();

            if($db->hasTable(static::class))
            {
                $table = $db->table(static::class);

                if($table->hasPermission('truncate'))
                $table->truncate();
            }
        }
    }


    // findByContext
    // retourne la cache par contexte
    final public static function findByContext(array $context):?self
    {
        $table = static::tableFromFqcn();
        $where = ['context'=>$context];

        return $table->select($where);
    }
}

// init
CacheRoute::__init();
?>