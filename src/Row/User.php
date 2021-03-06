<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Row;
use Quid\Base;
use Quid\Core;
use Quid\Main;

// user
// class for a row of the user table
class User extends Core\RowAlias
{
    // trait
    use _emailModel;


    // config
    protected static array $config = [
        'key'=>['username'], // colonne utilisé pour key
        'name'=>['name','username'], // colonne(s) utilisé pour le nom d'une ligne
        'priority'=>900,
        'relation'=>['what'=>['username','email'],'output'=>'username'],
        'cols'=>[
            'active'=>['class'=>Core\Col\UserActive::class,'general'=>true],
            'role'=>['class'=>Core\Col\UserRole::class,'general'=>true],
            'username'=>true,
            'timezone'=>true,
            'password'=>['class'=>Core\Col\UserPassword::class],
            'passwordReset'=>[
                'class'=>Core\Col\UserPasswordReset::class,'export'=>false,'exists'=>false],
            'email'=>true,
            'dateLogin'=>true],
        'permission'=>[
            '*'=>['fakeRoles'=>false,'update'=>true]],
        'emailModel'=>[
            'registerAdmin'=>'registerAdmin',
            'registerConfirm'=>'registerConfirm',
            'resetPassword'=>'resetPassword'],
        'register'=>false, // détermine si on peut enregistrer un nouvel utilisateur
        'log'=>[ // lit des événements à des classes de table
            'register'=>Log::class,
            'changePassword'=>Log::class,
            'resetPassword'=>Log::class,
            'activatePassword'=>Log::class],
        'credentials'=>['email'=>'email','username'=>'username'], // champs valides pour la connexion
        'crypt'=>[
            'passwordHash'=>[ // configuration pour passwordHash
                'algo'=>PASSWORD_DEFAULT,
                'options'=>['cost'=>10]],
            'passwordNew'=>10] // longueur d'un nouveau mot de passe
    ];


    // dynamique
    protected ?Main\Roles $roles = null; // garde une copie de l'objet roles


    // onInserted
    // appelé après une insertion réussi dans core/table insert
    protected function onInserted(array $option)
    {
        return $this->onRegister();
    }


    // onLogin
    // callback lorsque l'utilisateur login
    protected function onLogin():void
    {
        $this->updateDateLogin();
        $this->emptyPasswordReset();
    }


    // onLogout
    // callback lorsque l'utilisateur logout
    protected function onLogout():void
    {
        return;
    }


    // onChangePassword
    // lorsque l'utilisateur a changé son mot de passe
    protected function onChangePassword():void
    {
        $this->emptyPasswordReset();
    }


    // onResetPassword
    // lorsque l'utilisateur a son mot de passe reset
    // le nouveau mot de passe est donné en argument
    protected function onResetPassword(string $password):void
    {
        return;
    }


    // onActivatePassword
    // lorsque l'utilisateur a activé son mot de passe reset
    protected function onActivatePassword():void
    {
        return;
    }


    // onChangeActive
    // au changement de la valeur du champ active d'un utilisateur
    protected function onChangeActive(array $option):void
    {
        return;
    }


    // onRegister
    // lors de l'enregistrement d'un nouvel utilisateur
    protected function onRegister():void
    {
        $this->sendRegisterEmails();
    }


    // onCommittedOrDeleted
    // au changement d'une row user, vide la cache si nécessaire
    // pas de commit si c'est un login (évite de vider la cache lors du login)
    protected function onCommittedOrDeleted(array $option)
    {
        if(empty($option['dateLogin']))
        parent::onCommittedOrDeleted($option);

        return;
    }


    // toEmail
    // retourne un tableau email=>fullName lors de l'envoie dans un email
    // peut retourner null
    final public function toEmail():?array
    {
        $return = null;

        if($this->canReceiveEmail())
        {
            $email = $this->email()->value();
            $fullName = $this->fullName();
            $return = [$email=>$fullName];
        }

        return $return;
    }


    // getEmailReplace
    // retourne un tableau de remplacement de base pour les courriels
    protected function getEmailReplace():array
    {
        return [
            'user'=>$this->username(),
            'userEmail'=>$this->email(),
            'userName'=>$this->fullName()
        ];
    }


