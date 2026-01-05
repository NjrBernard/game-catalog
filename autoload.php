<?php 

use Dom\NamespaceInfo;

spl_autoload_register(function ($class) {
    
    // Notre traitement - Namespace -> Chemin fichier.
    $baseDir = __DIR__ . '/src/';
    $file = $baseDir . str_replace('\\', '/', $class) . '.php';

    // Vérifier si le fichier existe. Si oui, on le require.
    if (file_exists($file)) {
        require $file;
    }   
});

