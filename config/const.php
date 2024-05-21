<?php

return [
    'ROLE_SUPER_ADMIN' => 'super-admin',
    'ROLE_PRODUCT_ADMIN' => 'product-admin',
    'ADMIN_EMAIL' => env('APP_ADMIN_EMAIL', 'admin@example.com'),
    'PERMISSION_PRODUCTS_MANAGE' => 'products-manage',
    'PERMISSION_SYSTEM_MANAGE' => 'manage-system',
    'TAGS_MANAGED' => 1,
    'TAGS_UNMANAGED' => 0,
    'MAX_FILESIZE_UPLOAD' => '2mb',
    'ALLOWED_FILETYPES' => "['image/png', 'image/jpg', 'image/jpeg']",
    'TAGS_ADMIN' => 'admin',
    'TAGS_USER' => 'user',
    'STATUS_NEW' => 'new',
    'STATUS_CONSIDERED' => 'considered',
    'STATUS_DECLINED' => 'declined',
    'STATUS_PLANNED' => 'planned',
    'STATUS_STARTED' => 'started',
    'STATUS_COMPLETED' => 'completed',
    'STATUS_SUPPORTCALL' => 'supportcall',
    'ACCEPTED_IMAGE_MIMETYPES' => ['image/jpg', 'image/jpeg', 'image/png', 'image/svg', 'image/svg+xml'],
    'APP_FORCE_HTTPS' => env('APP_FORCE_HTTPS', false)
];
