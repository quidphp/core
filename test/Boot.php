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
use Quid\Routing;

// boot
// class for testing Quid\Core\Boot
class Boot extends Base\Test
{
    // trigger
    final public static function trigger(array $data):bool
    {
        // prepare
        $boot = $data['boot'];
        $type = $boot->type();

        // construct

        // toString

        // destruct

        // onPrepare

        // onDispatch

        // onCloseBody

        // onCloseDown

        // onShutDown

        // onCore

        // onBeforeReady

        // onReady

        // onLaunch

        // onMatch

        // onAfter

        // onTeardown

        // onCleanup

        // cast
        assert(count($boot->_cast()) === 2);

        // getReplace
        assert(count($boot->getReplace()) === 23);

        // getReplaceForCache
        assert(count($boot->getReplaceForCache()) === 6);

        // getAdminEmail
        assert(is_array($boot->getAdminEmail()));
        assert(is_string($boot->getAdminEmail(true)));

        // prepare

        // dispatch

        // core

        // compile

        // compileAttr

        // launch

        // getFirstMatch

        // match
        assert(!empty($boot->match()));

        // setRoute

        // route
        assert($boot->route() === null);

        // teardown

        // cleanup

        // end

        // isStatus
        assert($boot->isStatus($boot->status()));

        // setStatus

        // status
        assert(is_int($boot->status()));

        // checkStatus
        assert($boot->checkStatus($boot->status()) === $boot);

        // isReady
        assert($boot->isReady());

        // checkReady
        assert($boot->checkReady() === $boot);

        // setName

        // name
        assert($boot->name() === 'assert');
        assert($boot->name(true) === 'Assert');

        // makeInitialAttr

        // makeFinalAttr

        // getConfigFile

        // makeFinderShortcut

        // makeRequest

        // request
        assert($boot->request() instanceof Core\Request);

        // response
        assert($boot->response() instanceof Main\ResponseCurrent);

        // paths
        assert(count($boot->paths()) >= 6);

        // path
        assert(is_string($boot->path('public')));

        // pathOverview

        // makePaths
        assert(is_string($boot->makePaths(['[public]/james'])[0]));

        // makePath
        assert(is_string($boot->makePath('[public]/james')));

        // envs
        assert($boot->envs() === ['dev','staging','prod']);

        // types
        assert($boot->types() === ['assert']);

        // hosts
        assert(is_array($boot->hosts()));

        // host
        assert(is_string($boot->host('dev')));

        // checkHost

        // makeEnvType

        // envType
        assert(count($boot->envType()) === 2);

        // context
        assert(count($boot->context()) === 4);
        assert(count($boot->context(true)) === 5);

        // env
        assert(is_string($boot->env()));

        // envIndex
        assert(is_int($boot->envIndex()));

        // type
        assert(is_string($boot->type()));

        // typeIndex
        assert($boot->typeIndex() === 0);

        // typePrimary
        assert(is_string($boot->typePrimary()));

        // envTypeFromHost

        // isEnv
        assert(is_bool($boot->isEnv($boot->env())));

        // isDev
        assert(is_bool($boot->isDev()));

        // isStaging
        assert(is_bool($boot->isStaging()));

        // isProd
        assert(is_bool($boot->isProd()));

        // isType
        assert($boot->isType($type));
        assert(!$boot->isType('what'));

        // typeAs
        assert($boot->typeAs('test','what') === null);

        // climbableKeys
        assert(count($boot->climbableKeys()) === 4);

        // valuesWrapClimb
        assert($boot->valuesWrapClimb(['james'=>'test'])['james'] === '@test');

        // makeConfigClosure

        // replaceSpecial
        $array = ['@assert'=>['ok'=>true],'@appz'=>['ok'=>false]];
        $array2 = ['@assert'=>['ok2'=>true],'@appz'=>['ok'=>false]];
        assert($boot->replaceSpecial(null,[],$array,$array2) === ['@appz'=>['ok'=>false],'ok'=>true,'ok2'=>true]);

        // ini

        // isPreload
        assert(is_bool($boot->isPreload()));

        // autoloadType
        assert(is_string($boot->autoloadType()));

        // autoload

        // checkIp

        // getSchemeArray

        // getFinderHostTypes

        // schemes
        assert(!empty($boot->schemes()));

        // scheme
        assert($boot->scheme(null,null,false) === $boot->scheme());
        assert($boot->scheme('prod',$type) === 'https');
        assert($boot->scheme(true,true) === Base\Request::scheme());

        // shemeHost
        assert($boot->schemeHost() === Base\Request::schemeHost());

        // schemeHosts
        assert(count($boot->schemeHosts()) === 2);

        // schemeHostTypes
        assert(count($boot->schemeHostTypes()) === 1);

        // schemeHostEnvs
        assert($boot->schemeHostEnvs('what') === []);
        assert(count($boot->schemeHostEnvs($type)) >= 1);

        // setsUriShortcut

        // versions
        assert(count($boot->versions()) === 2);

        // version
        assert($boot->version('what') === null);
        assert($boot->version($type) === $boot->version(true));
        assert($boot->version($type,false) === '1.0.1');
        assert($boot->version() === '1.0.1-'.QUID_VERSION);

        // setsSymlink

        // setsCopyLink

        // setsCallable

        // isFromCache
        assert(is_bool($boot->isFromCache()));

        // shouldCache
        assert(is_bool($boot->shouldCache()));

        // shouldCompile
        assert(is_bool($boot->shouldCompile()));

        // makeExtenders

        // newExtenders

        // newExtendersClosure

        // extenders
        assert($boot->extenders() instanceof Main\Extenders);
        assert($boot->extenders()->count() === 12);

        // routes
        assert($boot->routes() instanceof Routing\Routes);
        assert($boot->routes($type) === $boot->routes());

        // setRoles

        // roles
        assert($boot->roles() instanceof Main\Roles);

        // lang
        assert($boot->lang() instanceof Core\Lang);

        // langContentClass
        assert(is_a($boot->langContentClass('en'),Core\Lang\En::class,true));

        // label
        assert($boot->label() === 'Assert');

        // description
        assert($boot->description() === 'Descr Boot');

        // typeLabel
        assert($boot->typeLabel() === 'Assertion script');

        // envLabel
        assert(is_string($boot->envLabel()));

        // typeEnvLabel
        assert(is_string($boot->typeEnvLabel()));

        // services
        assert($boot->services() instanceof Main\Services);
        assert(count($boot->services()) >= 2);

        // service
        assert($boot->service('mailer') instanceof Core\Service\PhpMailer);

        // checkService
        assert($boot->checkService('mailer') instanceof Core\Service\PhpMailer);

        // serviceMailer
        assert($boot->serviceMailer() instanceof Core\ServiceMailer);

        // checkServiceMailer
        assert($boot->checkServiceMailer() instanceof Core\ServiceMailer);

        // redirection
        assert($boot->redirection() instanceof Routing\Redirection);

        // db
        assert($boot->db() instanceof Core\Db);

        // hasDb
        assert($boot->hasDb());

        // session
        assert($boot->session() instanceof Core\Session);

        // hasSession

        // manageRedirect

        // getOption
        assert($boot->getOption('logo') === null);

        // getSecretKey
        assert($boot->getSecretKey() === $boot::class);

        // info
        assert(count($boot->info()) === 9);

        // STATIC

        // parseSchemeHost

        // envTypeFromValue
        assert($boot->envTypeFromValue('test.com',['dev/'.$type=>'test.com'],$boot->envs(),$boot->types()) === ['env'=>'dev','type'=>$type]);
        assert($boot->envTypeFromValue('test.com',['dev/appz'=>'test.com'],$boot->envs(),$boot->types()) === null);

        // envTypeFromString
        assert($boot->envTypeFromString('dev/'.$type,$boot->envs(),$boot->types()) === ['env'=>'dev','type'=>$type]);
        assert($boot->envTypeFromString('dev/appz',$boot->envs(),$boot->types()) === null);

        // requirement

        // setErrorLog

        // checkWritable

        // setsConfig

        // quidVersion
        assert(is_string($boot::quidVersion()));

        // quidCredit
        assert(strlen($boot::quidCredit()) > 100);
        assert(count($boot::quidCredit(false)) === 9);

        // extendersNamespaces
        assert(count($boot::extendersNamespaces()) >= 2);

        // unclimbableKeys
        assert(count($boot::unclimbableKeys()) === 7);

        // initReplaceMode
        assert(count($boot::initReplaceMode()) === 3);

        // isInit
        assert($boot::isInit());

        // initialize

        // nameFromClass

        // setAutoloadType

        // start

        // new

        // inst
        assert(Core\Boot::inst() === $boot);
        assert(Core\Lang::inst() === $boot->lang());
        assert(Core\Db::inst() === $boot->db());
        assert(Core\Boot::instReady() === $boot);

        return true;
    }
}
?>