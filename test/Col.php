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
use Quid\Test\Suite;

// col
// class for testing Quid\Core\Col
class Col extends Base\Test
{
    // trigger
    final public static function trigger(array $data):bool
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
        $lang = $tb['relationLang'];
        $media = $tb['media'];
        $passwordReset = $db['user']['passwordReset'];
        $storage = $tb['storage'];
        $row = $tb[1];
        $medias = $tb['medias'];
        $float = $tb['float'];
        $int = $tb['int'];

        // details
        assert(count($col->details()) === 3);
        assert($email->details() === ['Cannot be empty','Length must be at maximum 100 characters','Must be a valid email (x@x.com)']);
        assert($email->details(false) === ['required',['maxLength'=>100],'email']);

        // generalExcerptMin
        assert($col->generalExcerptMin() === 100);

        // generalCurrentLang
        assert(Core\Col::generalCurrentLang($col) === false);

        // orm
        assert($col instanceof Core\Col);
        assert($row instanceof Suite\Row\OrmCol);
        assert($password::getOverloadKeyPrepend() === 'Col');

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
        assert($active->get(1) === 1);

        // boolean

        // context

        // contextType

        // countCommit

        // date
        assert($date instanceof Core\Col\Date);
        assert($date->date() === 'dateToDay');
        assert($date::makeDateFormat(true) === 'F j, Y');
        assert($date->allowedFormats() === [true,'dateToDay','dateToMinute','dateToSecond','sql']);
        assert($date->dateMin() === null);
        assert($date->dateMax() === null);
        assert($date->dateDaysDiff() === null);
        assert($date->dateDaysDiffFilterMethod() === 'or|day');
        assert($date->daysIn() === []);
        assert($date->monthsIn() === []);
        assert($date->yearsIn() === []);
        assert($dateAdd->dateMin() === 10);
        assert($dateAdd->dateMax() === 20);
        assert($dateAdd->dateDaysDiff() === 0);
        assert($dateAdd->dateDaysDiffFilterMethod() === 'or|day');
        assert(count($dateAdd->daysIn()) === 1);
        assert(count($dateAdd->monthsIn()) === 1);
        assert(count($dateAdd->yearsIn()) === 1);

        // dateAdd
        assert($dateAdd->date() === 'long');

        // dateLogin

        // dateModify

        // email
        assert($email instanceof Core\Col\Email);
        assert(is_string($email->get()));

        // enum

        // error

        // files

        // floating
        assert($float instanceof Core\Col\Floating);

        // integer
        assert($int instanceof Core\Col\Integer);

        // json

        // media + medias
        assert($media instanceof Core\Col\Media);
        assert(Base\Dir::isWritableOrCreatable($media->rootPath()));
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
        assert($storage->ruleValidate(true)[1] === 'The extension of the file must be: pdf');
        assert(count($storage->extension()) === 1);
        assert(count($storage->extensionDetails(false)) === 1);
        assert($storage->extensionDetails() === 'The extension of the file must be: pdf');
        assert(!$storage->hasIndex());
        assert($storage->getAmount() === 1);
        assert($medias->hasIndex());
        assert($medias->getAmount() === 6);
        assert(count($medias->defaultVersion()) === 6);
        assert($media->defaultVersionExtension() === ['jpg','png']);
        assert($media->defaultConvertExtension() === 'jpg');

        // pointer

        // primary

        // relation

        // request

        // requestIp

        // serialize

        // session

        // set

        // timezone

        // uri

        // uriAbsolute

        // uriRelative

        // userActive

        // userAdd

        // userCommit

        // userModify

        // username

        // userPassword
        assert($password instanceof Core\Col\UserPassword);
        assert(count($password->inputs()) === 2);

        // userPasswordReset
        assert($passwordReset instanceof Core\Col\UserPasswordReset);
        assert(strlen($passwordReset->get('dssddsa')) === 40);

        // userRole

        // yes

        // cleanup
        $tb->rowsUnlink();
        assert($db->truncate($table) instanceof \PDOStatement);

        return true;
    }
}
?>