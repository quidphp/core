<?php
declare(strict_types=1);
namespace Quid\Core;
use Quid\Routing;
use Quid\Orm;
use Quid\Main;
use Quid\Base;

// boot
abstract class Boot extends Main\Root
{
	// trait
	use Main\_inst, Main\_option, Main\_attr;
	
	
	// config
	public static $config = [
		// prepare
		'host'=>[], // tableau des hosts avec clés env/type, ne peut pas être mis dans un @
		'path'=>[ // tableau des chemins, ne peut pas être mis dans un @
			'private'=>null,
			'vendor'=>null,
			'public'=>null,
			'storage'=>null],
		'envs'=>['dev','staging','prod'], // définis les environnements, ne peut pas être mis dans un @
		'types'=>['app','cms'], // définis les types applicatif, ne peut pas être mis dans un @
		'climbChar'=>'@', // caractère à mettre avec une clé grimpable, ne peut pas être mis dans un @
		'typeAs'=>[], // permet de spécifier des classes dont les types doivent utiliser un autre type, ne peut pas être mis dans un @
		'request'=>null, // valeur par défaut pour la création de request, ne peut pas être mis dans un @
		'finderShortcut'=>[ // shortcut pour finder
			'vendor'=>'[vendor]',
			'vendorCore'=>'[vendor]/quid5/core',
			'storage'=>'[storage]',
			'storageCache'=>'[storage]/cache',
			'storageLog'=>'[storage]/log',
			'storagePublic'=>'[storage]/public',
			'storagePrivate'=>'[storage]/private',
			'private'=>'[private]',
			'privateJs'=>'[private]/js',
			'privatePhp'=>'[private]/php',
			'privateScss'=>'[private]/scss',
			'public'=>'[public]',
			'publicCss'=>'[public]/css',
			'publicJs'=>'[public]/js',
			'publicMedia'=>'[public]/media',
			'publicStorage'=>'[public]/storage'],
		'configFile'=>null, // fichiers config supplémentaire à charger, doit retourner un tableau
		'live'=>false, // active ou désactive le merge du tableau de configLive
		'configLive'=>[ // peut contenir un tableau de configuration à mettre par-dessus à utiliser si live est true
			'requirement'=>false,
			'writable'=>false,
			'symlink'=>false], 
		'autoload'=>'composer', // type d'autoload utilisé peut être composer, internal ou preload
		'psr4'=>[ // psr4 pour autoload
			'%key%'=>'[private]/php'],
		'composer'=>[ // paramètre pour composer
			'classMapAuthoritative'=>false], // défini si la classmap de composer est définitive, accélère de genre 2-3%
		'option'=>[], // défini les options liés à l'objet boot
		// dispatch
		'requirement'=>true, // s'il faut faire les requirement
		'umaskGroupWritable'=>false, // définit si le serveur doit créer des fichiers/dossiers écrivable par son groupe
		'errorLog'=>'[storageLog]/php.log',
		'phpInfo'=>null, // tue le script et affiche phpinfo
		'kill'=>null, // tue le script, possible d'afficher un message
		'ip'=>null, // ip whiteList ou blackList
		'writable'=>['[storage]','[public]'], // chemins qui doivent être écrivables ou créables
		'timeLimit'=>5, // limit de temps
		'alias'=>[], // alias pour autoload
		'overload'=>[], // overload pour autoload, permet d'obtenir la version surchargé de la classe
		'scheme'=>[], // tableaux des schemes avec env/type
		'finderHost'=>[], // tableau avec host => chemin serveur
		'finderHostTypes'=>['[public]','[storagePublic]'], // chemin à utiliser pour représenter le point racine (public) de chaque type
		'uriShortcut'=>[ // shortcut pour uri, pas besoin de mettre le slash avant
			'public'=>'',
			'media'=>'media'],
		'symlink'=>[ // symlink à créer au chargement
			'[storagePublic]/*'=>'[public]',
			'[vendorCore]/js/jquery'=>'[publicJs]/jquery'],
		'callable'=>[
			'assertActive'=>[Base\Assert::Class,'set',ASSERT_ACTIVE,true],
			'assertBail'=>[Base\Assert::Class,'set',ASSERT_BAIL,true],
			'assertWarning'=>[Base\Assert::Class,'set',ASSERT_WARNING,true],
			'assertQuietEval'=>[Base\Assert::Class,'set',ASSERT_QUIET_EVAL,false],
			'dirCurrent'=>[Base\Dir::class,'setCurrent','[storage]'],
			'rootCacheFile'=>[Base\Root::class,'setCacheFileStorage','[storageCache]'],
			'uriOptionImg'=>[Base\Html::class,'setUriOption','img',['append'=>['v'=>'%version%'],'exists'=>false]],
			'uriOptionLink'=>[Base\Html::class,'setUriOption','link',['append'=>['v'=>'%version%'],'exists'=>false]],
			'uriOptionScript'=>[Base\Html::class,'setUriOption','script',['append'=>['v'=>'%version%'],'exists'=>false]],
			'uriOptionStyle'=>[Base\Style::class,'setUriOption',['append'=>['v'=>'%version%'],'exists'=>false]],
			'emailTest'=>[Base\Email::class,'setTestTo',true],
			'sessionStorage'=>[Base\Session::class,'setSavePath','[storage]/session'],
			'mailerDispatch'=>[Main\ServiceMailer::class,'setDispatch','send'],
			'ormExceptionQuery'=>[Orm\Exception::class,'showQuery',false],
			'errorHtmlDepth'=>[Error::class,'setDefaultHtmlDepth',false],
			'dbHistory'=>[Db::class,'setDefaultHistory',false]],
		'lang'=>'en', // lang à mettre dans setLang
		'response'=>[ // tableau de paramètre à envoyer comme défaut de réponse
			'code'=>500,
			'headers'=>[
				'Quid-Version'=>[Base\Server::class,'quidVersion'],
				'Quid-Request'=>[Base\Request::class,'id'],
				'Quid-Response'=>[Base\Response::class,'id'],
				'Quid-Uri'=>[Base\Request::class,'absolute']],
			'headersCallbackSpeed'=>'Quid-Speed'],
		'speed'=>false, // affiche speed ou closeBody
		'version'=>[], // tableau des versions
		'config'=>[], // permet de merger des config sur une classe
		'configUnset'=>[], // permet de retirer des config sur une classe
		// core
		'cache'=>true, // active ou désactive la cache globale
		'clearCache'=>true, // vide le dossier de cache si cache est false
		'extenders'=>[ // paramètres pour l'étendeurs de classe, si c'est un tableau et que la deuxième valeur est false, quid va tenter de ne pas charger la classe lors du extend
			'role'=>Roles::class,
			'lang'=>[Main\Extender::class,false],
			'service'=>[Main\Extender::class,false],
			'widget'=>[Main\Extender::class,false],
			'table'=>[Main\Extender::class,false],
			'rows'=>[Main\Extender::class,false],
			'row'=>[Main\Extender::class,false],
			'cols'=>[Main\Extender::class,false],
			'col'=>[Main\Extender::class,false],
			'cells'=>[Main\Extender::class,false],
			'cell'=>[Main\Extender::class,false]],
		'routeNamespace'=>null, // permet de spécifier un ensemble de classe de route pour un type
		'compile'=>null, // active ou désactive toutes les compilations (js, scss et php), si c'est null la compilation aura lieu si fromCache est false
		'concatenateJs'=>[ // permet de concatener et minifier des fichiers js au lancement, fournir un tableau to => from
			'[publicJs]/include.js'=>[0=>'[vendorCore]/js/include']],
		'concatenateJsOption'=>null, // option pour la concatenation de js
		'compileScss'=>null, // permet de lancer un encodage scss (fournir un tableau to => from)
		'compileScssOption'=>null, // option pour le rendu du scss
		'concatenatePhp'=>[ // tableau pour la compilation de php, fournir un tableau avec target et option
			'quid'=>[
				'target'=>null,
				'option'=>[
					'registerClosure'=>true,
					'namespace'=>[
						Base::class=>[
							'closure'=>false,
							'priority'=>['_root.php','Root.php','Assoc.php','Listing.php','Set.php','Obj.php','Str.php','Finder.php','File.php','Request.php','Sql.php','Uri.php']],
						Base\Test::class=>['closure'=>false],
						Main::class=>[
							'closure'=>false,
							'priority'=>['_root.php','_rootClone.php','Root.php','ArrObj.php','ArrMap.php','Exception.php','Map.php','Res.php','File.php','Service.php','Widget.php','File/_log.php','File/_storage.php','File/Html.php','File/Dump.php','File/Log.php','File/Serialize.php','File/Json.php','Map/_classeObj.php','Map/_obj.php']],
						Main\Test::class=>['closure'=>false],
						Orm::class=>[
							'closure'=>false,
							'priority'=>['_tableAccess.php','Relation.php']],
						Orm\Test::class=>[
							'closure'=>false],
						Routing::class=>['closure'=>true],
						Routing\Test::class=>['closure'=>false],
						__NAMESPACE__=>['closure'=>true],
						Test::class=>['closure'=>false]]]]], 
		'langRow'=>Row\Lang::class, // row pour contenu additionnel lang
		'langOption'=>null, // option pour lang, peut être une callable
		'service'=>null, // tableau des classes credentials pour les services
		'serviceMailer'=>'mailer', // clé par défaut pour le serviceMailer
		'db'=>null, // tableau de connexion à db
		'dbOption'=>null, // option pour db, peut être une callable
		'sessionStorage'=>Row\Session::class, // classe pour le storage de la session
		'sessionOption'=>[], // permet de spécifier des paramètres à passer à session, peut être une callable
		'sessionVersionMatch'=>true, // spécifie si la version doit match pour continuer la session
		'redirection'=>null, // tableau redirection par défaut, peut être une callable
		'redirectionRow'=>Row\Redirection::class, // row pour contenu additionnel de redirection
		'redirectLog'=>Row\LogHttp::class, // classe log pour les mauvaises requêtes http
		'@app'=>[
			'sessionVersionMatch'=>false,
			'compileScss'=>[
				'[publicCss]/app.css'=>[
					0=>'[vendorCore]/scss/include/normalize.css',
					1=>'[vendorCore]/scss/include/include.scss',
					2=>'[vendorCore]/scss/include/component.scss',
					50=>'[privateScss]/app/app.scss']],
			'concatenateJs'=>[
				'[publicJs]/app.js'=>'[privateJs]/app']],
		'@cms'=>[
			'option'=>[
				'background'=>null,
				'logo'=>null],
			'compileScss'=>[
				'[publicCss]/cms.css'=>[
					0=>'[vendorCore]/scss/include/normalize.css',
					1=>'[vendorCore]/scss/include/include.scss',
					2=>'[vendorCore]/scss/include/component.scss',
					3=>'[vendorCore]/scss/cms/include.scss',
					4=>'[privateScss]/cms/include.scss',
					20=>'[vendorCore]/scss/cms/cms.scss',
					50=>'[privateScss]/cms/cms.scss']],
			'concatenateJs'=>[
				'[publicJs]/cms.js'=>[
					0=>'[vendorCore]/js/cms',
					10=>'[privateJs]/cms']]],
		'@dev'=>[
			'cache'=>false,
			'umaskGroupWritable'=>true,
			'callable'=>[
				'uriOptionImg'=>[Base\Html::class,'setUriOption','img',['append'=>true,'exists'=>true]],
				'uriOptionLink'=>[Base\Html::class,'setUriOption','link',['append'=>true,'exists'=>true]],
				'uriOptionScript'=>[Base\Html::class,'setUriOption','script',['append'=>true,'exists'=>true]],
				'uriOptionStyle'=>[Base\Style::class,'setUriOption',['append'=>true,'exists'=>true]],
				'mailerDispatch'=>[Main\ServiceMailer::class,'setDispatch','queue'],
				'ormExceptionQuery'=>[Orm\Exception::class,'showQuery',true],
				'errorHtmlDepth'=>[Error::class,'setDefaultHtmlDepth',true],
				'dbHistory'=>[Db::class,'setDefaultHistory',true]],
			'concatenateJsOption'=>['compress'=>false],
			'compileScssOption'=>['compress'=>false]],
		'@prod'=>[
			'cache'=>true,
			'composer'=>[
				'classMapAuthoritative'=>true]]
	];

	
	// map
	protected static $allow = ['sort','set','unset','remove','empty','replace','overwrite']; // méthodes permises pour map


