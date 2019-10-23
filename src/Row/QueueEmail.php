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

// queueEmail
// class to deal with a row of the queueEmail table, stores the email to send
class QueueEmail extends Core\RowAlias implements Main\Contract\Queue
{
    // trait
    use _queue;


    // config
    public static $config = [
        'panel'=>false,
        'search'=>false,
        'priority'=>953,
        'parent'=>'system',
        'cols'=>[
            'context'=>['class'=>Core\Col\Context::class],
            'status'=>['general'=>true,'relation'=>'queueEmailStatus']],
        'permission'=>[
            'nobody'=>['insert'=>true],
            'shared'=>['insert'=>true],
            'user'=>['insert'=>true],
            'contributor'=>['insert'=>true,'update'=>false,'delete'=>false],
            'editor'=>['insert'=>true,'update'=>false,'delete'=>false],
            'subAmin'=>['update'=>false],
            'admin'=>['update'=>false]]
    ];


    // isUnsent
    // retourne vrai si le email n'a pas été envoyé
    public function isUnsent():bool
    {
        return ($this->cell('status')->isEqual(1))? true:false;
    }


    // isInProgress
    // retourne vrai si le email est en train d'être envoyé
    public function isInProgress():bool
    {
        return ($this->cell('status')->isEqual(2))? true:false;
    }


    // isError
    // retourne vrai si l'envoie du email a échoué
    public function isError():bool
    {
        return ($this->cell('status')->isEqual(3))? true:false;
    }


    // isSent
    // retourne vrai si le email a été envoyé
    public function isSent():bool
    {
        return ($this->cell('status')->isEqual(4))? true:false;
    }


    // sendEmail
    // retourne le tableau message qui contient tout le nécessaire pour envoyer le courriel
    public function sendEmail():array
    {
        return $this->cell('json')->get();
    }


    // getMailerKey
    // retourne la clé pour trouver l'objet mailer à utiliser pour envoyer le courriel
    // envoie une exception si introuvable
    public function getMailerKey():string
    {
        $return = null;
        $json = $this->cell('json')->get();

        if(is_array($json) && array_key_exists('key',$json) && Base\Arr::isKey($json['key']))
        $return = $json['key'];

        else
        static::throw('noMailerKeyFound');

        return $return;
    }


    // unqueue
    // unqueue la row et change le status
    public function unqueue():bool
    {
        $return = false;
        $key = $this->getMailerKey();
        $mailer = $this->mailer($key);
        $status = $this->cell('status');

        $status->set(2);
        $save = $this->updateChangedIncluded();

        $return = $mailer->send($this);

        if($return === true)
        $status->set(4);
        else
        $status->set(3);

        $save = $this->updateChangedIncluded();

        return $return;
    }


    // newData
    // crée le tableau d'insertion
    public static function newData(array $json):array
    {
        $return = [];
        $return['status'] = 1;
        $return['json'] = $json;

        return $return;
    }


    // getQueued
    // retourne un objet rows avec toutes les rows queued
    // la plus ancienne est retourné en premier
    public static function getQueued(?int $limit=null):?Main\Map
    {
        $return = null;
        $table = static::newTable();

        if(!empty($table))
        {
            $db = $table->db();
            $return = $db->rows($table,['status'=>1],['id'=>'asc'],$limit);
        }

        return $return;
    }
}

// init
QueueEmail::__init();
?>