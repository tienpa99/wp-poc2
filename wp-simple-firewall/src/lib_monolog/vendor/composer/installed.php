<?php return array(
    'root' => array(
        'name' => 'apto-shield/requirements_monolog',
        'pretty_version' => 'dev-develop',
        'version' => 'dev-develop',
        'reference' => '99c339ee2ddc26b4efb3c6635bd8dad706f35d06',
        'type' => 'library',
        'install_path' => __DIR__ . '/../../',
        'aliases' => array(),
        'dev' => true,
    ),
    'versions' => array(
        'apto-shield/requirements_monolog' => array(
            'pretty_version' => 'dev-develop',
            'version' => 'dev-develop',
            'reference' => '99c339ee2ddc26b4efb3c6635bd8dad706f35d06',
            'type' => 'library',
            'install_path' => __DIR__ . '/../../',
            'aliases' => array(),
            'dev_requirement' => false,
        ),
        'monolog/monolog' => array(
            'pretty_version' => '2.9.1',
            'version' => '2.9.1.0',
            'reference' => 'f259e2b15fb95494c83f52d3caad003bbf5ffaa1',
            'type' => 'library',
            'install_path' => __DIR__ . '/../monolog/monolog',
            'aliases' => array(),
            'dev_requirement' => false,
        ),
        'psr/log' => array(
            'pretty_version' => '1.1.4',
            'version' => '1.1.4.0',
            'reference' => 'd49695b909c3b7628b6289db5479a1c204601f11',
            'type' => 'library',
            'install_path' => __DIR__ . '/../psr/log',
            'aliases' => array(),
            'dev_requirement' => false,
        ),
        'psr/log-implementation' => array(
            'dev_requirement' => false,
            'provided' => array(
                0 => '1.0.0 || 2.0.0 || 3.0.0',
            ),
        ),
    ),
);
