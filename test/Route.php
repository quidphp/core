<?php
declare(strict_types=1);
namespace Quid\Core\Test;
use Quid\Test;
use Quid\Core;
use Quid\Routing;
use Quid\Main;
use Quid\Base;

// route
class Route extends Base\Test
{
	// trigger
	public static function trigger(array $data):bool
	{
		// prepare
		$boot = $data['boot'];
		$type = $boot->type();
		$contact = Test\Assert\Contact::class;
		$route = Core\Cms\Error::class;
		$login = Core\Cms\Login::class;
		$sitemap = Core\Cms\Sitemap::class;
		$loginSubmit = Core\Cms\LoginSubmit::class;
		$general = Core\Cms\General::class;
		$specific = Core\Cms\Specific::class;
		$session = $boot->session();
		$session->timeoutEmpty();
		$schemeHost = $boot->schemeHost(true,$type);
		$db = Core\Boot::inst()->db();
		$lang = $session->lang();
		$obj = new $route(Core\Request::live());
		$obj2 = new $loginSubmit();
		$g = new $general(new Core\Request("/fr/table/ormTable/1/20/-/-/-/-/-/-/-"));
		$g2 = new $general(array('table'=>$db['ormSql'],'page'=>3,'limit'=>10));
		$query = new $general(new Core\Request("/fr/table/ormTable/1/20/-/-/-/-/-/-/-?s=éric"));
		assert(count(Base\Classe::parents($login,true)) === 7);
		
		// session

		// getTimeoutObject
		assert($obj::getTimeoutObject() instanceof Main\Timeout);

		// onPrepareDoc

		// type
		assert($obj2->type() === $type);

		// setType

		// run

		// getBaseReplace
		assert(count($obj->getBaseReplace()) === 15);

		// prepareTitle

		// prepareDocServices
		
		// prepareDocJsInit
		
		// title
		assert($obj2->title() === 'Login - Submit');
		assert($obj2->title(3) === "Log");
		
		// rowExists
		assert($obj->rowExists() === false);
		
		// row
		assert($obj->row() === null);
		
		// getOtherMeta
		assert($obj->getOtherMeta() === null);
		
		// allowed
		assert($route::allowed());
		assert(!$login::allowed());
		assert($sitemap::allowed());

		// label
		assert($route::label() === 'Error');
		assert($route::label('%:') === 'Error:');

		// description
		assert($route::description() === null);

		// host
		assert($loginSubmit::host() === $boot->host(true,$type));
		assert($route::host() === $boot->host(true,$type));

		// schemeHost
		assert($route::schemeHost() === $boot->schemeHost(true,$type));

		// context
		assert($route::context()['context'] === $type.':error');

		// routes
		assert($route::routes() instanceof Core\Routes);

		// childs

		// make
		assert($loginSubmit::make() instanceof $loginSubmit);

		// makeOverload
		assert($loginSubmit::makeOverload() instanceof $loginSubmit);
		assert(Core\Route\ActivatePassword::makeOverload() instanceof Test\Assert\ActivatePassword);

		// makeParent
		assert($loginSubmit::getOverloadClass() === Core\Cms\LoginSubmit::class);
		assert($loginSubmit::makeParent() instanceof Core\Cms\Login);

		// makeParentOverload
		
		// tableSegment
		
		// rowClass
		assert($route::rowClass() === null);
		assert($contact::rowClass() === Test\Row\OrmCol::class);

		// tableFromRowClass
		assert($contact::tableFromRowClass() instanceof Core\Table);

		// jsInit
		assert($loginSubmit::jsInit() === '$(document).ready(function() { $(this).navigation(); });');
		
		// getOverloadKeyPrepend
		assert($route::getOverloadKeyPrepend() === 'Route');

		// routing
		assert($obj2->_cast() === '/en/login');
		assert($obj->routeRequest() instanceof Routing\RouteRequest);
		assert($obj->request() instanceof Core\Request);
		assert($obj->getFallbackContext() === null);
		assert($obj->init() === $obj);
		assert($obj->isValid());
		assert($obj->checkValid() === $obj);
		assert($obj->getMetaTitle() === null);
		assert($obj->getMetaKeywords() === null);
		assert($obj->getMetaDescription() === null);
		assert($obj->getMetaImage() === null);
		assert($obj->getBodyClass() === null);
		assert($obj->getBodyStyle() === null);
		assert(!empty($obj->docOpen()));
		assert(count($obj->getReplace()) === 15);
		assert(!$obj2->isSelected());
		assert($obj2->hasUri());
		assert(!$route::make()->hasUri());
		assert($obj2->uri() === '/en/login');
		assert($login::make()->uri() === '/');
		assert($sitemap::make()->uri() === '/sitemap.xml');
		assert($obj2->uriOutput() === '/en/login');
		assert($obj2->uriRelative() === '/en/login');
		assert($obj2->uriAbsolute() === Base\Request::schemeHost()."/en/login");
		assert($obj2->a() === "<a href='/en/login' hreflang='en'></a>");
		assert($obj2->a('okk','#id class2','fr',array('attr'=>array('href'=>array('lang'=>false)))) === "<a href='/fr/connexion' id='id' class='class2'>okk</a>");
		assert($obj2->aOpen() === "<a href='/en/login' hreflang='en'>");
		assert($obj2->aOpen('okkk','#id class2','fr') === "<a href='/fr/connexion' id='id' class='class2' hreflang='fr'>okkk");
		assert($obj2->aTitle() === "<a href='/en/login' hreflang='en'>Login - Submit</a>");
		assert($obj2->aOpenTitle() === "<a href='/en/login' hreflang='en'>Login - Submit");
		assert($obj2->aOpenTitle(3) === "<a href='/en/login' hreflang='en'>Log");
		assert($obj2->aOpenTitle('%:','#id class2') === "<a href='/en/login' id='id' class='class2' hreflang='en'>Login - Submit:");
		$loginMake = $login::make();
		assert(strlen($obj2->formOpen()) === 207);
		assert(strlen($loginMake->formOpen(array('method'=>'post'))) === 199);
		assert($loginMake->formSubmit(null,'nameOK') === "<form action='/' method='get'><button name='nameOK' type='submit'></button></form>");
		assert($loginMake->submitTitle("% ok") === "<button type='submit'>Login ok</button>");

		// _static
		assert(!$route::isIgnored());
		assert(!$route::inMenu('test'));
		assert($route::isActive());
		assert($route::isGroup('error') === true);
		assert($sitemap::isGroup('seo'));
		assert(!$route::isGroup('none'));
		assert(!$route::inSitemap());
		assert(!$login::inSitemap());
		assert($contact::inSitemap());
		assert(!$loginSubmit::inSitemap());
		assert($route::allowNavigation());
		assert($loginSubmit::allowNavigation());
		assert($route::group() === 'error');
		assert($login::group() === 'nobody');
		assert($login::group(true) === 'nobody');
		assert($route::name() === 'error');
		assert($route::priority() === 999);
		assert($route::parent() === null);
		assert($loginSubmit::parent() === Core\Cms\Login::class);
		assert($loginSubmit::hasPath('fr'));
		assert($loginSubmit::hasPath());
		assert($loginSubmit::paths() === array('en'=>'login','fr'=>'connexion'));
		assert($route::path() === false);
		assert($route::path(null,true) === null);
		assert($login::path() === '');
		assert($login::path('fr',true) === null);
		assert($loginSubmit::path() === false);
		assert($loginSubmit::path('ge',true) === false);
		assert($loginSubmit::path('fr') === 'connexion');
		assert($loginSubmit::isSsl() === null);
		assert($route::isSsl() === null);
		assert($loginSubmit::isAjax() === null);
		assert($route::isAjax() === null);
		assert($loginSubmit::isMethod('post'));
		assert(!$route::isMethod('post'));
		assert(!$loginSubmit::isRedirectable());
		assert(!$route::isRedirectable());
		assert($contact::isRedirectable());
		assert(!$sitemap::isRedirectable());
		assert(!$route::hasCheck('captcha'));
		assert($route::timeout() === array());
		assert(is_array($loginSubmit::timeout()['trigger']));
		$max = $loginSubmit::timeout()['trigger']['max'];
		assert($loginSubmit::prepareTimeout()->isCount(1));
		$key = array($loginSubmit::classFqcn(),'trigger');
		assert($loginSubmit::isTimedOut('trigger') === false);
		assert($loginSubmit::timeoutGet('trigger') === 0);
		assert($loginSubmit::timeoutGet('triggerz') === null);
		assert($loginSubmit::timeoutIncrement('trigger')->getCount($key) === 1);
		assert($loginSubmit::timeoutBlock('trigger')->getCount($key) === $max);
		assert($loginSubmit::timeoutGet('trigger') === $max);
		assert($loginSubmit::isTimedOut('trigger'));
		assert($loginSubmit::timeoutReset('trigger')->getCount($key) === 0);
		assert($loginSubmit::isTimedOut('trigger') === false);
		assert($loginSubmit::timeoutStamp('trigger') instanceof Main\Timeout);
		assert(count($loginSubmit::tagAttr('a',array('class','#id'))) === 2);
		assert($loginSubmit::tagOption('form') === null);

		// _segment
		assert($general::make()->routeSegmentRequest() instanceof Routing\RouteSegmentRequest);
		assert($g->initSegment() === $g);
		assert($g->checkValidSegment());
		assert($g->segment(null,false) === array('table'=>'ormTable','page'=>1,'limit'=>20,'order'=>'-','direction'=>'-','cols'=>'-','filter'=>'-','in'=>'-','notIn'=>'-','highlight'=>'-'));
		assert($g->segment('table',false) === 'ormTable');
		assert($g->segment(0,false) === 'ormTable');
		assert($g2->segment()['table'] instanceof Core\Table);
		assert($g2->segment(null,true)['table'] === 'ormSql');
		assert($g->segment(null,false)['table'] === 'ormTable');
		assert($g->segment(null,true)['table'] === 'ormTable');
		assert($g->segment() === array('table'=>$db['ormTable'],'page'=>1,'limit'=>20,'order'=>$db['ormTable']['id'],'direction'=>'desc','cols'=>$g->segment('cols'),'filter'=>array(),'in'=>array(),'notIn'=>array(),'highlight'=>array()));
		assert($g->segment('table') === $db['ormTable']);
		assert($g->segment(0) === $db['ormTable']);
		assert($g->hasSegment('table','page'));
		assert(!$g->hasSegment('table','pagez'));
		assert($g->checkSegment('table','page'));
		assert(($g3 = $g->changeSegment('page',4)) instanceof Core\Route);
		assert($g3 !== $g);
		assert($g3->routeRequest() !== $g->routeRequest());
		assert($g3->request() === $g->request());
		assert($g3->uri() === '/en/table/ormTable/4/20/-/-/-/-/-/-/-');
		assert($g->uri() === '/en/table/ormTable/1/20/-/-/-/-/-/-/-');
		assert(count($g3->segment()) === 10);
		assert($g3->isValidSegment());
		assert($g->isValidSegment());
		assert($g3->checkValidSegment());
		assert($g3->isValid());
		assert($g3->checkValid());
		assert(($g4 = $g3->changeSegments(array('table'=>123,'page'=>4))) instanceof Core\Route);
		assert(!$g4->isValidSegment());
		assert(($g5 = $g3->keepSegments('page')) instanceof Core\Route);
		assert($g5->segment()['page'] === 4);
		assert($g5->segment()['table'] === null);
		assert(!$route::isSegmentClass());
		assert($general::isSegmentClass());
		assert($general::checkSegmentClass());
		assert($loginSubmit::makeRouteRequest() instanceof Routing\RouteRequest);
		assert($general::makeRouteRequest(array('table'=>'ormTable')) instanceof Routing\RouteSegmentRequest);
		assert($general::makeRouteRequest('asdasdss') instanceof Routing\RouteSegmentRequest);
		assert($general::getDefaultSegment() === '-');
		assert($general::getReplaceSegment() === '%%%');
		assert($g->title() === 'Super Orm En');
		assert($g->aTitle() === "<a href='/en/table/ormTable/1/20/-/-/-/-/-/-/-' hreflang='en'>Super Orm En</a>");
		assert($query->isValid());
		assert($query->checkValid() === $query);
		assert($query->aTitle() === "<a href='/en/table/ormTable/1/20/-/-/-/-/-/-/-?s=%C3%A9ric' hreflang='en'>Super Orm En</a>");
		assert($g->aOpenTitle() === "<a href='/en/table/ormTable/1/20/-/-/-/-/-/-/-' hreflang='en'>Super Orm En");
		assert($query->uri() === '/en/table/ormTable/1/20/-/-/-/-/-/-/-?s=éric');
		assert(($g2 = $g->make($g)) instanceof Core\Route);
		assert($g2->uri() === '/en/table/ormTable/1/20/-/-/-/-/-/-/-');
		assert(count($g2->segment()) === 10);

		// root
		assert(count($obj->help()) === 11);

		// access
		assert($obj::session() instanceof Core\Session);
		assert($obj::sessionCom() instanceof Core\Com);
		assert($obj::sessionUser() instanceof Core\Row\User);
		assert($obj::lang() instanceof Core\Lang);
		assert($obj::langText('label') === 'Assert');
		assert($obj::langPlural(2,'label') === 'Asserts');
		assert($obj::service('mailer') instanceof Main\Service);
		assert($obj::serviceMailer() instanceof Main\ServiceMailer);

		// bootAccess
		assert($obj::boot() instanceof Core\Boot);
		assert($obj::bootSafe() instanceof Core\Boot);
		assert($obj::bootReady() instanceof Core\Boot);

		// bootDbAccess
		assert($obj::db() instanceof Core\Db);

		/* ROUTE REQUEST */
		// prepare
		$userAgent = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_6) AppleWebKit/604.4.7 (KHTML, like Gecko) Version/11.0.2 Safari/604.4.7';
		$param = array('ssl'=>true,'ajax'=>true,'path'=>'/fr/test/de/la/vie','host'=>'google.com','method'=>'post','ip'=>'127.0.0.1','userAgent'=>$userAgent);
		$request = new Core\Request($param);
		$param2 = array('ssl'=>true,'ajax'=>false,'path'=>'/table/routeMatch','host'=>'google.com','method'=>'get','ip'=>'127.0.0.1','userAgent'=>$userAgent);
		$request2 = new Core\Request($param2);
		$param3 = array('ssl'=>true,'ajax'=>false,'path'=>'/','query'=>'abc=123&ok=true&james=lavié','post'=>array('-captcha-'=>$session->captcha(true),'-csrf-'=>$session->csrf(),'-genuine-'=>'','abc'=>'111','ok'=>'true','james'=>'lavié'),'host'=>'google.com','method'=>'get','ip'=>'127.0.0.1','userAgent'=>$userAgent);
		$request3 = new Core\Request($param3);
		assert($request3['abc'] === 111);
		assert($request3['ok'] === 'true');
		$session['test'] = 2;
		$session['test2'] = 'BLA';

