<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    |
    | This option controls the default authentication "guard" and password
    | reset options for your application. You may change these defaults
    | as required, but they're a perfect start for most applications.
    |该选项控制应用程序的默认身份验证“防护”和密码重置选项。
    |您可以根据需要更改这些缺省值，但对于大多数应用程序来说，
    |它们是一个完美的开始。
    */

    'defaults' => [
        'guard' => 'web',
        'passwords' => 'users',
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    |
    | Next, you may define every authentication guard for your application.
    | Of course, a great default configuration has been defined for you
    | here which uses session storage and the Eloquent user provider.
    |接下来，可以为应用程序定义每个身份验证guard。
    |当然，这里已经为您定义了一个很好的默认配置，
    |它使用session存储和Eloquent用户provider。
    |
    | All authentication drivers have a user provider. This defines how the
    | users are actually retrieved out of your database or other storage
    | mechanisms used by this application to persist your user's data.
    |所有身份验证驱动程序都有用户提供程序。这定义了如何从数据库或此应
    |用程序用于持久化用户数据的其他存储机制中实际检索用户。
    |
    |
    | Supported: "session", "token"
    |
    */

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],

        'api' => [
            'driver' => 'passport',
            'provider' => 'users',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    |
    | All authentication drivers have a user provider. This defines how the
    | users are actually retrieved out of your database or other storage
    | mechanisms used by this application to persist your user's data.
    |所有身份验证驱动程序都有用户provider。这定义了如何从数据库或此应
    |用程序用于持久化用户数据的其他存储机制中实际检索用户。
    |
    | If you have multiple user tables or models you may configure multiple
    | sources which represent each model / table. These sources may then
    | be assigned to any extra authentication guards you have defined.
    |如果有多个用户表或模型，可以配置代表每个模型/表的多个源。然后，
    |这些源可以被分配给您定义的任何额外的身份验证guard
    |
    | Supported: "database", "eloquent"
    |
    |
    */

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\User::class,
        ],

        // 'users' => [
        //     'driver' => 'database',
        //     'table' => 'users',
        // ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Resetting Passwords
    |--------------------------------------------------------------------------
    |
    | You may specify multiple password reset configurations if you have more
    | than one user table or model in the application and you want to have
    | separate password reset settings based on the specific user types.
    |如果在应用程序中有多个用户表或模型，并且希望根据特定用户类型设置单
    |独的密码重置设置，则可以指定多个密码重置配置。
    |
    | The expire time is the number of minutes that the reset token should be
    | considered valid. This security feature keeps tokens short-lived so
    | they have less time to be guessed. You may change this as needed.
    |expire时间是重置令牌应被认为有效的分钟数。这个安全特性使令牌持续寿
    |命短，因此它们被破解的时间更少。你可以根据需要改变这一点。
    */

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_resets',
            'expire' => 60,
        ],
    ],

];
