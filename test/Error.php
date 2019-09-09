<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Test\Core;
use Quid\Core;
use Quid\Main;
use Quid\Base;

// error
// class for testing Quid\Core\Error
class Error extends Base\Test
{
    // trigger
    public static function trigger(array $data):bool
    {
        // prepare
        $logError = Core\Row\LogError::class;
        $fatal = new Core\Error(['fatalz',__FILE__,__LINE__],23);
        $warning = new Core\Error(['warningz',__FILE__,__LINE__],22);
        $exception = new Main\Exception('numero1');
        $arg = new Core\Error(new Main\Exception('message!',null,null,'caRoule',['string'],$exception,'james'));

        // init

        // exception
        assert("Quid\Main\Error::__construct" === $fatal->getTraceLastCall());
        assert('Warning (#22)' === $warning->title());
        $warning->setOption('lang','fr');
        assert($warning->title() === 'Avertissement (#22)');
        $warning->setOption('lang','en');
        assert($warning::getCom() instanceof Core\Com);
        assert($arg->_cast() === 'What !!! [1] numero1 james [4]');
        assert($arg->getMessage() === 'What !!! [1] numero1 james [4]');
        assert($arg->titleMessage() === "Exception: Quid\Main\Exception (#31) -> What !!! [1] numero1 james [4]");

        // logError
        $row = $logError::new($fatal);
        assert($row instanceof Core\Row);
        assert(!empty($row['context']->get()));
        assert($row['request']->get() instanceof Core\Request);
        assert($row['error']->get() instanceof Core\Error);
        assert($row['error']->get() !== $fatal);
        assert(is_int($row['session_id']->get()));
        assert(is_int($row['userCommit']->get()));
        assert($row['userAdd']->relationRow() instanceof Core\Row\User);
        assert(is_string($row['dateAdd']->get()));
        assert($row['userModify']->get() === null);
        assert($row['dateModify']->get() === null);

        return true;
    }
}
?>