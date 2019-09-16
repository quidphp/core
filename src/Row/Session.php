<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
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
    public static $config = [
        'search'=>false,
        'relation'=>'id',
        'priority'=>954,
        'parent'=>'system',
        'order'=>['dateModify'=>'desc'],
        'cols'=>[
            'context'=>['class'=>Core\Col\Context::class],
            'data'=>['class'=>Core\Col\Serialize::class],
            'name'=>['general'=>false],
            'sid'=>['required'=>true],
            'count'=>['class'=>Core\Col\CountCommit::class,'general'=>true],
            'user_id'=>['class'=>Core\Col\UserCommit::class,'panel'=>false,'general'=>true],
            'userAdd'=>['general'=>false],
            'dateAdd'=>['general'=>true],
            'userModify'=>['general'=>false],
            'dateModify'=>['general'=>true]],
        'inRelation'=>false
    ];


    // isDeleteable
    // une ligne de session peut toujours être effacé
    public function isDeleteable(?array $option=null):bool
    {
        return true;
    }


    // sessionSid
    // retourne la clé de session
    public function sessionSid():string
    {
        return $this->cell('sid')->value() ?? '';
    }


    // sessionData
    // retourne les données d'une ligne session
    public function sessionData():string
    {
        return $this->cell('data')->value() ?? '';
    }


    // sessionWrite
    // écrit de nouvelles data dans la ligne de session
    public function sessionWrite(string $data):bool
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
    public function sessionUpdateTimestamp():bool
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
    public function sessionDestroy():bool
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
    public static function sessionExists(string $path,string $name,string $sid):bool
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
    public static function sessionCreate(string $path,string $name,string $sid):?Main\Contract\Session
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
    public static function sessionRead(string $path,string $name,string $sid):?Main\Contract\Session
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
    public static function sessionGarbageCollect(string $path,string $name,int $lifetime,$not=null):int
    {
        $return = 0;
        $table = static::tableFromFqcn();

        if(!empty($lifetime))
        {
            $timestamp = Base\Date::timestamp() - $lifetime;
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
    public static function sessionMostRecent(string $name,Main\Contract\User $user,?object $not=null):?Main\Contract\Session
    {
        $return = null;
        $table = static::tableFromFqcn();

        if(!empty($name))
        {
            $where = ['name'=>$name,'user_id'=>$user];

            if(!empty($not) && $not instanceof self)
            {
                $dateAdd = $not['dateAdd'];
                $primary = $table->primary();
                $where[] = [$primary,'!=',$not];
                $where[] = ['dateAdd','>',$dateAdd];
            }

            $return = $table->select($where,['dateAdd'=>'desc'],1);
        }

        return $return;
    }
}

// config
Session::__config();
?>