	// quidVersion
	protected static $quidVersion = '5.26.0'; // version de quid
	
	
	// quidCredit
	protected static $quidCredit = [ // les crédits de quid
		'name'=>'Quid',
		'author'=>'Pierre-Philippe Emond',
		'email'=>'emondpph@gmail.com',
		'type'=>'GPLv3',
		'github'=>'https://github.com/emondpph/quid5',
		'readme'=>'https://github.com/emondpph/quid5/blob/master/README.md',
		'license'=>'https://github.com/emondpph/quid5/blob/master/LICENSE'
	];
	
	
	// replaceMode
	protected static $replaceMode = ['=writable','=types','=envs']; // défini les config à ne pas merger récursivement


	// init
	protected static $init = false; // s'il y a déjà eu un init
	
	
	// dynamique
	protected $name = null; // nom du boot
	protected $value = []; // argument de départ
	protected $status = 0; // niveau de préparation de l'objet
	protected $envType = null; // garde en mémoire le envType
	protected $extenders = null; // garde l'objet extenders
	protected $route = null; // classe de la dernière route qui a été trigger
	protected $fromCache = false; // détermine si la cache pour extenders est utilisé
	
	
	// construct
	// construit et prépare l'objet boot
	protected function __construct(?array $value=null)
	{
		$value = (array) $value;
		$root = static::nameFromClass();
		
		if(empty($root))
		static::throw('extendedBootClass','requiresNamespace');
		
		$this->setName($root);
		$this->makeInitialAttr($value);
		$this->setStatus(1);
		
		return;
	}
	
	
	// toString
	// retourne le contexte en string
	public function __toString():string
	{
		return implode('-',$this->context());
	}


