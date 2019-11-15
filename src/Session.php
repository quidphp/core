<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core;
use Quid\Base;
use Quid\Main;
use Quid\Routing;

// session
// extended class that adds session support for user
class Session extends Main\Session
{
    // trait
    use _bootAccess;
    use _dbAccess;


    // config
    public static $config = [
        'userClass'=>Row\User::class, // classe row de l'utilisateur
        'historyClass'=>Routing\RequestHistory::class, // classe de l'historique de requête
        'userDefault'=>null, // définit le user par défaut (à l'insertion)
        'logoutOnPermissionChange'=>true, // force le logout sur changement de la valeur de permission
        'loginLifetime'=>3600, // durée du login dans une session
        'loginSinglePerUser'=>true, // un user peut seulement avoir une session ouverte à la fois, garde la plus récente
        'log'=>[ // lit des événements à des classes de table
            'login'=>Row\Log::class,
            'logout'=>Row\Log::class],
        'structure'=>[ // callables de structure additionnelles dans data, se merge à celle dans base/session
            'nav'=>'structureNav',
            'user'=>'structureUser',
            'fakeRoles'=>'structureFakeRoles'],
        '@dev'=>[
            'loginLifetime'=>(3600 * 24 * 25)]
    ];


    // dynamique
    protected $user = null; // objet user de la session


    // onStart
    // callback une fois que la session a été démarré
    // lie l'objet com à db et trigge le role
    final protected function onStart():void
    {
        parent::onStart();

        $this->syncUser();
        $this->userSession();
        $this->syncLang();
        $this->syncTimezone();
        $com = $this->com();
        $this->db()->setCom($com);
        $roles = $this->roles();
        $this->setRoles($roles);

        return;
    }


    // onEnd
    // callback une fois que la session est terminé
    // reset le timezone
    final protected function onEnd():void
    {
        parent::onEnd();

        $this->user = null;
        $db = $this->db();
        $db->setCom(null);
        $roles = static::boot()->roles();
        $nobody = $roles->nobody()->roles();
        $this->setRoles($nobody);
        Base\Timezone::reset(true);

        return;
    }


    // onLogin
    // callback lors d'un login
    protected function onLogin():void
    {
        return;
    }


    // onLogout
    // callback lors d'une logout
    protected function onLogout():void
    {
        return;
    }


    // is
    // retourne vrai si le role a l'attribut à true
    final public function is($value,bool $fake=true):bool
    {
        return $this->roles($fake)->isOne($value);
    }


    // isNobody
    // retourne vrai si le user est nobody
    final public function isNobody(bool $fake=true):bool
    {
        return $this->roles($fake)->isNobody();
    }


    // isSomebody
    // retourne vrai si le user est somebody
    final public function isSomebody(bool $fake=true):bool
    {
        return $this->roles($fake)->isSomebody();
    }


    // isAdmin
    // retourne vrai si le user est admin
    final public function isAdmin(bool $fake=true):bool
    {
        return $this->roles($fake)->isOne('admin');
    }


    // isCli
    // retourne vrai si le user est cli
    final public function isCli(bool $fake=true):bool
    {
        return $this->roles($fake)->isOne('cli');
    }


    // isUserSynched
    // retourne vrai si le user est sync
    // c'est à dire que le user objet a le même id et permission que dans les data de session
    final public function isUserSynched():bool
    {
        $return = false;
        $userUid = $this->userUid();
        $userPermission = $this->userPermission();
        $row = $this->user;

        if(!empty($row) && $userUid === $row->uid() && $userPermission === $row->permission())
        $return = true;

        return $return;
    }


    // canViewRow
    // retourne vrai si la row peut être vu
    public function canViewRow(Row $row)
    {
        return ($row->isUpdateable() === true)? true:false;
    }


