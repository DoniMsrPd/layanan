<?php

return [
    'doc_serv_converter_url' => env('DS_CONVERTER_URL', 'http://localhost/ConvertService.ashx'),
    'doc_serv_api_url' => env('DS_API_URL', 'http://localhost/web-apps/apps/api/documents/api.js'),
    'doc_serv_preloader_url' => env('DS_PRELOADER_URL', 'http://localhost/ds-vpath/web-apps/apps/api/documents/cache-scripts.html'),
    'app_url' => env('DS_APP_URL', 'http://172.19.0.1:8000'),
    'app_id' => env('DS_APP_ID', 'KT1'),

    'storage_disk' => 'konseling',
    'storage_path' => storage_path('app/konseling'),
    'file_size_max' => 5242880,
];
