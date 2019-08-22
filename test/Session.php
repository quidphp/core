<?php
declare(strict_types=1);
namespace Quid\Core\Test;
use Quid\Test;
use Quid\Core;
use Quid\Base;

// session
class Session extends Base\Test
{
	// trigger
	public static function trigger(array $data):bool
	{
		// prepare
		$table = 'session';
		$boot = $data['boot'];
		$s = $boot->session();
		$db = $s->db();
		$tb = $db[$table];
		$lang = $boot->lang();
		$class = Core\Row\Session::class;
		$s->setUser(2);
		$db->update('user',['password'=>'$2y$11$8nFxo4CJfdzkT3ljRTrnAeYVsRIWDNlb/UDh.yRyuA9DN0GqZzMfe'],3);
		$db->delete('user',[['id','>',4]]);
		$db->table('user')->alterAutoIncrement();
		$s->terminate(true,true);
		$s = $boot->session();
		$s->setLang('en');

		// main
		assert($s->version() === '1.0.1-'.QUID_VERSION);

		// onStart

		// onEnd

		// onLogin

		// onLogout

		// isNobody
		assert(!$s->isNobody());

		// isSomebody
		assert($s->isSomebody());

		// isAdmin
		assert($s->isAdmin());

		// isUserSynched
		assert($s->isUserSynched());

		// canViewRow

		// isLoginSinglePerUser
		assert($s->isLoginSinglePerUser());

		// allowRegister
		assert($s->allowRegister() === false);

		// allowResetPasswordEmail
		assert($s->allowResetPasswordEmail());

		// allowWelcomeEmail
		assert(!$s->allowWelcomeEmail());

		// getUserClass
		assert($s->getUserClass() === Test\Row\User::class);

		// getDefaultUserPrimary
		assert($s->getDefaultUserPrimary() === 2);

		// getNobodyPrimary
		assert($s->getNobodyPrimary() === 1);

		// getLoginLifetime
		assert(is_int($s->getLoginLifetime()));

		// structureNav

		// structureUser

		// primary
		assert(is_int($s->primary()));

		// user
		assert($s->user() instanceof Core\Row\User);

		// userUid
		assert($s->userUid() === 2);

		// userPermission
		assert($s->userPermission() === 80);

		// triggerUser

		// syncUser
		
		// syncLang
		
		// syncTimezone
		
		// timezone
		assert($s->timezone() === null);
		
		// userSession

		// setUser
		assert($s->setUser(3) === $s);
		assert(!$s->isNobody());

		// setUserNobody
		assert($s->setUserNobody() === $s);
		assert($s->isNobody());
		
		// setUserDefault
		
		// role
		assert($s->role() instanceof Core\Role);

		// permission
		assert($s->permission() === 1);
		
		// setLang
		
		// nav
		assert($s->nav() instanceof Core\Nav);

		// navEmpty
		assert($s->navEmpty() === $s);

		// routeTableGeneral

		// flashPost

		// canLogin
		assert(!$s->canLogin());
		assert(!$s->canLogin('app'));

		// can
		assert(!$s->can('login/cms'));

		// beforeLogin

		// loginProcess
		$s['test'] = 'OK';
		assert(!$s->loginProcess('','',['com'=>true]));
		assert(strlen($s->com()->flush($lang)) === 68);
		assert(!$s->loginProcess('a','b',['com'=>true]));
		assert(strlen($s->com()->flush($lang)) === 59);
		assert(!$s->loginProcess('okas','x',['com'=>true]));
		assert(strlen($s->com()->flush($lang)) === 59);
		assert(!$s->loginProcess('editorz','Test123',['com'=>true]));
		assert(strlen($s->com()->flush($lang)) === 67);
		assert(!$s->loginProcess('editor@quid.com','Test12',['com'=>true]));
		assert(strlen($s->com()->flush($lang)) === 69);
		assert(!$s->loginProcess('EDITOR@quid.COM','Test12',['com'=>true]));
		assert(strlen($s->com()->flush($lang)) === 69);
		assert(!$s->loginProcess('inactive@quid.com','Test123',['com'=>true]));
		assert(strlen($s->com()->flush($lang)) === 64);
		assert(!$s->loginProcess('nobody','Test123',['com'=>true]));
		assert(strlen($s->com()->flush($lang)) === 65);
		assert($s->loginProcess('editor','Test123',['com'=>true,'strict'=>true]));
		assert(strlen($s->com()->flush($lang)) === 52);
		assert(!$s->loginProcess('editor','Test123',['com'=>true]));
		assert(strlen($s->com()->flush($lang)) === 60);
		assert($_SESSION['test'] === 'OK');
		assert($s->remember() === ['credential'=>'editor']);

		// login

		// logoutProcess
		assert($s->logoutProcess(['com'=>true]));
		assert(strlen($s->com()->flush($lang)) === 53);
		assert(!$s->logoutProcess(['com'=>true]));
		assert(strlen($s->com()->flush($lang)) === 56);
		assert($_SESSION['test'] === 'OK');
		assert($s->remember('credential') === 'editor');

		// logout

		// changePassword
		assert($s->loginProcess('EDITOR','Test123',['com'=>true]));
		assert(strlen($s->com()->flush($lang)) === 52);
		assert(!$s->changePassword('',"peps",null,['com'=>true]));
		assert(strlen($s->com()->flush()) === 57);
		assert(!$s->changePassword('pe',"peps",null,['com'=>true]));
		assert(strlen($s->com()->flush()) === 135);
		assert(!$s->changePassword("peps","peps",null,['com'=>true]));
		assert(strlen($s->com()->flush()) === 135);
		assert(!$s->changePassword("peps909090","peps808080",null,['com'=>true]));
		assert(strlen($s->com()->flush()) === 85);
		assert(!$s->changePassword("peps909090","peps909090",'ol',['com'=>true]));
		assert(strlen($s->com()->flush()) === 139);
		assert(!$s->changePassword("peps909090","peps909090",'asdsdsadasd123',['com'=>true]));
		assert(strlen($s->com()->flush()) === 72);
		assert(!$s->changePassword("Test123","Test123",'Test123',['com'=>true]));
		assert(strlen($s->com()->flush()) === 86);
		assert($s->changePassword("zzzz909090","zzzz909090",'Test123',['onCommitted'=>true,'com'=>true]));
		assert(strlen($s->com()->flush()) === 283);
		assert($s->changePassword("Test123","Test123",'zzzz909090',['onCommitted'=>true,'com'=>true]));
		assert(strlen($s->com()->flush()) === 283);
		assert(!$s->changePassword("Test123",null,null,['onCommitted'=>true,'com'=>true]));
		assert(strlen($s->com()->flush()) === 86);
		assert($s->changePassword("zzzz909090",null,null,['onCommitted'=>true,'com'=>true]));
		assert(strlen($s->com()->flush()) === 283);
		assert($s->changePassword("Test123",null,'zzzz909090',['onCommitted'=>true,'com'=>true]));
		assert(strlen($s->com()->flush()) === 283);
		assert(!$s->changePassword(" ",'',null,['onCommitted'=>true,'com'=>true]));
		assert(strlen($s->com()->flush()) === 135);

		// row/user
		$user = $s->user();
		$role = $user->role();
		assert($user->uid() === $user->primary());
		assert(!$user->isNobody());
		assert($user->isSomebody());
		assert(!$user->isAdmin());
		assert($user->role() instanceof Core\Role);
		assert($user->permission() === 60);
		assert($user->hasEmail());
		assert($user->hasUsername());
		assert($user->canReceiveEmail());
		assert(count($user->toEmail()) === 1);
		assert($user->toSession() === ['uid'=>3,'permission'=>60]);
		assert($user->can('login/cms'));
		assert($user->canLogin());
		assert($user->canLogin('app'));
		assert($user->canDb('insert','log'));
		assert($user->username()->name() === 'username');
		assert($user->email()->name() === 'email');
		assert($user->email()(true) === 'editor@quid.com');
		assert($user->dateLogin()->name() === 'dateLogin');
		assert($user->password()->name() === 'password');
		assert($user->passwordReset()->name() === 'passwordReset');
		assert($user->isPassword('Test123'));
		assert(!$user->isPassword('test123'));
		assert($user->setPassword(['Test123','Test123'],['com'=>false]) === null);
		assert($user->loginValidate('login') === null);
		assert($user->cellName()->name() === 'username');
		assert($user::findNobody()->primary() === 1);
		assert($user::findByCredentials('ÉDITOR@quid.COM')->primary() === 3);
		assert($user::findByCredentials('zDITOR@quid.COM') === null);
		assert($user::findByEmail('ÉDITOR@quid.COM')->primary() === 3);
		assert($user::findByEmail('EDITOR@quid.COM')->primary() === 3);
		assert($user::findByUsername('nobody')->primary() === 1);
		assert($user::findByUid(1)->primary() === 1);
		assert($user::getUsernameSecurity() === null);
		assert($user::getPasswordSecurity() === null);
		assert(!$user::resetPasswordProcess('inactive@quid.com',null,['com'=>true]));
		assert(strlen($s->com()->flush()) === 96);
		assert(!$user::resetPasswordProcess('inactive@quid.com',null,['com'=>true]));
		assert(strlen($s->com()->flush()) === 96);
		assert($s->logoutProcess());
		assert(!$user::resetPasswordProcess('',null,['com'=>true]));
		assert(strlen($s->com()->flush()) === 56);
		assert(!$user::resetPasswordProcess('test@james.com',null,['com'=>true]));
		assert(strlen($s->com()->flush()) === 67);
		assert(!$user::resetPasswordProcess('EDITOR',null,['com'=>true]));
		assert(strlen($s->com()->flush()) === 56);
		assert(!$user::resetPasswordProcess('inactive@quid.com',null,['com'=>true]));
		assert(strlen($s->com()->flush()) === 64);
		assert(!$user::resetPasswordProcess('nobody@quid.com',null,['com'=>true]));
		assert(strlen($s->com()->flush()) === 65);
		$password = $user::resetPasswordProcess('editor@quid.com',['subject'=>'lol','domain'=>'http://google.com'],['method'=>'queue','com'=>true]);
		assert(is_string($password));
		assert(strlen($password) === 10);
		assert(strlen($s->com()->flush()) === 93);
		$hash = 'abcde';
		assert($s->loginProcess('EDITOR','Test123',['strict'=>true]));
		$good = Base\Crypt::passwordActivate($s->user()->passwordReset()->value(),1);
		assert(!$user::activatePasswordProcess(4,$hash,['com'=>true]));
		assert(strlen($s->com()->flush()) === 99);
		assert($s->logoutProcess());
		assert(!$user::activatePasswordProcess(1243,$hash,['com'=>true]));
		assert(strlen($s->com()->flush()) === 67);
		assert(!$user::activatePasswordProcess(0,$hash,['com'=>true]));
		assert(strlen($s->com()->flush()) === 57);
		assert(!$user::activatePasswordProcess(1,$hash,['com'=>true]));
		assert(strlen($s->com()->flush()) === 65);
		assert(!$user::activatePasswordProcess(4,$hash,['com'=>true]));
		assert(strlen($s->com()->flush()) === 64);
		assert(!$user::activatePasswordProcess(3,$hash,['com'=>true]));
		assert(strlen($s->com()->flush()) === 72);
		assert($user::activatePasswordProcess(3,$good,['com'=>true]));
		assert(strlen($s->com()->flush()) === 74);
		assert($s->loginProcess('EDITOR',$password,['com'=>true]));
		assert(strlen($s->com()->flush($lang)) === 52);
		assert($s->changePassword("Test123","Test123",$password,['onCommitted'=>true,'com'=>true]));
		assert(strlen($s->com()->flush()) === 283);
		$data = ['username'=>'test','active'=>1,'email'=>'test@test.com','password'=>'test023'];
		assert($user::registerValidate($data,'bla') === 'register/alreadyConnected');
		assert($s->logoutProcess());
		assert($user::registerValidate($data,'bla') === 'register/passwordConfirm');
		assert($user::registerValidate([],'bla') === 'register/invalidValues');
		assert($user::registerProcess($data,'bla',['com'=>true]) === null);
		assert(strlen($s->com()->flush()) === 81);
		$row = $user::registerProcess($data,'test023',['com'=>true]);
		assert($row instanceof Core\Row);
		assert(strlen($s->com()->flush()) === 175);
		assert($s->loginProcess('test@test.com',"test023",['com'=>true]) === false);
		assert(strlen($s->com()->flush()) === 65);
		assert($s->loginProcess('EDITOR',"Test123",['com'=>true]));
		assert(strlen($s->com()->flush()) === 52);

		// row/session
		$row = $s->storage();
		assert($s->storage() instanceof Core\Row);
		$row = $s->storage();
		assert($row['name']->get() === $s->name());
		assert(is_int($row['count']->get()));
		assert(is_string($row['dateAdd']->get()));
		assert($row['userAdd']->relationRow() instanceof Core\Row\User);
		assert(!empty($row->sessionSid()));
		assert(is_string($row->sessionData()));
		assert($row->sessionUpdateTimestamp() === true);
		assert($row::sessionGarbageCollect('','QUID',12231312) === 0);

		// main
		assert(count($s->getStructure()) === 19);

		// cleanup
		assert($s->setUser(2) === $s);
		$db->delete('user',[['id','>',4]]);
		$db->table('user')->alterAutoIncrement();
		assert($db->truncate($table) instanceof \PDOStatement);
		
		return true;
	}
}
?>