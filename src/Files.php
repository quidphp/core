<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core;
use Quid\Main;

// files
// extended class for a collection containing many file objects
class Files extends Main\Files
{
    // trait
    use _fullAccess;


    // config
    public static $config = [];


    // zip
    // permet d'archiver tous les fichiers dans un zip
    // peut envoyer des exceptions
    // retourne la resource zip
    public function zip($value,?string $local=null,?array $option=null):File\Zip
    {
        $return = File\Zip::new($value,['create'=>true]);

        if(empty($return))
        static::throw('cannotCreateZipArchive');

        if(!$return->addFiles($this,$option))
        static::throw('couldNotAddFilesToZipArchive');

        $return->commit();

        return $return;
    }
}
?>