		// construct
		$rr = new Routing\RouteRequest(Core\Cms\Login::class);
		$match = new Routing\RouteRequest(Core\Cms\Login::class,$request);
		$match2 = new Routing\RouteRequest(Core\Cms\Login::class,$request2);
		$match3 = new Routing\RouteRequest(Core\Cms\Home::class,$request3);
		$match4 = new Routing\RouteRequest(Core\Cms\LoginSubmit::class,$request2);
		$match5 = new Routing\RouteRequest(Core\Cms\LoginSubmit::class,"https://google.com/asdsa?ok=2");
		$match6 = new Routing\RouteRequest(Core\Cms\LoginSubmit::class,array('host'=>'james.com'));
		assert($match5->request()->absolute() === "https://google.com/asdsa?ok=2");
		assert($match6->request()->absolute() === Base\Request::scheme()."://james.com");

		// toString

		// reset

		// isValid

		// checkValid

		// isValidMatch
		assert($match3->isValidMatch($session));

		// checkValidMatch
		assert($match3->checkValidMatch());

		// isValidVerify
		assert($match3->isValidVerify($session));

		// checkValidVerify
		assert($match3->checkValidVerify());

		// isRequestInst
		assert(!$match->isRequestInst());
		assert($rr->isRequestInst());

		// valid
		assert($match3->valid() === array('match'=>true,'verify'=>true));
		assert($match3->valid('match') === true);
		assert($match3->valid('matchz') === false);

