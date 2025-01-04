<?php

try{
    $smtp=new PDO("mysql:host=localhost;dbname=chatgpt","root","");
    $smtp->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    $smtp->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_ASSOC);
    'echo "<h5>Connection successfully</h5>";';
    }catch(PDOException $e){
    error_log('ERROR 404'.$e->getMessage());
    die('Database connection failed');
    };
    

?>