    // allowRegister
    // retourne vrai si l'utilisateur pourriat procéder à un enregistrement
    // doit être nobody et qu'il y ait au moins un modèle d'email de confirmation
    final public function allowRegister($type=true):bool
    {
        return $this->getAttr('register') === true && ($this->allowRegisterConfirmEmail($type) || $this->allowRegisterAdminEmail($type));
    }


    // allowRegisterConfirmEmail
    // retourne vrai si le user permet l'envoie de courrier de confirmation de l'enregistrement
    public function allowRegisterConfirmEmail($type=true):bool
    {
        return $this->hasEmailModel('registerConfirm',$type);
    }


    // allowRegisterAdminEmail
    // retourne vrai si le user permet l'envoie de courrier de confirmation de l'enregistrement à l'administrateur
    public function allowRegisterAdminEmail($type=true):bool
    {
        return $this->hasEmailModel('registerAdmin',$type);
    }


    // allowResetPasswordEmail
    // retourne vrai si le user permet l'envoie de courrier pour regénérer le mot de passe
    final public function allowResetPasswordEmail($type=true):bool
    {
        return $this->hasPasswordReset() && $this->hasEmailModel('resetPassword',$type) && !empty($this->activatePasswordRoute());
    }


    // registerConfirmEmail
    // retourne un tableau avec tout ce qu'il faut pour envoyer le courriel pour confirmer l'enregistrement
    final protected function registerConfirmEmail($type=true,?array $replace=null):array
    {
        return $this->getEmailArray('registerConfirm',$type,$this->prepareEmailReplace($this->registerConfirmEmailReplace(),$replace));
    }


    // registerConfirmEmailReplace
    // retourne les valeurs de remplacement pour le courriel de confirmation de l'enregistrement
    protected function registerConfirmEmailReplace():array
    {
        return [];
    }


    // registerAdminEmail
    // retourne un tableau avec tout ce qu'il faut pour envoyer le courriel pour confirmer l'enregistrement à l'administrateur
    final protected function registerAdminEmail($type=true,?array $replace=null):array
    {
        return $this->getEmailArray('registerAdmin',$type,$this->prepareEmailReplace($this->registerAdminEmailReplace(),$replace));
    }


    // registerAdminEmailReplace
    // retourne les valeurs de remplacement pour le courriel de confirmation de l'enregistrement
    protected function registerAdminEmailReplace():array
    {
        return [];
    }


    // sendRegisterConfirmEmail
    // envoie le courriel de confirmation de l'enregistrement
    final public function sendRegisterConfirmEmail($type=true,?array $replace=null,?array $option=null):bool
    {
        return $this->sendEmail($this->registerConfirmEmail($type,$replace),$this,$option);
    }


    // sendRegisterAdminEmail
    // envoie le courriel de confirmation de l'enregistrement à l'administrateur
    final public function sendRegisterAdminEmail($type=true,?array $replace=null,?array $option=null):bool
    {
        return $this->sendEmail($this->registerAdminEmail($type,$replace),static::getAdminEmail(),$option);
    }


    // sendRegisterEmails
    // envoie les emails d'enregistrement (à admin et confirmation)
    final public function sendRegisterEmails($type=true):array
    {
        $return = [];

        if($this->allowRegisterConfirmEmail($type))
        $return[] = $this->sendRegisterConfirmEmail();

        if($this->allowRegisterAdminEmail($type))
        $return[] = $this->sendRegisterAdminEmail();

        return $return;
    }


    // resetPasswordEmail
    // retourne un tableau avec tout ce qu'il faut pour envoyer le courriel pour regénérer le mot de passe
    final protected function resetPasswordEmail($type=true,?array $replace=null):array
    {
        return $this->getEmailArray('resetPassword',$type,$this->prepareEmailReplace($this->resetPasswordEmailReplace(),$replace));
    }


    // resetPasswordEmailReplace
    // retourne les valeurs de remplacement pour le courriel de regénération du mot de passe
    protected function resetPasswordEmailReplace():array
    {
        return [];
    }


    // activatePasswordRoute
    // retourne la route à utiliser pour activer le mot de passe
    protected function activatePasswordRoute():?string
    {
        return null;
    }