	// destruct
	// détruit l'objet boot
	public function __destruct() 
	{
		$this->terminate();
		
		return;
	}
	
	
	// onPrepare
	// callback au début de prepare
	protected function onPrepare():self 
	{
		return $this;
	}
	
	
	// onDispatch
	// callback au début de dispatch
	protected function onDispatch():self 
	{
		return $this;
	}
	
	
	// onCloseBody
	// callback à lancer au closeBody de la réponse
	protected function onCloseBody():?\Closure
	{
		return null;
	}
	
	
	// onCloseDown
	// callback à lancer au closeDown de la réponse (avant le shutdown de php)
	protected function onCloseDown():?\Closure
	{
		return null;
	}
	
	
	// onShutDown
	// callback à lancer au shutdown de la réponse
	protected function onShutDown():?\Closure
	{
		return null;
	}
	
	
	// onCore
	// callback au début de core
	protected function onCore():self 
	{
		return $this;
	}
	
	
	// onReady
	// callback étant appelé une fois le status à ready (4)
	protected function onReady():self 
	{
		return $this;
	}
	
	
	// onLaunch
	// callback au début de launch
	protected function onLaunch():self 
	{
		return $this;
	}
	
	
	// onAfter
	// callback une fois tout fini
	protected function onAfter():self 
	{
		return $this;
	}
	
	
	// cast
	// retourne la valeur cast, le tableau contexte
	public function _cast():array 
	{
		return $this->context();
	}
	
	
	// prepare
	// prépare l'objet
	// init error, le code de réponse, crée la request, génère le envType, ensuite merge les attr, finalement autoload
	public function prepare():self 
	{
		$this->setInst();
		$this->checkStatus(1);
		
		if(static::isInit() === true)
		static::throw('bootAlreadyPrepared');
		
		$this->onPrepare();
		
		Main\Error::init();
		Base\Response::serverError();
		
		$this->makeRequest();
		$this->checkHost();
		$this->makeEnvType();
		
		$closure = $this->makeConfigClosure();
		Base\Root::setConfigCallable($closure);
		$this->makeFinalAttr();
		
		$this->autoload();

		$option = $this->attr('option');
		$this->option($option);
		
		$this->setStatus(2);
		static::$init = true;
		
		return $this;
	}
	
	
	// dispatch
	// prépare l'objet
	// plusieurs changements sont envoyés, ceci affecte php et les classes statiques
	public function dispatch():self 
	{
		$this->checkStatus(2);
		$this->onDispatch();
		
		if($this->attr('requirement') === true)
		static::requirement();
		
		$this->ini();
		$this->makeFinderShortcut();
		
		$umaskGroupWritable = $this->attr('umaskGroupWritable');
		if(is_bool($umaskGroupWritable))
		Base\Finder::umaskGroupWritable($umaskGroupWritable);

		$errorLog = $this->attr('errorLog');
		if($errorLog !== null)
		static::setErrorLog($errorLog);

		$phpInfo = $this->attr('phpInfo');
		if(!empty($phpInfo))
		Base\Server::phpInfo($phpInfo);
		
		$kill = $this->attr('kill');
		if(!empty($kill))
		Base\Response::kill($kill);

		$ip = $this->attr('ip');
		if($ip !== null)
		$this->checkIp($ip);

		$writable = $this->attr('writable');
		if($writable !== null)
		static::checkWritable($writable);

		$timeLimit = $this->attr('timelimit');
		if(is_int($timeLimit))
		Base\Response::timeLimit($timeLimit);

		$alias = $this->attr('alias');
		if(is_array($alias) && !empty($alias))
		Main\Autoload::setsAlias($alias);

		$overload = $this->attr('overload');
		if(is_array($overload) && !empty($overload))
		Base\Autoload::setsOverload($overload);

		$scheme = $this->attr('scheme');
		if(is_array($scheme) && !empty($scheme))
		{
			$scheme = $this->getSchemeArray($scheme);
			if(!empty($scheme))
			Base\Uri::schemeStatic($scheme);
		}

		$finderHost = $this->attr('finderHost');
		if(is_array($finderHost) && !empty($finderHost))
		Base\Finder::host($finderHost);
		
		$finderHostTypes = $this->attr('finderHostTypes');
		if(!empty($finderHostTypes))
		{
			$finderHostTypes = $this->getFinderHostTypes(true,$finderHostTypes);
			if(!empty($finderHostTypes))
			Base\Finder::host($finderHostTypes);
		}

		$uriShortcut = $this->attr('uriShortcut');
		if(is_array($uriShortcut) && !empty($uriShortcut))
		$this->setsUriShortcut($uriShortcut);
		
		$symlink = $this->attr('symlink');
		if(is_array($symlink) && !empty($symlink))
		static::setsSymlink($symlink);
		
		$callable = $this->attr('callable');
		if(is_array($callable) && !empty($callable))
		static::setsCallable($callable);
		
		Error::init();
		
		$lang = $this->attr('lang');
		if(!empty($lang))
		Base\Lang::set(null,$lang);
		
		$response = $this->attr('response');
		$response = Base\Call::digStaticMethod($response);
		Base\Response::prepare($response);

		$speed = $this->attr('speed');
		if($speed === true)
		Base\Response::speedOnCloseBody();
		
		$closeBody = $this->onCloseBody();
		if(!empty($closeBody))
		Base\Response::onCloseBody($closeBody);
		
		$closeDown = $this->onCloseDown();
		if(!empty($closeDown))
		Base\Response::onCloseDown($closeDown);
		
		$shutDown = $this->onShutDown();
		if(!empty($shutDown))
		Base\Response::onShutDown($shutDown);
		
		$config = $this->attr('config');
		if(is_array($config) && !empty($config))
		static::setsConfig($config);
	
		$configUnset = $this->attr('configUnset');
		if(is_array($configUnset) && !empty($configUnset))
		static::unsetsConfig($configUnset);
		
		Base\Session::setStaticEnv($this->env());
		Base\Session::setStaticType($this->type());
		Base\Session::setStaticVersion($this->version());
	
		$this->setStatus(3);
		
		return $this;
	}
	
	
	// core
	// gère l'extension du core avec celui du package et de l'application
	// une fois que le statut est à 4, les objets lang, services, db et session peuvent être crées
	protected function core():self 
	{
		$this->checkStatus(3);
		$this->onCore();
		
		if($this->attr('clearCache') === true && !$this->shouldCache())
		static::emptyCacheFile();
		
		$this->makeExtenders();
		
		$error = Error::getOverloadClass();
		$error::init();
		
		if($this->shouldCompile())
		$this->compile();
		
		$this->setStatus(4);
		$this->session();
		$this->manageRedirect();
		$this->onReady();
		
		return $this;
	}
	
	
	// compile
	// permet de compile le js, scss et php
	protected function compile():self 
	{
		$js = $this->attr('concatenateJs');
		$jsOption = $this->attr('concatenateJsOption');
		if(is_array($js) && !empty($js))
		$this->concatenateJs($js,$jsOption);
		
		$scss = $this->attr('compileScss');
		$scssOption = $this->attr('compileScssOption');
		if(is_array($scss) && !empty($scss))
		$this->compileScss($scss,$scssOption);
		
		$php = $this->attr('concatenatePhp');
		if(is_array($php) && !empty($php) && !$this->isPreload())
		$this->concatenatePhp($php);
		
		return $this;
	}
	
	
	// launch
	// match la route avec la request et lance la route
	protected function launch():self 
	{
		$this->checkReady();
		$this->onLaunch();
		$request = $this->request();
		$session = $this->session();
		$routes = $this->routesActive();
		$match = null;
		$once = false;
		
		while ($match = $routes->matchOne($request,$session,$match)) 
		{
			$once = true;
			$route = new $match($request);
			$one = $route->run(true);
			
			if($one['bool'] === true)
			$this->setRoute($match);
			
			if($one['continue'] === true)
			continue;
			
			else
			break;
		}
		
		$this->setStatus(5);
		$this->onAfter();
		
		if($once === false)
		static::throw('noRouteMatch');
		
		return $this;
	}
	
	
	// match
	// match les routes avec la requête
	public function match():array
	{
		return $this->routesActive()->match($this->request(),$this->session());
	}


	// setRoute
	// garde en mémoire la dernière classe de la route qui a été triggé
	// méthode protégé
	protected function setRoute(string $value):self 
	{
		if(is_subclass_of($value,Route::class,true))
		$this->route = $value;
		
		else
		static::throw();
		
		return $this;
	}


	// route
	// retourne le nom de la classe de la route qui a été triggé, si existant
	public function route():?string
	{
		return $this->route;
	}
	
	
	// terminate
	// termine et détruit l'objet boot
	// commit la session si elle est toujours active
	public function terminate():self
	{
		Base\Root::setConfigCallable(null);
		
		if($this->isReady())
		{
			$insts = [Lang::class,Services::class,Redirection::class,Session::class,Request::class];
			foreach ($insts as $class) 
			{
				$obj = $class::instSafe();
				if(!empty($obj) && $obj->inInst())
				$obj->unsetInst();
			}
			
			$db = Db::instSafe();
			if(!empty($db) && $db->isReady())
			$db->disconnect();
		}
		
		$this->setStatus(0);
		$this->option = [];
		$this->attr = [];
		$this->name = null;
		$this->envType = null;
		$this->extenders = null;
		
		if($this->inInst())
		$this->unsetInst();
		
		return $this;
	}
	
	
	// isStatus
	public function isStatus($value):bool 
	{
		return ($this->status() === $value)? true:false;
	}
	

	// setStatus
	// change le numéro de statut de boot
	protected function setStatus(int $value):self 
	{
		$this->status = $value;
		
		return $this;
	}
	
	
	// status
	// retourne le numéro de statut de boot
	public function status():int
	{
		return $this->status;
	}
	
	
	// checkStatus
	// envoie une exception si le statut n'est pas le bon
	public function checkStatus(int $value):self
	{
		$this->checkInst();
		
		if(!$this->isStatus($value))
		static::throw('statusIsNot',$value);
		
		return $this;
	}
	
	
	// isReady
	// retourne vrai si boot est ready, status 4 ou >
	public function isReady():bool
	{
		return ($this->status >= 4)? true:false;
	}