		// fallback
		assert($match3->fallback() === null);

		// setFallback

		// route
		assert($rr->route() === Core\Cms\Login::class);

		// setRoute

		// request
		assert($rr->request() instanceof Core\Request);

		// setRequest

		// validateMatch
		assert($match3->validateMatch($session) === true);

		// validateVerify
		assert($match3->validateVerify($session) === true);

		// validateArray

		// path
		assert($match2->path('/table/routeMatch'));

		// ssl
		assert($match->ssl(null));
		assert($match->ssl(true));
		assert(!$match->ssl(false));

		// ajax
		assert($match->ajax(null));
		assert($match->ajax(true));
		assert(!$match->ajax(false));

		// host
		assert($match->host(null));
		assert($match->host('google.com'));
		assert($match->host(array('google.com','ok.com')));
		assert(!$match->host(array('google2.com','ok.com')));

		// method
		assert($match->method(null));
		assert($match->method('post'));
		assert(!$match->method('get'));
		assert($match->method(array('POST','get')));
		assert(!$match->method(array('get')));

		// header
		assert($match->header(true));
		assert($match->header(array('X-Requested-With'=>true)));
		assert($match->header(array('X-Requested-With'=>array('='=>'XMLHttpRequest'))));
		assert(!$match->header(array('X-Requested-With'=>'XMLHttpRequestz')));