    // sendResetPasswordEmail
    // envoie un email à un utilisateur ayant fait un reset de mot de passe
    final public function sendResetPasswordEmail(string $password,$type=true,?array $replace=null,?array $option=null):bool
    {
        $return = false;
        $array = $this->resetPasswordEmail($type,$replace);
        $array['closure'] = function(array $return) use($password) {
            $cell = $this->passwordReset();
            $col = $cell->col();
            $security = $col->getSecurity();
            $hash = $cell->value();
            $primary = $this->primary();
            $route = $this->activatePasswordRoute() ?? static::throw('activatePasswordRoute');

            if(is_string($hash) && !empty($hash))
            $hash = Base\Crypt::passwordActivate($hash,1);

            if(!Base\Validate::isPassword($password,$security))
            static::throw('invalidPassword');

            if(!is_string($hash) || empty($hash))
            static::throw('invalidHash');

            $route = $route::make(['primary'=>$primary,'hash'=>$hash]);
            $absolute = $route->uriAbsolute() ?: static::throw('invalidAbsoluteUri');

            $return['userPassword'] = $password;
            $return['activateUri'] = $absolute;

            return $return;
        };

        return $this->sendEmail($array,$this,$option);
    }


    // validateSend
    // méthode protégé qui valide que le user et le modèle de courriel sont valides
    final protected function validateSend(Main\Contract\Email $model,?array $option=null):void
    {
        if(!$this->canReceiveEmail())
        static::throw('userCannotReceiveEmail');

        $this->validateSendModel($model,$option);
    }


    // isUpdateable
    // retourne vrai si l'utilisateur peut être modifié
    final public function isUpdateable(?array $option=null):bool
    {
        $return = parent::isUpdateable($option);

        if($return === true && empty($option['isUpdateable']))
        {
            $return = false;
            $session = static::session();
            $currentUser = $session->user();
            $currentPermission = $session->permission();

            $isNobody = $this->isNobody();
            $isAdmin = $this->isAdmin();
            $permission = $this->permission();

            if($isNobody === false || $session->isAdmin())
            $return = ($currentPermission > $permission || $currentUser === $this || ($isAdmin === true && $currentPermission === $permission));
        }

        return $return;
    }


    // isDeleteable
    // retourne vrai si l'utilisateur peut être effacé
    final public function isDeleteable(?array $option=null):bool
    {
        $return = parent::isDeleteable($option);

        if($return === true)
        {
            $session = static::session();
            $currentPermission = $session->permission();

            $isNobody = $this->isNobody();
            $permission = $this->permission();

            $return = ($isNobody === false && $currentPermission > $permission);
        }

        return $return;
    }


    // is
    // retourne vrai si le role du user a l'attribut à true
    final public function is($value):bool
    {
        return $this->roles()->isOne($value);
    }


    // isNobody
    // retourne vrai si le user est nobody
    final public function isNobody():bool
    {
        return $this->roles()->isNobody();
    }


    // isSomebody
    // retourne vrai si le user est de rôle somebody
    final public function isSomebody():bool
    {
        return $this->roles()->isSomebody();
    }


    // isAdmin
    // retourne vrai si le user est de rôle admin
    final public function isAdmin():bool
    {
        return $this->roles()->isOne('admin');
    }


    // isCli
    // retourne vrai si le user est de rôle cli
    final public function isCli():bool
    {
        return $this->roles()->isOne('cli');
    }


    // attrPermissionRolesObject
    // retourne les roles par défaut à utiliser, soit les rôles de l'utilisateur courant
    final protected function attrPermissionRolesObject():Main\Roles
    {
        return $this->roles();
    }


    // canLogin
    // retourne vrai si le role permet le login
    public function canLogin(?string $type=null):bool
    {
        return $this->hasPermission($this->getLoginKey($type));
    }


    // getLoginKey
    // retourne la clé à utiliser pour la méthode canLogin
    protected function getLoginKey(?string $type=null):string
    {
        $type ??= static::boot()->type();
        return $type.'Login';
    }


    // hasUsername
    // retourne vrai si le user a un username
    final public function hasUsername():bool
    {
        $username = $this->username();
        $value = $username->value();
        $security = $username->col()->getSecurity();

        return $username->isNotEmpty() && Base\Validate::isUsername($value,$security);
    }


    // hasEmail
    // retourne vrai si le user a un email
    final public function hasEmail():bool
    {
        $email = $this->email();
        return $email->isNotEmpty() && $email->is('email');
    }


    // canReceiveEmail
    // retourne vrai si l'utilisateur peut recevoir un courriel
    public function canReceiveEmail():bool
    {
        return $this->isSomebody() && $this->hasUsername() && $this->hasEmail();
    }


