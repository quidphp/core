<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Service;
use Quid\Base;
use Quid\Core;
use Quid\Main;

// phpMailer
// class that provides methods to use phpmailer/phpmailer in order to send emails
class PhpMailer extends Core\ServiceMailerAlias
{
    // config
    protected static array $config = [
        'ping'=>2, // fait un ping avant l'envoie
        'kill'=>null, // permet de tuer le script après un envoie (permet d'afficher le debug)
        'username'=>null, // username pour connexion smtp
        'password'=>null, // password pour connection smtp
        'host'=>null, // host smtp
        'port'=>25, // port de connection smtp
        'encryption'=>false, // type d'encryption pour la connexion smtp
        'timeout'=>5, // durée maximale d'éxécution lors de l'envoie du courriel
        'autoTls'=>true, // active ou non le autoTsl dans phpMailer
        'allowSelfSigned'=>null, // permet le fonctionnement si le certificat ssl est self-signed, si null utilise configuration de base/server
        'debug'=>0, // code de débogage
        'output'=>'html', // output de débogagge, seulement si debug pas vide (pourrait être une callable)
        'charset'=>null, // charset du message
        'contentType'=>null, // contentType du message
        'subject'=>null, // sujet du message
        'body'=>null, // corps du message
        'priority'=>null, // x-priority du message
        'xmailer'=>null, // x-mailer du message
        'bcc'=>null, // copie-conforme invisible
        'cc'=>null,// copie-conforme
        'replyTo'=>null, // addresse replyTo
        'to'=>null, // address To
        'from'=>null, // address from, note name et email ont plus de priorités
        'header'=>null // tableau header additionnels
    ];


    // prepare
    // prépare l'objet et créer l'instance de l'objet mailer
    // une exception peut être envoyé si les options ne sont pas valides
    final protected function prepare():void
    {
        $this->checkReady(false);
        $mailer = new \PHPMailer\PHPMailer\PHPMailer();
        $this->mailer = $mailer;
    }


    // prepareMailer
    // met à jour l'objet du mailer à partir d'un tableau de configuration
    final protected function prepareMailer(array $value):void
    {
        $mailer = $this->mailer();
        $mailer->clearAllRecipients();
        $mailer->clearAttachments();
        $mailer->isMail();

        if(!empty($value['host']) && !empty($value['port']))
        {
            $mailer->isSMTP();
            $mailer->Host = $value['host'];
            $mailer->Port = $value['port'];
            $mailer->SMTPAuth = false;
            $mailer->SMTPSecure = '';
            $mailer->SMTPAutoTLS = false;
            $mailer->SMTPOptions = [];

            if(!empty($value['username']) || !empty($value['password']))
            {
                $mailer->SMTPAuth = true;

                if(!empty($value['username']))
                $mailer->Username = $value['username'];

                if(array_key_exists('password',$value) && !empty($value['password']))
                $mailer->Password = $value['password'];
            }

            if(array_key_exists('encryption',$value))
            $mailer->SMTPSecure = $value['encryption'];

            if(array_key_exists('autoTls',$value))
            $mailer->SMTPAutoTLS = $value['autoTls'];

            if(array_key_exists('allowSelfSigned',$value))
            {
                $value['allowSelfSigned'] ??= Base\Server::allowSelfSignedCertificate();

                if(!empty($value['allowSelfSigned']))
                {
                    $options = ['ssl'=>['verify_peer'=>false,'verify_peer_name'=>false,'allow_self_signed'=>true]];
                    $mailer->SMTPOptions = $options;
                }
            }

            if(array_key_exists('timeout',$value))
            $mailer->Timeout = $value['timeout'];

            if(array_key_exists('debug',$value) || array_key_exists('output',$value))
            $this->setDebug($value['debug'] ?? null,$value['output'] ?? null);
        }
    }


    // prepareMailerMessage
    // prépare le message et met à jour l'objet du mailer à partir d'un tableau
    final protected function prepareMailerMessage(array $value):void
    {
        $mailer = $this->mailer();
        $keyMethods = ['bcc'=>'addBCC','cc'=>'addCC','replyTo'=>'addReplyTo','to'=>'addAddress','from'=>'setFrom'];

        $mailer->CharSet = $value['charset'];
        $mailer->ContentType = $value['contentType'];
        $mailer->Subject = $value['subject'];
        $mailer->Body = $value['body'];

        $mailer->Priority = (!empty($value['priority']) && is_numeric($value['priority']))? $value['priority']:null;
        $mailer->XMailer = (!empty($value['xmailer']) && is_string($value['xmailer']))? $value['xmailer']:'';

        foreach ($keyMethods as $k => $method)
        {
            if(!empty($value[$k]) && is_array($value[$k]))
            {
                if(!empty($value[$k]['email']) && is_string($value[$k]['email']))
                $mailer->$method($value[$k]['email'],$value[$k]['name'] ?? '');

                else
                {
                    foreach ($value[$k] as $address)
                    {
                        if(is_array($address) && !empty($address['email']) && is_string($address['email']))
                        $mailer->$method($address['email'],$address['name'] ?? '');
                    }
                }
            }
        }

        if(!empty($value['header']) && is_array($value['header']))
        {
            foreach ($value['header'] as $k => $v)
            {
                if(is_string($k) && is_scalar($v))
                $mailer->addCustomHeader($k,$v);
            }
        }
    }


    // error
    // retourne la dernière erreur sur l'objet mailer
    final public function error():string
    {
        return $this->mailer()->ErrorInfo;
    }


    // trigger
    // envoie le courriel maintenant
    // retourne un booléean
    final public function trigger($value):bool
    {
        $return = false;
        $mailer = $this->mailer();
        $value = Base\Arr::replace($this->attr(),$value);

        if(!empty($value['host']) && !empty($value['port']) && !empty($value['ping']) && is_int($value['ping']))
        static::checkPing($value['host'],$value['port'],$value['ping']);

        $this->prepareMailer($value);

        try
        {
            $message = $this->prepareMessage($value);
            $this->prepareMailerMessage($message);

            if($this->isActive())
            {
                $return = $mailer->send();
                $this->afterSend($return,$value);
            }

            else
            $return = true;
        }

        catch (\Exception $e)
        {
            Main\Exception::staticCatched($e);
        }

        finally
        {
            $this->log($return,$message);
        }

        return $return;
    }


    // afterSend
    // callback après l'envoie, gère le kill
    final protected function afterSend(bool $return,array $value):void
    {
        $debug = $value['debug'] ?? null;
        $kill = $value['kill'] ?? null;

        if($debug === true && $kill === null)
        $kill = true;

        if($kill === true)
        Base\Response::kill();
    }


    // setDebug
    // change les options de débogagge de l'objet mailer
    // mettre 2 pour output
    final public function setDebug($debug=0,$output=null):self
    {
        $mailer = $this->mailer();

        if($debug === true)
        $debug = 2;

        elseif($debug === false || $debug === null)
        $debug = 0;

        if(is_int($debug))
        {
            $mailer->SMTPDebug = $debug;
            $mailer->Debugoutput = (is_string($output))? $output:'html';
        }

        return $this;
    }
}

// init
PhpMailer::__init();
?>