	// checkReady
	// envoie une exception si boot n'est pas ready, sinon retourne boot
	public function checkReady():self 
	{
		$this->checkInst();
		
		if(!$this->isReady())
		static::throw('notReady');

		return $this;
	}
	
	
	// setName
	// attribue un nom à l'objet boot
	protected function setName(string $value):self 
	{
		$this->name = lcfirst($value);
		
		return $this;
	}
	
	
	// name
	// retourne le nom du boot
	public function name(bool $ucfirst=false):string 
	{
		$return = $this->name;
		
		if($ucfirst === true)
		$return = ucfirst($return);
		
		return $return;
	}

		
	// makeInitialAttr
	// génère les attributs, merge le tableau avec la static config
	protected function makeInitialAttr(array $value):self
	{
		$this->value = $value;
		$parent = get_parent_class(static::class);
		$replaceMode = static::configReplaceMode();
		$keys = static::unclimbableKeys();
		$closure = function(...$values) use($replaceMode) {
			return Base\Arrs::replaceWithMode($replaceMode,...$values);
		};
		
		$merge = Base\Classe::propertyMergeParents('config',$parent,$closure,false);
		$keep = Base\Arr::gets($keys,$merge);
		$value = $closure($keep,static::$config,$value);
		$this->makeAttr($value);
		
		return $this;
	}
	
	
	// makeFinalAttr
	// génère les attributds finaux, maintenant que le envType et la closure pour merge les config sont sets
	// gère aussi le configFile et live
	protected function makeFinalAttr():self 
	{
		static::__config();
		$attr = $this->replaceSpecial(static::class,static::configReplaceMode(),static::$config,$this->value);
		$this->makeAttr($attr);
		$this->makeFinderShortcut();
		
		$configFile = $this->attr('configFile');
		$merge = [];
		if(!empty($configFile))
		$merge = $this->getConfigFile((array) $configFile);
		
		$live = $this->attr('live');
		$liveConfig = $this->attr('configLive');
		if($live === true && is_array($liveConfig) && !empty($liveConfig))
		$merge[] = $liveConfig;
		
		if(!empty($merge))
		{
			$attr = $this->replaceSpecial(static::class,static::configReplaceMode(),$this->attr(),...$merge);
			$this->makeAttr($attr);
		}
		
		return $this;
	}
	
	
	// getConfigFile
	// fait un require sur le ou les fichiers fichier de config additionnel
	// retourne un tableau multidimensionnel
	protected function getConfigFile(array $files):array
	{
		$return = [];
		
		foreach ($files as $file) 
		{
			if(is_string($file))
			{
				$file = Base\Finder::path($file);
				
				if(file_exists($file))
				{
					$array = require $file;
					if(is_array($array) && !empty($array))
					$return[] = $array;
				}
			}
		}
		
		return $return;
	}
	
	
	// makeFinderShortcut
	// génère les finder shortcut
	protected function makeFinderShortcut():self
	{
		$finderShortcut = $this->attr('finderShortcut');
		if(is_array($finderShortcut) && !empty($finderShortcut))
		{
			$finderShortcut = $this->makePaths($finderShortcut);
			Base\Finder::setsShortcut($finderShortcut);
		}
		
		return $this;
	}
	
	
	// makeRequest
	// crée la requête et conserve dans l'objet
	// méthode protégé
	protected function makeRequest():self 
	{
		$value = $this->attr('request');
		$request = Request::newOverload($value);
		$request->setInst();
		
		if($request->hasFiles())
		$request->setLogData(['files'=>'youBET']);
		
		return $this;
	}
	
	
	// request
	// retourne l'objet request
	public function request():Request 
	{
		return Request::inst();
	}
	
	
	// envs
	// retourne tous les environnements
	public function envs():array 
	{
		return $this->attr('envs');
	}
	
	
	// types
	// retournes tous les types d'applications
	public function types():array 
	{
		return $this->attr('types');
	}
	
	
	// paths
	// retourne tous les chemins
	public function paths():array 
	{
		return $this->attr('path');
	}
	
	
	// path
	// retourne un chemin 
	public function path(string $key):string 
	{
		return $this->attr(['path',$key]);
	}
	
	
	// pathOverview
	// retourne le total des lignes et size pour les paths
	// possible de filtrer par extension
	public function pathOverview($path=null,$extension=null):array
	{
		$return = [];
		$extension = ($extension===null)? [Base\Finder::phpExtension(),'js','scss']:$extension;
		$return['size'] = 0;
		$return['format'] = null;
		$return['line'] = 0;
		
		if(is_string($path))
		$paths = (array) static::path($path);
		else
		$paths = $this->paths();

		foreach ($paths as $key => $value) 
		{
			$return['line'] += Base\Dir::line($value,$extension);
			$return['size'] += Base\Dir::size($value,false,$extension);
			$return['format'] = Base\Number::sizeFormat($return['size']);
			$return['path'][$key] = Base\Dir::subDirLine($value,null,$extension);
		}

		return $return;
	}


	// makePaths
	// fait plusieurs paths, sans passer par finder
	// si replaceKey est true, remplace %key% par le nom de boot
	public function makePaths(array $array,bool $replaceKey=false):array 
	{
		$return = [];
		
		foreach ($array as $k => $v) 
		{
			if($replaceKey === true)
			{
				$name = $this->name(true);
				$k = str_replace("%key%",$name,$k);
			}
			
			$return[$k] = $this->makePath($v);
		}
		
		return $return;
	}
	
	
	// makePath
	// fait un path, sans passer par finder
	public function makePath(string $return):string 
	{
		$paths = $this->paths();
		foreach ($paths as $key => $value) 
		{
			$return = str_replace("[$key]",$value,$return);
		}
		
		return $return;
	}
	
	
	// hosts
	// retournes tous les hosts pour le boot
	public function hosts():array 
	{
		return $this->attr('host');
	}
	
	
	// host
	// retourne un host, selon le env et le type
	public function host($env=null,$type=null):?string
	{
		$return = null;
		$hosts = $this->hosts();
		$env = (is_string($env))? $env:$this->env();
		$type = (is_string($type))? $type:$this->type();
		$key = Base\Arrs::keyPrepare([$env,$type]);
		
		if(array_key_exists($key,$hosts))
		$return = $hosts[$key];

		return $return;
	}
	
	
	// checkHost
	// vérifie si le host est valide avec le tableau de host
	// sinon envoie une exception
	protected function checkHost():self 
	{
		$hosts = $this->hosts();
		$request = $this->request();
		$host = $request->host();
		
		if(!is_string($host) || !is_array($hosts) || !in_array($host,$hosts,true))
		static::throw($host,$hosts);
		
		return $this;
	}
	
	
	// makeEnvType
	// génère le envType à partir des tableaux hosts, envs et types
	// la valeur est mise en cache
	protected function makeEnvType():self
	{
		$request = $this->request();
		$host = $request->host();
		$envType = $this->envTypeFromHost($host);	
		
		if(empty($envType))
		static::throw('invalidEnvType',$host);
		
		else
		$this->envType = $envType;
		
		return $this;
	}
	
	
	// envType
	// retourne la valeur envType
	public function envType():array 
	{
		return $this->envType;
	}
	
	
	// context
	// retourne le context courant
	// contient env, type et lang (lang peut changer)
	public function context():array 
	{
		$return = $this->envType();
		$return['lang'] = Base\Lang::current();
		
		return $return;
	}
	
	
	// env
	// retourne l'environnement courant
	public function env():string
	{
		return $this->envType()['env'];
	}
	
	
	// type
	// retourne le type courant de l'application
	public function type():string
	{
		return $this->envType()['type'];
	}
	
	
	// envTypeFromHost
	// retourne le context à partir du host fourni en argument
	protected function envTypeFromHost(string $value):?array 
	{
		return static::envTypeFromValue($value,$this->hosts(),$this->envs(),$this->types());
	}
	
	
	// isEnv
	// retourne vrai si l'environnement est celui fourni en argument
	public function isEnv($value):bool 
	{
		return (is_string($value) && $this->env() === $value)? true:false;
	}
	
	
	// isDev
	// retourne vrai si l'environnement est dev
	public function isDev():bool 
	{
		return ($this->env() === 'dev')? true:false;
	}
	
	
	// isStaging
	// retourne vrai si l'environnement est staging
	public function isStaging():bool 
	{
		return ($this->env() === 'staging')? true:false;
	}
	
	
	// isProd
	// retourne vrai si l'environnement est prod
	public function isProd():bool 
	{
		return ($this->env() === 'prod')? true:false;
	}
	
	
	// isType
	// retourne vrai si le type est celui fourni en argument
	public function isType($value):bool 
	{
		return (is_string($value) && $this->type() === $value)? true:false;
	}
	
	
	// isApp
	// retourne vrai si la clé de l'application roulant présentement est app
	public function isApp():bool 
	{
		return ($this->type() === 'app')? true:false;
	}
	
	
	// isCms
	// retourne vrai si la clé de l'application roulant présentement est cms
	public function isCms():bool 
	{
		return ($this->type() === 'cms')? true:false;
	}
	
	
	// typeAs
	// retourne le ou les types à utiliser pour une classe, en plus du type courant
	// les types à utiliser ont priorités
	public function typeAs(string $class,string $type)
	{
		return $this->attr(['typeAs',$class,$type]);
	}
	
	
	// climbableKeys
	// retourne toutes les clés grimpables pour le tableau
	// un tableau peut être fourni, à ce moment les clés du tableau non existantes sont aussi considérés comme climbables
	public function climbableKeys(?array $values=null):array
	{
		$return = Base\Arr::append($this->envs(),$this->types());
		
		if(is_array($values))
		{
			foreach ($values as $value) 
			{
				if(!in_array($value,$return,true))
				$return[] = $value;
			}
		}
		
		$return = $this->valuesWrapClimb($return);

		return $return;
	}
	
	
	// valuesWrapClimb
	// enrobe les clés à gauche du caractère pour climb
	public function valuesWrapClimb(array $return):array 
	{
		return Base\Arr::valuesWrap($this->attr('climbChar'),'',$return);
	}
	
	
	// makeConfigClosure
	// retourne la closure à utiliser pour le merge de config des classes
	public function makeConfigClosure():\Closure
	{
		return function(string $class,...$values) {
			return $this->replaceSpecial($class,$class::configReplaceMode(),...$values);
		};
	}
	
	
	// replaceSpecial
	// méthode de remplacement complexe utilisé à travers quid
	// permet de merge les clés @ dans les tableaux de config
	public function replaceSpecial(?string $class,array $replaceMode,...$values) 
	{
		$return = null;
		$envType = $this->envType();
		
		if(is_string($class))
		{
			$typeAs = $this->typeAs($class,$envType['type']);
			if(!empty($typeAs))
			$envType = Base\Arr::append($envType,$typeAs);
		}
		
		$envType = array_values($envType);
		$climbable = $this->climbableKeys($envType);
		$envType = $this->valuesWrapClimb($envType);
		
		$return = Base\Arrs::replaceSpecial($envType,$climbable,$replaceMode,...$values);
		
		return $return;
	}
	

