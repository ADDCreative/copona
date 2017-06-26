<?php

return [

    /**
     * App Env
     */
    'app_env'               => env('APP_ENV', 'production'),

    /**
     * Site
     */
    'site_base'             => HTTP_SERVER,
    'site_ssl'              => HTTPS_SERVER,

    /**
     * Permission Dir
     */
    'directory_permission'        => 0755,

    /**
     * Url
     */
    'url_autostart'         => true,

    /**
     * Language (DIRECTORY!)
     */
    'language_default'      => 'en',
    'language_autoload'     => ['en'],

    /**
     * Template
     */
    'template_type'         => 'php',

    /**
     * Error
     */
    'config_error_display'  => env('APP_ENV') == 'production' ? false : true,
    'config_error_log'      => true,
    'config_error_filename' => 'error.log',

    /**
     * Reponse
     */
    'response_header'       => ['Content-Type: text/html; charset=utf-8'],
    'response_compression'  => 0,

    /**
     * Autoload Configs
     */
    'config_autoload'       => [],

    /**
     * Autoload Libraries
     */
    'library_autoload'      => [],

    /**
     * Autoload model
     */
    'model_autoload'        => [],
];