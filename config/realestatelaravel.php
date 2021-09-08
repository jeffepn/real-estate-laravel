<?php

/**
 * We strongly advise using ​​environment variables, since when the package install
 *  command is executed, this file will be overwritten
 */
return [
    /**
     *  Defines whether to use the package `template`
     *  -   When set to false, you will need to define a `template` and
     *      section_content for the content.
     */
    'use_template' => env('RE_USE_TEMPLATE', true),
    /**
     *  The name of the `template` used to extend the package view
     */
    'template' => env('RE_TEMPLATE', 'jprealestate::layout.template'),
    /**
     *  Template content section name
     */
    'section_content' => env('RE_SECTION_CONTENT', 'content'),
    /**
     *  Define middlewares of routes web and routes api
     */
    'middleware' => [
        'web' => env('RE_MIDDLEWARES_WEB', ['web']),
        'api' => env('RE_MIDDLEWARES_API', ['api']),
    ],
    /**
     *  Defines the disk used and the `paths` to save the entity files
     */
    'filesystem' => [
        'disk' => env('RE_DISK', 'public'),
        'optmize' => env('RE_OPTMIZE_PROPERTY', true),
        'entities' => [
            'properties' => [
                'disk' => env('RE_DISK_PROPERTY', 'public'),
                'path' => env('RE_PATH_PROPERTY', 'properties'),
                'optmize' => env('RE_OPTMIZE_PROPERTY', true),
            ],
        ],
    ],
    /**
     *  When `use_template` is true, it may be necessary to have a url to go
     *  back to a different admin area, this url will be added to the `General Panel` menu item
     */
    'url_home' => env('RE_URL_HOME', null)
];