    // isPassword
    // vérifie si le mot de passe est celui donné
    final public function isPassword($value):bool
    {
        $return = false;
        $cell = $this->password();
        $password = $cell->value();
        $security = $cell->col()->getSecurity();

        if(is_string($value) && is_string($password) && Base\Validate::isPassword($value,$security))
        $return = Base\Crypt::passwordVerify($value,$password);

        return $return;
    }


    // hasPasswordReset
    // retourne vrai si l'utilisateur a le champ password reset
    final public function hasPasswordReset():bool
    {
        return $this->hasCell('passwordReset');
    }


    // isPasswordReset
    // retourne vrai si la valeur donné est un sha1 du crypt passwordReset
    final public function isPasswordReset($value):bool
    {
        $return = false;
        $passwordReset = $this->passwordReset()->value();

        if(is_string($value) && strlen($value) && is_string($passwordReset) && strlen($passwordReset))
        $return = (Base\Crypt::passwordActivate($passwordReset,1) === $value);

        return $return;
    }


    // uid
    // retourne le uid du user
    final public function uid():int
    {
        return $this->primary();
    }


    // userSessionValidate
    // callback utilisé dans core/session lors de la validation du compte utilisateur pour la session (et aussi du login)
    // si retourne une string ce sera passé à com neg
    // login ou sessionUser sera ajouté au début de la chaîne de retour
    public function userSessionValidate():?string
    {
        return null;
    }


    // setRoles
    // change les roles du user
    final public function setRoles(Main\Roles $value):void
    {
        $this->roles = $value;
    }


    // roles
    // retourne les roles du user
    final public function roles():Main\Roles
    {
        return $this->roles;
    }


    // role
    // retourne le role principal de la row user
    final public function role():Main\Role
    {
        return $this->roles()->main();
    }


    // allowFakeRoles
    // retourne vrai si l'utilisateur a la permission d'avoir des fake roles
    final public function allowFakeRoles():bool
    {
        return $this->hasPermission('fakeRoles');
    }


    // permission
    // retourne la permission du role
    final public function permission():int
    {
        return $this->role()->permission();
    }


    // username
    // retourne la cellule du username
    final public function username():Core\Cell
    {
        return $this->cell('username');
    }


    // email
    // retourne la cellule du email
    final public function email():Core\Cell
    {
        return $this->cell('email');
    }


    // fullName
    // retourne le nom complet de l'utilisateur, par défaut utilise la méthode name
    // doit retourner une string
    public function fullName(?array $option=null):string
    {
        return $this->cellName()->value();
    }


    // toSession
    // retourne le tableau a mettre dans session
    final public function toSession():array
    {
        return [
            'uid'=>$this->primary(),
            'permission'=>$this->permission(),
            'username'=>(string) $this->username()
        ];
    }


    // timezone
    // retourne la cellule de timezone
    final public function timezone():Core\Cell
    {
        return $this->cell('timezone');
    }


    // getTimezone
    // retourne string timezone à partir de l'utilisateur
    final public function getTimezone():?string
    {
        $timezone = $this->timezone();
        return ($timezone->isNotEmpty())? $timezone->relation():null;
    }


    // getLocale
    // retourne la string locale à partir de l'utilisateur
    final public function getLocale():?string
    {
        return null;
    }


    // dateLogin
    // retourne la cellule de dateLogin
    final public function dateLogin():Core\Cell
    {
        return $this->cell('dateLogin');
    }


    // updateDateLogin
    // permet de mettre à jour la date de login
    final public function updateDateLogin(?array $option=null):?int
    {
        $return = null;
        $option = Base\Arr::plus(['include'=>false],$option);
        $db = $this->db();
        $timestamp = Base\Datetime::now();
        $this->dateLogin()->set($timestamp);

        $db->off();
        $return = $this->updateChanged(['include'=>false,'dateLogin'=>true]);
        $db->on();

        return $return;
    }


    // password
    // retourne la cellule de password
    final public function password():Core\Cell
    {
        return $this->cell('password');
    }


    // passwordReset
    // retourne la cellule de passwordReset
    final public function passwordReset()
    {
        return $this->cell('passwordReset');
    }


