<?php

return [
    'ossServer' => env('ALIOSS_SERVER', null),                      // 外网
    'ossServerInternal' => env('ALIOSS_SERVERINTERNAL', null),      // 内网
    'AccessKeyId' => env('OSS_ACCESS_KEY_ID', null),                     // key
    'AccessKeySecret' => env('OSS_ACCESS_KEY_SECRET', null),             // secret
    'BucketName' => env('OSS_BUCKET_NAME', null)                  // bucket
];