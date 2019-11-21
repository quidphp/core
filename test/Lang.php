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
        $boot = $data['boot'];
        $lang = $bootLang = $boot->lang();
        $tb = $boot->db()->table('ormCol');
        $bootLang->changeLang('fr');
        $fr = $boot->getAttr('assert/lang/fr');
        $frFile = $boot->getAttr('assert/langFile/fr');
        $en = $boot->getAttr('assert/lang/en');

        // onChange

        // existsRelation
        assert($lang->existsRelation('role/label/1'));
        assert(!$lang->existsRelation('activcontextTypeeVisible/appaa'));

        // existsCom
        assert($lang->existsCom('pos','insert/*/success'));

        // direction
        assert($lang->direction('DESC') === 'Descendant');

        // bootLabel
        assert($lang->bootLabel() === 'Assertion');

        // bootDescription
        assert($lang->bootDescription() === 'Descr Boot');

        // typeLabel

        // envLabel
        assert($lang->envLabel('dev') === 'Développement');

        // langLabel
        assert($lang->langLabel('fr') === 'Français');

        // dbLabel
        assert($lang->replace($frFile) === $lang);
        assert($lang->dbLabel('assert') === 'Well');

        // dbDescription
        assert($lang->dbDescription('assert') === 'OK');

        // tableLabel
        assert($lang->tableLabel('user') === 'Utilisateur');
        assert($lang->tableLabel('user','en') === 'User');

        // tableDescription

        // colLabel
        assert($lang->colLabel('username','user') === "Nom d'utilisateur");
        assert($lang->colLabel('session_id','user') === 'Session');

        // colDescription

        // rowLabel
        assert($lang->rowLabel(2,'user') === 'Utilisateur #2');
        assert($lang->rowLabel(2,'user','en') === 'User #2');

        // rowDescription
        assert($lang->rowDescription(3,'user') === null);

        // panelLabel

        // panelDescription

        // roleLabel
        assert($lang->roleLabel(80) === 'Administrateur');
        assert($lang->roleLabel(80,'en') === 'Admin');

        // roleDescription
        assert($lang->roleDescription(3) === null);

        // routeLabel
        assert($lang->routeLabel('error') === 'Erreur');
        assert($lang->routeLabel('error','en') === 'Error');

        // routeDescription
        assert($lang->routeDescription('error') === null);

        // bool
        assert($lang->bool(true) === 'Oui');
        assert($lang->bool(false,'en') === 'No');
        assert($lang->bool(0,'en') === 'No');
        assert($lang->bool('0','en') === 'No');

        // relation
        assert(Base\Arrs::is($lang->relation()));
        assert($lang->relation('role/label/1','en') === 'Nobody');

        // validate
        assert($lang->validate(['Doit être unique #6']) === 'Doit être unique #6');
        assert($lang->validate(['email']) === 'Doit être un courriel valide (x@x.com)');
        assert($lang->validate(['>'=>2]) === 'Doit être plus grand que 2');
        assert($lang->validate(['>'=>2],'en') === 'Must be larger than 2');
        assert($lang->validate(['string']) === 'Doit être une chaîne');
        assert($lang->validate(['strMaxLength'=>4]) === 'Doit avoir une longueur maximale de 4 caractères');
        assert($lang->validate(['arrCount'=>4],'en') === 'Array count must be 4');
        assert($lang->validate(['strLength'=>4]) === 'Doit être une chaîne avec 4 caractères');
        assert($lang->validate(['strLength'=>1]) === 'Doit être une chaîne avec 1 caractère');
        assert($lang->validate(['numberMinLength'=>3]) === 'Doit avoir une longueur minimale de 3 caractères');
        assert($lang->validate(['maxLength'=>1]) === 'Doit avoir une longueur maximale de 1 caractère');
        assert($lang->validate(['reallyEmpty']) === 'Doit être vide (0 permis)');
        assert($lang->validate(['closure']) === 'Doit passer le test de la fonction anynonyme');
        assert($lang->validate(['instance'=>\DateTime::class]) === 'Doit être une instance de DateTime');
        assert($lang->validate(['uriPath'],'en') === 'Must be a valid uri path');
        assert($lang->validate(['numberWholeNotEmpty']) === 'Doit être un chiffre entier non vide');
        assert($lang->validate(['scalarNotBool']) === 'Doit être chaîne scalaire non booléenne');
        assert($lang->validate(['extension'=>['jpg','png']]) === "L'extension du fichier doit être: jpg, png");
        assert($lang->validate(['maxFilesize'=>'5 Ko']) === 'La taille du fichier doit être plus petite que 5 Ko');
        assert(count($lang->validate()) === 116);

        // validates
        assert($lang->validates(['alpha','!'=>3,'>'=>2])[1] === 'Ne doit pas être égal à 3');
        assert($lang->validates(['alpha','!'=>3,'>'=>2],'en')[1] === 'Must be different than 3');
        assert($lang->validates(['maxLength'=>45]) === ['Doit avoir une longueur maximale de 45 caractères']);
        assert($lang->validates([['maxLength'=>55]]) === ['Doit avoir une longueur maximale de 55 caractères']);

        // compare
        assert($lang->compare(['>'=>'james']) === 'Doit être plus grand que james');
        assert($lang->compare(['>'=>'james','<'=>'test']) === null);
        assert(count($lang->compare(null,null,['path'=>['table','what']])) === 11);

        // compares
        assert($lang->compares(['>'=>'james','<'=>'test'])[1] === 'Doit être plus petit que test');

        // required
        assert($lang->required(true) === 'Ne peut pas être vide');
        assert($lang->required(true,'en') === 'Cannot be empty');
        assert(count($lang->required()) === 2);

        // unique
        assert($lang->unique(true) === 'Doit être unique');
        assert($lang->unique(4) === 'Doit être unique (#4)');
        assert($lang->unique('what','en') === 'Must be unique (what)');
        assert($lang->unique([2,3,'what',4]) === 'Doit être unique (#2, #3, what, #4)');
        assert(count($lang->unique()) === 2);

        // editable
        assert($lang->editable(true) === 'Ne peut pas être modifié');
        assert($lang->editable(true,'en') === 'Cannot be modified');
        assert(count($lang->editable()) === 2);

        // pathAlternate
        assert($lang->pathAlternate('required',null) === 'required');

        // pathAlternateTake
        assert(count($lang->pathAlternateTake('validate')) === 116);
        assert(count($lang->pathAlternateTake('compare')) === 11);
        assert(count($lang->pathAlternateTake('compare',null,['table','what'])) === 11);
        assert(count($lang->pathAlternateTake('required')) === 2);
        assert(count($lang->pathAlternateTake('unique')) === 2);

        // pathAlternateValue

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