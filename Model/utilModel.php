<?php

    if(session_status() == PHP_SESSION_NONE){
        session_start();
    }

    function OpenDB()
    {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        return new mysqli("u-cs-webdev-proyecto.cvusi82ukm39.us-east-2.rds.amazonaws.com:3306", "admin", "adminadmin", "cine_db");
    }

    function CloseDB($conn)
    {
        $conn -> close();
    }

    