    // isLoginSinglePerUser
    // returne vrai si un utilisateur ne peut avoir qu'une session ouverte
    final public function isLoginSinglePerUser():bool
    {
        return $this->getAttr('loginSinglePerUser');
    }


    // allowRegister
    // retourne vrai si le user a accès au formulaire d'enregistrement
    final public function allowRegister():bool
    {
        return false;
    }


    // allowResetPasswordEmail
    // retourne vrai si le user de la session permet l'envoie de courrier pour regénérer le mot de passe
    final public function allowResetPasswordEmail():bool
    {
        return $this->user()->allowResetPasswordEmail();
    }


    // allowWelcomeEmail
    // retourne vrai si le user de la session permet l'envoie de courrier de bienvenue
    final public function allowWelcomeEmail():bool
    {
        return $this->user()->allowWelcomeEmail();
    }


    // getUserClass
    // retourne la classe à utiliser pour utilisateur
    final public function getUserClass():string
    {
        return $this->getAttr('userClass')::getOverloadClass();
    }


    // getUserDefault
    // retourne la row l'utilisateur par défaut (lors de l'insertion)
    // sinon utilise le storage de user pour aller chercher le nobody ou cli
    final public function getUserDefault():Row\User
    {
        $return = $this->getAttr('userDefault',true);

        if(is_int($return))
        {
            $class = $this->getUserClass();
            $return = $class::findByUid($return);
        }

        if(!$return instanceof Row\User)
        {
            if(Base\Server::isCli())
            $return = $this->getUserCli();
            else
            $return = $this->getUserNobody();
        }

        return $return;
    }


    // getUserNobody
    // retourne l'utilisateur nobody
    final public function getUserNobody():Row\User
    {
        return $this->getUserClass()::findNobody();
    }


    // getUserCli
    // retourne l'utilisateur cli
    final public function getUserCli():Row\User
    {
        return $this->getUserClass()::findCli();
    }


    // getLoginLifetime
    // returne la durée de vie du login ou null
    final public function getLoginLifetime():?int
    {
        return $this->getAttr('loginLifetime');
    }


    // getSidDefault
    // retourne le sid à utiliser par défaut
    // pour cli, ça permet de continuer toujours sur la même session
    final public function getSidDefault():?string
    {
        $return = null;

        if(Base\Server::isCli())
        {
            $storage = $this->getStorageClass();

            if(method_exists($storage,'sessionMostRecent'))
            {
                $user = $this->getUserDefault();
                $session = $storage::sessionMostRecent($this->name(),$user,null,$this->context());
                if(!empty($session))
                $return = $session->sessionSid();
            }
        }

        return $return;
    }


    // structureNav
    // gère le champ structure nav de la session
    // mode insert, update ou is
    final public function structureNav(string $mode,$value=null)
    {
        $return = $value;

        if($mode === 'insert')
        $return = Routing\Nav::newOverload();

        elseif($mode === 'is')
        $return = ($value instanceof Routing\Nav)? true:false;

        return $return;
    }


    // structureUser
    // gère le champ structure user de la session
    // mode insert, update ou is
    // dans init, prend seulement le user si ce sont les mêmes uid et permission
    final public function structureUser(string $mode,$value=null)
    {
        $return = null;
        $class = $this->getUserClass();

        if($mode === 'init')
        {
            if(is_array($value) && Base\Arr::keysAre(['uid','permission'],$value) && is_int($value['uid']))
            {
                $user = $class::findByUid($value['uid']);

                if(!$this->getAttr('logoutOnPermissionChange') || ($user->permission() === $value['permission']))
                $return = $user;
            }

            elseif($value instanceof $class && $value instanceof Row\User)
            $return = $value;

            else
            $return = null;
        }

        elseif($mode === 'insert' || $mode === 'update')
        {
            if($mode === 'insert')
            $value = $this->getUserDefault();

            if($value instanceof $class)
            {
                $this->user = $value;
                $return = $value->toSession();
            }

            else
            static::throw('userInvalid');
        }

        elseif($mode === 'is')
        {
            $return = false;

            if($value instanceof $class && $value instanceof Row\User)
            $return = true;
        }

        return $return;
    }