	// ini
	// initialise les ini
	// les include paths sont le résultat de la méthode paths
	protected function ini():self 
	{	
		$charset = Base\Encoding::getCharset();
		$timezone = Base\Timezone::get();
		
		Base\Ini::setIncludePath(['public'=>$this->path('public')]);
		Base\Ini::setDefault(['default_charset'=>$charset,'date.timezone'=>$timezone]);
		
		return $this;
	}
	
	
	// isPreload
	// retourne vrai si le type de autoload est preload
	public function isPreload():bool 
	{
		return ($this->autoloadType() === 'preload')? true:false;
	}


	// autoloadType
	// retourne le type d'autoload, peut être internal, composer ou preload
	public function autoloadType():string 
	{
		return $this->attr('autoload');
	}
	
	
	// autoload
	// renvoie vers la bonne méthode d'autoload, selon le type
	protected function autoload():self
	{
		$type = $this->autoloadType();
		
		if(in_array($type,['internal','composer','preload'],true))
		{
			if($type !== 'preload')
			Main\Autoload::registerClosure();
			
			Main\Autoload::registerAlias();
			
			$psr4 = $this->attr('psr4');
			if(!empty($psr4))
			{
				$psr4 = $this->makePaths($psr4,true);
				Main\Autoload::registerPsr4($psr4,true,'__config');
			}
			
			if($type === 'composer')
			$this->autoloadComposer();
		}
		
		else
		static::errorKill('invalidAutoloadType',$type);
		
		return $this;
	}
	
	
	// autoloadComposer
	// complete l'initialization avec l'autoload de composer
	protected function autoloadComposer():self
	{
		$psr4 = static::getPsr4FromComposer();
		Base\Autoload::setsPsr4($psr4);
		
		$authoritative = $this->attr(['composer','classMapAuthoritative']);
		if(is_bool($authoritative))
		{
			$composer = static::composer();
			$composer->setClassMapAuthoritative($authoritative);
		}
		
		return $this;
	}
	
	
	// checkIp
	// vérifie si le ip est valide pour accéder
	// sinon tue le script
	protected function checkIp($value):self 
	{
		if(is_string($value))
		$value = [$value];

		if(is_array($value))
		{
			$request = $this->request();
			$ip = $request->ip();
			
			if(empty($ip) || !Base\Ip::allowed($ip,$value))
			static::throw($ip);
		}

		return $this;
	}


	// getSchemeArray
	// traite un tableau de scheme avec clés pouvant être env/type
	protected function getSchemeArray(array $array):array
	{
		$return = [];
		
		if(!empty($array))
		{
			$envs = $this->envs();
			$types = $this->types();
			
			foreach ($array as $host => $scheme) 
			{
				if(is_string($host) && is_scalar($scheme))
				{
					$envType = static::envTypeFromString($host,$envs,$types);
					if(is_array($envType))
					$host = $this->host($envType['env'],$envType['type']);
					
					if(is_string($host) && !strpos($host,'/'))
					$return[$host] = $scheme;
				}
			}
		}
		
		return $return;
	}
	
	
	// getFinderHostTypes
	// fait un tableau de finderHost à partir d'un type
	// prend le host paramétré et utilise le chemin donné en argument
	protected function getFinderHostTypes($value,$paths):array 
	{
		$return = [];
		
		if(!empty($paths))
		{
			if(!is_array($paths))
			$paths = (array) $paths;
			
			foreach ($paths as $key => $path) 
			{
				$paths[$key] = Base\Finder::shortcut($path);
			}
			
			if($value === true)
			{
				$types = $this->types();
				$value = array_flip($types);
				foreach ($value as $k => $v) 
				{
					$value[$k] = true;
				}
			}

			if(is_array($value) && !empty($value))
			{
				foreach ($value as $type => $bool) 
				{
					if(is_string($type) && $bool === true)
					{
						$host = $this->host(true,$type);
						
						if(is_string($host))
						$return[$host] = $paths;
					}
				}
			}
		}
		
		return $return;
	}
	
	
	// schemes
	// retourne le tableau des schemes
	// si convert est true, le scheme est converti de boolean/port vers la string http/https
	public function schemes(bool $convert=true):array
	{
		$return = $this->attr('scheme');
		
		if($convert === true)
		$return = $this->getSchemeArray($return);
		
		return $return;
	}
	
	
	// scheme
	// retourne un scheme selon l'environnement et le type
	// si convert est true, le scheme est converti de boolean/port vers la string http/https
	public function scheme($env=null,$type=null,bool $convert=true)
	{
		$return = null;
		$schemes = $this->schemes(false);
		$env = (is_string($env))? $env:$this->env();
		$type = (is_string($type))? $type:$this->type();
		$key = Base\Arrs::keyPrepare([$env,$type]);
		
		if(array_key_exists($key,$schemes))
		{
			$return = $schemes[$key];
			
			if($convert === true)
			$return = Base\Http::scheme($return);
		}

		return $return;
	}


	// schemeHost
	// retourne un host avec le scheme
	// le type de scheme est spécifié en troisième argument, par défaut celui de la requête courante
	public function schemeHost($env=true,$type=true,$scheme=null):?string 
	{
		$return = null;
		$host = $this->host($env,$type);

		if(is_string($host))
		{
			if($scheme === null)
			$scheme = $this->scheme($env,$type);
			
			$return = Base\Uri::changeScheme($scheme,$host);
		}

		return $return;
	}


	// schemeHostTypes
	// retourne le schemeHost pour tous les types d'un même environnement
	public function schemeHostTypes($env=true,$scheme=null):array
	{
		$return = [];
		$env = (is_string($env))? $env:$this->env();

		foreach ($this->types() as $type) 
		{
			$schemeHost = $this->schemeHost($env,$type);
			if(!empty($schemeHost))
			$return[$type] = $schemeHost;
		}

		return $return;
	}


