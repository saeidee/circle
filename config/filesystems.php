<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application. Just store away!
    |
    */

    'default' => env('FILESYSTEM_DRIVER', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Default Cloud Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Many applications store files both locally and in the cloud. For this
    | reason, you may specify a default "cloud" driver here. This driver
    | will be bound as the Cloud disk implementation in the container.
    |
    */

    'cloud' => env('FILESYSTEM_CLOUD', 's3'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many filesystem "disks" as you wish, and you
    | may even configure multiple disks of the same driver. Defaults have
    | been setup for each driver as an example of the required options.
    |
    | Supported Drivers: "local", "ftp", "sftp", "s3"
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],

        'avatars' => [
            'driver' => 'local',
            'root' => storage_path('app/public/avatars'),
            'url' => env('APP_URL') . '/storage/avatars',
            'visibility' => 'public',
        ],

        'projectCover' => [
            'driver' => 'local',
            'root' => storage_path('app/public/projectCover'),
            'url' => env('APP_URL').'/storage/public/projectCover',
            'visibility' => 'public',
        ],

        'sceneVideo' => [
            'driver' => 'local',
            'root' => storage_path('app/public/sceneVideo'),
            'url' => env('APP_URL').'/storage/public/sceneVideo',
            'visibility' => 'public',
        ],

        'sceneCover' => [
            'driver' => 'local',
            'root' => storage_path('app/public/sceneCover'),
            'url' => env('APP_URL').'/storage/public/sceneCover',
            'visibility' => 'public',
        ],

        'projectModule' => [
            'driver' => 'local',
            'root' => storage_path('app/public/projectModule'),
            'url' => env('APP_URL').'/storage/public/projectModule',
            'visibility' => 'public',
        ],

        'projectInfo' => [
            'driver' => 'local',
            'root' => storage_path('app/public/projectInfo'),
            'url' => env('APP_URL').'/storage/public/projectInfo',
            'visibility' => 'public',
        ],

        'companyLogo' => [
            'driver' => 'local',
            'root' => storage_path('app/public/companyLogo'),
            'url' => env('APP_URL').'/storage/public/companyLogo',
            'visibility' => 'public',
        ],

        'productCover' => [
            'driver' => 'local',
            'root' => storage_path('app/public/productCover'),
            'url' => env('APP_URL').'/storage/public/productCover',
            'visibility' => 'public',
        ],

        'campaignCover' => [
            'driver' => 'local',
            'root' => storage_path('app/public/campaignCover'),
            'url' => env('APP_URL').'/storage/public/campaignCover',
            'visibility' => 'public',
        ],

        'productInfo' => [
            'driver' => 'local',
            'root' => storage_path('app/public/productInfo'),
            'url' => env('APP_URL').'/storage/public/productInfo',
            'visibility' => 'public',
        ],

        'productModel' => [
            'driver' => 'local',
            'root' => storage_path('app/public/productModel'),
            'url' => env('APP_URL').'/storage/public/productModel',
            'visibility' => 'public',
        ],

        'projectModel' => [
            'driver' => 'local',
            'root' => storage_path('app/public/projectModel'),
            'url' => env('APP_URL').'/storage/public/projectModel',
            'visibility' => 'public',
        ],

        'productStorage' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('S3_PRODUCT_STORAGE_BUCKET'),
        ],

        'campaignStorage' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('S3_CAMPAIGN_STORAGE_BUCKET'),
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Symbolic Links
    |--------------------------------------------------------------------------
    |
    | Here you may configure the symbolic links that will be created when the
    | `storage:link` Artisan command is executed. The array keys should be
    | the locations of the links and the values should be their targets.
    |
    */

    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],

];
