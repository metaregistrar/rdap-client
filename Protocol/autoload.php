<?php
spl_autoload_register('protocolLoader');

function protocolLoader($className) {
    loadfile($className,'');
    loadfile($className,'data');
    loadfile($className,'responses');
}

function loadfile($className, $directory) {
    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
        $delimiter = '\\';
    } else {
        $delimiter = '/';
    }
    if (strlen($directory)>0) {
        $directory .= $delimiter;
    }
    $fileName = __DIR__ . $delimiter . $directory . $className . '.php';
    //echo "Test autoload $fileName\n";
    if (is_readable($fileName)) {
        //echo "Autoloaded $fileName\n";
        require($fileName);
    }
}