    // setPassword
    // change le mot de passe, peut être une string ou array
    // ne log pas la query
    // envoie une exception si le mot de passe est invalide
    final public function setPassword($value,?array $option=null):?int
    {
        $return = null;
        $option = Base\Arr::plus(['onChange'=>true,'include'=>false],$option);

        try
        {
            if(!empty($value) && (is_string($value) || is_array($value)))
            {
                $this->password()->set($value,$option);

                $db = $this->db()->off();
                $return = $this->updateChanged($option);

                if($return === 1 && $option['onChange'] === true)
                $this->onChangePassword();

                $db->on();
            }

            else
            static::throw('invalidPassword');
        }

        catch (Main\CatchableException $e)
        {
            $e->catched($option);
        }

        return $return;
    }


    // setPasswordFromPasswordReset
    // met le password reset comme mot de passe
    // se fait lors d'une activation du mot de passe
    final public function setPasswordFromPasswordReset(?array $option=null):?int
    {
        $option = Base\Arr::plus(['include'=>false],$option);
        $passwordReset = $this->passwordReset();
        $value = $passwordReset->value() ?: static::throw('emptyPasswordReset');

        $password = $this->password()->set($value);
        $passwordReset->set(null);

        $db = $this->db()->off();
        $save = $this->updateChanged($option);
        $db->on();

        return (is_int($save))? $save:null;
    }


    // setPasswordReset
    // crypt le nouveau mot de passe et met le hash dans la cellule passwordReset
    final public function setPasswordReset(string $value,?array $option=null):?int
    {
        $option = Base\Arr::plus(['include'=>false],$option);
        $this->passwordReset()->hashSet($value);

        $db = $this->db()->off();
        $save = $this->updateChanged($option);
        $db->on();

        return (is_int($save))? $save:null;
    }


    // emptyPasswordReset
    // enlève la châine du password reset
    final public function emptyPasswordReset(?array $option=null):?int
    {
        $return = null;

        if($this->hasPasswordReset())
        {
            $option = Base\Arr::plus(['include'=>false,'onCommitted'=>false,'isUpdateable'=>true],$option);

            $this->passwordReset()->unset();

            $db = $this->db()->off();
            $save = $this->updateChanged($option);
            $db->on();

            if(is_int($save))
            $return = $save;
        }

        return $return;
    }


    // passwordRehash
    // rehash le password si nécessaire
    final public function passwordRehash(string $value,?array $option=null):?int
    {
        $return = null;
        $option = Base\Arr::plus($option,['onChange'=>false]);
        $hashOption = $this->getAttr('crypt/passwordHash');
        $password = $this->password()->value();

        if(Base\Crypt::passwordNeedsRehash($password,$hashOption))
        $return = $this->setPassword([$value],$option);

        return $return;
    }


    // resetPassword
    // reset le mot de passe de l'utilisateur courant
    // il faut fournir une route, un modèle de courriel, un tableau de remplacement pour le courriel
    // retourne null ou une string contenant le nouveau mot de passe
    // option com, strict et log
    public function resetPassword($type=true,?array $replace=null,?array $option=null):?string
    {
        $return = null;
        $neg = $this->loginValidate('resetPassword');
        $com = $this->db()->com();
        $pos = null;

        if(empty($neg))
        {
            $newOption = $this->getAttr('crypt/passwordNew');
            $newPassword = Base\Crypt::passwordNew($newOption);

            if(!empty($newPassword))
            {
                $save = $this->setPasswordReset($newPassword,['isUpdateable'=>true]);

                if($save === 1)
                {
                    $send = $this->sendResetPasswordEmail($newPassword,$type,$replace,$option);

                    if($send === true)
                    {
                        $this->onResetPassword($newPassword);
                        $pos = 'resetPassword/success';
                        $return = $newPassword;
                    }

                    else
                    $neg = 'resetPassword/email';
                }
            }
        }

        if($return === null && empty($neg))
        $neg = 'resetPassword/error';

        $com->posNegLogStrict('resetPassword',(is_string($return)),$pos,$neg,$this->getAttr(['log','resetPassword']),$option);

        return $return;
    }


