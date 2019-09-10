<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Test\Core;
use Quid\TestSuite;
use Quid\Core;
use Quid\Base;

// col
// class for testing Quid\Core\Col
class Col extends Base\Test
{
    // trigger
    public static function trigger(array $data):bool
    {
        // prepare
        $db = Core\Boot::inst()->db();
        $table = 'ormCol';
        assert($db->truncate($table) instanceof \PDOStatement);
        assert($db->inserts($table,['id','active','name','password','email','dateAdd','userAdd','dateModify','userModify'],[1,1,'james','james','james@gmail.com',10,11,12,13],[2,2,'james2','james2','james2@gmail.com',20,21,22,23]) === [1,2]);
        $tb = $db[$table];
        $col = $tb['name'];
        $active = $tb->cols()->get('active');
        $dateAdd = $tb->cols()->get('dateAdd');
        $email = $tb->cols()->get('email');
        $password = $tb->cols()->get('password');
        $date = $tb['date'];
        $array = $tb['myRelation'];
        $lang = $tb['relationLang'];
        $multi = $tb['multi'];
        $check = $tb['check'];
        $media = $tb['media'];
        $phone = $tb['phone'];
        $passwordReset = $db['user']['passwordReset'];
        $slug = $tb['slug_fr'];
        $storage = $tb['storage'];
        $row = $tb[1];
        $medias = $tb['medias'];

        // getOverloadKeyPrepend
        assert($password::getOverloadKeyPrepend() === 'Col');

        // orm
        assert($col instanceof Core\Col);
        assert($row instanceof TestSuite\Row\OrmCol);

        // route

        // colCell

        // tableAccess
        assert($col->checkLink() === $col);
        assert($col->table() instanceof Core\Table);
        assert($col->db() instanceof Core\Db);
        assert($col->sameTable($tb));

        // col
        assert($tb->colAttr('myRelation') === ['relation'=>['test',3,4,9=>'ok']]);
        assert($tb->colAttr('email')['description'] === 'Ma description');
        assert(count($tb->colAttr('email')) === 5);

        // active
        assert($active->onGet(1,[]) === 1);
        assert($active->complexTag() === 'checkbox');
        assert(strlen($active->formComplex()) === 175);

        // auto

        // boolean

        // context

        // contextType

        // countCommit

        // date
        assert($date instanceof Core\Col\Date);
        assert($date->date() === 'dateToDay');
        assert($date->valueComplex('08-08-1984') === '08-08-1984');
        assert($date->valueComplex(true) === null);
        assert($date->valueComplex(mktime(0,0,0,8,8,1984)) === '08-08-1984');
        assert($date::makeDateFormat(true) === 'F j, Y');
        assert($date::allowedFormats() === [true,'dateToDay','dateToMinute','dateToSecond']);

        // dateAdd
        assert($dateAdd->date() === 'long');

        // dateLogin

        // dateModify

        // email
        assert($email instanceof Core\Col\Email);
        assert(is_string($email->get()));

        // enum
        assert($lang->complexTag() === 'radio');
        assert(strlen($lang->formComplex()) === 555);
        assert(strlen($lang->formComplex(3)) === 573);

        // error

        // excerpt

        // files

        // floating

        // fragment

        // integer

        // json

        // jsonArray

        // jsonArrayRelation

        // jsonExport

        // media + medias
        assert($media instanceof Core\Col\Media);
        assert(Base\Dir::is($media->rootPath()));
        assert($media->formComplex() === "<div class='block empty'><div class='form'><input name='media' type='file'/></div></div>");
        assert($media->hasVersion());
        assert(count($media->versions()) === 2);
        assert($media->version(1) !== $media->version(-1));
        assert(count($media->version('small')) === 6);
        assert($media->versionDetails()['large'] === 'Large: 500px x 400px ratio_y 70% jpg');
        assert(count($media->details()) === 4);
        assert($media->details()[2] === 'Small: 300px x 200px crop 50% jpg');
        assert($media->versionKey('large') === 'large');
        assert($media->versionKey(null) === 0);
        assert($media->versionKey(-1) === 'small');
        assert($media->versionKey(1) === 'large');
        assert($storage->ruleValidate()['extension'] instanceof \Closure);
        assert($storage->ruleValidate()['extension']('lang') === ['pdf']);
        assert($storage->ruleValidate(true)[3] === 'The extension of the file must be: pdf');
        assert(count($storage->extension()) === 1);
        assert(count($storage->extensionDetails(false)) === 1);
        assert($storage->extensionDetails() === 'The extension of the file must be: pdf');
        assert(!$storage->hasIndex());
        assert($storage->getAmount() === 1);
        assert($medias->hasIndex());
        assert($medias->getAmount() === 6);
        assert(count($medias::defaultVersion()) === 6);
        assert($media::defaultVersionExtension() === ['jpg','png']);
        assert($media::defaultConvertExtension() === 'jpg');

        // phone
        assert($phone instanceof Core\Col\Phone);
        assert($phone->onGet(5144839999,[]) === '(514) 483-9999');

        // pointer

        // primary

        // relation

        // request

        // requestIp

        // serialize

        // session

        // set
        assert($multi->complexTag() === 'multiselect');
        assert($check->complexTag() === 'search');
        assert(strlen($array->formComplex(null,['data-required'=>null])) === 177);
        assert(strlen($array->formComplex()) === 195);
        assert(strlen($multi->formComplex(2)) === 165);
        assert(strlen($multi->formComplex([2,5])) === 185);

        // slug
        assert($slug instanceof Core\Col\Slug);
        assert($slug->onSet('dasasd dsaasd asddas',[],null,[]) === 'dasasd dsaasd asddas');
        assert($slug->onSet(null,['name_en'=>'OK'],null,[]) === null);
        assert(is_array($slug->slugAttr()));
        assert($slug->slugDateConvert('date','12-05-2018') === '2018-12-05');
        assert($slug->slugDo('lol') === false);
        assert($slug->slugUnique('blabla'));
        assert($slug->slugKeyFromArr(['name'=>'james']) === 'james');
        assert($slug->slugKeyFromArr(['name_fr'=>'jamesFr','name_en'=>'jamesEn']) === 'jamesFr');
        assert($slug->slugAddNow('blabla') !== 'blabla');
        assert($slug->slugDateFirst() === 'ymd');

        // slugPath

        // textarea

        // timezone

        // userActive

        // userAdd

        // userCommit

        // userModify

        // username

        // userPassword
        assert($password instanceof Core\Col\UserPassword);
        assert(count($password->inputs()) === 2);
        assert(strlen($password->formComplex()) === 285);

        // userPasswordReset
        assert($passwordReset instanceof Core\Col\UserPasswordReset);
        assert(strlen($passwordReset->onGet('dssddsa',[])) === 40);

        // userRole

        // video

        // yes

        // cleanup
        assert($db->truncate($table) instanceof \PDOStatement);

        return true;
    }
}
?>