<?php
declare(strict_types=1);
namespace Quid\Core\Row;
use Quid\Core;
use Quid\Main;
use Quid\Base;

// user
class User extends Core\RowAlias implements Main\Contract\User
{
	// config
	public static $config = [
		'key'=>['username'], // colonne utilisé pour key
		'name'=>['name','username'], // colonne(s) utilisé pour le nom d'une ligne
		'relation'=>['what'=>['username','email'],'output'=>'username'],
		'cols'=>[
			'active'=>['class'=>Core\Col\UserActive::class,'general'=>true],
			'role'=>['class'=>Core\Col\UserRole::class,'general'=>true],
			'username'=>true,
			'timezone'=>true,
			'password'=>['class'=>Core\Col\UserPassword::class,],
			'passwordReset'=>[
				'class'=>Core\Col\UserPasswordReset::class,'export'=>false,'exists'=>false],
			'email'=>true,
			'dateLogin'=>true],
		'priority'=>900,
		'nobody'=>['role'=>1], // custom, pour trouver utilisateur nobody
		'log'=>[ // lit des événements à des classes de table
			'register'=>Log::class,
			'changePassword'=>Log::class,
			'resetPassword'=>Log::class,
			'activatePassword'=>Log::class],
		'credentials'=>['email'=>'email','username'=>'username'], // champs valides pour la connexion
		'emailModel'=>[
			'registerAdmin'=>null,
			'registerConfirm'=>null,
			'resetPassword'=>null,
			'userWelcome'=>null],
		'crypt'=>[
			'passwordHash'=>[ // configuration pour passwordHash
				'algo'=>PASSWORD_DEFAULT,
				'options'=>['cost'=>10]],
			'passwordNew'=>10], // longueur d'un nouveau mot de passe
		'@cms'=>[
			'route'=>[
				'userWelcome'=>Core\Cms\SpecificUserWelcome::class],
			'specificOperation'=>[self::class,'specificOperation']],
	];
	
	
	// dynamique
	protected $role = null; // garde une copie de l'objet role
	
	
	// onInserted
	// appelé après une insertion réussi dans core/table insert
	public function onInserted(array $option)
	{
		return $this->onRegister();
	}
	
	
	// onRegister
	// lors de l'enregistrement d'un nouvel utilisateur
	public function onRegister():Main\Contract\User
	{
		if($this->allowRegisterConfirmEmail())
		$this->sendRegisterConfirmEmail();
		
		if($this->allowRegisterAdminEmail())
		$this->sendRegisterAdminEmail();
		
		return $this;
	}
	
	
	// onLogin
	// callback lorsque l'utilisateur login
	public function onLogin():Main\Contract\User
	{
		$db = $this->db();
		$timestamp = Base\Date::timestamp();
		$this->dateLogin()->set($timestamp);
		
		$db->off();
		$this->updateChanged();
		$db->on();
		
		return $this;
	}
	
	
	// onLogout
	// callback lorsque l'utilisateur logout
	public function onLogout():Main\Contract\User
	{
		return $this;
	}
	
	
	// onChangePassword
	// lorsque l'utilisateur a changé son mot de passe
	public function onChangePassword():Main\Contract\User
	{
		return $this;
	}
	
	
	// onResetPassword
	// lorsque l'utilisateur a son mot de passe reset
	// le nouveau mot de passe est donné en argument
	public function onResetPassword(string $password):Main\Contract\User
	{
		return $this;
	}
	
	
	// onActivatePassword
	// lorsque l'utilisateur a activé son mot de passe reset
	public function onActivatePassword():Main\Contract\User
	{
		return $this;
	}
	
	
	// onRegisterConfirmEmailSent
	// lorsque le courriel de confirmation de l'enregistrement a été envoyé à l'utilisateur
	protected function onRegisterConfirmEmailSent():Main\Contract\User
	{
		return $this;
	}
	
	
	// onRegisterAdminEmailSent
	// lorsque le courriel de confirmation de l'enregistrement a été envoyé à l'administrateur
	protected function onRegisterAdminEmailSent():Main\Contract\User
	{
		return $this;
	}
	
	
	// onResetPasswordEmailSent
	// lorsque le courriel de regénération de mot de passe a été envoyé à l'utilisateur
	protected function onResetPasswordEmailSent():Main\Contract\User 
	{
		return $this;
	}
	
	
	// onWelcomeEmailSent
	// lorsque le courriel de bienvenue a été envoyé à l'utilisateur
	protected function onWelcomeEmailSent():Main\Contract\User 
	{
		return $this;
	}
	
	
	// allowRegisterConfirmEmail
	// retourne vrai si le user permet l'envoie de courrier de confirmation de l'enregistrement
	public function allowRegisterConfirmEmail():bool 
	{
		return (!empty($this->registerConfirmEmailModel()))? true:false;
	}
	
	
	// allowRegisterAdminEmail
	// retourne vrai si le user permet l'envoie de courrier de confirmation de l'enregistrement à l'administrateur
	public function allowRegisterAdminEmail():bool 
	{
		return (!empty($this->registerAdminEmailModel()))? true:false;
	}
	
	
	// allowWelcomeEmail
	// retourne vrai si le user permet l'envoie de courrier de bienvenue
	public function allowWelcomeEmail():bool 
	{
		return (!empty($this->welcomeEmailModel()))? true:false;
	}
	
	
	// allowResetPasswordEmail
	// retourne vrai si le user permet l'envoie de courrier pour regénérer le mot de passe
	public function allowResetPasswordEmail():bool 
	{
		return ($this->hasPasswordReset() && !empty($this->resetPasswordEmailModel()) && !empty($this->activatePasswordRoute()))? true:false;
	}
	
	
	// isUpdateable
	// retourne vrai si l'utilisateur peut être modifié
	public function isUpdateable(?array $option=null):bool
	{
		$return = parent::isUpdateable($option);
		
		if($return === true && empty($option['isUpdateable']))
		{
			$return = false;
			$current = static::sessionUser();
			
			$isNobody = $this->isNobody();
			$isAdmin = $this->isAdmin();
			$permission = $this->permission();
			$currentPermission = $current->permission();
			
			if($isNobody === false || $current->isAdmin())
			{
				if($currentPermission > $permission || $current === $this || ($isAdmin === true && $currentPermission === $permission))
				$return = true;
			}
		}
		
		return $return;
	}
	
	
	// isDeleteable
	// retourne vrai si l'utilisateur peut être effacé
	public function isDeleteable(?array $option=null):bool
	{
		$return = parent::isDeleteable($option);
		
		if($return === true)
		{
			$return = false;
			$current = static::sessionUser();
			
			$isNobody = $this->isNobody();
			$permission = $this->permission();
			$currentPermission = $current->permission();
			
			if($isNobody === false && $currentPermission > $permission)
			$return = true;
		}
		
		return $return;
	}
	
	
	// isNobody
	// retourne vrai si le user est nobody
	public function isNobody():bool
	{
		return $this->role()->isNobody();
	}
	
	
	// isSomebody
	// retourne vrai si le user est de rôle somebody
	public function isSomebody():bool
	{
		return $this->role()->isSomebody();
	}
	
	
	// isAdmin
	// retourne vrai si le user est de rôle cron
	public function isAdmin():bool
	{
		return $this->role()->isAdmin();
	}
	
	
	// can
	// retourne vrai si le role permet de faire
	public function can($path):bool 
	{
		return $this->role()->can($path);
	}
	
	
	// canLogin
	// retourne vrai si le role permet le login
	public function canLogin(?string $type=null):bool 
	{
		return $this->role()->canLogin($type);
	}
	
	
	// canDb
	// retourne une permission en lien avec une table dans l'objet base de donnée
	public function canDb(string $action,$table=null):bool 
	{
		return $this->role()->canDb($action,$table);
	}
	
	
	// hasUsername
	// retourne vrai si le user a un username
	public function hasUsername():bool 
	{
		$return = false;
		$username = $this->username();
		$value = $username->value();
		$security = $username->col()->getSecurity();
		
		if($username->isNotEmpty() && Base\Validate::isUsername($value,$security))
		$return = true;
		
		return $return;
	}
	
	
	// hasEmail
	// retourne vrai si le user a un email
	public function hasEmail():bool 
	{
		$return = false;
		$email = $this->email();
		
		if($email->isNotEmpty() && $email->is('email'))
		$return = true;
		
		return $return;
	}
	
	
	// canReceiveEmail
	// retourne vrai si l'utilisateur peut recevoir un courriel
	public function canReceiveEmail():bool
	{
		return ($this->isSomebody() && $this->hasUsername() && $this->hasEmail())? true:false;
	}
	
	
	// isPassword
	// vérifie si le mot de passe est celui donné
	public function isPassword($value):bool
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
	public function hasPasswordReset():bool 
	{
		return ($this->hasCell('passwordReset'))? true:false;
	}
	
	
	// isPasswordReset
	// retourne vrai si la valeur donné est un sha1 du crypt passwordReset
	public function isPasswordReset($value):bool 
	{
		$return = false;
		$passwordReset = $this->passwordReset()->value();
		
		if(is_string($value) && strlen($value) && is_string($passwordReset) && strlen($passwordReset))
		{
			if(Base\Crypt::passwordActivate($passwordReset,1) === $value)
			$return = true;
		}
		
		return $return;
	}
	
	
	// uid
	// retourne le uid du user
	public function uid():int
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
	
	
	// setRole
	// change le role du user
	public function setRole(Main\Role $value):Main\Contract\User 
	{
		$this->role = $value;
		
		return $this;
	}
	
	
	// role
	// retourne le role de la row user
	public function role():Main\Role 
	{
		return $this->role;
	}
	
	
	// permission
	// retourne la permission du role
	public function permission():int 
	{
		return $this->role()::permission();
	}
	
	
	// getEmailArray
	// retourne le tableau pour envoyer un courriel en lien avec l'utilisateur
	// peut retourner null
	// méthode protégé
	protected function getEmailArray(string $name,?array $replace=null):?array 
	{
		$return = null;
		$method = $name.'EmailModel';
		$model = $this->$method();
		
		if(!empty($model))
		{
			$method = $name.'EmailReplace';
			$replace = Base\Arr::replace($this->$method(),$replace);
			
			if(!empty($replace))
			{
				$return = [];
				$return['model'] = $model;
				$return['replace'] = $replace;
			}
		}
		
		return $return;
	}
	
	
	// getEmailModel
	// retourne un modèle de courriel à partir d'une clé
	// méthode protégé
	protected function getEmailModel(string $name):?Main\Contract\Email 
	{
		$return = null;
		$key = $this->attr(['emailModel',$name]);
		
		if(!empty($key))
		$return = Email::find($key);
		
		return $return;
	}
	
	
	// getEmailReplace
	// retourne un tableau de remplacement de base pour les courriels
	// méthode protégé
	protected function getEmailReplace():array 
	{
		$return = [];
		$return['username'] = $this->username();
		$return['email'] = $this->email();
		$return['name'] = $this->fullName();
		
		return $return;
	}
	
	
	// registerConfirmEmail
	// retourne un tableau avec tout ce qu'il faut pour envoyer le courriel pour confirmer l'enregistrement
	public function registerConfirmEmail(?array $replace=null):?array 
	{
		return $this->getEmailArray('registerConfirm',$replace);
	}
	
	
	// registerConfirmEmailModel
	// retourne le model pour le courriel de confirmation de l'enregistrement
	public function registerConfirmEmailModel():?Main\Contract\Email
	{
		return $this->getEmailModel('registerConfirm');
	}
	
	
	// registerConfirmEmailReplace
	// retourne les valeurs de remplacement pour le courriel  de confirmation de l'enregistrement
	public function registerConfirmEmailReplace():array 
	{
		return $this->getEmailReplace();
	}
	
	
	// registerAdminEmail
	// retourne un tableau avec tout ce qu'il faut pour envoyer le courriel pour confirmer l'enregistrement à l'administrateur
	public function registerAdminEmail(?array $replace=null):?array 
	{
		return $this->getEmailArray('registerAdmin',$replace);
	}
	
	
	// registerAdminEmailModel
	// retourne le model pour le courriel de confirmation de l'enregistrement à l'administrateur
	public function registerAdminEmailModel():?Main\Contract\Email
	{
		return $this->getEmailModel('registerAdmin');
	}
	
	
	// registerAdminEmailReplace
	// retourne les valeurs de remplacement pour le courriel  de confirmation de l'enregistrement
	public function registerAdminEmailReplace():array 
	{
		return $this->getEmailReplace();
	}
	
	
	// resetPasswordEmail
	// retourne un tableau avec tout ce qu'il faut pour envoyer le courriel pour regénérer le mot de passe
	public function resetPasswordEmail(?array $replace=null):?array 
	{
		return $this->getEmailArray('resetPassword',$replace);
	}
	
	
	// resetPasswordEmailModel
	// retourne le model pour le courriel de regénération du mot de passe
	public function resetPasswordEmailModel():?Main\Contract\Email
	{
		return $this->getEmailModel('resetPassword');
	}
	
	
	// resetPasswordEmailReplace
	// retourne les valeurs de remplacement pour le courriel de regénération du mot de passe
	public function resetPasswordEmailReplace():array 
	{
		return $this->getEmailReplace();
	}
	
	
	// activatePasswordRoute
	// retourne la route à utiliser pour activer le mot de passe
	public function activatePasswordRoute():?string
	{
		return Core\Route\ActivatePassword::class;
	}
	
	
	// welcomeEmail
	// retourne un tableau avec tout ce qu'il faut pour envoyer le courriel de bienvenue
	public function welcomeEmail(?array $replace=null):?array 
	{
		return $this->getEmailArray('welcome',$replace);
	}
	
	
	// welcomeEmailModel
	// retourne le model pour le courriel de bienvenue ou null
	public function welcomeEmailModel():?Main\Contract\Email
	{
		return $this->getEmailModel('userWelcome');
	}
	
	
	// welcomeEmailReplace
	// retourne les valeurs de remplacement pour le courriel de bienvenue
	public function welcomeEmailReplace():array 
	{
		return $this->getEmailReplace();
	}
	
	
	// username
	// retourne la cellule du username
	public function username():Core\Cell
	{
		return $this->cell('username');
	}
	
	
	// email
	// retourne la cellule du email
	public function email():Core\Cell
	{
		return $this->cell('email');
	}
	
	
	// toEmail
	// retourne un tableau email=>fullName lors de l'envoie dans un email
	// peut retourner null
	public function toEmail():?array 
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
	
	
	// fullName
	// retourne le nom complet de l'utilisateur, par défaut utilise la méthode name
	// doit retourner une string
	public function fullName(?array $option=null):string 
	{
		return $this->cellName()->value();
	}
	
	
	// toSession
	// retourne le tableau a mettre dans session
	public function toSession():array
	{
		$return = [];
		$return['uid'] = $this->primary();
		$return['permission'] = $this->permission();
		
		return $return;
	}
	
	
	// timezone
	// retourne la cellule de timezone
	public function timezone():Core\Cell
	{
		return $this->cell('timezone');
	}
	
	
	// dateLogin
	// retourne la cellule de dateLogin
	public function dateLogin():Core\Cell
	{
		return $this->cell('dateLogin');
	}
	
	
	// password
	// retourne la cellule de password
	public function password():Core\Cell
	{
		return $this->cell('password');
	}
	
	
	// passwordReset
	// retourne la cellule de passwordReset
	public function passwordReset()
	{
		return $this->cell('passwordReset');
	}
	
	
	// setPassword
	// change le mot de passe, peut être une string ou array
	// ne log pas la query
	// envoie une exception si le mot de passe est invalide
	public function setPassword($value,?array $option=null):?int
	{
		$return = null;
		$option = Base\Arr::plus(['onChange'=>true],$option);
		
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
			$e->onCatched($option);
		}
		
