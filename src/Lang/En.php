<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Lang;
use Quid\Base;

// en
// english language content used by this namespace
class En extends Base\Lang\En
{
    // trait
    use _overload;


    // config
    public static $config = [
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
            'accessDenied'=>'Access Denied'
        ],

        // error
        'error'=>[

            // label
            'label'=>[
                1=>'Error',
                2=>'Notice',
                3=>'Deprecated',
                11=>'Assertion',
                21=>'Silent',
                22=>'Warning',
                23=>'Fatal',
                31=>'Exception',
                32=>'Catchable exception',
                33=>'Database exception',
                34=>'Catchable database exception',
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

        // lang
        'lang'=>[
            'fr'=>'French',
            'en'=>'English'
        ],

        // langSwitch
        'langSwitch'=>[
            'fr'=>'Français',
            'en'=>'English'
        ],

        // relationOrder
        'relationOrder'=>[
            'key'=>[
                1=>'Oldest first',
                2=>'Newest first',
            ],

            'value'=>[
                3=>'Alphabetical order',
                4=>'Inverted alphabetical order'
            ]
        ],

        // login
        'login'=>[
            'usernameEmail'=>'Username or email',
            'remember'=>'Remember me ?',
        ],

        // browscap
        'browscap'=>[
            'noscript'=>'Cannot login because JavaScript is disabled on your browser.',
            'cookie'=>'Cannot login because your browser does not accept cookies.'
        ],

        // resetPassword
        'resetPassword'=>[
            'forgot'=>'Forgot your password ?',
            'submit'=>'Submit'
        ],

        // register
        'register'=>[
            'confirmPassword'=>'Password confirmation'
        ],

        // changePassword
        'changePassword'=>[
            'newPassword'=>'New password',
            'newPasswordConfirm'=>'New password confirmation'
        ],

        // accountChangePassword
        'accountChangePassword'=>[
            'oldPassword'=>'Current password',
            'newPassword'=>'New password',
            'newPasswordConfirm'=>'New password confirmation',
            'submit'=>'Change my password'
        ],

        // exception
        'exception'=>[

            // catchableException
            'catchableException'=>[
                'notDeleteable'=>'Row cannot be deleted.',
                'notUpdatable'=>'Row cannot be updated.',
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

        // direction
        'direction'=>[
            'asc'=>'Asc',
            'desc'=>'Desc',
        ],

        // label
        'label'=>'Quid',

        // db
        'db'=>[

            // label
            'label'=>[],

            // description
            'description'=>[]
        ],

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
                'admin'=>'Admin',
                'system'=>'System'],

            // description
            'description'=>[]
        ],

        // col
        'col'=>[

            // label
            'label'=>[

                // *
                '*'=>[
                    'id'=>'Id',
                    'active'=>'Active',
                    'featured'=>'Featured',
                    'code'=>'Code',
                    'category'=>'Category',
                    'order'=>'Order',
                    'lang'=>'Language',
                    'type'=>'Type',
                    'status'=>'Status',
                    'index'=>'index',
                    'role'=>'Role',
                    'context'=>'Context',
                    'subject'=>'Subject',
                    'message'=>'Message',
                    'route'=>'Route',
                    'method'=>'Method',
                    'option'=>'Option',
                    'menu'=>'In menu',
                    'priority'=>'Priority',
                    'data'=>'Data',
                    'body'=>'Body',
                    'header'=>'Header',
                    'phone'=>'Phone number',
                    'company'=>'Company',
                    'amount'=>'Amount',
                    'count'=>'Count',
                    'year'=>'Year',
                    'month'=>'Month',
                    'day'=>'Day',
                    'email'=>'Email',
                    'ip'=>'IP address',
                    'url'=>'Url',
                    'uri'=>'Uri',
                    'website'=>'Website',
                    'media'=>'Media',
                    'media_fr'=>'French media',
                    'media_en'=>'English media',
                    'medias'=>'Medias',
                    'video'=>'Video',
                    'json'=>'Json',
                    'thumbnail'=>'Thumbnail',
                    'icon'=>'Icon',
                    'icons'=>'Icons',
                    'storage'=>'File',
                    'storage_fr'=>'French file',
                    'storage_en'=>'English file',
                    'storages'=>'Files',
                    'background'=>'Background',
                    'key'=>'Key',
                    'name'=>'Name',
                    'title'=>'Title',
                    'content'=>'Content',
                    'firstName'=>'First Name',
                    'lastName'=>'Last Name',
                    'fullName'=>'Full name',
                    'username'=>'Username',
                    'userAdd'=>'Added by',
                    'userModify'=>'Modified by',
                    'password'=>'Password',
                    'passwordReset'=>'Password - Reset',
                    'country'=>'Country',
                    'state'=>'State',
                    'province'=>'Province',
                    'city'=>'City',
                    'zipCode'=>'Zip code',
                    'postalCode'=>'Postal code',
                    'key_fr'=>'French key',
                    'key_en'=>'English key',
                    'slug'=>'Slug',
                    'slug_fr'=>'French slug',
                    'slug_en'=>'English slug',
                    'slugPath'=>'Slug path',
                    'slugPath_fr'=>'French slug path',
                    'slugPath_en'=>'English slug path',
                    'fragment'=>'Fragment',
                    'fragment_fr'=>'French fragment',
                    'fragment_en'=>'English fragment',
                    'name_fr'=>'French name',
                    'name_en'=>'English name',
                    'title_fr'=>'French title',
                    'title_en'=>'English title',
                    'content_fr'=>'French content',
                    'content_en'=>'English content',
                    'uri_fr'=>'French Uri',
                    'uri_en'=>'English Uri',
                    'metaTitle_fr'=>'French meta title',
                    'metaTitle_en'=>'English meta title',
                    'metaDescription_fr'=>'French meta description',
                    'metaDescription_en'=>'English meta description',
                    'metaKeywords_fr'=>'French meta keywords',
                    'metaKeywords_en'=>'English meta keywords',
                    'metaImage_fr'=>'French meta image',
                    'metaImage_en'=>'English meta image',
                    'metaSearch_fr'=>'French meta search',
                    'metaSearch_en'=>'English meta search',
                    'media_fr'=>'French media',
                    'media_en'=>'English media',
                    'video_fr'=>'French video',
                    'video_en'=>'English video',
                    'json_fr'=>'French json',
                    'json_en'=>'English json',
                    'timestamp'=>'Timestamp',
                    'datetime'=>'Date and time',
                    'datetimeStart'=>'Start date and time',
                    'datetimeEnd'=>'End date and time',
                    'date'=>'Date',
                    'dateStart'=>'Start date',
                    'dateEnd'=>'End date',
                    'time'=>'Time',
                    'timeStart'=>'Start time',
                    'timeEnd'=>'End time',
                    'dateAdd'=>'Date added',
                    'dateModify'=>'Last modification',
                    'dateExpire'=>'Expiration date',
                    'dateLogin'=>'Last login',
                    'dateBirth'=>'Birth date',
                    'dateSent'=>'Sent date',
                    'pointer'=>'Pointer',
                    'value'=>'Value',
                    'excerpt_fr'=>'French excerpt',
                    'excerpt_en'=>'English excerpt',
                    'info_fr'=>'French info',
                    'info_en'=>'English info',
                    'role_fr'=>'French role',
                    'role_en'=>'English role',
                    'fax'=>'Fax',
                    'address'=>'Address',
                    'request'=>'Request',
                    'error'=>'Error',
                    'host'=>'Host',
                    'userCommit'=>'Session user',
                    'color'=>'Color code',
                    'attr'=>'Attribute',
                    'visible'=>'Visible',
                    'author'=>'Author',
                    'price'=>'Price',
                    'total'=>'Total',
                    'timezone'=>'Timezone'
                ],

                // page
                'page'=>[
                    'page_id'=>'Parent page'
                ]
            ],

            // description
            'description'=>[]
        ],

        // row
        'row'=>[

            // label
            'label'=>[
                '*'=>'[table] #[primary]'
            ],

            // description
            'description'=>[]
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
            ],

            // description
            'description'=>[]
        ],

        // route
        'route'=>[

            // label
            'label'=>[
                'account'=>'My account',
                'accountSubmit'=>'My account - Submit',
                'accountChangePassword'=>'Change my password',
                'accountChangePasswordSubmit'=>'Change my password - Submit',
                'activatePassword'=>'Activate password',
                'error'=>'Error',
                'home'=>'Home',
                'login'=>'Login',
                'loginSubmit'=>'Login - Submit',
                'logout'=>'Logout',
                'register'=>'Register',
                'registerSubmit'=>'Register - Submit',
                'resetPassword'=>'Reset password',
                'resetPasswordSubmit'=>'Reset password - Submit',
                'robots'=>'Robots',
                'sitemap'=>'Sitemap'
            ],

            // description
            'description'=>[]
        ],

        // validate
        'validate'=>[
            'pointer'=>'Must be a valid pointer (table/id)',
            'inRelation'=>'Must be in the relation',

            // tables
            'tables'=>[]
        ],

        // required
        'required'=>[
            'tables'=>[]
        ],

        // unique
        'unique'=>[
            'tables'=>[]
        ],

        // editable
        'editable'=>[
            'tables'=>[]
        ],

        // compare
        'compare'=>[
            'tables'=>[]
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
                ],

                // user
                'user'=>[

                    // welcome
                    'welcome'=>[
                        'failure'=>'The welcome email was not sent.'
                    ]
                ],

                // insert
                'insert'=>[
                    '*'=>[
                        'exception'=>'[message]',
                        'failure'=>'Add failed'
                    ]
                ],

                // update
                'update'=>[
                    '*'=>[
                        'tooMany'=>'Error: many rows updated',
                        'exception'=>'[message]',
                        'system'=>'Error system'
                    ]
                ],

                // delete
                'delete'=>[
                    '*'=>[
                        'notFound'=>'Error: no rows deleted',
                        'tooMany'=>'Error: many rows deleted',
                        'exception'=>'[message]',
                        'system'=>'Error system'
                    ]
                ],

                // truncate
                'truncate'=>[
                    '*'=>[
                        'exception'=>'[message]',
                        'system'=>'Error system'
                    ]
                ],

                // duplicate
                'duplicate'=>[
                    'failure'=>'Duplicate has failed'
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
                ],

                // slug
                'slug'=>[
                    'updated'=>'[count] other%s% line%s% updated'
                ],

                // user
                'user'=>[

                    // welcome
                    'welcome'=>[
                        'success'=>'The welcome email was sent.'
                    ],
                ],

                // insert
                'insert'=>[
                    '*'=>[
                        'success'=>'Add success'
                    ]
                ],

                // update
                'update'=>[
                    '*'=>[
                        'success'=>'Modify success',
                        'partial'=>'Modify partial success',
                        'noChange'=>'No change'
                    ]
                ],

                // delete
                'delete'=>[
                    '*'=>[
                        'success'=>'Delete success'
                    ]
                ],

                // truncate
                'truncate'=>[
                    '*'=>[
                        'success'=>'Table has been truncated'
                    ]
                ],

                // duplicate
                'duplicate'=>[
                    'success'=>'Duplicate success'
                ]
            ]
        ],

        // relation
        'relation'=>[

            // bool
            'bool'=>[
                0=>'No',
                1=>'Yes'
            ],

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
                6=>'Register'
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