    // activatePassword
    // permet d'activer un password à partir d'un sha1 du hash dans password reset
    // option com, strict et log
    final public function activatePassword(string $hash,?array $option=null):bool
    {
        $return = false;
        $neg = $this->loginValidate('activatePassword');
        $pos = null;
        $com = $this->db()->com();

        if(empty($neg))
        {
            if($this->isPasswordReset($hash))
            {
                if($this->setPasswordFromPasswordReset(['isUpdateable'=>true]) === 1)
                {
                    $this->onActivatePassword();
                    $pos = 'activatePassword/success';
                    $return = true;
                }

                else
                $neg = 'activatePassword/error';
            }

            else
            $neg = 'activatePassword/invalidHash';
        }

        if($return === false && empty($neg))
        $neg = 'activatePassword/error';

        $com->posNegLogStrict('activatePassword',$return,$pos,$neg,$this->getAttr(['log','activatePassword']),$option);

        return $return;
    }


    // loginValidate
    // retourne un message de validation en lien avec la connection du user courant
    // vérifie si le user est actif et peut se connecter
    // une string type doit être fourni
    final protected function loginValidate(string $type):?string
    {
        $return = null;

        if(!$this->isActive())
        $return = 'userInactive';

        elseif(!$this->canLogin())
        $return = 'userCantLogin';

        if(!empty($return))
        $return = $type.'/'.$return;

        return $return;
    }


    // loginProcess
    // fait le processus de login, mais ne change rien à la session
    // si la méthode retourne un user, le login est possible
    // si la méthode retourne une string, c'est un message de communication négatif
    final public static function loginProcess(string $connect,string $password)
    {
        $return = null;
        $user = static::findByCredentials($connect);
        $neg = null;

        if(empty($user))
        $neg = 'login/cantFindUser';

        elseif(!$user->isPassword($password))
        $neg = 'login/wrongPassword';

        else
        $neg = $user->loginValidate('login');

        if(!empty($user) && empty($neg))
        $return = $user;

        else
        $return = $neg;

        return $return;
    }


    // resetPasswordProcess
    // reset le mot de passe d'un l'utilisateur
    // connect se fait normalement par email
    // retourne null ou le nouveau password
    final public static function resetPasswordProcess(string $value,$type=true,?array $replace=null,?array $option=null):?string
    {
        $return = null;
        $option = Base\Arr::plus(['onlyEmail'=>true],$option);
        $table = static::tableFromFqcn();
        $session = static::session();
        $com = $table->db()->com();
        $neg = null;
        $pos = null;

        if(!$session->isNobody())
        $neg = 'resetPassword/alreadyConnected';

        elseif($option['onlyEmail'] === true && !Base\Validate::isEmail($value))
        $neg = 'resetPassword/invalidEmail';

        elseif(strlen($value))
        {
            $method = ($option['onlyEmail'] === true)? 'findByEmail':'findByCredentials';
            $user = static::$method($value);

            if(!empty($user))
            {
                if(!$user->hasEmail())
                $neg = 'resetPassword/userNoEmail';

                else
                $return = $user->resetPassword($type,$replace,$option);
            }

            else
            $neg = 'resetPassword/userNotFound';
        }

        else
        $neg = 'resetPassword/invalidValue';

        $com->posNegLogStrict('resetPassword',(is_string($return)),$pos,$neg,static::$config['log']['resetPassword'] ?? null,$option);

        return $return;
    }


    // activatePasswordProcess
    // active le mot de passe de l'utilisateur d'un utilisateur
    // le mot de passe doit avoir été préalablement reset
    final public static function activatePasswordProcess(int $primary,string $hash,?array $option=null):bool
    {
        $return = false;
        $table = static::tableFromFqcn();
        $session = static::session();
        $com = $table->db()->com();
        $neg = null;
        $pos = null;

        if(!$session->isNobody())
        $neg = 'activatePassword/alreadyConnected';

        elseif(!empty($primary))
        {
            $user = static::findByUid($primary);

            if(!empty($user))
            $return = $user->activatePassword($hash,$option);

            else
            $neg = 'activatePassword/userNotFound';
        }

        else
        $neg = 'activatePassword/invalidValue';

        $com->posNegLogStrict('activatePassword',$return,$pos,$neg,static::$config['log']['activatePassword'] ?? null,$option);

        return $return;
    }


    // registerValidate
    // gère la validation avant l'enregistrement
    // peut valider que passwordConfirm est conforme si donnée en argument
    // retourne une string ou null
    final public static function registerValidate(array $data,?string $passwordConfirm=null,?array $option=null):?string
    {
        $return = null;
        $option = Base\Arr::plus(['onlyNobody'=>true],$option);
        $session = static::session();
        $password = 'password';

        if($option['onlyNobody'] === true && !$session->isNobody())
        $return = 'register/alreadyConnected';

        elseif(!empty($data) && array_key_exists($password,$data) && is_string($data[$password]))
        {
            if(is_string($passwordConfirm) && $passwordConfirm !== $data[$password])
            $return = 'register/passwordConfirm';
        }

        else
        $return = 'register/invalidValues';

        return $return;
    }