    // structureFakeRoles
    // gère le champ structure fake roles de la session
    final public function structureFakeRoles(string $mode,$value=null)
    {
        $return = $value;

        if($mode === 'insert')
        $return = null;

        elseif($mode === 'is')
        $return = ($value === null || $value instanceof Main\Roles)? true:false;

        return $return;
    }


    // context
    // retourne le contexte de boot
    final public function context():array
    {
        return static::boot()->context();
    }


    // primary
    // retourne la clé primaire de la row de la session
    final public function primary():int
    {
        return $this->storage()->primary();
    }


    // user
    // retourne l'objet session user
    final public function user():Row\User
    {
        if(!$this->isUserSynched())
        static::throw('userOutOfSync');

        return $this->user;
    }


    // userUid
    // retourne le uid du user, tel qu'enregistré dans la session
    final public function userUid():?int
    {
        return $this->get('user/uid');
    }


    // userPermission
    // retourne la permission du user, tel qu'enregistré dans la session
    final public function userPermission():?int
    {
        return $this->get('user/permission');
    }


    // hasPermission
    // retourne vrai si toutes les permissions sont accordés par l'utilisateur
    final public function hasPermission(...$keys):bool
    {
        return $this->user()->hasPermission(...$keys);
    }


    // checkPermission
    // envoie une exception si la ou les permissions ne sont pas accordés par l'utilisateur
    final public function checkPermission(...$keys):self
    {
        $this->user()->checkPermission(...$keys);

        return $this;
    }


    // triggerUser
    // lie un objet user et trigge celui-ci
    final protected function triggerUser(Row\User $value):void
    {
        $this->user = $value;
        $this->set('user',$value->toSession());
        $roles = $value->roles();
        $this->setRoles($roles);

        return;
    }


    // syncUser
    // tente de resynchroniser le user objet avec le user dans les data de session
    // il faut resynchroniser pour le regenerateId (onEnd annule le user)
    final protected function syncUser():void
    {
        if(!$this->isUserSynched())
        {
            $sync = false;
            $userUid = $this->userUid();
            $userPermission = $this->userPermission();
            $class = $this->getUserClass();

            if(is_int($userUid))
            {
                $user = $class::findByUid($userUid);

                if(!empty($user) && $user->permission() === $userPermission)
                {
                    $this->triggerUser($user);
                    $sync = true;
                }
            }

            if($sync === false)
            $this->logout();
        }

        return;
    }


    // syncLang
    // synchronise la langue de la session avec celle de l'objet lang
    final protected function syncLang():void
    {
        $lang = $this->db()->lang();
        $current = $this->lang();
        $current = ($lang->isLang($current))? $current:$lang->defaultLang();
        $this->setLang($current);

        return;
    }


    // syncTimezone
    // synchronise le timezone de la session avec celle courante de php
    final protected function syncTimezone():void
    {
        $timezone = $this->timezone();
        if(is_string($timezone))
        Base\Timezone::set($timezone);

        return;
    }


    // timezone
    // retourne la timezone de la session à partir de l'utilisateur
    final public function timezone():?string
    {
        $return = null;
        $timezone = $this->user()->timezone();

        if($timezone->isNotEmpty())
        $return = $timezone->relation();

        return $return;
    }


