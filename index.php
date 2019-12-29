<?php

declare(strict_types=1);

require __DIR__.'/vendor/autoload.php';

try {
    $cosClient = new Qcloud\Cos\Client([
        'region' => getenv('INPUT_REGION'),
        'credentials' => [
            'appId' => getenv('INPUT_APP_ID'),
            'secretId' => getenv('INPUT_KEY'),
            'secretKey' => getenv('INPUT_SECRET'),
        ],
    ]);

    $input_files = getenv('INPUT_FILES');

    if (is_object(json_decode($input_files))) {
        foreach (json_decode(getenv('INPUT_FILES'), true) as $file => $label) {
            $result = $cosClient->putObject([
            'Bucket' => getenv('INPUT_BUCKET'),
            'Key' => $label,
            'Body' => fopen($file, 'r'),
        ]);

            echo "===> Upload $file TO $label result";

            var_dump($result);
        }
    } else {
        $files = explode(',', $input_files);

        foreach ($files as $file) {
            $result = $cosClient->putObject([
            'Bucket' => getenv('INPUT_BUCKET'),
            'Key' => $file,
            'Body' => fopen($file, 'r'),
        ]);

            echo "===> Upload $file TO $file result";

            var_dump($result);
        }
    }
} catch (Throwable $e) {
    echo $e->__toString();
}