		// lang
		assert($match->lang(null));
		assert($match->lang('fr'));
		assert(!$match->lang('en'));
		assert($match->lang(array('fr','en')));
		assert(!$match->lang(array('Fr','en')));

		// ip
		assert($match->ip(null));
		assert($match->ip(array('127.0.0.*')));
		assert($match->ip(array('127.0.*.*')));
		assert(!$match->ip(array('128.0.*.*')));
		assert($match->ip(array('128.0.*.*','127.0.0.1')));

		// browser
		assert($match->browser(null));
		assert($match->browser('Safari'));
		assert(!$match->browser('Google'));
		assert($match->browser(array('Google','Safari')));

		// query
		assert(!$match->query(true));
		assert($match->query(false));
		assert($match3->query(true));
		assert($match3->query(true));
		assert($match3->query(array('abc'=>123,'ok'=>'string','james'=>array('='=>'lavié'))));
		assert(!$match3->query(array('abc'=>123,'ok'=>'string','james'=>array('='=>'lavié2'))));

		// post
		assert(!$match->post(true));
		assert($match->post(false));
		assert($match3->post(true));
		assert($match3->post(true));
		assert($match3->post(array('abc'=>111,'ok'=>'string','james'=>array('='=>'lavié'))));
		assert(!$match3->post(array('bla'=>'array','abc'=>111,'ok'=>'string','james'=>array('='=>'lavié'))));
		assert(!$match3->post(array('abc'=>111,'ok'=>'array','james'=>array('='=>'lavié'))));

