<?php

$application->registerModules([
    'Backend' => [
        'className' => 'Backend\Module',
        'path'      => __DIR__ . '/../apps/Backend/Module.php'
    ],
    'Frontend' => [
        'className' => 'Frontend\Module',
        'path'      => __DIR__ . '/../apps/Frontend/Module.php'
    ],
]);