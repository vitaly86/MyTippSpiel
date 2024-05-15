<?php

require_once file_exists('index_class_register.php') ? 'index_class_register.php' : '../index_class_register.php';

Schema::verify_create_schema();

if (!Schema::$check_schema_exists) {
    $schema = new Schema('localhost', 'root', '', 'tippspiel');
}
