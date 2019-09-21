<?php

// For storing configurations.

$config = [
    "application" => [
        "name"       => "Ineevio",
        "deployment" => "development"
    ],
    "database" => [
        "type" => "mysql",
        "host" => "localhost",
        "user" => "root",
        "pass" => "",
        "name" => "test",
    ],
    "template" => [
        "directory" => "/views",
        "extension" => ".art.php"
    ]
];




return $config;
