<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Col;
use Quid\Base;
use Quid\Core;
use Quid\Main;
use Quid\Orm;

// medias
// class to work with a column containing a value which is a link to many files
class Medias extends FilesAlias
{
    // config
    public static $config = [
        'search'=>false,
        'preValidate'=>'fileUploads',
        'onGet'=>[Base\Json::class,'onGet'],
        'cell'=>Core\Cell\Medias::class,
        'media'=>2, // custom
        'validateKeys'=>['extension'=>'extensions','maxFilesize'=>'maxFilesizes']
    ];


    // hasIndex
    // retourne vrai comme medias supporte multiple indexes
    final public function hasIndex():bool
    {
        return true;
    }


    // preValidatePrepare
    // prépare le tableau de chargement avant la prévalidation
    final public function preValidatePrepare($values)
    {
        $return = null;

        if(Base\Arrs::is($values))
        {
            $return = [];

            foreach ($values as $key => $value)
            {
                if(is_array($value))
                {
                    if(Base\File::isUploadNotEmpty($value) || Base\File::isUploadTooBig($value))
                    $return[$key] = $value;
                }

                elseif(is_string($value) && !empty($value))
                {
                    $value = Base\Json::decode($value);

                    if(is_array($value) && Base\Arr::keysExists(['action','path'],$value))
                    {
                        if(in_array($value['action'],['delete','regenerate'],true))
                        {
                            $path = Base\Path::append($this->rootPath(),$value['path']);
                            $return[$key] = Base\File::makeUploadArray($path,9,false);
                            $return[$key]['action'] = $value['action'];
                        }
                    }
                }
            }
        }

        return $return;
    }


    // onSet
    // logique onSet pour un champ médias
    // process ne sera lancé que si l'opération sur la ligne (insertion/mise à jour) a réussi
    final protected function onSet($value,array $row,?Orm\Cell $cell=null,array $option)
    {
        $return = parent::onSet($value,$row,$cell,$option);
        $indexes = null;

        if(!empty($cell))
        {
            $indexes = $cell->indexes();
            $return = $cell->value();
        }

        if(is_array($value) && $this->allowFileUpload())
        $value = $this->onSetFileUpload($value,$indexes);

        if($value instanceof Main\Files)
        {
            $this->checkFilesIndex($value);
            $news = Main\Files::newOverload();
            $olds = Main\Files::newOverload();
            $regenerate = [];

            if(!empty($cell))
            {
                $return = Main\Files::newOverload();

                foreach($this->indexRange() as $i)
                {
                    $current = null;

                    if($indexes->exists($i))
                    $current = $indexes->get($i);

                    if($value->exists($i))
                    {
                        $new = $value->get($i);
                        $action = $new->getAttr('uploadAction');

                        if(empty($action) || $action === 'delete')
                        {
                            if(empty($action))
                            {
                                $news->set($i,$new);
                                $return->set($i,$new);
                            }

                            else
                            $cell->checkCanBeDeleted($i);

                            if(!empty($current))
                            {
                                $version = $cell->version($i);
                                if(!empty($version))
                                $olds->add($version);
                                $olds->set(null,$current);
                            }
                        }

                        elseif($action === 'regenerate')
                        {
                            $cell->checkCanBeRegenerated($i);
                            $regenerate[] = $i;
                            $return->set($i,$new);
                        }
                    }

                    elseif(!empty($current))
                    $return->set($i,$current);
                }

                $return->sort();
                $news->sort();
            }

            else
            $return = $news = $value;

            $array = [];
            foreach ($return as $key => $file)
            {
                $basename = $file->mimeBasename($file->getAttr('uploadBasename'));
                $array[$key] = Base\Path::safeBasename($basename);
            }

            $return = (!empty($array))? Base\Json::encode($array):null;

            $this->setCommittedCallback('getNewFiles',function() use($news) {
                return $news;
            });

            $this->setCommittedCallback('onCommitted',function(Core\Cell $cell) use($olds,$news,$regenerate,$option) {
                $cell->process($olds,$news,$regenerate,$option);
            },$cell);
        }

        return $return;
    }


    // onSetFileUpload
    // gère le onSet si c'est upload fichier (array dans $_FILES)
    final protected function onSetFileUpload(array $array,?Main\Files $indexes):?Main\Files
    {
        $return = null;
        $value = null;

        if(Base\Column::is($array) && Base\File::isUploadArray(...array_values($array)))
        {
            $return = Main\Files::newOverload();

            foreach ($array as $k => $v)
            {
                $error = $v['error'];

                if(Base\File::isUploadNotEmpty($v))
                {
                    $return->set($k,$v);
                    $file = $return->get($k);

                    if(!empty($file))
                    {
                        $name = Base\File::uploadBasename($v);
                        $file->setAttr('uploadBasename',$name);
                        $file->setAttr('uploadDeleteSource',true);

                        if($error === 9 && !empty($v['action']))
                        $file->setAttr('uploadAction',$v['action']);
                    }
                }

                elseif($error === 9 && !empty($indexes))
                $indexes->remove($k);
            }
        }

        return $return;
    }
}

// init
Medias::__init();
?>