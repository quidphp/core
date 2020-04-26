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
use Quid\Base;
use Quid\Core;
use Quid\Main;

// session
// class to work with a row of the session table
class Session extends Core\RowAlias implements Main\Contract\Session
{
    // config
    public static array $config = [
        'search'=>false,
        'relation'=>'id',
        'priority'=>970,
        'parent'=>'system',
        'order'=>['dateModify'=>'desc'],
        'cols'=>[
            'envType'=>['class'=>Core\Col\EnvType::class],
            'data'=>['class'=>Core\Col\Serialize::class],
            'name'=>['general'=>false],
            'sid'=>['required'=>true],
            'count'=>['class'=>Core\Col\CountCommit::class,'general'=>true],
            'user_id'=>['class'=>Core\Col\UserCommit::class,'panel'=>false,'general'=>true],
            'userAdd'=>['general'=>false],
            'dateAdd'=>['general'=>true],
            'userModify'=>['general'=>false],
            'dateModify'=>['general'=>true]],
        'inRelation'=>false,
        'permission'=>[
            '*'=>['update'=>true,'delete'=>true],
            'nobody'=>['insert'=>true]]
    ];


    // isDeleteable
    // une ligne de session peut toujours être effacé
    final public function isDeleteable(?array $option=null):bool
    {
        return true;
    }


    // sessionSid
    // retourne la clé de session
    final public function sessionSid():string
    {
        return $this->cell('sid')->value() ?? '';
    }


    // sessionData
    // retourne les données d'une ligne session
    final public function sessionData():string
    {
        return $this->cell('data')->value() ?? '';
    }


    // sessionWrite
    // écrit de nouvelles data dans la ligne de session
    final public function sessionWrite(string $data):bool
    {
        $return = false;
        $db = $this->db();
        $this->cell('data')->set($data);

        $db->off();
        $save = $this->update();
        $db->on();

        if(is_int($save))
        $return = true;

        return $return;
    }


    // sessionUpdateTimestamp
    // update le timestamp de la ligne, retourne true même si rien n'a changé
    final public function sessionUpdateTimestamp():bool
    {
        $return = false;
        $db = $this->db();

        $db->off();
        $save = $this->update();
        $db->on();

        if(is_int($save))
        $return = true;

        return $return;
    }


    // sessionDestroy
    // détruit la ligne de session
    final public function sessionDestroy():bool
    {
        $return = false;
        $db = $this->db();

        $db->off();
        $delete = $this->delete();
        $db->on();

        if(is_int($delete))
        $return = true;

        return $return;
    }


    // sessionExists
    // retourne vrai si le sid exists pour le nom donné
    final public static function sessionExists(string $path,string $name,string $sid):bool
    {
        $return = false;
        $table = static::tableFromFqcn();

        if(!empty($name) && !empty($sid))
        {
            $count = $table->db()->selectCount($table,['name'=>$name,'sid'=>$sid]);
            if($count > 0)
            $return = true;
        }

        return $return;
    }


    // sessionCreate
    // crée une nouvelle session avec le nom et sid donné
    final public static function sessionCreate(string $path,string $name,string $sid):?Main\Contract\Session
    {
        $return = null;
        $table = static::tableFromFqcn();

        if(!empty($name) && !empty($sid))
        {
            $db = $table->db();
            $data = [];
            $data['name'] = $name;
            $data['sid'] = $sid;

            $db->off();
            $row = $table->insert($data,['reservePrimary'=>false]);
            $db->on();

            if($row instanceof Core\Row)
            $return = $row;
        }

        return $return;
    }


    // sessionRead
    // lit une session à partir d'un nom et d'un sid
    // retourne une classe qui implémente Contract\Session
    final public static function sessionRead(string $path,string $name,string $sid):?Main\Contract\Session
    {
        $return = null;
        $table = static::tableFromFqcn();

        if(!empty($name) && !empty($sid))
        {
            $where = ['name'=>$name,'sid'=>$sid];
            $return = $table->row($where);
        }

        return $return;
    }


    // sessionGarbageCollect
    // lance le processus de garbageCollect pour le nom de session donné
    final public static function sessionGarbageCollect(string $path,string $name,int $lifetime,$not=null):int
    {
        $return = 0;
        $table = static::tableFromFqcn();

        if(!empty($lifetime))
        {
            $timestamp = Base\Datetime::now() - $lifetime;
            if($timestamp > 0)
            {
                $db = $table->db();
                $where = [];
                $where['name'] = $name;
                $where[] = ['dateModify','<',$timestamp];

                if(!empty($notIn))
                $where[] = ['id','notIn',$notIn];

                $db->off();
                $return = $db->delete($table,$where);
                $db->on();
            }
        }

        return $return;
    }


    // sessionMostRecent
    // retourne la session la plus récente pour l'utilisateur donné
    final public static function sessionMostRecent(string $name,User $user,?self $not=null,?array $envType=null):?Main\Contract\Session
    {
        $return = null;
        $table = static::tableFromFqcn();

        if(!empty($name))
        {
            $where = ['name'=>$name,'user_id'=>$user];

            if(!empty($not))
            {
                $dateAdd = $not['dateAdd'];
                $primary = $table->primary();
                $where[] = [$primary,'!=',$not];
                $where[] = ['dateAdd','>',$dateAdd];
            }

            if(!empty($envType))
            $where[] = ['envType','=',$envType];

            $return = $table->select($where,['dateAdd'=>'desc'],1);
        }

        return $return;
    }


    // sessionDestroyOther
    // efface toutes les sessions sauf la courante
    final public static function sessionDestroyOther(string $name,User $user,?self $not=null,?array $envType=null):?int
    {
        $return = null;
        $table = static::tableFromFqcn();

        if(!empty($name))
        {
            $where = ['name'=>$name,'user_id'=>$user];

            if(!empty($not))
            {
                $primary = $table->primary();
                $where[] = [$primary,'!=',$not];
            }

            if(!empty($envType))
            $where[] = ['envType','=',$envType];

            $return = $table->delete($where);
        }

        return $return;
    }
}

// init
Session::__init();
?>