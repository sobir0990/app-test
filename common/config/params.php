<?php
return [
    'adminEmail' => 'admin@example.com',
    'supportEmail' => 'support@example.com',
    'user.passwordResetTokenExpire' => 3600,
    'upload_dir_file' => '@static/uploads/',
    'upload_dir_file_src' => getenv('STATIC_URL') . 'uploads/',
];
