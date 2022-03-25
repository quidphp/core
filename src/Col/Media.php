<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Col;
use Quid\Base;
use Quid\Core;
use Quid\Main;
use Quid\Orm;

// media
// class to work with a column containing a value which is a link to a file
class Media extends FilesAlias
{
    // config
    protected static array $config = [
        'search'=>true,
        'preValidate'=>'fileUpload',
        'cell'=>Core\Cell\Media::class,
        'validateKeys'=>['extension'=>'extension','maxFilesize'=>'maxFilesize'] // custom
    ];


    // preValidatePrepare
    // prépare le tableau de chargement avant la prévalidation
    final public function preValidatePrepare($value)
    {
        $return = null;

        if(Base\File::isUploadNotEmpty($value))
        $return = $value;

        elseif(is_string($value) && !empty($value))
        {
            $value = Base\Json::decode($value);

            if(is_array($value) && Base\Arr::keysExists(['action','path'],$value))
            {
                if(in_array($value['action'],['delete','regenerate'],true))
                {
                    $path = Base\Path::append($this->rootPath(),$value['path']);
                    $return = Base\File::makeUploadArray($path,9,false);
                    $return['action'] = $value['action'];
                }
            }
        }

        return $return;
    }


    // onSet
    // logique onSet pour un champ média
    // process ne sera lancé que si l'opération sur la ligne (insertion/mise à jour) a réussi
    final protected function onSet($value,?Orm\Cell $cell,array $row,array $option)
    {
        $return = null;

        if(!empty($cell))
        $return = $cell->value();

        if(is_array($value) && $this->allowFileUpload())
        {
            $deleteSource = $option['uploadDeleteSource'] ?? true;
            $array = $this->onSetFileUpload($value,$deleteSource);
            $return = $array['return'];
            $value = $array['value'];
        }

        if($value instanceof Main\File)
        $return = $this->setFromFile($value,$cell,$option);

        return $return;
    }


    // setFromFile
    // permet de set à partir d'un objet fichier
    final public function setFromFile(Main\File $value,?Orm\Cell $cell,array $option):?string
    {
        $old = Main\Files::newOverload();
        $regenerate = false;
        $basename = $value->mimeBasename($value->getAttr('uploadBasename'));
        $return = Base\Path::safeBasename($basename);

        if(!empty($cell))
        {
            $old = $cell->all();
            $action = $value->getAttr('uploadAction');

            if(!empty($action))
            {
                if($action === 'delete')
                {
                    $cell->checkCanBeDeleted();
                    $value = null;
                    $return = null;
                }

                elseif($action === 'regenerate')
                {
                    $cell->checkCanBeRegenerated();
                    $old = Main\Files::newOverload();
                    $value = null;
                    $regenerate = true;
                    $return = $cell->value();
                }
            }
        }

        $this->setCommittedCallback('getNewFiles',fn() => Main\Files::newOverload($value));

        $closure = fn(Core\Cell $cell) => $cell->process($old,$value,$regenerate,$option);
        $this->setCommittedCallback('onCommitted',$closure,$cell);

        return $return;
    }


    // onSetFileUpload
    // gère le onSet si c'est upload fichier (array dans $_FILES)
    final protected function onSetFileUpload(array $array,bool $deleteSource):array
    {
        $return = [];
        $r = null;
        $value = null;
        $error = $array['error'] ?? null;

        if(Base\File::isUploadNotEmpty($array))
        {
            $action = $array['action'] ?? null;
            $name = Base\File::uploadBasename($array);
            $value = Main\File::new($array);
            $value->setAttr('uploadBasename',$name);
            $value->setAttr('uploadDeleteSource',$deleteSource);

            if($error === 9 && !empty($action))
            $value->setAttr('uploadAction',$action);
        }

        elseif($error === 9)
        $r = null;

        $return['return'] = $r;
        $return['value'] = $value;

        return $return;
    }
}

// init
Media::__init();
?>