		return $return;
	}
	
	
	// setPasswordFromPasswordReset
	// met le password reset comme mot de passe
	// se fait lors d'une activation du mot de passe
	public function setPasswordFromPasswordReset(?array $option=null):?int
	{
		$return = null;
		$passwordReset = $this->passwordReset();
		$value = $passwordReset->value();
		
		if(!empty($value))
		{
			$password = $this->password()->set($value);
			$passwordReset->set(null);

			$db = $this->db()->off();
			$save = $this->updateChanged($option);
			$db->on();

			if(is_int($save))
			$return = $save;
		}
		
		else
		static::throw('emptyPasswordReset');

		return $return;
	}
	
	
	// setPasswordReset
	// crypt le nouveau mot de passe et met le hash dans la cellule passwordReset
	public function setPasswordReset(string $value,?array $option=null):?int 
	{
		$return = null;
		$this->passwordReset()->hashSet($value);
		
		$db = $this->db()->off();
		$save = $this->updateChanged($option);
		$db->on();
		
		if(is_int($save))
		$return = $save;
		
		return $return;
	}
	
	
	// passwordRehash
	// rehash le password si nécessaire
	public function passwordRehash(string $value,?array $option=null):?int 
	{
		$return = null;
		$option = Base\Arr::plus($option,['onChange'=>false]);
		$hashOption = $this->attr('crypt/passwordHash');
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
	public function resetPassword(?array $replace=null,?array $option=null):?string 
	{
		$return = null;
		$neg = $this->loginValidate('resetPassword');
		$com = $this->db()->com();
		$pos = null;
		
		if(empty($neg))
		{
			$newOption = $this->attr('crypt/passwordNew');
			$newPassword = Base\Crypt::passwordNew($newOption);
			
			if(!empty($newPassword))
			{
				$save = $this->setPasswordReset($newPassword,['isUpdateable'=>true]);
				
				if($save === 1)
				{
					$send = $this->sendResetPasswordEmail($newPassword,$replace,$option);
					
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
		
		$com->posNegLogStrict('resetPassword',(is_string($return))? true:false,$pos,$neg,static::$config['log']['resetPassword'] ?? null,$option);
		
		return $return;
	}
	
	
	// validateSend
	// méthode protégé qui valide que le user et le modèle de courriel sont valides
	protected function validateSend(Main\Contract\Email $model,?array $option=null):Main\Contract\User
	{
		$option = Base\Arr::plus(['method'=>'dispatch'],$option);
		
		if(!$this->canReceiveEmail())
		static::throw('userCannotReceiveEmail');
		
		if(!$model->isActive())
		static::throw('invalidEmailModel');
		
		if(!is_string($option['method']) || !method_exists($model,$option['method']))
		static::throw('invalidMethod',$option['method']);
		
		return $this;
	}
	
	
	// sendResetPasswordEmail
	// envoie un email à un utilisateur ayant fait un reset de mot de passe
	// plusieurs exceptions peuvent être envoyés, ne gère pas l'objet de communication
	// méthode protégé
	public function sendResetPasswordEmail(string $password,?array $replace=null,?array $option=null):bool
	{
		$return = false;
		$option = Base\Arr::plus(['key'=>null,'method'=>'dispatch'],$option);
		$array = $this->resetPasswordEmail($replace);
		$route = $this->activatePasswordRoute();

		if(!empty($array) && !empty($route))
		{
			$model = $array['model'];
			$this->validateSend($model,$option);
			
			$key = $option['key'] ?? null;
			$method = $option['method'];
			$replace = $array['replace'];
			$cell = $this->passwordReset();
			$col = $cell->col();
			$security = $col->getSecurity();
			$hash = $cell->value();
			$primary = $this->primary();
			
			if(is_string($hash) && !empty($hash))
			$hash = Base\Crypt::passwordActivate($hash,1);
			
			if(!Base\Validate::isPassword($password,$security))
			static::throw('invalidPassword');
			
			if(!is_string($hash) || empty($hash))
			static::throw('invalidHash');
			
			$route = $route::makeOverload(['primary'=>$primary,'hash'=>$hash]);
			$absolute = $route->uriAbsolute();
			if(empty($absolute))
			static::throw('invalidAbsoluteUri');
			
			$replace['password'] = $password;
			$replace['uri'] = $absolute;
			$return = $model->$method($key,$this,$replace);
			
			if($return === true)
			$this->onResetPasswordEmailSent();
		}
		
		else
		static::throw('cannotSendEmail','resetPassword');
		
		return $return;
	}
	
	
	// sendEmail
	// méthode utilisé par sendWelcomeEmail, sendRegisterConfirmEmail et sendRegisterAdminEmail
	// plusieurs exceptions peuvent être envoyés
	// méthode protégé
	protected function sendEmail(string $type,$to,?array $replace=null,?\Closure $closure=null,?array $option=null):bool
	{
		$return = false;
		$option = Base\Arr::plus(['key'=>null,'method'=>'dispatch'],$option);
		$method = $type.'Email';
		$array = $this->$method($replace);
		
		if(!empty($array) && !empty($to))
		{
			$model = $array['model'];
			$this->validateSend($model,$option);
			$key = $option['key'];
			$method = $option['method'];
			$replace = $array['replace'];
			
			if(!empty($closure))
			$replace = $closure($replace);
			
			$return = $model->$method($key,$to,$replace);
			
			if($return === true)
			{
				$method = 'on'.ucfirst($type).'EmailSent';
				$this->$method();
			}
		}
		
		else
		static::throw('cannotSendEmail',$type);
		
		return $return;
	}
	
	
	// sendWelcomeEmail
	// envoie le courriel de bienvenue
	// plusieurs exceptions peuvent être envoyés
	public function sendWelcomeEmail(?array $replace=null,?array $option=null):bool
	{
		$return = false;
		$closure = function(array $return) {
			if(empty($return['password']) || !is_string($return['password']))
			{
				$newOption = $this->attr('crypt/passwordNew');
				$password = Base\Crypt::passwordNew($newOption);
				
				if($this->setPassword([$password]) !== 1)
				static::throw('cannotChangePassword');
				$return['password'] = $password;
			}
			
			return $return;
		};
		$return = $this->sendEmail('welcome',$this,$replace,$closure,$option);
		
		return $return;
	}
	

	// sendRegisterConfirmEmail
	// envoie le courriel de confirmation de l'enregistrement
	// plusieurs exceptions peuvent être envoyés
	public function sendRegisterConfirmEmail(?array $replace=null,?array $option=null):bool
	{
		return $this->sendEmail('registerConfirm',$this,$replace,$option);
	}
	
	
	// sendRegisterAdminEmail
	// envoie le courriel de confirmation de l'enregistrement à l'administrateur
	// plusieurs exceptions peuvent être envoyés
	public function sendRegisterAdminEmail(?array $replace=null,?array $option=null):bool
	{
		return $this->sendEmail('registerAdmin',$this->getAdminEmail(),$replace,$option);
	}
	
	
	// getAdminEmail
	// retourne le email de l'administrateur
	public function getAdminEmail():?array
	{
		return null;
	}
	
	
	// activatePassword
	// permet d'activer un password à partir d'un sha1 du hash dans password reset
	// option com, strict et log
	public function activatePassword(string $hash,?array $option=null):bool 
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
		
		$com->posNegLogStrict('activatePassword',$return,$pos,$neg,static::$config['log']['activatePassword'] ?? null,$option);
		
		return $return;
	}
	
	
	// loginValidate
	// retourne un message de validation en lien avec la connection du user courant
	// vérifie si le user est actif et peut se connecter
	// une string type doit être fourni
	public function loginValidate(string $type):?string 
	{
		$return = null;
		
		if(!empty($type))
		{
			if(!$this->isActive())
			$return = 'userInactive';
			
			elseif(!$this->canLogin())
			$return = 'userCantLogin';
			
			if(!empty($return))
			$return = $type.'/'.$return;
		}
		
		return $return;
	}
	
	
	// loginProcess
	// fait le processus de login, mais ne change rien à la session
	// si la méthode retourne un user, le login est possible
	// si la méthode retourne une string, c'est un message de communication négatif
	public static function loginProcess(string $connect,string $password) 
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
	public static function resetPasswordProcess(string $email,?array $replace=null,?array $option=null):?string 
	{
		$return = null;
		$table = static::tableFromFqcn();
		$session = static::session();
		$com = $table->db()->com();
		$neg = null;
		$pos = null;
		
		if(!$session->isNobody())
		$neg = 'resetPassword/alreadyConnected';
		
		elseif(strlen($email))
		{
			$user = static::findByEmail($email);

			if(!empty($user))
			$return = $user->resetPassword($replace,$option);
			
			else
			{
				if(!Base\Validate::isEmail($email))
				$neg = 'resetPassword/invalidEmail';
				
				else
				$neg = 'resetPassword/userNotFound';
			}
		}
		
		else
		$neg = 'resetPassword/invalidValue';

		$com->posNegLogStrict('resetPassword',(is_string($return))? true:false,$pos,$neg,static::$config['log']['resetPassword'] ?? null,$option);
		
		return $return;
	}
	
	
	// activatePasswordProcess
	// active le mot de passe de l'utilisateur d'un utilisateur
	// le mot de passe doit avoir été préalablement reset
	public static function activatePasswordProcess(int $primary,string $hash,?array $option=null):bool 
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
	public static function registerValidate(array $data,?string $passwordConfirm=null):?string
	{
		$return = null;
		$session = static::session();
		$password = 'password';
		
		if(!$session->isNobody())
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
	public static function registerProcess(array $data,?string $passwordConfirm=null,?array $option=null):?self 
	{
		$return = null;
		$option = Base\Arr::plus($option,['row'=>true]);
		$table = static::tableFromFqcn();
		$password = 'password';
		$com = $table->db()->com();
		$neg = static::registerValidate($data,$passwordConfirm);
		$pos = null;
		
		if(empty($neg))
		{
			$data[$password] = (array) $data[$password];
			$return = $table->insert($data,$option);
		}
		
		$com->posNegLogStrict('register',($return instanceof self)? true:false,$pos,$neg,static::$config['log']['register'] ?? null,$option);
		
		return $return;
	}
	
	
	// findNobody
	// retourne l'utilisateur nobody
	// envoie une exception si la méthode ne retourne pas une row
	public static function findNobody():Main\Contract\User
	{
		$return = null;
		$table = static::tableFromFqcn();
		$nobody = $table->attr('nobody');
		
		if(is_array($nobody) && !empty($nobody))
		{
			$rows = $table->rows($nobody);
			
			if($rows->isCount(1))
			$return = $rows->first();
		}
		
		if(!$return instanceof self)
		static::throw();
		
		return $return;
	}
	
	
	// findByCredentials
	// retourne un utilisateur à partir des champs valides pour la connexion
	// cette recherche est insensible à la case, seul le mot de passe est sensible à la case
	public static function findByCredentials(string $value):?Main\Contract\User
	{
		$return = null;
		$table = static::tableFromFqcn();
		$credentials = $table->attr('credentials');
		
		if(is_array($credentials))
		{
			$where = [];
			
			foreach ($credentials as $key) 
			{
				$col = $table->col($key);
				$name = $col->name();
				
				if(!empty($where))
				$where[] = 'or';
				
				$where[$name] = $value;
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
	public static function findByUsername(string $value):?Main\Contract\User
	{
		return static::tableFromFqcn()->row($value);
	}
	
	
	// findByUid
	// retourne un utilisateur à partir d'un uid
	public static function findByUid(int $value):?Main\Contract\User 
	{
		return static::tableFromFqcn()->row($value);
	}
	
	
	// findByEmail
	// retourne un utilisateur à partir d'un email
	// cette recherche est insensible à la case, seul le mot de passe est sensible à la case
	public static function findByEmail(string $value):?Main\Contract\User
	{
		$return = null;
		$table = static::tableFromFqcn();
		$credentials = $table->attr('credentials');
		
		if(is_array($credentials) && !empty($credentials['email']))
		{
			$col = $table->col($credentials['email']);
			$return = $table->row([$col->name()=>$value]);
		}
		
		return $return;
	}
	
	
	// specificOperation
	// utilisé dans le cms, permet d'envoyer un courriel de bienvenue à l'utilisateur
	public static function specificOperation(self $row):string 
	{
		$r = '';
		$route = $row->routeClass('userWelcome');
				
		if($row->table()->hasPermission('userWelcome'))
		{
			if($row->isActive() && $row->allowWelcomeEmail() && $row->isUpdateable() && $row->canReceiveEmail())
			{
				$route = $row->routeClass('userWelcome');
				
				if(!empty($route))
				{
					$route = $route::makeOverload($row)->initSegment();
					$data = ['confirm'=>static::langText('common/confirm')];
					$attr = ['name'=>'--userWelcome--','value'=>1,'submit','icon','padLeft','email','data'=>$data];
					$r .= $route->submitTitle(null,$attr);
				}
			}
		}
		
		return $r;
	}
	
	
	// userExport
	// méthode utilisé pour exporter les colonnes et cellules d'un utilisateur
	public static function userExport(array $value,string $type,Core\Cell $cell,array $option):array
	{
		$return = [];
		$col = $cell->col();
		$relation = $col->relation();
		$table = $relation->relationTable();
		$cols = $table->cols()->filter(['attrNotEmpty'=>true],'relationExport');
		$cols = $cols->sortBy('attr',true,'relationExport');
		
		if($type === 'col')
		$return = $cols->label();
		
		else
		{
			$row = $cell->relationRow();
			$cells = $row->cells($cols);
			$return = $cells->pair('exportOne',$option);
		}
		
		return $return;
	}
	
	
	// getUsernameSecurity
	// retourne la sécurité utilisé pour le champ username
	// peut être null
	public static function getUsernameSecurity():?string 
	{
		$return = null;
		$table = static::tableFromFqcn();
		$col = $table->col('username');
		$return = $col->getSecurity();
		
		return $return;
	}
	
	
	// getPasswordSecurity
	// retourne la sécurité utilisé pour le champ password
	// peut être null
	public static function getPasswordSecurity():?string 
	{
		$return = null;
		$table = static::tableFromFqcn();
		$col = $table->col('password');
		$return = $col->getSecurity();
		
		return $return;
	}
}

// config
User::__config();
?>