    // userSession
    // si le user est login, vérifie s'il doit toujours l'être
    // si le user est rendu inactif, log out
    // la timestamp différence ne doit pas être plus grande que le login lifetime, sinon logout
    // si loginSingleUser est true et qu'une session plus récente existe, alors loguout
    // lors du logout ne regénère pas le id de la session
    final protected function userSession():void
    {
        $logout = false;
        $neg = null;

        if($this->isSomebody() && $this->canLogin())
        {
            $user = $this->user();
            $loginLifetime = $this->getLoginLifetime();
            $storage = $this->storage();

            if(!$user->isActive())
            {
                $neg = 'userSession/userInactive';
                $logout = true;
            }

            if(empty($logout) && is_int($loginLifetime))
            {
                $timestampDifference = $this->timestampDifference();

                if(is_int($timestampDifference) && $timestampDifference > $loginLifetime)
                {
                    $neg = 'userSession/loginLifetime';
                    $logout = true;
                }
            }

            if(empty($logout) && $this->isLoginSinglePerUser() && method_exists($storage,'sessionMostRecent'))
            {
                $mostRecentStorage = $storage::sessionMostRecent($this->name(),$user,$storage,$this->context());
                if(!empty($mostRecentStorage))
                {
                    $neg = 'userSession/mostRecentStorage';
                    $logout = true;
                }
            }

            $validate = $user->userSessionValidate();
            if(is_string($validate))
            {
                $logout = true;
                $neg = ['userSession',$validate];
            }
        }

        if($logout === true)
        $this->logout(['neg'=>$neg]);

        return;
    }


    // setUser
    // changer le user lié à la session
    // ne change pas le user si c'est le même
    // lance le trigger du role du user une fois le changement réussi
    // possibilité de mettre une row user ou un uid de user
    final public function setUser($value):self
    {
        $this->user();
        $user = $this->user();
        $class = $this->getUserClass();

        if(is_int($value))
        $value = $class::findByUid($value);

        if($value instanceof $class)
        {
            if($value !== $user)
            $this->triggerUser($value);
        }

        else
        static::throw('userNotInstanceOf',$class);

        return $this;
    }


    // setUserNobody
    // attribue le user nobody
    final public function setUserNobody():self
    {
        return $this->setUser($this->getUserNobody());
    }


    // setUserDefault
    // attribue le user par défaut
    final public function setUserDefault():self
    {
        return $this->setUser($this->getUserDefault());
    }


    // setRoles
    // permet de changer les rôles de la session
    // lie à la base de donnée
    final protected function setRoles(Main\Roles $roles):void
    {
        if($roles->isEmpty())
        static::throw('rolesEmpty');

        $this->db()->setRoles($roles);

        return;
    }


    // roles
    // retourne l'objet roles de l'user
    // possible de retourner les fake roles
    final public function roles(bool $fake=true):Main\Roles
    {
        $return = null;

        if($fake === true && $this->allowFakeRoles())
        $return = $this->getFakeRoles();

        if(empty($return))
        $return = $this->user()->roles();

        return $return;
    }


    // role
    // retourne l'objet role de l'user (le rôle principal)
    final public function role(bool $fake=true):Role
    {
        return $this->roles($fake)->main();
    }


    // permission
    // retourne le code de permission du rôle
    final public function permission(bool $fake=true):int
    {
        return $this->role($fake)->permission();
    }


    // setLang
    // change la langue de la session et de l'objet lang
    // une exception est envoyé si la langue n'existe pas dans base lang
    final public function setLang(string $value):parent
    {
        $lang = $this->db()->lang();
        $lang->changeLang($value);

        parent::setLang($value);

        return $this;
    }


    // nav
    // retourne l'objet nav
    final public function nav():Routing\Nav
    {
        return $this->get('nav');
    }


    // navEmpty
    // vide l'objet nav
    final public function navEmpty():self
    {
        $this->nav()->empty();

        return $this;
    }


    // historyPreviousRoute
    // retourne la route de la requête précédente ou un fallback
    // ne peut pas retourner vide
    final public function historyPreviousRoute($fallback=null,bool $hasExtra=true):Route
    {
        $return = null;
        $routes = static::boot()->routes();
        $requestHistory = $this->history();
        $return = $requestHistory->previousRoute($routes,$fallback,$hasExtra);

        return $return;
    }


