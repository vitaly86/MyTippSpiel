<?php
// Define your autoloader function
function db_autoloader($class_name)
{


    // Construct the file path
    // $file_path1 = $directory . $class_name . '.php';
    $file_path1 = 'DATABASE/' . $class_name . '.php';
    $file_path2 = '../' . 'DATABASE/' . $class_name . '.php';

    // If the file exists, include it
    if (file_exists($file_path1)) {
        include $file_path1;
    } else if (file_exists($file_path2)) {
        include $file_path2;
    }
    return;
}

// Register your autoloader function
spl_autoload_register('db_autoloader');
