<?php
return [
    /*
    |--------------------------------------------------------------------------
    | Default Bot Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the bots below you wish to use as
    | your default bot for regular use. Of course, you may use many
    | bots at once using the manager class.
    |
    */
    'default' => 'DanversBot',
    /*
    |--------------------------------------------------------------------------
    | Telegram Bots
    |--------------------------------------------------------------------------
    |
    | Here are each of the telegram bots config.
    |
    | Supported Params:
    | - username: Your Telegram Bot's Username.
    |         Example: (string) 'BotFather'.
    |
    | - token: Your Telegram Bot's Access Token.
               Refer for more details: https://core.telegram.org/bots#botfather
    |          Example: (string) '123456:ABC-DEF1234ghIkl-zyx57W2v1u123ew11'.
    |
    | - commands: (Optional) Commands to register for this bot,
    |             Supported Values: "Command Group Name", "Shared Command Name", "Full Path to Class".
    |             Default: Registers Global Commands.
    |             Example: (array) [
    |               'admin', // Command Group Name.
    |               'status', // Shared Command Name.
    |               Acme\Project\Commands\BotFather\HelloCommand::class,
    |               Acme\Project\Commands\BotFather\ByeCommand::class,
    |             ]
    */
    'bots' => [
        'DanversBot' => [
            'username' => 'DanversBot',
            'token' => env('TELEGRAM_BOT_TOKEN', '698812105:AAGEyVj00TKypEBzXB9-0sUwvY1-B8CQwX4'),
            'commands' => [
                App\Commands\StartCommand::class,
            ],
        ],
//        'second' => [
//            'username'  => 'MySecondBot',
//            'token' => '123456:abc',
//        ],
    ],
    /*
    |--------------------------------------------------------------------------
    | Asynchronous Requests [Optional]
    |--------------------------------------------------------------------------
    |
    | When set to True, All the requests would be made non-blocking (Async).
    |
    | Default: false
    | Possible Values: (Boolean) "true" OR "false"
    |
    */
    'async_requests' => env('TELEGRAM_ASYNC_REQUESTS', false),
    /*
    |--------------------------------------------------------------------------
    | HTTP Client Handler [Optional]
    |--------------------------------------------------------------------------
    |
    | If you'd like to use a custom HTTP Client Handler.
    | Should be an instance of \Telegram\Bot\HttpClients\HttpClientInterface
    |
    | Default: GuzzlePHP
    |
    */
    'http_client_handler' => null,
    /*
    |--------------------------------------------------------------------------
    | Resolve Injected Dependencies in commands [Optional]
    |--------------------------------------------------------------------------
    |
    | Using Laravel's IoC container, we can easily type hint dependencies in
    | our command's constructor and have them automatically resolved for us.
    |
    | Default: true
    | Possible Values: (Boolean) "true" OR "false"
    |
    */
    'resolve_command_dependencies' => true,
    /*
    |--------------------------------------------------------------------------
    | Register Telegram Global Commands [Optional]
    |--------------------------------------------------------------------------
    |
    | If you'd like to use the SDK's built in command handler system,
    | You can register all the global commands here.
    |
    | Global commands will apply to all the bots in system and are always active.
    |
    | The command class should extend the \Telegram\Bot\Commands\Command class.
    |
    | Default: The SDK registers, a help command which when a user sends /help
    | will respond with a list of available commands and description.
    |
    */
    'commands' => [
        Telegram\Bot\Commands\HelpCommand::class,
    ],
    'database' => [
        'inline_query' => 'telegram_inline_query',
        'user_chat' => 'telegram_user_chat',
        'chats' => 'telegram_chats',
        'users' => 'telegram_users',
        'chosen_inline_result' => 'telegram_chosen_inline_result',
        'message' => 'telegram_message',
        'edited_message' => 'telegram_edited_message',
        'callback_query' => 'telegram_callback_query',
        'shipping_query' => 'telegram_shipping_query',
        'pre_checkout_query' => 'telegram_pre_checkout_query',
        'poll' => 'telegram_poll',
        'update' => 'telegram_update',
        'conversation' => 'telegram_conversation',
        'request_limiter' => 'telegram_request_limiter'
    ],
    /*
    |--------------------------------------------------------------------------
    | Shared Commands [Optional]
    |--------------------------------------------------------------------------
    |
    | Shared commands let you register commands that can be shared between,
    | one or more bots across the project.
    |
    | This will help you prevent from having to register same set of commands,
    | for each bot over and over again and make it easier to maintain them.
    |
    | Shared commands are not active by default, You need to use the key name to register them,
    | individually in a group of commands or in bot commands.
    | Think of this as a central storage, to register, reuse and maintain them across all bots.
    |
    */
    'shared_commands' => [
        // 'start' => Acme\Project\Commands\StartCommand::class,
        // 'stop' => Acme\Project\Commands\StopCommand::class,
        // 'status' => Acme\Project\Commands\StatusCommand::class,
    ],
];