	// schemeHostEnvs
	// retourne le schemeHost pour tous les environnements d'un type
	public function schemeHostEnvs($type=true,$scheme=null):array
	{
		$return = [];
		$type = (is_string($type))? $type:$this->type();

		foreach ($this->envs() as $env) 
		{
			$schemeHost = $this->schemeHost($env,$type);
			if(!empty($schemeHost))
			$return[$env] = $schemeHost;
		}

		return $return;
	}
	
	
	// setsUriShortcut
	// permet de lier plusieurs shortcuts à la classe uri
	// si un shortcut est un tableau, passe dans schemeHost
	public function setsUriShortcut(array $shortcut):self
	{
		foreach ($shortcut as $key => $value) 
		{
			if(is_array($value))
			$shortcut[$key] = $this->schemeHost(...$value);
		}
		
		Base\Uri::setsShortcut($shortcut);

		return $this;
	}
	
	
	// versions
	// retourne toutes les versions
	// si quid est true, retourne aussi celle de quid
	public function versions(bool $quid=true):?array
	{
		$return = $this->attr('version');

		if($quid === true)
		{
			$quidVersion = static::quidVersion();
			$return['quid'] = $quidVersion;
		}

		return $return;
	}


	// version
	// retourne la version d'un type, par défaut le type courant
	// si quid est true, retourne aussi celle de quid
	// possible de retourner le zero à la fin d'un numéro de version
	// doit retourner une string
	public function version($type=true,bool $quid=true,bool $removeZero=false):?string
	{
		$return = null;
		$implode = null;
		$types = $this->types();
		$versions = $this->versions($quid);

		if($type === true)
		$type = $this->type();
		
		if(!empty($versions))
		{
			if(is_string($type))
			{
				if(in_array($type,$types,true))
				{
					if(array_key_exists($type,$versions))
					$implode = [$versions[$type]];
					
					else
					$implode = [current($versions)];
					
					if($quid === true)
					{
						$quidVersion = static::quidVersion();
						$implode[] = $quidVersion;
					}
				}
			}

			else
			$implode = $versions;

			if(!empty($implode))
			{
				if($removeZero === true)
				{
					foreach ($implode as $key => $value) 
					{
						if(is_string($value))
						$implode[$key] = Base\Str::stripEnd('.0',$value);
					}
				}
				
				$return = implode('-',$implode);
			}
		}
		
		return $return;
	}
	
	
	// setsSymlink
	// gère la création des symlinks
	// envoie une exception en cas d'erreur
	public function setsSymlink(array $array):self 
	{
		$array = Base\Dir::fromToCatchAll($array);
		$syms = Base\Symlink::sets($array,true,true);

		foreach ($syms as $to => $array) 
		{
			if($array['status'] === false)
			static::throw('from',$array['from'],'to',$to);
		}

		return $this;
	}
	
	
	// setsCallable
	// fait les appels au callable pour configuration plus poussée
	public function setsCallable(array $array):self 
	{
		if(!empty($array))
		{
			$replace = $this->envType();
			$replace['version'] = $this->version();
			$replace = Base\Arr::keysWrap('%','%',$replace);
			$array = Base\Arrs::valuesReplace($replace,$array);
			
			foreach ($array as $key => $value) 
			{
				if(is_array($value) && !empty($value))
				Base\Call::staticClass(...array_values($value));
			}
		}

		return $this;
	}
	
	
	// isFromCache
	// retourne vrai si la cache d'extenders a été trouvé et est utilisé
	public function isFromCache():bool 
	{
		return $this->fromCache;
	}
	
	
	// shouldCache
	// retourne vrai si la cache globale est activé
	public function shouldCache():bool 
	{
		return $this->attr('cache');
	}

	
	// shouldCompile
	// retourne vrai s'il faut compiler le php, js et scss
	public function shouldCompile():bool 
	{
		$return = false;
		$request = $this->request();
		
		if($request->isStandard())
		{
			$return = $this->attr('compile');
			
			if(!is_bool($return))
			$return = ($this->isFromCache())? false:true;
		}

		return $return;
	}
	
	
	// makeExtenders
	// peut créer un nouveau ou utiliser celui de la cache
	// l'extenders est garder comme propriété de l'objet
	protected function makeExtenders():self
	{
		$cache = $this->shouldCache();
		$type = $this->type();
		$version = $this->version();
		$key = ['extenders',$type,$version];
		
		$this->fromCache = true;
		$extenders = static::cacheFile($key,function() {
			$this->fromCache = false;
			$config = (array) $this->attr('extenders');
			return static::newExtenders($config);
		},$cache);

		if($this->isFromCache())
		{
			$core = $extenders->get('core');
			$core->extended()->alias(null,true);
			
			foreach ($extenders as $extender) 
			{
				$extender->overloadSync();
			}
		}

		foreach ($extenders as $key => $extender) 
		{
			if($extender instanceof Routes)
			$extender->setType($key,false);
		}

		$roles = $extenders->get('role');
		$roles->init($type);
		$roles->readOnly(true);
		$routes = $extenders->get($type);
		$routes->init($type);
		$routes->readOnly(true);
		
		$this->extenders = $extenders;
		
		return $this;
	}


	// newExtenders
	// créer et retourne le extenders
	// fait les alias et overload
	protected function newExtenders(array $config):Main\Extenders 
	{
		$return = Main\Extenders::newOverload();
		$currentKey = $this->name(true);
		$currentType = $this->type();
		$types = $this->types();
		$namespaces = static::extendersNamespaces();

		$closure = function(string $class,?string $key=null,array $namespaces,?array $option=null) use($currentKey)  {
			if(is_string($key))
			$ucKey = ucfirst($key);
			$namespace = [];
			
			foreach ($namespaces as $value) 
			{
				$namespace[] = (!empty($key))? Base\Fqcn::append($value,$ucKey):$value;
			}
			
			return $class::newOverload($namespace,$option);
		};
		
		// core
		$core = $closure(Main\Extender::class,null,$namespaces);
		$core->extended()->alias(null,true);
		$core->overload();
		$return->set('core',$core);

		// routes
		foreach ($types as $type) 
		{
			$routeNamespace = $this->attr(['routeNamespace',$type]);
			if(!empty($routeNamespace))
			$extender = $closure(Routes::class,null,$routeNamespace);
			else
			$extender = $closure(Routes::class,$type,$namespaces);
			
			if($type === $currentType)
			$extender->overload();
			
			$return->set($type,$extender);
		}
		
		// config
		foreach ($config as $key => $value) 
		{
			if(is_string($value))
			$value = (array) $value;
			
			if(is_string($key) && is_array($value) && !empty($value))
			{
				$class = current($value);
				$option = Base\Arr::index(1,$value);
				
				if($option === false)
				$option = ['exists'=>false,'overloadKeyPrepend'=>ucfirst($key)];
				
				$extender = $closure($class,$key,$namespaces,$option);
				$extender->overload();
				$return->set($key,$extender);
			}
		}

		return $return;
	}
	
	
	// extenders
	// retourne l'objet extenders
	public function extenders():Main\Extenders 
	{
		return $this->extenders;
	}
	
	
	// routes
	// retourne l'objet routes de boot
	// peut retourner l'objet d'un type différent si fourni en argument
	public function routes(?string $type=null):Routes
	{
		$return = null;

		if($type === null)
		$type = $this->type();

		$return = $this->extenders()->get($type);

		return $return;
	}


	// routesActive
	// retourne l'objet routes de boot
	// mais seuls les routes actives sont incluses dans l'objet de retour
	public function routesActive(?string $type=null):Routes
	{
		return $this->routes($type)->active();
	}


