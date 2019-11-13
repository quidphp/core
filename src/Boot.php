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
use Quid\Orm;
use Quid\Routing;
use Quid\Test;

// boot
// abstract class for boot which is the object that bootstraps the application
abstract class Boot extends Main\Root
{
    // trait
    use Main\_inst;


    // config
    public static $config = [
        // prepare
        'schemeHost'=>[], // tableau des schemeHosts, est parsed et transféré dans host et scheme
        'host'=>[], // tableau des hosts avec clés env/type, ne peut pas être mis dans un @
        'path'=>[ // tableau des chemins, ne peut pas être mis dans un @
            'src'=>null,
            'boot'=>null,
            'js'=>null,
            'scss'=>null,
            'vendor'=>null,
            'public'=>null,
            'storage'=>null],
        'envs'=>['dev','staging','prod'], // définis les environnements, ne peut pas être mis dans un @
        'types'=>[], // définis les types applicatif, ne peut pas être mis dans un @
        'climbChar'=>'@', // caractère à mettre avec une clé grimpable, ne peut pas être mis dans un @
        'typeAs'=>[], // permet de spécifier des classes dont les types doivent utiliser un autre type, ne peut pas être mis dans un @
        'request'=>null, // valeur par défaut pour la création de request, ne peut pas être mis dans un @
        'finderShortcut'=>[ // shortcut pour finder
            'vendor'=>'[vendor]',
            'storage'=>'[storage]',
            'storageCache'=>'[storage]/cache',
            'storageLog'=>'[storage]/log',
            'storagePublic'=>'[storage]/public',
            'storagePrivate'=>'[storage]/private',
            'src'=>'[src]',
            'js'=>'[js]',
            'scss'=>'[scss]',
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
            '%key%'=>'[src]'],
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
        'uriAbsolute'=>null, // force toutes les uris générés via uri output à être absolute
        'symlink'=>[ // symlink à créer au chargement
            '[storagePublic]/*'=>'[public]'],
        'callable'=>[
            'assertActive'=>[Base\Assert::class,'set',ASSERT_ACTIVE,true],
            'assertBail'=>[Base\Assert::class,'set',ASSERT_BAIL,true],
            'assertWarning'=>[Base\Assert::class,'set',ASSERT_WARNING,true],
            'assertQuietEval'=>[Base\Assert::class,'set',ASSERT_QUIET_EVAL,false],
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
            'ormCatchableExceptionQuery'=>[Orm\CatchableException::class,'showQuery',false],
            'errorOutputDepth'=>[Error::class,'setDefaultOutputDepth',false],
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
            'lang'=>[Main\Extender::class,Base\Config::class],
            'file'=>[Main\Extender::class,Main\File::class],
            'service'=>[Main\Extender::class,Main\Service::class],
            'table'=>[Main\Extender::class,Table::class],
            'rows'=>[Main\Extender::class,Rows::class],
            'row'=>[Main\Extender::class,Row::class],
            'cols'=>[Main\Extender::class,Cols::class],
            'col'=>[Main\Extender::class,Col::class],
            'cells'=>[Main\Extender::class,Cells::class],
            'cell'=>[Main\Extender::class,Cell::class]],
        'roles'=>[
            'nobody'=>[1],
            'admin'=>[80,['admin'=>true]],
            'cli'=>[90,['admin'=>true,'cli'=>true]]],
        'routeNamespace'=>null, // permet de spécifier un ensemble de classe de route pour un type
        'compile'=>null, // active ou désactive toutes les compilations (js, scss et php), si c'est null la compilation aura lieu si fromCache est false
        'concatenateJs'=>null, // permet de concatener et minifier des fichiers js au lancement, fournir un tableau to => from
        'concatenateJsOption'=>null, // option pour la concatenation de js
        'compileScss'=>null, // permet de lancer un encodage scss (fournir un tableau to => from)
        'compileScssOption'=>null, // option pour le rendu du scss
        'concatenatePhp'=>[ // tableau pour la compilation de php, fournir un tableau avec target et option
            'quid'=>[
                'target'=>null,
                'option'=>[
                    'credit'=>[self::class,'quidCredit'],
                    'registerClosure'=>true,
                    'bootPreload'=>true,
                    'initMethod'=>'__init',
                    'namespace'=>[
                        Base::class=>[
                            'closure'=>false,
                            'priority'=>['_root.php','Root.php','Assoc.php','Listing.php','Set.php','Obj.php','Str.php','Finder.php','File.php','Request.php','Sql.php','Uri.php','Path.php']],
                        Test\Base::class=>['closure'=>false],
                        Main::class=>[
                            'closure'=>false,
                            'priority'=>['_root.php','_rootClone.php','Root.php','ArrObj.php','ArrMap.php','Exception.php','Map.php','Res.php','File.php','Service.php','Widget.php','File/_log.php','File/_storage.php','File/Html.php','File/Dump.php','File/Log.php','File/Serialize.php','File/Json.php','Map/_classeObj.php','Map/_obj.php']],
                        Test\Main::class=>['closure'=>false],
                        Orm::class=>[
                            'closure'=>false,
                            'priority'=>['_tableAccess.php','Relation.php','Exception.php','Pdo.php']],
                        Test\Orm::class=>[
                            'closure'=>false],
                        Routing::class=>['closure'=>true],
                        Test\Routing::class=>['closure'=>false],
                        __NAMESPACE__=>['closure'=>true],
                        Test\Core::class=>['closure'=>false],
                        '%key%'=>['closure'=>true]]]]],
        'onReady'=>null, // possible de mettre une callable sur onReady
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
        'pathOverviewExtension'=>['php','js','scss'], // extension pour pathOverview si extension est null
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
                'ormCatchableExceptionQuery'=>[Orm\CatchableException::class,'showQuery',true],
                'errorOutputDepth'=>[Error::class,'setDefaultOutputDepth',true],
                'dbHistory'=>[Db::class,'setDefaultHistory',true]],
            'concatenateJsOption'=>['compress'=>false],
            'compileScssOption'=>['compress'=>false]],
        '@prod'=>[
            'cache'=>true,
            'composer'=>[
                'classMapAuthoritative'=>true]]
    ];


    // quidVersion
    protected static $quidVersion = '5.29.0'; // version de quid


    // quidCredit
    protected static $quidCredit = [ // les crédits de quid php
        'framework'=>'QuidPHP',
        'author'=>'Pierre-Philippe Emond',
        'email'=>'emondpph@gmail.com',
        'website'=>'https://quidphp.com',
        'github'=>'https://github.com/quidphp',
        'readme'=>'https://github.com/quidphp/project/blob/master/README.md',
        'licenseType'=>'MIT',
        'licenseUrl'=>'https://github.com/quidphp/project/blob/master/LICENSE'
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
    protected $roles = null; // garde l'objet roles
    protected $route = null; // classe de la dernière route qui a été trigger
    protected $fromCache = false; // détermine si la cache pour extenders est utilisé


    // construct
    // construit et prépare l'objet boot
    final protected function __construct(?array $value=null)
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
    final public function __toString():string
    {
        return implode('-',$this->context());
    }


    // destruct
    // détruit l'objet boot
    final public function __destruct()
    {
        if($this->status() > 0)
        {
            $this->terminate();
            $this->cleanup();
        }

        return;
    }


    // onPrepare
    // callback au début de prepare
    protected function onPrepare():void
    {
        return;
    }


    // onDispatch
    // callback au début de dispatch
    protected function onDispatch():void
    {
        return;
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
    protected function onCore():void
    {
        return;
    }


    // onReady
    // callback étant appelé une fois le status à ready (4)
    // possible d'avoir une callable dans les attributs
    protected function onReady():void
    {
        $this->getAttr('onReady',true);

        return;
    }


    // onLaunch
    // callback au début de launch
    // retourne un booléean, si false skip le process du match et trigger de la route
    protected function onLaunch():bool
    {
        return true;
    }


    // onMatch
    // callback à chaque match de route
    // doit retourne true
    protected function onMatch(Route $value):bool
    {
        return true;
    }


    // onAfter
    // callback une fois tout fini
    protected function onAfter():void
    {
        return;
    }


    // onTerminate
    // callback au début de terminate
    protected function onTerminate():void
    {
        return;
    }


    // onCleanup
    // callback au début de cleanup
    protected function onCleanup():void
    {
        return;
    }


    // cast
    // retourne la valeur cast, le tableau contexte
    final public function _cast():array
    {
        return $this->context();
    }


    // prepare
    // prépare l'objet
    // init error, le code de réponse, crée la request, génère le envType, ensuite merge les attr, finalement autoload
    final protected function prepare():void
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
        Base\Root::setInitCallable($closure);
        $this->makeFinalAttr();

        $this->autoload();

        $this->setStatus(2);
        static::$init = true;

        return;
    }


    // dispatch
    // prépare l'objet
    // plusieurs changements sont envoyés, ceci affecte php et les classes statiques
    final protected function dispatch():void
    {
        $this->checkStatus(2);
        $this->onDispatch();

        if($this->getAttr('requirement') === true)
        static::requirement();

        $this->ini();
        $this->makeFinderShortcut();

        $umaskGroupWritable = $this->getAttr('umaskGroupWritable');
        if(is_bool($umaskGroupWritable))
        Base\Finder::umaskGroupWritable($umaskGroupWritable);

        $errorLog = $this->getAttr('errorLog');
        if($errorLog !== null)
        static::setErrorLog($errorLog);

        $phpInfo = $this->getAttr('phpInfo');
        if(!empty($phpInfo))
        Base\Server::phpInfo($phpInfo);

        $kill = $this->getAttr('kill');
        if(!empty($kill))
        Base\Response::kill($kill);

        $ip = $this->getAttr('ip');
        if($ip !== null)
        $this->checkIp($ip);

        $writable = $this->getAttr('writable');
        if($writable !== null)
        static::checkWritable($writable);

        $timeLimit = $this->getAttr('timelimit');
        if(is_int($timeLimit))
        Base\Response::timeLimit($timeLimit);

        $alias = $this->getAttr('alias');
        if(is_array($alias) && !empty($alias))
        Main\Autoload::setsAlias($alias);

        $overload = $this->getAttr('overload');
        if(is_array($overload) && !empty($overload))
        Main\Autoload::setsOverload($overload);

        $scheme = $this->getAttr('scheme');
        if(is_array($scheme) && !empty($scheme))
        {
            $scheme = $this->getSchemeArray($scheme);
            if(!empty($scheme))
            Base\Uri::schemeStatic($scheme);
        }

        $finderHost = $this->getAttr('finderHost');
        if(is_array($finderHost) && !empty($finderHost))
        Base\Finder::host($finderHost);

        $finderHostTypes = $this->getAttr('finderHostTypes');
        if(!empty($finderHostTypes))
        {
            $finderHostTypes = $this->getFinderHostTypes(true,$finderHostTypes);
            if(!empty($finderHostTypes))
            Base\Finder::host($finderHostTypes);
        }

        $uriShortcut = $this->getAttr('uriShortcut');
        if(is_array($uriShortcut) && !empty($uriShortcut))
        $this->setsUriShortcut($uriShortcut);

        $uriAbsolute = $this->getAttr('uriAbsolute');
        if(is_bool($uriAbsolute))
        Base\Uri::setAllAbsolute($uriAbsolute);

        $symlink = $this->getAttr('symlink');
        if(is_array($symlink) && !empty($symlink))
        static::setsSymlink($symlink);

        $callable = $this->getAttr('callable');
        if(is_array($callable) && !empty($callable))
        static::setsCallable($callable);

        Error::init();

        $lang = $this->getAttr('lang');
        if(!empty($lang))
        Base\Lang::set(null,$lang);

        $response = $this->getAttr('response');
        $response = Base\Call::digStaticMethod($response);
        Base\Response::prepare($response);

        $speed = $this->getAttr('speed');
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

        $config = $this->getAttr('config');
        if(is_array($config) && !empty($config))
        static::setsConfig($config);

        $configUnset = $this->getAttr('configUnset');
        if(is_array($configUnset) && !empty($configUnset))
        static::unsetsConfig($configUnset);

        $this->setStatus(3);

        return;
    }


    // core
    // gère l'extension du core
    // une fois que le statut est à 4, les objets lang, services, db et session peuvent être crées
    final protected function core():void
    {
        $this->checkStatus(3);
        $this->onCore();

        if($this->getAttr('clearCache') === true && !$this->shouldCache())
        static::emptyCacheFile();

        $this->makeExtenders();

        $roles = $this->getAttr('roles');
        $this->setRoles($roles);

        $error = Error::getOverloadClass();
        if($error !== Error::class)
        $error::init();

        if($this->shouldCompile())
        $this->compile();

        $this->setStatus(4);
        $this->session();

        $this->manageRedirect();

        $this->onReady();

        return;
    }


    // compile
    // permet de compile le js, scss et php
    final protected function compile():void
    {
        $js = $this->getAttr('concatenateJs');
        $jsOption = $this->getAttr('concatenateJsOption');
        if(is_array($js) && !empty($js))
        File\Js::concatenateMany($js,$jsOption);

        $scss = $this->getAttr('compileScss');
        $scssOption = $this->getAttr('compileScssOption');
        if(is_array($scss) && !empty($scss))
        File\Css::compileMany($scss,$scssOption);

        $php = $this->getAttr('concatenatePhp');
        if(is_array($php) && !empty($php))
        {
            $replace = ['%key%'=>$this->name(true)];
            $php = Base\Arrs::keysReplace($replace,$php);
            File\Php::concatenateMany($php);
        }

        return;
    }


    // launch
    // match la route avec la request et lance la route
    // retourne le contenu du match
    protected function launch()
    {
        $return = null;
        $this->checkReady();

        if($this->onLaunch() === true)
        {
            $request = $this->request();
            $routes = $this->routes();

            $match = null;

            $firstMatch = $this->getFirstMatch() ?? $routes->route($request,$match,true,true);
            $once = false;

            while (!empty($firstMatch) || ($match = $routes->route($request,$match,true,true)))
            {
                if(!empty($firstMatch))
                {
                    $match = $firstMatch;
                    $firstMatch = null;
                }

                if($this->onMatch($match) === true)
                {
                    $once = true;
                    $run = $match->launch();
                    ['bool'=>$bool,'continue'=>$continue,'output'=>$output] = $run;

                    if($bool === true)
                    {
                        $return = $output;
                        $this->setRoute($match);
                    }

                    if($continue === true)
                    continue;

                    else
                    break;
                }
            }

            if($once === false)
            static::throw('noRouteMatch');
        }

        $this->setStatus(5);
        $this->onAfter();

        return $return;
    }


    // getFirstMatch
    // retourne un premier match avant la boucle
    // doit retourner un objet route
    protected function getFirstMatch():?Route
    {
        return null;
    }


    // match
    // match les routes avec la requête
    final public function match(bool $fallback=false,bool $debug=true):array
    {
        return $this->routes()->match($this->request(),$fallback,$debug);
    }


    // setRoute
    // garde en mémoire la dernière classe de la route qui a été triggé
    // doit fournir un objet, le nom de la classe est gardé
    final protected function setRoute(Route $value):void
    {
        $this->route = get_class($value);

        return;
    }


    // route
    // retourne le nom de la classe de la route qui a été triggé, si existant
    final public function route():?string
    {
        return $this->route;
    }


    // terminate
    // termine et détruit l'objet boot
    // commit la session si elle est toujours active
    final protected function terminate():void
    {
        $this->onTerminate();
        Base\Root::setInitCallable(null);
        Base\Response::closeDown();

        if($this->isReady())
        {
            $insts = [Lang::class,Main\Services::class,Routing\Redirection::class,Session::class,Request::class];
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
        $this->attr = [];
        $this->name = null;
        $this->envType = null;
        $this->extenders = null;

        if($this->inInst())
        $this->unsetInst();

        static::$init = false;

        return;
    }


    // cleanup
    // inverse de dispatch, nettoie un maximum de changements static
    final protected function cleanup():void
    {
        Base\Finder::emptyShortcut();
        Base\Uri::emptyShortcut();
        Main\Autoload::emptyAlias();
        Main\Autoload::emptyOverload();
        Base\Uri::emptySchemeStatic();
        Base\Finder::emptyHost();
        Base\Uri::setAllAbsolute(false);
        Base\Lang::set(null,true);

        return;
    }


    // end
    // termine le boot, flush les données terminate + cleanup
    final public function end($return=null)
    {
        Base\Buffer::flushEcho($return);

        if(Base\Server::isCli())
        Base\Cli::eol();

        $this->terminate();
        $this->cleanup();

        return $return;
    }


    // isStatus
    final public function isStatus($value):bool
    {
        return ($this->status() === $value)? true:false;
    }


    // setStatus
    // change le numéro de statut de boot
    final protected function setStatus(int $value):void
    {
        $this->status = $value;

        return;
    }


    // status
    // retourne le numéro de statut de boot
    final public function status():int
    {
        return $this->status;
    }


    // checkStatus
    // envoie une exception si le statut n'est pas le bon
    final public function checkStatus(int $value):self
    {
        $this->checkInst();

        if(!$this->isStatus($value))
        static::throw('statusIsNot',$value);

        return $this;
    }


    // isReady
    // retourne vrai si boot est ready, status 4 ou >
    final public function isReady():bool
    {
        return ($this->status >= 4)? true:false;
    }


    // checkReady
    // envoie une exception si boot n'est pas ready, sinon retourne boot
    final public function checkReady():self
    {
        $this->checkInst();

        if(!$this->isReady())
        static::throw('notReady');

        return $this;
    }


    // setName
    // attribue un nom à l'objet boot
    final protected function setName(string $value):void
    {
        $this->name = lcfirst($value);

        return;
    }


    // name
    // retourne le nom du boot
    final public function name(bool $ucfirst=false):string
    {
        $return = $this->name;

        if($ucfirst === true)
        $return = ucfirst($return);

        return $return;
    }


    // makeInitialAttr
    // génère les attributs, merge le tableau avec la static config
    final protected function makeInitialAttr(array $value):void
    {
        $this->value = $value;
        $parent = get_parent_class(static::class);
        $replaceMode = static::initReplaceMode();
        $keys = static::unclimbableKeys();
        $closure = function(...$values) use($replaceMode) {
            return Base\Arrs::replaceWithMode($replaceMode,...$values);
        };

        $merge = Base\Classe::propertyMergeParents('config',$parent,$closure,false);
        $keep = Base\Arr::gets($keys,$merge);
        $value = $closure($keep,static::$config,$value);
        $value = static::parseSchemeHost($value);
        $this->makeAttr($value);

        return;
    }


    // makeFinalAttr
    // génère les attributds finaux, maintenant que le envType et la closure pour merge les config sont sets
    // gère aussi le configFile et live
    final protected function makeFinalAttr():void
    {
        static::__init();
        $attr = $this->replaceSpecial(static::class,static::initReplaceMode(),static::$config,$this->value);
        $attr = static::parseSchemeHost($attr);
        $this->makeAttr($attr);
        $this->makeFinderShortcut();

        $configFile = $this->getAttr('configFile');
        $merge = [];
        if(!empty($configFile))
        $merge = $this->getConfigFile((array) $configFile);

        $live = $this->getAttr('live');
        $liveConfig = $this->getAttr('configLive');
        if($live === true && is_array($liveConfig) && !empty($liveConfig))
        $merge[] = $liveConfig;

        if(!empty($merge))
        {
            $attr = $this->replaceSpecial(static::class,static::initReplaceMode(),$this->attr(),...$merge);
            $attr = static::parseSchemeHost($attr);
            $this->makeAttr($attr);
        }

        return;
    }


    // getConfigFile
    // fait un require sur le ou les fichiers fichier de config additionnel
    // retourne un tableau multidimensionnel
    final protected function getConfigFile(array $files):array
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
    final protected function makeFinderShortcut():void
    {
        $finderShortcut = $this->getAttr('finderShortcut');
        if(is_array($finderShortcut) && !empty($finderShortcut))
        {
            $finderShortcut = $this->makePaths($finderShortcut);
            Base\Finder::setsShortcut($finderShortcut);
        }

        return;
    }


    // makeRequest
    // crée la requête et conserve dans l'objet
    final protected function makeRequest():void
    {
        $value = $this->getAttr('request');

        if(is_array($value) && !empty($value))
        Base\Request::change($value,true);

        $request = Request::newOverload();
        $request->setInst();

        return;
    }


    // request
    // retourne l'objet request
    final public function request():Request
    {
        return Request::inst();
    }


    // envs
    // retourne tous les environnements
    final public function envs():array
    {
        return $this->getAttr('envs');
    }


    // types
    // retournes tous les types d'applications
    final public function types():array
    {
        return $this->getAttr('types');
    }


    // paths
    // retourne tous les chemins
    final public function paths():array
    {
        return $this->getAttr('path');
    }


    // path
    // retourne un chemin
    final public function path(string $key):string
    {
        return $this->getAttr(['path',$key]);
    }


    // pathOverview
    // retourne le total des lignes et size pour un path
    // possible de filtrer par extension
    final public function pathOverview(string $path,$extension=null):array
    {
        $return = [];
        $extension = ($extension === null)? $this->getAttr('pathOverviewExtension'):$extension;
        $path = $this->path($path);
        $return['line'] = 0;
        $size = 0;
        $size += Base\Dir::size($path,false,$extension);

        $return['size'] = Base\Number::sizeFormat($size);
        $return['line'] += Base\Dir::line($path,$extension);
        $return['path'] = Base\Dir::subDirLine($path,null,$extension);

        return $return;
    }


    // makePaths
    // fait plusieurs paths, sans passer par finder
    // si replaceKey est true, remplace %key% par le nom de boot
    final public function makePaths(array $array,bool $replaceKey=false):array
    {
        $return = [];

        foreach ($array as $k => $v)
        {
            if($replaceKey === true)
            {
                $name = $this->name(true);
                $k = str_replace('%key%',$name,$k);
            }

            $return[$k] = $this->makePath($v);
        }

        return $return;
    }


    // makePath
    // fait un path, sans passer par finder
    final public function makePath(string $return):string
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
    final public function hosts():array
    {
        return $this->getAttr('host');
    }


    // host
    // retourne un host, selon le env et le type
    final public function host($env=null,$type=null):?string
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
    final protected function checkHost():void
    {
        $hosts = $this->hosts();
        $request = $this->request();
        $host = $request->host();

        if(!is_string($host) || !is_array($hosts) || !in_array($host,$hosts,true))
        static::throw($host,$hosts);

        return;
    }


    // makeEnvType
    // génère le envType à partir des tableaux hosts, envs et types
    // la valeur est mise en cache
    final protected function makeEnvType():void
    {
        $request = $this->request();
        $host = $request->host();
        $envType = $this->envTypeFromHost($host);

        if(empty($envType))
        static::throw('invalidEnvType',$host);

        else
        $this->envType = $envType;

        return;
    }


    // envType
    // retourne la valeur envType
    final public function envType():array
    {
        return $this->envType;
    }


    // context
    // retourne le context courant
    // contient env, type et lang (lang peut changer)
    final public function context():array
    {
        $return = $this->envType();
        $return['lang'] = Base\Lang::current();

        return $return;
    }


    // env
    // retourne l'environnement courant
    final public function env():string
    {
        return $this->envType()['env'];
    }


    // envIndex
    // retourne l'index de l'environnement courant
    final public function envIndex():int
    {
        return Base\Arr::search($this->env(),$this->envs());
    }


    // type
    // retourne le type courant de l'application
    final public function type():string
    {
        return $this->envType()['type'];
    }


    // typeIndex
    // retourne l'index du type courant de l'application
    final public function typeIndex():int
    {
        return Base\Arr::search($this->type(),$this->types());
    }


    // typePrimary
    // retourne le type primaire de l'application
    final public function typePrimary():string
    {
        return current($this->types());
    }


    // envTypeFromHost
    // retourne le context à partir du host fourni en argument
    final protected function envTypeFromHost(string $value):?array
    {
        return static::envTypeFromValue($value,$this->hosts(),$this->envs(),$this->types());
    }


    // isEnv
    // retourne vrai si l'environnement est celui fourni en argument
    final public function isEnv($value):bool
    {
        return (is_string($value) && $this->env() === $value)? true:false;
    }


    // isDev
    // retourne vrai si l'environnement est dev
    final public function isDev():bool
    {
        return ($this->env() === 'dev')? true:false;
    }


    // isStaging
    // retourne vrai si l'environnement est staging
    final public function isStaging():bool
    {
        return ($this->env() === 'staging')? true:false;
    }


    // isProd
    // retourne vrai si l'environnement est prod
    final public function isProd():bool
    {
        return ($this->env() === 'prod')? true:false;
    }


    // isType
    // retourne vrai si le type est celui fourni en argument
    final public function isType($value):bool
    {
        return (is_string($value) && $this->type() === $value)? true:false;
    }


    // typeAs
    // retourne le ou les types à utiliser pour une classe, en plus du type courant
    // les types à utiliser ont priorités
    final public function typeAs(string $class,string $type)
    {
        return $this->getAttr(['typeAs',$class,$type]);
    }


    // climbableKeys
    // retourne toutes les clés grimpables pour le tableau
    // un tableau peut être fourni, à ce moment les clés du tableau non existantes sont aussi considérés comme climbables
    final public function climbableKeys(?array $values=null):array
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
    final public function valuesWrapClimb(array $return):array
    {
        return Base\Arr::valuesWrap($this->getAttr('climbChar'),'',$return);
    }


    // makeConfigClosure
    // retourne la closure à utiliser pour le merge de config des classes
    final public function makeConfigClosure():\Closure
    {
        return function(string $class,...$values) {
            return $this->replaceSpecial($class,$class::initReplaceMode(),...$values);
        };
    }


    // replaceSpecial
    // méthode de remplacement complexe utilisé à travers quid
    // permet de merge les clés @ dans les tableaux de config
    final public function replaceSpecial(?string $class,array $replaceMode,...$values)
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
    final protected function ini():void
    {
        $charset = Base\Encoding::getCharset();
        $timezone = Base\Timezone::get();

        Base\Ini::setIncludePath(['public'=>$this->path('public')]);
        Base\Ini::setDefault(['default_charset'=>$charset,'date.timezone'=>$timezone]);

        return;
    }


    // isPreload
    // retourne vrai si le type de autoload est preload
    final public function isPreload():bool
    {
        return ($this->autoloadType() === 'preload')? true:false;
    }


    // autoloadType
    // retourne le type d'autoload, peut être internal, composer ou preload
    final public function autoloadType():string
    {
        return $this->getAttr('autoload');
    }


    // autoload
    // renvoie vers la bonne méthode d'autoload, selon le type
    final protected function autoload():void
    {
        $type = $this->autoloadType();

        if(in_array($type,['internal','composer','preload'],true))
        {
            $initMethod = '__init';

            if(!Main\Autoload::isRegistered('closure'))
            Main\Autoload::registerClosure(false,$initMethod);

            if(!Main\Autoload::isRegistered('alias'))
            Main\Autoload::registerAlias();

            $psr4 = $this->getAttr('psr4');
            if(!empty($psr4))
            {
                $psr4 = $this->makePaths($psr4,true);
                Main\Autoload::registerPsr4($psr4,true,$initMethod);
            }

            if($type === 'composer')
            {
                $authoritative = $this->getAttr(['composer','classMapAuthoritative']);
                Service\Composer::setPsr4();
                Service\Composer::setClassMapAuthoritative($authoritative);
            }
        }

        else
        static::errorKill('invalidAutoloadType',$type);

        return;
    }


    // checkIp
    // vérifie si le ip est valide pour accéder
    // sinon tue le script
    final protected function checkIp($value):void
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

        return;
    }


    // getSchemeArray
    // traite un tableau de scheme avec clés pouvant être env/type
    final protected function getSchemeArray(array $array):array
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
    final protected function getFinderHostTypes($value,$paths):array
    {
        $return = [];

        if(!empty($paths))
        {
            if(!is_array($paths))
            $paths = (array) $paths;

            foreach ($paths as $key => $path)
            {
                $paths[$key] = Base\Finder::normalize($path);
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
    final public function schemes(bool $convert=true):array
    {
        $return = $this->getAttr('scheme');

        if($convert === true)
        $return = $this->getSchemeArray($return);

        return $return;
    }


    // scheme
    // retourne un scheme selon l'environnement et le type
    // si convert est true, le scheme est converti de boolean/port vers la string http/https
    final public function scheme($env=null,$type=null,bool $convert=true)
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
    final public function schemeHost($env=true,$type=true,$scheme=null):?string
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


    // schemeHosts
    // méthode qui retourne tous les schemeHosts du boot
    final public function schemeHosts($scheme=null):array
    {
        $return = [];

        foreach ($this->envs() as $env)
        {
            foreach ($this->schemeHostTypes($env,$scheme) as $type => $schemeHost)
            {
                $key = "$env/$type";
                $return[$key] = $schemeHost;
            }
        }

        return $return;
    }


    // schemeHostTypes
    // retourne le schemeHost pour tous les types d'un même environnement
    final public function schemeHostTypes($env=true,$scheme=null):array
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
    final public function schemeHostEnvs($type=true,$scheme=null):array
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
    final public function setsUriShortcut(array $shortcut):void
    {
        foreach ($shortcut as $key => $value)
        {
            if(is_array($value))
            $shortcut[$key] = $this->schemeHost(...$value);
        }

        Base\Uri::setsShortcut($shortcut);

        return;
    }


    // versions
    // retourne toutes les versions
    // si quid est true, retourne aussi celle de quid
    final public function versions(bool $quid=true):?array
    {
        $return = $this->getAttr('version');

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
    final public function version($type=true,bool $quid=true,bool $removeZero=false):?string
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
    final public function setsSymlink(array $array):void
    {
        $array = Base\Dir::fromToCatchAll($array);
        $syms = Base\Symlink::sets($array,true,true);

        foreach ($syms as $to => $array)
        {
            if($array['status'] === false)
            static::throw('from',$array['from'],'to',$to);
        }

        return;
    }


    // setsCallable
    // fait les appels au callable pour configuration plus poussée
    final protected function setsCallable(array $array):void
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

        return;
    }


    // isFromCache
    // retourne vrai si la cache d'extenders a été trouvé et est utilisé
    final public function isFromCache():bool
    {
        return $this->fromCache;
    }


    // shouldCache
    // retourne vrai si la cache globale est activé
    final public function shouldCache():bool
    {
        return $this->getAttr('cache');
    }


    // shouldCompile
    // retourne vrai s'il faut compiler le php, js et scss
    final public function shouldCompile():bool
    {
        $return = false;
        $request = $this->request();

        if($request->isStandard() && !$this->isPreload())
        {
            $return = $this->getAttr('compile');

            if(!is_bool($return))
            $return = ($this->isFromCache())? false:true;
        }

        return $return;
    }


    // makeExtenders
    // peut créer un nouveau ou utiliser celui de la cache
    // l'extenders est garder comme propriété de l'objet
    final protected function makeExtenders():void
    {
        $cache = $this->shouldCache();
        $type = $this->type();
        $version = $this->version();
        $key = ['extenders',$type,$version];

        $this->fromCache = true;
        $extenders = static::cacheFile($key,function() {
            $this->fromCache = false;
            $config = (array) $this->getAttr('extenders');
            return $this->newExtenders($config);
        },$cache);

        if($this->isFromCache())
        {
            $core = $extenders->get('core');
            $core->extended()->alias();
            $extenders->pair('overloadSync');
        }

        foreach ($extenders as $key => $extender)
        {
            if($extender instanceof Routing\Routes)
            $extender->setType($key,false);

            if($key !== 'core')
            $extender->extended()->alias();

            if($key === 'file')
            $extender->pair('registerClass');

            if($cache === false)
            {
                // ici vérifie qu'il n'y a pas d'objet non désiré dans le dossier core
                if($key === 'core')
                $extender->checkParentSameName();

                // vérifie l'extension des classes
                $extender->checkExtend();

                // vérifie que toutes les classes sont des sous-classe de celle défini dans configuration
                $subClass = $this->getAttr(['extenders',$key,1]);
                if(is_string($subClass))
                $extender->checkSubClassOf($subClass);
            }
        }

        $routes = $extenders->get($type);
        $routes->init($type);
        $routes->readOnly(true);

        $this->extenders = $extenders;

        return;
    }


    // newExtenders
    // créer et retourne le extenders
    // fait les alias et overload
    final protected function newExtenders(array $config):Main\Extenders
    {
        $return = Main\Extenders::newOverload();
        $currentType = $this->type();
        $types = $this->types();
        $namespaces = static::extendersNamespaces();
        $closure = $this->newExtendersClosure();

        // core
        $core = $closure(Main\Extender::class,null,$namespaces);
        $core->extended()->alias();
        $core->overload();
        $return->set('core',$core);

        // routes
        $routeOption = ['noSubDir'=>true];
        foreach ($types as $type)
        {
            $routeNamespace = $this->getAttr(['routeNamespace',$type]);
            if(!empty($routeNamespace))
            $extender = $closure(Routing\Routes::class,null,$routeNamespace,$routeOption);
            else
            $extender = $closure(Routing\Routes::class,$type,$namespaces,$routeOption);

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
                $option = ['noSubDir'=>true,'overloadKeyPrepend'=>ucfirst($key)];
                $extender = $closure($class,$key,$namespaces,$option);
                $extender->overload();
                $return->set($key,$extender);
            }
        }

        return $return;
    }


    // newExtendersClosure
    // retourne la closure pour l'objet extenders
    final protected function newExtendersClosure():\Closure
    {
        $currentKey = $this->name(true);
        $return = function(string $class,?string $key=null,array $namespaces,?array $option=null) use($currentKey)  {
            if(is_string($key))
            $ucKey = ucfirst($key);
            $namespace = [];

            foreach ($namespaces as $value)
            {
                $namespace[] = (!empty($key))? Base\Fqcn::append($value,$ucKey):$value;
            }

            return $class::newOverload($namespace,$option);
        };

        return $return;
    }


    // extenders
    // retourne l'objet extenders
    final public function extenders():Main\Extenders
    {
        return $this->extenders;
    }


    // routes
    // retourne l'objet routes de boot
    // peut retourner l'objet d'un type différent si fourni en argument
    final public function routes(?string $type=null):Routing\Routes
    {
        $return = null;

        if($type === null)
        $type = $this->type();

        $return = $this->extenders()->get($type);

        return $return;
    }


    // setRoles
    // génère l'objet roles
    final protected function setRoles(array $array):void
    {
        $this->roles = $roles = Main\Roles::makeFromArray($array);
        $roles->sortDefault();
        $roles->readOnly(true);
        $roles->setInst();

        return;
    }


    // roles
    // retourne l'objet roles de boot
    final public function roles():Main\Roles
    {
        return $this->roles;
    }


    // lang
    // retourne ou crée l'objet lang de boot
    // ne charge pas les contenus lang tout de suite, les contenus sont chargés via onLangLoad méthode statique et abstraite
    final public function lang():Lang
    {
        $this->checkReady();
        $return = Lang::instSafe();

        if(empty($return))
        {
            $all = Base\Lang::all();
            $option = (array) $this->getAttr('langOption',true);
            $option['onLoad'] = function(string $value) {
                $fqcn = $this->langContentClass($value);
                $return = Base\Lang::content($fqcn::config());

                $langRow = $this->getAttr('langRow');
                if(is_string($langRow) && $this->hasDb() && $this->db()->hasTable($langRow))
                {
                    $table = $this->db()->table($langRow);

                    if($table->isColsReady())
                    {
                        $content = $langRow::grabContent($value,$this->typeIndex());
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
    final public function langContentClass(?string $value=null):string
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
    final public function label(?string $lang=null):string
    {
        return $this->lang()->bootLabel($lang);
    }


    // description
    // retourne la description de l'application courante
    final public function description(?string $lang=null):?string
    {
        return $this->lang()->bootDescription($lang);
    }


    // typeLabel
    // retourne le label du type de contexte
    final public function typeLabel(?string $lang=null):string
    {
        return $this->lang()->typeLabel($this->type(),$lang);
    }


    // envLabel
    // retourne le label de l'env de contexte
    final public function envLabel(?string $lang=null):string
    {
        return $this->lang()->envLabel($this->env(),$lang);
    }


    // typeEnvLabel
    // retourne un label combiné pour type et env
    final public function typeEnvLabel(string $separator=' / ',?string $lang=null):string
    {
        return implode($separator,[$this->typeLabel($lang),$this->envLabel($lang)]);
    }


    // services
    // retourne l'objet services ou crée le et met le dans inst
    final public function services():Main\Services
    {
        $this->checkReady();
        $return = Main\Services::instSafe();

        if(empty($return))
        {
            $services = $this->getAttr('service');
            $return = Main\Services::newOverload($services);
            $return->setInst();
            $return->readOnly(true);
        }

        return $return;
    }


    // service
    // retourne un objet service à partir d'une clé
    final public function service(string $key):?Main\Service
    {
        return $this->services()->get($key);
    }


    // checkService
    // retourne un objet service à partir d'une clé, sinon envoie une exception
    final public function checkService(string $key):Main\Service
    {
        $return = $this->service($key);

        if(!$return instanceof Main\Service)
        static::throw($key);

        return $return;
    }


    // serviceMailer
    // retourne un objet service mailer à partir d'une clé
    final public function serviceMailer(?string $key=null):?Main\ServiceMailer
    {
        $return = null;
        $key = ($key === null)? $this->getAttr('serviceMailer'):$key;

        if(is_string($key))
        $return = $this->service($key);

        return $return;
    }


    // checkServiceMailer
    // retourne un objet service mailer à partir d'une clé, sinon envoie une exception
    final public function checkServiceMailer(?string $key=null):Main\ServiceMailer
    {
        $return = $this->serviceMailer($key);

        if(!$return instanceof Main\ServiceMailer)
        static::throw($key);

        return $return;
    }


    // redirection
    // retourne ou crée l'objet redirection
    final public function redirection():Routing\Redirection
    {
        $this->checkReady();
        $return = Routing\Redirection::instSafe();

        if(empty($return))
        {
            $redirection = (array) $this->getAttr('redirection',true);

            $redirectionRow = $this->getAttr('redirectionRow');
            if(is_string($redirectionRow) && $this->hasDb() && $this->db()->hasTable($redirectionRow))
            {
                $content = $redirectionRow::grabContent($this->typeIndex());
                if(!empty($content))
                $redirection = Base\Arr::replace($redirection,$content);
            }

            $return = Routing\Redirection::newOverload($redirection);
            $return->setInst();
            $return->readOnly(true);
        }

        return $return;
    }


    // db
    // créer ou retourne l'objet db de boot
    final public function db():Db
    {
        $this->checkReady();
        $return = Db::instReady();

        if(empty($return))
        {
            $credentials = $this->getAttr('db');

            if(is_array($credentials) && count($credentials) === 3)
            {
                $option = (array) $this->getAttr('dbOption',true);

                if($this->shouldCache())
                {
                    $type = $this->type();
                    $version = $this->version();

                    $option['classeClosure'] = function(Main\Extenders $extenders) use($type,$version) {
                        $key = ['classe',$type,$version];
                        return static::cacheFile($key,function() use($extenders) {
                            $this->classe = Orm\Classe::newOverload($extenders,$this->getAttr('classe'));
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
                $nobody = $this->roles()->nobody()->roles();
                $values = Base\Arr::push($credentials,$extenders,$nobody,$option);
                $return = Db::newOverload(...array_values($values));
                $return->setLang($lang);

                $langRow = $this->getAttr('langRow');
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
    final public function hasDb():bool
    {
        return (Db::instReady() instanceof Db)? true:false;
    }


    // session
    // retourne ou crée l'objet session de boot
    final public function session():Session
    {
        $this->checkReady();
        $return = Session::instSafe();

        if(empty($return))
        {
            $storage = $this->getAttr('sessionStorage');
            $versionMatch = $this->getAttr('sessionVersionMatch');
            $option = (array) $this->getAttr('sessionOption',true);
            $option['env'] = $this->env();
            $option['type'] = $this->type();
            $option['version'] = $this->version();

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
    final public function hasSession():bool
    {
        return (Session::instReady() instanceof Session)? true:false;
    }


    // manageRedirect
    // vérifie la requête et manage les redirections avant de continuer
    // certaines errors vont générer un code http 400 plutôt que 404 (bad request)
    // crée aussi un callback au closeDown dans la classe de log, une entrée sera ajouté si le code de réponse n'est pas positif
    final protected function manageRedirect():void
    {
        $request = $this->request();
        $redirection = $this->redirection();
        $manage = $request->manageRedirect($redirection);
        $log = $this->getAttr('redirectLog');

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
        // gère aussi l'objet redirection
        elseif(!empty($log))
        {
            $log::onCloseDown();

            if($manage['location'] !== null)
            Base\Response::redirect($manage['location'],$manage['code'],true);
        }

        return;
    }


    // getOption
    // retourne la valeur d'une option dans les attributs
    final public function getOption($value)
    {
        return Base\Arrs::get($value,$this->getAttr('option'));
    }


    // info
    // retourne un tableau d'informations sur boot
    final public function info():array
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


    // parseSchemeHost
    // permet de parse la valeur schemeHost du tableau d'attribut
    // ajoute les scheme et les hosts dans leur clé de tableau
    // vide l'entrée du tableau schemeHost pour éviter des désynchronisations
    final public static function parseSchemeHost(array $return):array
    {
        if(!empty($return['schemeHost']) && is_array($return['schemeHost']))
        {
            foreach ($return['schemeHost'] as $envType => $value)
            {
                $scheme = Base\Uri::scheme($value);
                $host = Base\Uri::host($value);

                if(!empty($scheme) && !empty($host))
                {
                    $return['scheme'][$envType] = $scheme;
                    $return['host'][$envType] = $host;
                }

                else
                static::throw($envType,$value);
            }

            $return['schemeHost'] = [];
        }

        return $return;
    }


    // envTypeFromValue
    // méthode utilisé par envTypeFromHost pour obtenir le env/type d'une valeur dans un tableau
    // il faut fournir envs et types
    final public static function envTypeFromValue(string $value,array $values,array $envs,array $types):?array
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
    final public static function envTypeFromString(string $value,array $envs,array $types):?array
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
    final public static function requirement():void
    {
        $server = Base\Server::requirement();
        if(!empty($server))
        static::throw('server',$server);

        $extension = Base\Extension::requirement();
        if(!empty($extension))
        static::throw('extension',$extension);

        $ini = Base\Ini::requirement();
        if(!empty($ini))
        static::throw('ini',$ini);

        return;
    }


    // setErrorLog
    // enregistre le errorLog dans le ini
    // tente de le créer, vérifie qu'il est accessible en écriture
    final public static function setErrorLog(string $value):void
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
    final protected static function checkWritable($value)
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
    final public static function setsConfig(array $config):void
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
    final public static function unsetsConfig(array $unsets):void
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
    final public static function quidVersion():string
    {
        return static::$quidVersion;
    }


    // quidCredit
    // retourne les informations de crédit de quid
    // peut retorner sous forme de string ou tableau
    final public static function quidCredit(bool $str=true)
    {
        $return = null;
        $credit = static::$quidCredit;
        $credit['version'] = static::quidVersion();

        if($str === true)
        {
            $return = '';

            foreach ($credit as $key => $value)
            {
                $return .= (!empty($return))? "\n":'';
                $return .= ucfirst($key).': '.$value;
            }
        }

        else
        $return = $credit;

        return $return;
    }


    // extendersNamespaces
    // retourne un tableau avec tous les namespaces étendus (core et après)
    final public static function extendersNamespaces():array
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
    final public static function unclimbableKeys():array
    {
        return ['host','path','envs','types','climbChar','typeAs','request'];
    }


    // initReplaceMode
    // retourne le tableau des clés à ne pas merger recursivement
    final public static function initReplaceMode():array
    {
        return static::$replaceMode;
    }


    // isInit
    // retourne vrai si un boot a déjà été lancé
    final public static function isInit():bool
    {
        return (static::$init === true)? true:false;
    }


    // initialize
    // initialise la racine de quid
    // attribue les constantes, initialise la classe server et charge les helpers de debug
    final public static function initialize():void
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
        return Base\Fqcn::root(static::class);
    }


    // start
    // crée un objet quid et fait tous le processus
    // retourne le contenu à output
    final public static function start(?array $value=null,bool $end=true):?string
    {
        $return = null;

        $boot = static::new($value);
        $boot->prepare();
        $boot->dispatch();
        $boot->core();
        $return = $boot->launch();

        if($end === true)
        $return = $boot->end($return);

        return $return;
    }


    // new
    // config la classe et génère un nouvel objet
    // envoie une exception is isInit est true
    final public static function new(?array $value=null):self
    {
        return new static($value);
    }
}

// initialize
Boot::initialize();
?>