    // hasFakeRoles
    // retourne vrai si la session a des fake roles
    final public function hasFakeRoles():bool
    {
        return (!empty($this->getFakeRoles()))? true:false;
    }


    // allowFakeRoles
    // retourne vrai si l'utilisateur a la permission d'avoir des fake roles
    final public function allowFakeRoles():bool
    {
        return $this->user()->allowFakeRoles();
    }


    // setFakeRoles
    // applique des fakes rôles si l'utilisateur peut en avoir
    final public function setFakeRoles($roles):void
    {
        if(!$this->allowFakeRoles() && !empty($roles))
        static::throw('fakeRolesNotAllowed');

        if(is_array($roles))
        $roles = static::boot()->roles()->only(...array_values($roles));

        if($roles instanceof Main\Roles)
        {
            $current = $this->permission(false);
            foreach ($roles as $permission => $role)
            {
                if($permission > $current)
                static::throw('cannotSetFakeRole',$permission);
            }

            if($roles->isEmpty())
            $roles = null;
        }

        $this->set('fakeRoles',$roles);

        if($roles === null)
        $roles = $this->user()->roles();

        $this->setRoles($roles);

        return;
    }


    // getFakeRoles
    // retourne les fake roles si l'utilisateur peut en avoir
    final public function getFakeRoles():?Main\Roles
    {
        return ($this->allowFakeRoles())? $this->get('fakeRoles'):null;
    }


    // fakeRolesEmpty
    // vide la valeur fakeRoles de la session
    final public function fakeRolesEmpty():void
    {
        $this->setFakeRoles(null);

        return;
    }


    // flashPost
    // flash les données de post
    // retourne l'objet session flash
    final public function flashPost(Route $route,bool $onlyCol=true,bool $stripTags=false):Flash
    {
        return $this->flash()->setPost($route,$onlyCol,$stripTags);
    }


    // canLogin
    // retourne vrai si le user permet le login dans le type
    final public function canLogin(?string $key=null):bool
    {
        $return = false;
        $user = $this->user();

        if($user->isSomebody() && $user->canLogin($key))
        $return = true;

        return $return;
    }


    // isPasswordVisible
    // retourne vrai si le champ mot de passe devrait s'afficher
    final public function isPasswordVisible(Col $col,Cell $cell):bool
    {
        $return = true;
        $user = $this->user();
        $row = $cell->row();

        if($user === $row)
        $return = false;

        return $return;
    }


    // beforeLogin
    // méthode protégé appelé au début du processus de login
    final protected function beforeLogin(string $connect,string $password,?array $option=null):?string
    {
        $return = null;
        $option = Base\Arr::plus(['password'=>true,'usernameSecurity'=>null,'passwordSecurity'=>null],$option);

        if($this->canLogin())
        $return = 'login/alreadyConnected';

        elseif(strlen($connect) && strlen($connect))
        {
            if(!Base\Validate::isUsername($connect,$option['usernameSecurity']) && !Base\Validate::isEmail($connect))
            $return = 'login/invalidUsername';

            elseif($option['password'] === true && !Base\Validate::isPassword($password,$option['passwordSecurity']))
            $return = 'login/invalidPassword';
        }

        else
        $return = 'login/invalidValues';

        return $return;
    }


