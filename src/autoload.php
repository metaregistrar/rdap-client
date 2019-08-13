<?php
spl_autoload_register('protocolLoader');

function protocolLoader($className) {
    $className = str_replace('Metaregistrar\RDAP\\', '', $className);
    loadfile($className, '');
    loadfile($className, 'data');
    loadfile($className, 'responses');
}

function loadfile($className, $directory) {
    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
        $delimiter = '\\';
    } else {
        $delimiter = '/';
    }
    if (strlen($directory) > 0) {
        $directory .= $delimiter;
    }
    $fileName = __DIR__ . $delimiter . $directory . $className . '.php';;
    if (is_readable($fileName)) {
        require($fileName);
    }
}