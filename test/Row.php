<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Test\Core;
use Quid\Base;
use Quid\Core;
use Quid\Main;

// row
// class for testing Quid\Core\Row
class Row extends Base\Test
{
    // trigger
    final public static function trigger(array $data):bool
    {
        // prepare
        $boot = $data['boot'];
        $db = $boot->db();
        $user = $boot->session()->user();
        $table = 'ormRow';
        $tb = $db[$table];
        assert($db->truncate($table) instanceof \PDOStatement);
        assert($db->inserts($table,['id','active','name_en','dateAdd','userAdd','dateModify','userModify'],[1,1,'james',1521762409,2,12,2],[2,2,'james2',20,2,22,2]) === [1,2]);
        $row = $tb->row(1);
        $row2 = $tb->row(2);
        $admin = $boot->roles()->get(80);

        // inAllSegment
        assert($row->inAllSegment() === false);
        assert($row->cellClass($tb['id']) === Core\Cell\Primary::class);

        // tableFromFqcn
        assert(Core\Row\Session::tableFromFqcn() instanceof Core\Table);

        // row
        assert(Core\Row\Session::row(3213) === null);

        // rows
        assert(Core\Row\Session::rows() instanceof Core\Rows);

        // rowsVisible
        assert(Core\Row\Session::rowsVisible() instanceof Core\Rows);

        // rowsVisibleOrder
        assert(Core\Row\Session::rowsVisibleOrder() instanceof Core\Rows);

        // select
        assert(Core\Row\Session::select(1112) === null);

        // selects
        assert(Core\Row\Session::selects(2122)->isEmpty());

        // grab
        assert(Core\Row\Session::grab(['id'=>2312])->isEmpty());

        // grabVisible
        assert(Core\Row\Session::grabVisible(['id'=>1233])->isEmpty());

        // insert

        // _route

        // access
        $row3 = $tb->insert(['date'=>time(),'name_en'=>'sure']);
        assert($row3::session() instanceof Core\Session);
        assert($row3::sessionCom() instanceof Core\Com);
        assert($row3::sessionUser() instanceof Core\Row\User);
        assert($row3::lang() instanceof Core\Lang);
        assert($row3::langText('label') === 'Assert');
        assert($row3::langPlural(2,'label') === 'Asserts');
        assert($row3::services() instanceof Main\Services);
        assert($row3::service('mailer') instanceof Main\Service);
        assert($row3::serviceMailer() instanceof Core\ServiceMailer);

        // bootAccess
        assert($row3::boot() instanceof Core\Boot);
        assert($row3::bootReady() instanceof Core\Boot);

        // orm
        assert(is_array($row->getPermission($admin)));
        assert($user->hasRelationChilds());
        assert(!empty($user->relationChilds()));
        assert(Core\Row\Session::className(true) === 'session');
        assert(Core\Row\Session::className() === 'Session');
        assert($row->getAttr('priority') === 150);
        assert(Core\Row::getOverloadKeyPrepend() === null);
        assert(Core\Row\Session::getOverloadKeyPrepend() === 'Row');

        // cleanup
        assert($db->truncate($table) instanceof \PDOStatement);

        return true;
    }
}
?>