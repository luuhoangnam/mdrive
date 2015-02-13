<?php

return [
    'default_input'      => ['upload_file', 'image'],
    'storage_path'       => 'files', // Relatively
    // Avaiable variables: $year, $month, $filename, $ext
    'structure'          => '$year/$month/$filename.$ext',
    'validation'         => 'max:8192', // 8 Megabytes
    'validation_message' => 'Validation failed',
];
