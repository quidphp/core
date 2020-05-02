<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 * Readme: https://github.com/quidphp/core/blob/master/README.md
 */

namespace Quid\Core\Lang;
use Quid\Orm;

// en
// english language content used by this namespace
class En extends Orm\Lang\En
{
    // config
    protected static array $config = [
        // common
        'common'=>[
            'and'=>'And',
            'on'=>'On',
            'here'=>'here',
            'page'=>'Page',
            'limit'=>'Per page',
            'table'=>'Table',
            'row'=>'Row',
            'col'=>'Col',
            'add'=>'Add',
            'submit'=>'Submit',
            'modify'=>'Modify',
            'remove'=>'Remove',
            'confirm'=>'Confirm ?',
            'unload'=>'Are you sure you want to leave this page?',
            'first'=>'First',
            'prev'=>'Previous',
            'next'=>'Next',
            'last'=>'Last',
            'back'=>'Back',
            'nothing'=>'Nothing',
            'notFound'=>'Not Found',
            'search'=>'Search',
            'reset'=>'Reset',
            'filter'=>'Filter',
            'download'=>'Download',
            'backToTop'=>'Back to top',
            'print'=>'Print',
            'readMore'=>'Read more',
            'filtered'=>'Filtered',
            'total'=>'Total',
            'view'=>'View',
            'export'=>'Export',
            'insert'=>'Insert',
            'copyright'=>'All rights reserved. &copy; [name] [year]',
            'ago'=>'[value] ago',
            'loadMore'=>'Load more...',
            'captcha'=>'Enter the characters in the image',
            'accessDenied'=>'Access Denied',
            'isEmpty'=>'Is empty',
            'isNotEmpty'=>'Is not empty'
        ],

        // error
        'error'=>[

            // label
            'label'=>[
                35=>'Route exception',
                36=>'Route break exception'
            ],

            // page
            'page'=>[

                // content
                'content'=>[
                    400=>'The request is invalid and cannot be processed.',
                    403=>'The request is invalid because access is forbidden.',
                    404=>'This URL does not point to any valid content.',
                    500=>'The request cannot be processed due to a server error.'
                ],

                'back'=>'Click [link] to go back.'
            ]
        ],

        // langSwitch
        'langSwitch'=>[
            'fr'=>'Français',
            'en'=>'English'
        ],

        // changePassword
        'changePassword'=>[
            'newPassword'=>'New password',
            'newPasswordConfirm'=>'New password confirmation'
        ],

        // exception
        'exception'=>[

            // catchableException
            'catchableException'=>[
                'notUpdateable'=>'Row cannot be updated.',
                'notUpdateableTimestamp'=>'Row cannot be updated because data on the server is more recent.',
                'notDeleteable'=>'Row cannot be deleted.',
                'notDeleteableTimestamp'=>'Row cannot be deleted because data on the server is more recent.',
                'hostUnreachable'=>'Connexion error: host [1] is not reachable on port [2].',
                'responseCodeShouldBe'=>'Request error: response code should be [1] not [3].',
                'invalidResponseFormat'=>'Response error: invalid format',
                'userRoleNobody'=>'It is not possible to change the role to nobody.',
                'userRoleSelf'=>'It is not possible to change the role of the current user.',
                'userRoleUpperEqual'=>'It is not possible to assign a role equal or bigger than the role of the current user.',
                'userRoleUpper'=>'It is not possible to assign a role bigger than the role of the current user.',
                'userActiveSelf'=>'It is not possible to change the active status of the current user.',
                'changePasswordInvalidPassword'=>'Invalid password. [1]',
                'changePasswordInvalidOldPassword'=>'Invalid old password. [1]',
                'changePasswordNewPasswordMismatch'=>'The new password confirmation is incorrect',
                'changePasswordOldPasswordMismatch'=>'The old password is incorrect',
                'changePasswordNoChange'=>'The new password is the same as the old one',
                'changePasswordInvalidValues'=>'Invalid values',
                'invalidFileIndex'=>'Impossible to upload a file in index #[1]',
                'pathNotWritable'=>'Path [1] is not writable']
        ],

        // label
        'label'=>'QuidPHP',

        // table
        'table'=>[

            // label
            'label'=>[
                'email'=>'Email',
                'lang'=>'Language content',
                'redirection'=>'Redirection',
                'log'=>'Log - Event',
                'logCron'=>'Log - Cron',
                'logEmail'=>'Log - Email',
                'logError'=>'Log - Error',
                'logSql'=>'Log - Sql',
                'logHttp'=>'Log - Http',
                'queueEmail'=>'Queue - Email',
                'session'=>'Session',
                'user'=>'User',
                'system'=>'System']
        ],

        // col
        'col'=>[

            // label
            'label'=>[

                // *
                '*'=>[
                    'active'=>'Active',
                    'code'=>'Code',
                    'order'=>'Order',
                    'type'=>'Type',
                    'status'=>'Status',
                    'role'=>'Role',
                    'envType'=>'Env and type',
                    'context'=>'Context',
                    'route'=>'Route',
                    'data'=>'Data',
                    'count'=>'Count',
                    'email'=>'Email',
                    'ip'=>'IP address',
                    'website'=>'Website',
                    'media'=>'Media',
                    'json'=>'Json',
                    'storage'=>'File',
                    'medias'=>'Medias',
                    'storages'=>'Files',
                    'key'=>'Key',
                    'name'=>'Name',
                    'content'=>'Content',
                    'username'=>'Username',
                    'userAdd'=>'Added by',
                    'userModify'=>'Modified by',
                    'password'=>'Password',
                    'passwordReset'=>'Password - Reset',
                    'datetime'=>'Date and time',
                    'datetimeStart'=>'Start date and time',
                    'datetimeEnd'=>'End date and time',
                    'date'=>'Date',
                    'dateStart'=>'Start date',
                    'dateEnd'=>'End date',
                    'dateAdd'=>'Date added',
                    'dateModify'=>'Last modification',
                    'dateLogin'=>'Last login',
                    'pointer'=>'Pointer',
                    'value'=>'Value',
                    'request'=>'Request',
                    'error'=>'Error',
                    'userCommit'=>'Session user',
                    'timezone'=>'Timezone',
                    'contentType'=>'Content type'
                ]
            ]
        ],

        // role
        'role'=>[

            // label
            'label'=>[
                1=>'Nobody',
                10=>'Shared',
                20=>'User',
                80=>'Admin',
                90=>'Cli'
            ]
        ],

        // route
        'route'=>[

            // label
            'label'=>[
                'cliCompile'=>'Compile assets',
                'cliPreload'=>'Build PHP Preload',
                'cliSessionGc'=>'Session garbage collect',
                'cliVersion'=>'Version',
                'error'=>'Error',
                'home'=>'Home',
                'robots'=>'Robots',
                'sitemap'=>'Sitemap'
            ],

            // description
            'description'=>[]
        ],

        // validate
        'validate'=>[
            'pointer'=>'Must be a valid pointer (table/id)',
            'inRelation'=>'Must be in the relation'
        ],

        // com
        'com'=>[

            // neg
            'neg'=>[

                // captcha
                'captcha'=>'The characters entered do not match the ones in the image.',

                // csrf
                'csrf'=>[
                    'retry'=>'Please try this operation again.',
                ],

                // genuine
                'genuine'=>[
                    'retry'=>'Please try this operation again.',
                ],

                // fileUpload
                'fileUpload'=>[
                    'maxFilesize'=>'The size of the uploaded file must not be larger than [maxFilesize]',
                    'dataLost'=>'The data from the last submitted form has not been saved.'
                ],

                // timeout
                'timeout'=>[
                    'retry'=>'Please retry this operation later.',
                    'login'=>'Session is locked: too many failed login attemps',
                ],

                // login
                'login'=>[
                    'alreadyConnected'=>'Already logged in',
                    'invalidUsername'=>'Invalid username',
                    'invalidPassword'=>'Invalid password',
                    'userCantLogin'=>'This user cannot login',
                    'userInactive'=>'This user is inactive',
                    'wrongPassword'=>"The password doesn't match",
                    'cantFindUser'=>'This user does not exist',
                    'invalidValues'=>'Invalid connection values'
                ],

                // userSession
                'userSession'=>[
                    'userInactive'=>'This user is now inactive.',
                    'loginLifetime'=>'Please reconnect. The session has expired.',
                    'mostRecentStorage'=>'Please reconnect. A newer session has been detected for this user.'
                ],

                // logout
                'logout'=>[
                    'cannotBeLogin'=>'This user cannot be logged in.',
                    'notConnected'=>'Not logged in'
                ],

                // resetPassword
                'resetPassword'=>[
                    'alreadyConnected'=>'Cannot reset a password from a connected user account',
                    'invalidEmail'=>'Invalid email',
                    'userInactive'=>'This user is inactive',
                    'userCantLogin'=>'This user cannot login',
                    'error'=>'Error while doing a password reset',
                    'userNotFound'=>'This user does not exist',
                    'email'=>'Error while sending the email',
                    'invalidValue'=>'Invalid value'
                ],

                // activatePassword
                'activatePassword'=>[
                    'alreadyConnected'=>'Cannot activate a password from a connected user account',
                    'userInactive'=>'This user is inactive',
                    'userCantLogin'=>'This user cannot login',
                    'invalidHash'=>'Cannot activate this password',
                    'error'=>'Error while activating the password',
                    'userNotFound'=>'This user does not exist',
                    'invalidValue'=>'Invalid values'
                ],

                // register
                'register'=>[
                    'alreadyConnected'=>'Cannot register from a connected user account',
                    'passwordConfirm'=>'The password confirmation is incorrect',
                    'invalidValues'=>'Invalid values'
                ]
            ],

            // pos
            'pos'=>[

                // login
                'login'=>[
                    'success'=>'Logged in'
                ],

                // logout
                'logout'=>[
                    'success'=>'Logged out'
                ],

                // changePassword
                'changePassword'=>[
                    'success'=>'Password changed'
                ],

                // resetPassword
                'resetPassword'=>[
                    'success'=>'The password has been reset and sent to your email'
                ],

                // activatePassword
                'activatePassword'=>[
                    'success'=>'The password has been activated'
                ],

                // media
                'media'=>[
                    'delete'=>'[count] file%s% deleted',
                    'regenerate'=>'[count] file%s% regenerated'
                ]
            ]
        ],

        // relation
        'relation'=>[

            // yes
            'yes'=>[
                1=>'Yes'
            ],

            // contextType
            'contextType'=>[],

            // contextEnv
            'contextEnv'=>[
                'dev'=>'Development',
                'staging'=>'Staging',
                'prod'=>'Production'
            ],

            // emailType
            'emailType'=>[
                1=>'Text',
                2=>'Html',
            ],

            // logType
            'logType'=>[
                1=>'Login',
                2=>'Logout',
                3=>'Password reset',
                4=>'Activate password',
                5=>'Change password',
                6=>'Register',
                7=>'Form'
            ],

            // queueEmailStatus
            'queueEmailStatus'=>[
                1=>'To send',
                2=>'In progress',
                3=>'Error',
                4=>'Sent'
            ],

            // logHttpType
            'logHttpType'=>[
                1=>'Unsafe request',
                2=>'Invalid request',
                3=>'External POST',
                200=>'200 - OK',
                301=>'301 - Moved Permanently',
                302=>'302 - Found',
                400=>'400 - Bad Request',
                403=>'403 - Forbidden',
                404=>'404 - Not Found',
                500=>'500 - Internal Server Error'
            ],

            // logSqlType
            'logSqlType'=>[
                1=>'Select',
                2=>'Show',
                3=>'Insert',
                4=>'Update',
                5=>'Delete',
                6=>'Create',
                7=>'Alter',
                8=>'Truncate',
                9=>'Drop'
            ]
        ]
    ];
}

// init
En::__init();
?>