	// roles
	// retourne l'objet roles de boot
	public function roles():Roles
	{
		return $this->extenders()->get('role');
	}
	
	
	// concatenateJs
	// permet de concatener un ou plusieurs dossiers avec fichiers js
	// possible aussi de minifier
	public function concatenateJs(array $value,?array $option=null):Files 
	{
		$return = Files::newOverload();
		
		foreach ($value as $to => $from) 
		{
			if(is_string($to) && !empty($to) && !empty($from))
			{
				$to = File::newCreate($to);
				
				if($to instanceof File\Js)
				$to->concatenateFrom($from,$option);
				
				$return->add($to);
			}
		}
		
		return $return;
	}

	
	// compileScss
	// permet de compiler un ou plusieurs fichiers css/scss
	public function compileScss(array $value,?array $option=null):Files 
	{
		$return = Files::newOverload();
		$variables = $this->getScssVariables();
		
		foreach ($value as $to => $from) 
		{
			if(is_string($to) && !empty($to) && !empty($from))
			{
				$to = File::newCreate($to);

				if($to instanceof File\Css)
				$to->compileFrom($from,null,$variables,10,$option);
				
				$return->add($to);
			}
		}
		
		return $return;
	}
	
	
	// getScssVariables
	// génère un tableau de variable à injecter dans la feuille de style scss
	public function getScssVariables():array 
	{
		$return = [];
		
		foreach (Base\Finder::allShortcuts() as $key => $value) 
		{
			$key = 'finder'.ucfirst($key);
			$return[$key] = Base\Finder::shortcut($value);
		}
		
		foreach (Base\Uri::allShortcuts() as $key => $value) 
		{
			$key = 'uri'.ucfirst($key);
			$return[$key] = Base\Uri::relative($value);
		}
		
		return $return;
	}
	
	
	// concatenatePhp
	// permet de concatener du php à partir de namespace
	// ceci ne peut pas être fait si le autoload est en mode preload
	public function concatenatePhp(array $array):Files
	{
		$return = Files::newOverload();
		$service = Service\PhpConcatenator::class;
		
		if($this->isPreload())
		static::throw('cannotCompile','autoloadIsPreload');
		
		foreach ($array as $arr) 
		{
			if(is_array($arr) && count($arr) === 2)
			{
				$target = $arr['target'] ?? null;
				$option = $arr['option'] ?? null;
				
				if(!empty($target))
				{
					$target = File::newCreate($target);
					if($target instanceof File\Php)
					{
						$compiler = new $service(__METHOD__,$option);
						$target = $compiler->triggerWrite($target);
						$return->add($target);
					}
				}
			}
		}
		
		return $return;
	}
	
	
	// lang
	// retourne ou crée l'objet lang de boot
	// ne charge pas les contenus lang tout de suite, les contenus sont chargés via onLangLoad méthode statique et abstraite
	public function lang():Lang 
	{
		$this->checkReady();
		$return = Lang::instSafe();

		if(empty($return))
		{
			$all = Base\Lang::all();
			$option = (array) $this->attrCall('langOption');
			$option['onLoad'] = function(string $value) {
				$fqcn = $this->langContentClass($value);
				$return = Base\Lang::content($fqcn::config());
				
				$langRow = $this->attr('langRow');
				if(is_string($langRow) && $this->hasDb() && $this->db()->hasTable($langRow))
				{
					$table = $this->db()->table($langRow);
					
					if($table->isColsReady())
					{
						$content = $langRow::grabContent($value,$this->type());
						if(!empty($content))
						$return = Base\Arrs::replace($return,$content);
					}
				}
				
				return $return;
			};

			$return = Lang::newOverload($all,$option);
			$return->setInst();
		}

		return $return;
	}
	
	
	// langContentClass
	// retourne la classe à utiliser pour le contenu de langue à partir d'un code de langue
	public function langContentClass(?string $value=null):string 
	{
		$return = null;
		$value = ucfirst($value);
		$extenders = $this->extenders();
		$lang = $extenders->get('lang');
		$return = $lang->get($value);

		if(empty($value) || !is_subclass_of($return,Base\Config::class,true))
		static::throw('invalidLang',$value);

		return $return;
	}
	
	
	// label
	// retourne le label de l'application courante
	public function label(?string $lang=null):string 
	{
		return $this->lang()->bootLabel($lang);
	}


	// description
	// retourne la description de l'application courante
	public function description(?string $lang=null):?string 
	{
		return $this->lang()->bootDescription($lang);
	}


	// typeLabel
	// retourne le label du type de contexte
	public function typeLabel(?string $lang=null):string 
	{
		return $this->lang()->typeLabel($this->type(),$lang);
	}


	// envLabel
	// retourne le label de l'env de contexte
	public function envLabel(?string $lang=null):string 
	{
		return $this->lang()->envLabel($this->env(),$lang);
	}


	// typeEnvLabel
	// retourne un label combiné pour type et env
	public function typeEnvLabel(string $separator=' / ',?string $lang=null):string 
	{
		return implode($separator,[$this->typeLabel($lang),$this->envLabel($lang)]);
	}
	
	
	// services
	// retourne l'objet services ou crée le et met le dans inst
	public function services():Main\Services 
	{
		$this->checkReady();
		$return = Services::instSafe();

		if(empty($return))
		{
			$services = $this->attr('service');
			$return = Services::newOverload($services);
			$return->setInst();
			$return->readOnly(true);
		}

		return $return;
	}


	// service
	// retourne un objet service à partir d'une clé
	public function service(string $key):?Main\Service
	{
		return $this->services()->get($key);
	}


	// checkService
	// retourne un objet service à partir d'une clé, sinon envoie une exception
	public function checkService(string $key):Main\Service 
	{
		$return = $this->service($key);

		if(!$return instanceof Main\Service)
		static::throw($key);

		return $return;
	}


	// serviceMailer
	// retourne un objet service mailer à partir d'une clé
	public function serviceMailer(?string $key=null):?Main\ServiceMailer 
	{
		$return = null;
		$key = ($key === null)? $this->attr('serviceMailer'):$key;
		
		if(is_string($key))
		$return = $this->service($key);

		return $return;
	}