		// genuine
		assert($match3->genuine(true));
		assert(!$match4->genuine(true));
		assert($match3->genuine(null));
		assert($match4->genuine(false));

		// role
		assert($match->role(null,$session));
		assert(!$match3->role(1,$session));
		assert($match3->role(array('>'=>1),$session));
		assert($match3->role(array(80,90),$session));
		assert($match3->role(80,$session));
		assert(!$match3->role(array(90),$session));

		// session
		assert($match->session(null,$session));
		assert(!$match3->session(true,$session));
		assert(!$match3->session(false,$session));
		assert(!$match3->session('isNobody',$session));
		assert($match3->session('isSomebody',$session));
		assert($match3->session(array('isSomebody','isAdmin'),$session));
		assert(!$match3->session(array('isSomebody','isNobody'),$session));

		// csrf
		assert($match->csrf(null,$session));
		assert($match->csrf(false,$session));
		assert($match3->csrf(true,$session));

		// captcha
		assert($match->captcha(null,$session));
		assert($match->captcha(false,$session));
		assert($match3->captcha(true,$session));

		// timeout
		assert($match->timeout(null,$session));
		assert($match->timeout(false,$session));
		assert($match->timeout(true,$session));
		assert($match->timeout('testa',$session));

		// timedOut

		// schemeHost
		assert($match4->schemeHost() === $schemeHost);
		assert($match4->schemeHost(true) === $schemeHost);

		// uri
		assert($match4->uri('fr') === $schemeHost.'/fr/connexion');
		assert($match->uri('fr') === $schemeHost);
		assert($match4->uri('fr',array('query'=>array('test'=>2,'james'=>'lolé'))) === $schemeHost."/fr/connexion?test=2&james=lolé");
		assert($match4->uri('fr',array('query'=>true)) === $schemeHost.'/fr/connexion');

