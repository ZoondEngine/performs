<?php

use Cryptstick\Performs\Primitives\CreatePerform;
use Cryptstick\Performs\Primitives\DeletePerform;
use Cryptstick\Performs\Primitives\UpdatePerform;

return [
    /**
     * Anchors for checking logic
     */
    'anchors' => [

        /**
         * Transfer anchor, sometimes contains identifier of model(for model bindings)
         */
        'id' => 'identifier',

        /**
         * Data anchor, contains custom data for UpdatePerform
         */
        'data' => 'properties',

        /**
         * Default settings for perform primitives
         */
        'defaults' => [
            CreatePerform::class => [
                'data'
            ],
            DeletePerform::class => [
                'id'
            ],
            UpdatePerform::class => [
                'id',
                'data'
            ]
        ]
    ],

    /**
     * Delegates for lifecycle delegates
     */
    'delegates' => [

        /**
         * Registered delegates, used with defaults
         */
        'registered' => [],

        /**
         *
         */
        'default' => [
            'after', 'before'
        ]
    ],

    /**
     * Indicates need use __() for all your strings,
     * true as default
     */
    'lang' => [
        'use' => true
    ]
];