    // loginProcess
    // effectue un login sur la session
    // connect est insensible à la case alors que password est sensible à la case
    final public function loginProcess(string $connect,string $password,?array $option=null):bool
    {
        $return = false;
        $userClass = $this->getUserClass();
        $usernameSecurity = $userClass::getUsernameSecurity();
        $passwordSecurity = $userClass::getPasswordSecurity();
        $default = ['remember'=>true,'rehash'=>true,'password'=>true,'increment'=>true,'usernameSecurity'=>$usernameSecurity,'passwordSecurity'=>$passwordSecurity];
        $option = Base\Arr::plus($default,$option);
        $neg = $this->beforeLogin($connect,$password,$option);
        $pos = null;

        if(empty($neg))
        {
            $process = $userClass::loginProcess($connect,$password);

            if(is_string($process))
            $neg = $process;

            elseif(!empty($process))
            $user = $process;

            if(empty($neg))
            {
                $validate = $user->userSessionValidate();
                if(is_string($validate))
                $neg = ['login',$validate];

                else
                {
                    $return = true;
                    $pos = $this->login($connect,$user,$option['remember']);

                    if($option['rehash'] === true)
                    $user->passwordRehash($password);
                }
            }
        }

        $this->com()->posNegLogStrict('login',$return,$pos,$neg,$this->getAttr('log/login'),$option);

        return $return;
    }


    // login
    // méthode appelé après la validation pour le login réussi
    final public function login(string $connect,Row $user,$remember=null):string
    {
        $return = 'login/success';
        $this->regenerateId();
        $this->setUser($user);
        $this->rememberEmpty();
        $this->fakeRolesEmpty();
        $this->onLogin();

        $user->callThis(function() {
            $this->onLogin();
        });

        if($remember === true)
        $remember = ['credential'=>$connect];

        if(is_array($remember) && !empty($remember))
        $this->setsRemember($remember);

        return $return;
    }


    // logoutProcess
    // effectue un logout sur la session
    // cette méthode gère aussi les communications, et l'appel de user onLogout
    // regenerateId est true
    final public function logoutProcess(?array $option=null):bool
    {
        $return = false;
        $option = Base\Arr::plus(['regenerateId'=>true],$option);
        $neg = null;
        $pos = null;
        $user = $this->user();
        $storage = $this->storage();

        if($this->isNobody())
        $neg = 'logout/notConnected';

        elseif(!$this->canLogin())
        $neg = 'logout/cannotBeLogin';

        else
        {
            if($this->isLoginSinglePerUser() && method_exists($storage,'sessionDestroyOther'))
            $storage::sessionDestroyOther($this->name(),$user,$storage,$this->context());

            $return = true;
            $pos = 'logout/success';

            $user->callThis(function() {
                $this->onLogout();
            });

            $this->logout($option);
        }

        $this->com()->posNegLogStrict('logout',$return,$pos,$neg,$this->getAttr('log/logout'),$option);

        return $return;
    }


    // logout
    // effectue un logout sur la session
    // par défaut regenerateId est false
    final public function logout(?array $option=null):self
    {
        $option = Base\Arr::plus(['regenerateId'=>false,'history'=>true,'nav'=>true,'flash'=>true,'fakeRoles'=>true,'pos'=>null,'neg'=>null],$option);

        if($option['regenerateId'] === true)
        $this->regenerateId();

        $this->onLogout();
        $this->setUserNobody();

        if($option['history'] === true)
        $this->historyEmpty();

        if($option['nav'] === true)
        $this->navEmpty();

        if($option['flash'] === true)
        $this->flashEmpty();

        if($option['fakeRoles'] === true)
        $this->fakeRolesEmpty();

        if($option['pos'] !== null)
        $this->com()->pos($option['pos']);

        if($option['neg'] !== null)
        $this->com()->neg($option['neg']);

        return $this;
    }


    // changePassword
    // change le mot de passe de l'utilisateur dans la session
    // les passwords sont sensibles à la case
    // envoie une exception si user est nobody
    final public function changePassword(string $newPassword,?string $newPasswordConfirm=null,?string $oldPassword=null,?array $option=null):bool
    {
        $return = false;

        if($this->isNobody())
        static::throw('notConnected');

        else
        {
            $save = $this->user()->setPassword([$newPassword,$newPasswordConfirm,$oldPassword],$option);

            if($save === 1)
            {
                $this->regenerateId();
                $return = true;
            }
        }

        return $return;
    }
}

// init
Session::__init();
?>