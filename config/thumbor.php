<?php

return [
    /*
     |--------------------------------------------------------------------------
     | Thumbor server URL
     |--------------------------------------------------------------------------
     |
     | Thumbor server URL that will be used as prefix to the generated URL.
     */
    'host' => env('THUMBOR_HOST'),

    /*
     |--------------------------------------------------------------------------
     | Thumbor security key
     |--------------------------------------------------------------------------
     |
     | Configure Thumbor settings to customize an image to the exact size,
     | without changing it’s aspect ratio.
     |
     | The same security key used in the thumbor service to match the URL construction.
     */
    'security_key' => env('THUMBOR_SECURITY_KEY'),
];
