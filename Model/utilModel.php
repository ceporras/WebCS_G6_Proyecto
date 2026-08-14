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


    function addLog($timestamp,$level,$component,$message)
{
    try {
        $conn = OpenDB();
        $sql = "CALL sp_AddLog('$timestamp','$level','$component','$message')";
        $result = $conn->query($sql);

        CloseDB($conn);
        return $result;

    } catch (Exception $e) {

        return false;
    }
}

    function timestamp(){
        //funcion para agregar fecha en formato especifico para agregar a errores del DB
        $date = new DateTime();
        $date->setTimezone(new DateTimeZone('-6'));
        $formattedTime = $date->format('Y-m-d H:i:s');
        return $formattedTime;
    }


    

    