    // registerProcess
    // gère le processus pour enregistrer un nouvel utilisateur
    // retourne null ou un objet row
    final public static function registerProcess(array $data,?string $passwordConfirm=null,?array $option=null):?self
    {
        $return = null;
        $option = Base\Arr::plus($option,['row'=>true]);
        $table = static::tableFromFqcn();
        $password = 'password';
        $com = $table->db()->com();
        $neg = static::registerValidate($data,$passwordConfirm,$option);
        $pos = null;

        if(empty($neg))
        {
            $data[$password] = (array) $data[$password];
            $return = $table->insert($data,$option);
        }

        $com->posNegLogStrict('register',($return instanceof self),$pos,$neg,static::$config['log']['register'] ?? null,$option);

        return $return;
    }


    // findByRole
    // retourne un utilisateur par un rôle
    // envoie une exception si la méthode ne retourne pas une row
    final public static function findByRole($permission,$order=null):self
    {
        $return = null;
        $table = static::tableFromFqcn();

        if($permission instanceof Core\Role)
        $permission = $permission->permission();

        if(is_int($permission))
        {
            $primary = $table->primary();
            $where = ['role'=>$permission];
            $order ??= [$primary=>'asc'];
            $return = $table->select($where,$order);
        }

        return static::typecheck($return,self::class,$permission);
    }


    // findNobody
    // retourne le premier utilisateur avec le rôle nobody
    final public static function findNobody(?array $order=null):self
    {
        return static::findByRole(static::boot()->roles()->find(fn($role) => $role->isNobody()),$order);
    }


    // findCli
    // retourne le premier utilisateur avec le rôle cli
    final public static function findCli(?array $order=null):self
    {
        return static::findByRole(static::boot()->roles()->find(fn($role) => $role->isCli()),$order);
    }


    // findByCredentials
    // retourne un utilisateur à partir des champs valides pour la connexion
    // cette recherche est insensible à la case, seul le mot de passe est sensible à la case
    final public static function findByCredentials(string $value):?self
    {
        $return = null;
        $table = static::tableFromFqcn();
        $credentials = $table->getAttr('credentials');

        if(is_array($credentials))
        {
            $where = [];

            foreach ($credentials as $key)
            {
                $col = $table->col($key);

                if($col->shouldBeUnique())
                {
                    $name = $col->name();

                    if(!empty($where))
                    $where[] = 'or';

                    $where[$name] = $value;
                }
            }

            if(!empty($where))
            {
                $row = $table->row($where);

                if(!empty($row))
                $return = $row;
            }
        }

        return $return;
    }


    // findByUsername
    // retourne un utilisateur à partir d'un username
    // cette recherche est insensible à la case, seul le mot de passe est sensible à la case
    final public static function findByUsername(string $value):?self
    {
        return static::tableFromFqcn()->row($value);
    }


    // findByUid
    // retourne un utilisateur à partir d'un uid
    final public static function findByUid(int $value):?self
    {
        return static::tableFromFqcn()->row($value);
    }


    // findByEmail
    // retourne un utilisateur à partir d'un email
    // cette recherche est insensible à la case, seul le mot de passe est sensible à la case
    final public static function findByEmail(string $value):?self
    {
        $return = null;
        $table = static::tableFromFqcn();
        $credentials = $table->getAttr('credentials');

        if(is_array($credentials) && !empty($credentials['email']))
        {
            $col = $table->col($credentials['email']);
            $return = $table->row([$col->name()=>$value]);
        }

        return $return;
    }


    // getUsernameSecurity
    // retourne la sécurité utilisé pour le champ username
    final public static function getUsernameSecurity():?string
    {
        $col = static::tableFromFqcn()->col('username');
        return $col->getSecurity();
    }


    // getPasswordSecurity
    // retourne la sécurité utilisé pour le champ password
    final public static function getPasswordSecurity():?string
    {
        $col = static::tableFromFqcn()->col('password');
        return $col->getSecurity();
    }
}

// init
User::__init();
?>