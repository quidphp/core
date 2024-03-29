<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Test\Core;
use Quid\Base;
use Quid\Core;
use Quid\Main;

// serviceMailer
// class for testing Quid\Core\ServiceMailer
class ServiceMailer extends Base\Test
{
    // trigger
    final public static function trigger(array $data):bool
    {
        // prepare
        $boot = $data['boot'];
        $session = $boot->session();
        $user = $session->user();
        $db = $boot->db();
        $db->truncate('queueEmail');
        $table = 'email';
        $tb = $db[$table];
        $model = 'resetPassword';
        $mailer = $boot->service('mailer');
        $from = $mailer->username();
        $to = 'test@exempla.la';
        $msg = ['subject'=>'Test','body'=>'<b>what</b>','to'=>$to,'from'=>$from];
        $msg2 = ['subject'=>'Test','body'=>'<b>what</b>','to'=>$user,'from'=>$from];
        $msg3 = ['subject'=>'Test','body'=>'<b>what</b>','to'=>$user['email'],'from'=>$from];
        $msg4 = ['subject'=>'Test','body'=>'<b>what</b>','to'=>[$to=>'Pierre'],'from'=>['james@test.com'=>'NAME']];

        // getOverloadKeyPrepend

        // phpMailer
        assert($mailer->isActive());
        assert($mailer->setDebug(0) === $mailer);
        assert($mailer->error() === '');
        assert(count($mailer->attr()) === 31);
        assert($mailer->getServiceKey(true) === 'mailer');
        assert($mailer->username() === $from);
        assert($mailer->from() === ['email'=>'james@james.com','name'=>'James']);
        assert($mailer->mailer() instanceof \PHPMailer\PHPMailer\PHPMailer);
        assert($mailer->isReady());
        assert($mailer->checkReady() === $mailer);
        assert(count($mailer->messageWithOption($msg)) === 30);
        assert($mailer->queue($msg));
        assert($mailer->prepareMessage($msg2)['to'][0]['name'] === 'admin');
        assert($mailer->prepareMessage($msg3)['to'][0]['name'] === 'administrator');
        assert($mailer->queueLoop([$msg]) === [true]);
        $queue = $mailer->queueClass();
        assert(class_exists($queue));
        $log = $mailer->logClass();
        assert(class_exists($log));
        $rows = $queue::getQueued(10);
        assert($rows->isNotEmpty());
        $row = $rows->first();
        assert($row->getMailerKey() === 'mailer');
        assert($row instanceof Core\Row);
        assert(is_int($row->_cast()));
        assert($row->isUnsent());
        assert(!$row->isInProgress());
        assert(!$row->isError());
        assert(!$row->isSent());
        assert(count($row->sendEmail()) === 33);
        assert(count($row->sendEmail()['header']) === 0);
        assert($row->serviceMailer($row->getMailerKey()) === $mailer);
        assert($mailer::setDispatch('queue') === null);
        assert($mailer::getDispatch() === 'queue');
        assert($mailer::setDispatch('queue') === null);
        assert($mailer->dispatch($msg2));
        $row = $tb->row($model);
        assert($row instanceof Core\Row);
        assert($row->contentType() === 2);
        assert($row->subject() === 'Password reset [subject]');
        assert(strlen($row->body()) === 61);
        assert($row->messageSegment() === ['subject','userPassword','activateUri','domain']);
        $replace = ['subject'=>'ok','userPassword'=>'LOL','activateUri'=>'http://google.com','domain'=>'well'];
        assert(count($row->prepareMessage(['test@exempla.la','pierre'],$replace,null,['cc'=>'paul@ok.com'])) === 5);
        assert($row->serviceMailer('mailer') === $mailer);
        assert($row->queue('mailer',['test@exempla.la'=>'Pierre champion'],$replace));
        assert($row instanceof Main\Contract\Email);
        assert($row->queue('mailer',$user,$replace));
        assert($row->queue('mailer',$user['email'],$replace));
        $row->queue('mailer','test@exempla.la',$replace,null,['debug'=>2,'output'=>'html','priority'=>1,'header'=>['test'=>4]]);
        assert($db->truncate($queue::tableFromFqcn()) instanceof \PdoStatement);

        return true;
    }
}
?>