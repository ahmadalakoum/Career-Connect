<?php

//require the db connection
require_once "../connection/connection.php";

//get migration folder
$migrationsDir = 'migrations/';
// echo $migrationsDir;


//get the files inside the directory
$files = scandir($migrationsDir);
// var_dump($files);


//Iterate on each file and create the table
foreach ($files as $file) {
    //check if the file extension is php
    if (pathinfo($file, PATHINFO_EXTENSION) === "php") {
        require_once $migrationsDir . $file;
        $className = pathinfo($file, PATHINFO_FILENAME);
        //remove the leading numbers
        $className = preg_replace('/^\d+_/', '', $className);
        $className = str_replace("_", "", ucwords($className, "_"));
        // echo $className;
        $migration = new $className();
        $migration->up($pdo);

        echo "Migration " . $className . "applied \n";
    }
}
