<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 * Readme: https://github.com/quidphp/core/blob/master/README.md
 */

namespace Quid\Test\Core;
use Quid\Base;
use Quid\Core;

// lang
// class for testing Quid\Core\Lang
class Lang extends Base\Test
{
    // trigger
    final public static function trigger(array $data):bool
    {
        // prepare
        $boot = $data['boot'];
        $lang = $bootLang = $boot->lang();
        $tb = $boot->db()->table('ormCol');
        $bootLang->changeLang('fr');
        $fr = $boot->getAttr('assert/lang/fr');
        $en = $boot->getAttr('assert/lang/en');

        // bootLabel
        assert($lang->bootLabel() === 'Assertion');

        // bootDescription
        assert($lang->bootDescription() === 'Descr Boot');

        // typeLabel

        // envLabel
        assert($lang->envLabel('dev') === 'Développement');

        // routeLabel
        assert($lang->routeLabel('error') === 'Erreur');
        assert($lang->routeLabel('error','en') === 'Error');

        // routeDescription
        assert($lang->routeDescription('error') === null);

        // main
        assert($lang->getPath('colLabel','test') === 'col/label/*/test');
        assert($lang->take(['table','label',$tb]) === 'Meh');
        assert($lang->exists(['table','label',$tb]));
        assert($lang->existsAppend('table','label',$tb));
        assert($lang->existsTake(['table','label',$tb]));
        assert($lang->existsText(['table','label',$tb]));
        assert($lang->existsTextAppend('table',['label',$tb]));
        assert($lang->takes([['table','label',$tb]]) === ['table/label/ormCol'=>'Meh']);
        assert($lang->getAppend('table','label',$tb) === 'Meh');

        // inst
        $bootLang->unsetInst();
        $lang = new Core\Lang(['fr','en'],['onLoad'=>function(string $value) { }]);
        assert(!$lang->inInst());
        $lang->setInst();
        assert(Core\Lang::inst()->allLang() === ['fr','en']);
        $lang->checkInst();
        assert($lang->inInst());
        assert(Base\Lang::all() === ['fr','en']);
        $lang->addLang('ge');
        assert(Base\Lang::all() === ['fr','en','ge']);
        $lang->changeLang('ge');
        assert(Base\Lang::all() === ['fr','en','ge']);
        assert(Base\Lang::current() === 'ge');
        assert(Base\Uri::relative('bla_[lang].jpg') === '/bla_ge.jpg');
        $lang->changeLang('en');
        assert(Base\Lang::all() === ['fr','en','ge']);
        assert(Base\Request::$config['lang']['all'] === ['fr','en','ge']);
        assert(Base\Lang::current() === 'en');
        assert(Base\Uri::relative('bla_[lang].jpg') === '/bla_en.jpg');
        $lang->removeLang('ge');
        assert(Base\Lang::current() === 'en');
        assert(Base\Lang::all() === ['fr','en']);
        $lang->removeLang('fr');
        $lang->unsetInst();
        assert(!$lang->inInst());
        $bootLang->setInst();

        // base
        $bootLang->unsetInst();
        $lang = new Core\Lang(['en','fr']);
        $lang->changeLang('fr')->overwrite($fr::$config)->changeLang('en')->overwrite($en::$config)->setInst();
        $timestamp = 1512139242;
        assert(Base\Date::getFormat(0,'fr') === 'j %n% Y');
        assert(Base\Date::getFormatReplace(0,'fr')['format'] === 'j %n% Y');
        assert(Base\Date::getFormatReplace('y-@m@-d','fr')['replace']['@'][1] === 'Janvier');
        assert(Base\Date::parseFormat([0,'lang'=>'fr'])['format'] === 'j %n% Y');
        assert(Base\Date::format(['j %m% Y','lang'=>'fr'],$timestamp) === '1 décembre 2017');
        assert(Base\Date::format(['format'=>0,'lang'=>'fr'],$timestamp) === '1 décembre 2017');
        assert(Base\Date::format(['format'=>true,'lang'=>'fr'],$timestamp) === '1 décembre 2017');
        assert(Base\Date::format(['format'=>1,'lang'=>'fr'],$timestamp) === '1 décembre 2017 09:40:42');
        assert(Base\Date::format(['format'=>2,'lang'=>'fr'],$timestamp) === 'Décembre 2017');
        assert(Base\Date::format(['format'=>3,'lang'=>'fr'],$timestamp) === '01-12-2017');
        assert(Base\Date::format(['format'=>4,'lang'=>'fr'],$timestamp) === '01-12-2017 09:40:42');
        assert(Base\Date::parse(['j %m% Y','lang'=>'fr'],'1 décembre 2017') === ['year'=>2017,'month'=>12,'day'=>1]);
        assert(Base\Date::parse([0,'lang'=>'fr'],'12 décembre 2017') === ['year'=>2017,'month'=>12,'day'=>12]);
        assert(Base\Date::parse([2,'lang'=>'fr'],'Décembre 2017') === ['year'=>2017,'month'=>12]);
        assert(Base\Date::time('20 décembre 2017',['format'=>'j %m% Y','lang'=>'fr']) === 1513746000);
        assert(Base\Date::str(6,['year'=>12,'month'=>3,'day'=>14,'hour'=>10],'fr') === '12 années 3 mois 14 jours et 10 heures');
        assert('2 000.00' === Base\Number::format('2000','fr'));
        assert(Base\Number::getFormat('fr',['decimal'=>3]) === ['decimal'=>3,'separator'=>'.','thousand'=>' ']);
        assert('2 000.00 $' === Base\Number::moneyFormat(2000,'fr'));
        assert(Base\Number::getMoneyFormat('fr',['decimal'=>3]) === ['decimal'=>3,'separator'=>'.','thousand'=>' ','output'=>'%v% $']);
        assert(Base\Number::getSizeFormat('fr',['text'=>[1=>'James']])['text'][0] === 'Octet');
        assert(count(Base\Error::getCodes('fr')) === 16);
        assert(count(Base\Date::getMonths('fr')) === 12);
        assert(count(Base\Date::getStr('fr')) === 7);
        $lang->unsetInst();
        $bootLang->setInst();

        // cleanup
        $bootLang->changeLang('en');
        assert($bootLang->allLang() === Base\Lang::all());

        return true;
    }
}
?>