	// checkServiceMailer
	// retourne un objet service mailer à partir d'une clé, sinon envoie une exception
	public function checkServiceMailer(?string $key=null):Main\ServiceMailer 
	{
		$return = $this->serviceMailer($key);

		if(!$return instanceof Main\ServiceMailer)
		static::throw($key);

		return $return;
	}
	
	
	// redirection
	// retourne ou crée l'objet redirection
	public function redirection():Redirection 
	{
		$this->checkReady();
		$return = Redirection::instSafe();
		
		if(empty($return))
		{
			$redirection = (array) $this->attrCall('redirection');
			
			$redirectionRow = $this->attr('redirectionRow');
			if(is_string($redirectionRow) && $this->hasDb() && $this->db()->hasTable($redirectionRow))
			{
				$content = $redirectionRow::grabContent($this->type());
				if(!empty($content))
				$redirection = Base\Arr::replace($redirection,$content);
			}
			
			$return = Redirection::newOverload($redirection);
			$return->setInst();
			$return->readOnly(true);
		}

		return $return;
	}
	
	
	// db
	// créer ou retourne l'objet db de boot
	public function db():Db 
	{
		$this->checkReady();
		$return = Db::instReady();
		
		if(empty($return))
		{
			$credentials = $this->attr('db');
			
			if(is_array($credentials) && count($credentials) === 3)
			{
				$option = (array) $this->attrCall('dbOption');
				
				if($this->shouldCache())
				{
					$type = $this->type();
					$version = $this->version();
					
					$option['classeClosure'] = function(Main\Extenders $extenders) use($type,$version) {
						$key = ['classe',$type,$version];
						return static::cacheFile($key,function() use($extenders) {
							$this->classe = Orm\Classe::newOverload($extenders,$this->getOption('classe'));
							$this->tablesColsLoad();
							return $this->classe;
						});
					};
					
					$option['schemaClosure'] = function(Db $db) use($type,$version) {
						$key = ['schema',$type,$version];
						return static::cacheFile($key,function() use($db) {
							$schema = Orm\Schema::newOverload(null,$db);
							$schema->all();
							
							return $schema->toArray();
						});
					};
				}
				
				$lang = $this->lang();
				$extenders = $this->extenders();
				$values = Base\Arr::push($credentials,$extenders,$option);
				$return = Db::newOverload(...array_values($values));
				$return->setLang($lang);
				
				$langRow = $this->attr('langRow');
				if(!empty($langRow))
				$return->table($langRow)->cols();
			}
			
			else
			static::throw('invalidCredentials');
		}
		
		return $return;
	}
	
	
	// hasDb
	// retourne vrai si  boot a présentement une db
	public function hasDb():bool 
	{
		return (Db::instReady() instanceof Db)? true:false;
	}
	
	
	// session
	// retourne ou crée l'objet session de boot
	public function session():Session 
	{
		$this->checkReady();
		$return = Session::instSafe();

		if(empty($return))
		{
			$storage = $this->attr('sessionStorage');
			$versionMatch = $this->attr('sessionVersionMatch');
			$option = (array) $this->attrCall('sessionOption');
			
			if(is_bool($versionMatch))
			$option['versionMatch'] = $versionMatch;
			
			$return = Session::newOverload($storage,$option);
			$return->setInst();
		}
		
		elseif(!$return->isReady())
		$return->start();
		
		return $return;
	}
	
	
	// hasSession
	// retourne vrai si boot a présentement une session
	public function hasSession():bool 
	{
		return (Session::instReady() instanceof Session)? true:false;
	}
	
	
	// manageRedirect
	// vérifie la requête et manage les redirections avant de continuer
	// certaines errors vont générer un code http 400 plutôt que 404 (bad request)
	// crée aussi un callback au closeDown dans la classe de log, une entrée sera ajouté si le code de réponse n'est pas positif
	// méthode protégé
	protected function manageRedirect():self
	{
		$request = $this->request();
		$redirection = $this->redirection();
		$manage = $request->manageRedirect($redirection);
		$log = $this->attr('redirectLog');
		
		if($manage['type'] === 'blocked')
		$log = null;

		if(!empty($manage['type']))
		{
			if(!empty($log))
			$log::logOnCloseDown($manage['type'],Base\Arr::unset('type',$manage));
			
			if($manage['location'] !== null)
			Base\Response::redirect($manage['location'],$manage['code'],true);
			
			elseif(!empty($manage['code']))
			Base\Response::error($manage['code'],true);
		}

		// pour les codes non positif en fin au closeDown
		elseif(!empty($log))
		$log::onCloseDown();

		return $this;
	}
	
	
	// info
	// retourne un tableau d'informations sur boot
	public function info():array
	{
		$return = [];
		$return['class'] = static::class;
		$return['status'] = $this->status();
		$return['ready'] = $this->isReady();
		$return['version'] = $this->version();
		$return['fromCache'] = $this->isFromCache();
		$return['label'] = $this->label();
		$return['description'] = $this->description();
		$return['context'] = $this->context();
		$return['attr'] = $this->attr();
		
		return $return;
	}
	
	
	// envTypeFromValue
	// méthode utilisé par envTypeFromHost pour obtenir le env/type d'une valeur dans un tableau
	// il faut fournir envs et types
	public static function envTypeFromValue(string $value,array $values,array $envs,array $types):?array 
	{
		$return = null;
		
		if(in_array($value,$values,true))
		{
			$key = array_search($value,$values,true);
			
			if(is_string($key))
			$return = static::envTypeFromString($key,$envs,$types);
		}
		
		return $return;
	}
	
	
	// envTypeFromString
	// parse une string avec env/type et retourne un tableau
	// il faut fournir envs et types
	public static function envTypeFromString(string $value,array $envs,array $types):?array
	{
		$return = null;
		$explode = explode('/',$value);
		
		if(count($explode) === 2 && in_array($explode[0],$envs,true) && in_array($explode[1],$types,true))
		{
			$return = [];
			$return['env'] = $explode[0];
			$return['type'] = $explode[1];
		}
		
		return $return;
	}
	
	
	// requirement
	// lance les tests de requirement sur le serveur
	public static function requirement():void 
	{
		$server = Base\Server::requirement();
		if(!empty($server))
		static::throw($server);
		
		$extension = Base\Extension::requirement();
		if(!empty($extension))
		static::throw($server);
		
		$ini = Base\Ini::requirement();
		if(!empty($ini))
		static::throw($server);

		return;
	}
	
	
	// composer
	// retourne l'objet composer à partir du pool de callable autoload
	public static function composer():\Composer\Autoload\ClassLoader 
	{
		$return = null;
		
		foreach (Base\Autoload::all() as $key => $value) 
		{
			if(is_array($value) && !empty($value[0]) && $value[0] instanceof \Composer\Autoload\ClassLoader)
			{
				$return = $value[0];
				break;
			}
		}
		
		return $return;
	}
	
	
	// getPsr4FromComposer
	// retourne un tableau avec tous les psr4 de composer
	protected static function getPsr4FromComposer():array 
	{
		$return = [];
		$composer = static::composer();
		$psr4 = $composer->getPrefixesPsr4();
		
		if(!empty($psr4))
		{
			foreach ($psr4 as $namespace => $path) 
			{
				if(is_string($namespace) && is_array($path) && !empty($path))
				{
					$v = current($path);
					$namespace = rtrim($namespace,"\\");
					$return[$namespace] = $v;
				}
			}
		}
		
		return $return;
	}
	

	// setErrorLog
	// enregistre le errorLog dans le ini
	// tente de le créer, vérifie qu'il est accessible en écriture
	public static function setErrorLog(string $value):void
	{
		if(!Base\File::is($value))
		Base\File::set($value);

		if(!Base\File::isWritableOrCreatable($value))
		static::throw('notWritableOrCreatable',$value);

		Base\Ini::setErrorLog($value);

		return;
	}
	
	
	// checkWritable
	// vérifie si les dossiers paramétrés sont accessibles en écriture
	// sinon envoie une exception
	protected static function checkWritable($value) 
	{
		if(is_string($value))
		$value = [$value];

		if(is_array($value))
		{
			foreach ($value as $k => $v) 
			{
				if(is_string($v) && !Base\Dir::isWritableOrCreatable($v))
				static::throw($v);
			}
		}

		return;
	}

	
	// setsConfig
	// fait un replace avec les configurations fournis en arguments aux différentes classes
	public static function setsConfig(array $config):void 
	{
		foreach ($config as $key => $value) 
		{
			if(is_string($key) && is_array($value))
			$key::config($value);
		}

		return;
	}


	// unsetsConfig
	// fait un unset sur les clés fournis en arguments aux différentes classes
	public static function unsetsConfig(array $unsets):void 
	{
		foreach ($unsets as $key => $value) 
		{
			if(is_string($key) && is_array($value))
			Base\Arrs::unsetsRef($value,$key::$config);
		}

		return;
	}
	
	
	// quidVersion
	// retourne la version courant de quid
	// doit retourner une string
	public static function quidVersion():string
	{
		return static::$quidVersion;
	}
	
	
	// quidCredit
	// retourne les informations de crédit de quid
	public static function quidCredit():string
	{
		$return = [];
		$credit = static::$quidCredit;
		$keys = ['name','type','author','email','github','readme','license'];
		$version = static::quidVersion();
		
		if(Base\Arr::keysExists($keys,$credit))
		{
			$return = "Software: ".$credit['name'];
			$return .= "\nVersion: ".$version;
			$return .= "\nAuthor: ".$credit['author']." / ".$credit['email'];
			$return .= "\nRequires: PHP 7.2 (compatible PHP 7.3)";
			$return .= "\nGithub: ".$credit['github'];
			$return .= "\nLicense: GPLv3 | ".$credit['license'];
			$return .= "\nReadme: ".$credit['readme'];
		}
		
		return $return;
	}
	
	
	// extendersNamespaces
	// retourne un tableau avec tous les namespaces étendus (core et après)
	public static function extendersNamespaces():array
	{
		$return = [];
		$parents = static::classParents(true);
		
		foreach ($parents as $class) 
		{
			if(is_a($class,self::class,true))
			$return[] = Base\Fqcn::namespace($class);
		}
		
		return array_reverse($return);
	}
	
	
	// unclimbableKeys
	// retouren un tableau avec toutes les clés de config ne pouvant être grimpés avec @
	// ceci est utilisé dans makeInitialAttr
	public static function unclimbableKeys():array 
	{
		return ['host','path','envs','types','climbChar','typeAs','request'];
	}
	
	
	// configReplaceMode
	// retourne le tableau des clés à ne pas merger recursivement
	public static function configReplaceMode():array
	{
		return static::$replaceMode;
	}
	
	
	// isInit
	// retourne vrai si un boot a déjà été lancé
	public static function isInit():bool 
	{
		return (static::$init === true)? true:false;
	}
	
	
	// __init
	// initialise la racine de quid
	// attribue les constantes, initialise la classe server et charge les helpers de debug
	public static function __init():void 
	{
		$version = static::quidVersion();
		Base\Constant::set('QUID_VERSION',$version);
		Base\Constant::set('QUID_TIMESTAMP',time());
		Base\Server::setQuidVersion($version);
		class_exists(Base\Debug::class,true);
		
		return;
	}
	
	
	// nameFromClass
	// retourne le root a utilisé par boot dans le constructeur
	public static function nameFromClass():string 
	{
		return static::classRoot();
	}
	
	
	// start
	// crée un objet quid et fait tous le processus
	public static function start(?array $value=null):self 
	{
		$return = static::new($value);
		$return->prepare();
		$return->dispatch();
		$return->core();
		$return->launch();
		
		return $return;
	}
	
	
	// new
	// config la classe et génère un nouvel objet
	// envoie une exception is isInit est true
	public static function new(?array $value=null):self
	{
		return new static($value);
	}
}

// config
Boot::__init();
?>