		// uriPrepare

		// uriOutput
		assert($match4->uriOutput('fr',array('absolute'=>true)) === $schemeHost.'/fr/connexion');
		assert($match4->uriOutput('fr',array('absolute'=>false)) === '/fr/connexion');
		assert($match4->uriOutput('fr') === '/fr/connexion');

		// uriRelative
		assert($match4->uriRelative('fr') === '/fr/connexion');

		// uriAbsolute
		assert($match4->uriAbsolute('fr') === $schemeHost.'/fr/connexion');
		assert($match5->uriAbsolute('de') === null);
		assert($match6->uriAbsolute('en') === $schemeHost.'/en/login');
		assert($match5->uriAbsolute('fr') === $schemeHost.'/fr/connexion');

		// allowed
		assert($match3::allowed(80,$session->role()));


		/* ROUTE SEGMENT REQUEST */

		// construct
		$rr = new Routing\RouteSegmentRequest($general,new Core\Request("/fr/table/ormTable/1/20/-/-/-/-/-/-/-"),$lang);
		$rr2 = new Routing\RouteSegmentRequest($general,new Core\Request("/fr/table/ormTablezz/1/20/-/-/-/-/-/-/-"),$lang);
		$rr3 = new Routing\RouteSegmentRequest($specific,new Core\Request("/fr/table/user/1"),$lang);
		$rr4 = new Routing\RouteSegmentRequest($specific,new Core\Request("/fr/table/user/20"),$lang);
		$rr5 = new Routing\RouteSegmentRequest($general,'ormTable',$lang);
		$rr6 = new Routing\RouteSegmentRequest($specific,$db['user'][1],$lang);
		$rr7 = new Routing\RouteSegmentRequest($general,$db['user'],$lang);
		$rr8 = new Routing\RouteSegmentRequest($general,array('table'=>$db['user']),$lang);
		$rr9 = new Routing\RouteSegmentRequest($general,array('table'=>'user','limit'=>20,'page'=>1),$lang);
		$rr10 = new Routing\RouteSegmentRequest($specific,array('user',2),$lang);
		$rr11 = new Routing\RouteSegmentRequest($general,array('table'=>'user'),$lang);

		// reset

		// isValid
		assert(!$rr2->isValid($session));
		assert($rr->isValid($session));

		// checkValid
		assert($rr->checkValid($session));

		// setLangCode

		// langCode
		assert($rr->langCode() === 'en');

		// setRoute

		// routeSegment
		assert($rr->routeSegment() === array('table','page','limit','order','direction','cols','filter','in','notIn','highlight'));
		assert($rr5->routeSegment() === array('table','page','limit','order','direction','cols','filter','in','notIn','highlight'));

		// setRequest

		// parseRequestSegmentFromRequest

		// parseRequestSegmentFromRequestCatchAll

		// parseRequestSegmentFromValue

		// isRouteCatchAll
		assert(!$rr->isRouteCatchAll());

		// isSegmentParsedFromValue
		assert(!$rr->isSegmentParsedFromValue());
		assert($rr8->isSegmentParsedFromValue());

		// isRouteRequestCompatible
		assert($rr->isRouteRequestCompatible());

		// requestSegment
		assert($rr->requestSegment() === array('table'=>'ormTable','page'=>1,'limit'=>20,'order'=>'-','direction'=>'-','cols'=>'-','filter'=>'-','in'=>'-','notIn'=>'-','highlight'=>'-'));
		assert($rr3->requestSegment() === array('table'=>'user','primary'=>1));
		assert($rr5->requestSegment() === array('table'=>'ormTable','page'=>'ormTable','limit'=>'ormTable','order'=>'ormTable','direction'=>'ormTable','cols'=>'ormTable','filter'=>'ormTable','in'=>'ormTable','notIn'=>'ormTable','highlight'=>'ormTable'));
		assert($rr7->requestSegment() === array('table'=>$db['user'],'page'=>$db['user'],'limit'=>$db['user'],'order'=>$db['user'],'direction'=>$db['user'],'cols'=>$db['user'],'filter'=>$db['user'],'in'=>$db['user'],'notIn'=>$db['user'],'highlight'=>$db['user']));

		// hasRequestSegment
		assert($rr7->hasRequestSegment('page','limit'));
		assert(!$rr7->hasRequestSegment('page','limitz'));

