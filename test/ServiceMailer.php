<?php
declare(strict_types=1);
namespace Quid\Core\Test;
use Quid\Core;
use Quid\Main;
use Quid\Base;

// serviceMailer
class ServiceMailer extends Base\Test
{
	// trigger
	public static function trigger(array $data):bool
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
		$msg = array('subject'=>'Test','body'=>'<b>what</b>','to'=>$to);
		$msg2 = array('subject'=>'Test','body'=>'<b>what</b>','to'=>$user);
		$msg3 = array('subject'=>'Test','body'=>'<b>what</b>','to'=>$user['email']);
		$msg4 = array('subject'=>'Test','body'=>'<b>what</b>','to'=>array($to=>'Pierre'),'from'=>array('james@test.com'=>'NAME'));
		
		// getLangCode
		assert($mailer->getLangCode() === 'en');
		
		// getOverloadKeyPrepend

		// phpMailer
		assert($mailer->isActive());
		assert($mailer->setDebug(0) === $mailer);
		assert($mailer->error() === '');
		assert(count($mailer->option()) === 26);
		assert($mailer->getKey() === 'mailer');
		assert($mailer->username() === $from);
		assert($mailer->from() === array('email'=>'noreply@makeitrealplay.com','name'=>'James'));
		assert($mailer->mailer() instanceof \PHPMailer\PHPMailer\PHPMailer);
		assert($mailer->isReady());
		assert($mailer->checkReady() === $mailer);
		assert(count($mailer->messageWithOption($msg)) === 25);
		assert($mailer->queue($msg));
		assert($mailer->prepareMessage($msg2)['to'][0]['name'] === 'admin');
		assert($mailer->prepareMessage($msg3)['to'][0]['name'] === 'administrator');
		assert($mailer->queueTest($msg));
		assert($mailer->queueLoop(array($msg)) === array(true));
		$queue = $mailer::queueClass();
		assert(class_exists($queue));
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
		assert(count($row->sendEmail()) === 28);
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
		assert(strlen($row->body()) === 49);
		assert($row->messageSegment() === array('subject','password','uri','domain'));
		$replace = array('subject'=>'ok','password'=>'LOL','uri'=>'http://google.com','domain'=>'well');
		assert(count($row->prepareMessage(array('test@exempla.la','pierre'),$replace,array('cc'=>'paul@ok.com'))) === 5);
		assert($row->serviceMailer('mailer') === $mailer);
		assert($row->queue('mailer',array('test@exempla.la'=>'Pierre champion'),$replace));
		assert($row instanceof Main\Contract\Email);
		assert($row->queue('mailer',$user,$replace));
		assert($row->queue('mailer',$user['email'],$replace));
		$row->queue('mailer','test@exempla.la',$replace,array('debug'=>2,'output'=>'html','priority'=>1,'header'=>array('test'=>4)));
		assert($db->truncate($queue::tableFromFqcn()) instanceof \PdoStatement);
		
		return true;
	}
}
?>