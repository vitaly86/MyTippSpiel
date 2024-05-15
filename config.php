<?php
// Define your autoloader function
function my_autoloader($class_name)
{
    // Define an array of directories where your classes are located
    $directories = array(
        'DATABASE/',
        'Utils/'
    );

    // Iterate through each directory
    foreach ($directories as $directory) {
        // Construct the file path
        $file_path = '../' . $directory . $class_name . '.php';

        // If the file exists, include it
        if (file_exists($file_path)) {
            include $file_path;
            return;
        }
    }
}
// Register your autoloader function
spl_autoload_register('my_autoloader');