		// checkRequestSegment
		assert($rr7->checkRequestSegment('page','limit'));

		// changeRequestSegment
		assert($rr7->uri('fr') === '/fr/table/user/1/-/-/-/-/-/-/-/-');
		assert($rr7->changeRequestSegment('page',2) === $rr7);
		assert(count($rr7->requestSegment()) === 10);
		assert($rr7->requestSegment()['page'] === 2);
		assert($rr7->changeRequestSegment('page','BLA') === $rr7);
		assert($rr7->uri('fr') === '/fr/table/user/1/-/-/-/-/-/-/-/-');

		// changeRequestSegments
		assert($rr9->changeRequestSegments(array('table'=>'ormSql','page'=>3)) === $rr9);

		// keepRequestSegments
		assert($rr9->keepRequestSegments('table','limit') === $rr9);
		assert($rr9->requestSegment()['page'] === null);
		assert($rr9->uri('en') === '/en/table/ormSql/1/20/-/-/-/-/-/-/-');
		assert($rr9->makeRequestSegment()['page'] === '1');
		assert($rr9->changeRequestSegments(array('table'=>'ormSql','page'=>3)) === $rr9);

		// makeRequestSegment
		assert($rr7->requestSegment()['table'] instanceof Core\Table);
		assert($rr7->makeRequestSegment() === array('table'=>'user','page'=>'1','limit'=>'-','order'=>'-','direction'=>'-','cols'=>'-','filter'=>'-','in'=>'-','notIn'=>'-','highlight'=>'-'));

		// isValidSegment
		assert($rr->isValidSegment($session));
		assert($rr6->isValidSegment($session));

		// checkValidSegment
		assert($rr->checkValidSegment($session) === $rr);

		// validateSegment
		assert($rr->validateSegment($session));
		assert($rr2->validateSegment($session,false) === false);
		assert($rr3->validateSegment($session));
		assert(!$rr4->validateSegment($session));
		assert(!$rr5->validateSegment($session));
		assert(!$rr9->validateMatch($session));
		assert($rr9->validateVerify($session));
		assert($rr9->validateSegment($session,true));
		assert(!$rr6->validateMatch($session));
		assert($rr6->validateVerify($session));
		assert($rr6->validateSegment($session,true));

		// validateArray

		// segment
		assert($rr->segment() === array('table'=>$db['ormTable'],'page'=>1,'limit'=>20,'order'=>$db['ormTable']['id'],'direction'=>'desc','cols'=>$rr->segment()['cols'],'filter'=>array(),'in'=>array(),'notIn'=>array(),'highlight'=>array()));
		assert($rr6->segment() === array('table'=>$db['user'],'primary'=>$db['user'][1]));

		// path

		// pathCatchAll

		// uri
		assert($rr->uri('en') === '/en/table/ormTable/1/20/-/-/-/-/-/-/-');
		assert($rr5->uri('fr') === '/fr/table/ormTable/1/-/ormTable/ormtable/ormTable/ormTable/ormTable/ormTable/ormTable');
		assert($rr6->uri('fr') === '/fr/table/user/1');
		assert($rr7->uri('fr') === '/fr/table/user/1/-/-/-/-/-/-/-/-');
		assert($rr8->uri('fr') === '/fr/table/user/1/-/-/-/-/-/-/-/-');
		assert($rr9->uri('fr') === '/fr/table/ormSql/3/20/-/-/-/-/-/-/-');
		assert($rr10->uri('en') === '/en/table/user/2');
		assert($rr11->uri('en') === '/en/table/user/1/-/-/-/-/-/-/-/-');

		// routeRequest
		assert($rr5->uriAbsolute('en') === Base\Request::schemeHost()."/en/table/ormTable/1/-/ormTable/ormtable/ormTable/ormTable/ormTable/ormTable/ormTable");
		assert($rr->uriAbsolute('fr') === Base\Request::schemeHost()."/fr/table/ormTable/1/20/-/-/-/-/-/-/-");
		assert($rr->uriRelative('fr') === "/fr/table/ormTable/1/20/-/-/-/-/-/-/-");
		assert($rr6->uriAbsolute('en') === Base\Request::schemeHost()."/en/table/user/1");
		
		return true;
	}
}
?>