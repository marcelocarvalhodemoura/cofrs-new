<?php

use Monolog\Handler\NullHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\SyslogUdpHandler;

return [

    /*
    |--------------------------------------------------------------------------
    | Default Log Channel
    |--------------------------------------------------------------------------
    |
    | This option defines the default log channel that gets used when writing
    | messages to the logs. The name specified in this option should match
    | one of the channels defined in the "channels" configuration array.
    |
    */

    'default' => env('LOG_CHANNEL', 'stack'),

    /*
    |--------------------------------------------------------------------------
    | Log Channels
    |--------------------------------------------------------------------------
    |
    | Here you may configure the log channels for your application. Out of
    | the box, Laravel uses the Monolog PHP logging library. This gives
    | you a variety of powerful log handlers / formatters to utilize.
    |
    | Available Drivers: "single", "daily", "slack", "syslog",
    |                    "errorlog", "monolog",
    |                    "custom", "stack"
    |

    RFC 5424 The Syslog Protocol Março 2009
    Severidade numérica

    0 emergency: sistema é inutilizável
    1 alert: medidas devem ser tomadas imediatamente
    2 critical: condições críticas
    3 error: condições de erro
    4 warning: condições de aviso
    5 notice: condição normal, mas significativa
    6 info: mensagens informativas
    7 debug: mensagens de depuração-nível
    */

    'channels' => [
        'stack' => [
            'driver' => 'stack',
            'channels' => ['single'],
            'ignore_exceptions' => false,
            'days' => 30,
        ],

        'single' => [
            'driver' => 'single',
            'path' => storage_path('logs/laravel-single.log'),
            'level' => env('LOG_LEVEL', 'debug'),
            'days' => 30,
        ],

        'daily' => [
            'driver' => 'daily',
            'path' => storage_path('logs/laravel-daily.log'),
            'level' => env('LOG_LEVEL', 'debug'),
            'days' => 30,
        ],

        'slack' => [
            'driver' => 'slack',
            'url' => env('LOG_SLACK_WEBHOOK_URL'),
            'username' => 'Laravel Log',
            'emoji' => ':boom:',
            'level' => env('LOG_LEVEL', 'critical'),
            'days' => 30,
        ],

        'papertrail' => [
            'driver' => 'monolog',
            'level' => env('LOG_LEVEL', 'debug'),
            'handler' => SyslogUdpHandler::class,
            'handler_with' => [
                'host' => env('PAPERTRAIL_URL'),
                'port' => env('PAPERTRAIL_PORT'),
            ],
        ],

        'stderr' => [
            'driver' => 'monolog',
            'level' => env('LOG_LEVEL', 'debug'),
            'handler' => StreamHandler::class,
            'formatter' => env('LOG_STDERR_FORMATTER'),
            'with' => [
                'stream' => 'php://stderr',
            ],
        ],

        'syslog' => [
            'driver' => 'syslog',
            'level' => env('LOG_LEVEL', 'debug'),
            'days' => 30,
        ],

        'errorlog' => [
            'driver' => 'errorlog',
            'level' => env('LOG_LEVEL', 'debug'),
            'days' => 30,
        ],

        'null' => [
            'driver' => 'monolog',
            'handler' => NullHandler::class,
            'days' => 30,
        ],

        'emergency' => [
            'path' => storage_path('logs/laravel-emergency.log'),
            'days' => 30,
        ],

        'deprecations' => [
            'driver' => 'single',
            'path' => storage_path('logs/php-deprecation-warnings.log'),
            'days' => 30,
        ],
    ],

];
