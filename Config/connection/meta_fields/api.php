<?php

return [
    [
        'field'    => 'select',
        'title'    => 'Connection',
        'name'     => 'crm',
        'source'   => 'CodeClouds\\Unify\\Model\\Config\\Connection',
        'required' => true,
    ],
    [
        'field'    => 'input',
        'type'     => 'text',
        'title'    => 'Endpoint',
        'name'     => 'endpoint',
    ],
    [
        'field'    => 'input',
        'type'     => 'text',
        'title'    => 'Username',
        'name'     => 'api_username',
        'required' => true,
    ],
    [
        'field'    => 'input',
        'type'     => 'password',
        'title'    => 'Password',
        'name'     => 'api_password',
        'required' => true,
    ],
];
