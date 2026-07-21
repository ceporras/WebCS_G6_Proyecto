<?php

include_once $_SERVER['DOCUMENT_ROOT']
    . '/WebCS_G6_Proyecto/Model/UtilModel.php';

function GenerarContrasenna()
{
    $caracteres = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $contrasenna = '';
    $max = strlen($caracteres) - 1;

    for ($i = 0; $i < 8; $i++) {
        $contrasenna .= $caracteres[random_int(0, $max)];
    }

    return $contrasenna;
}

function EnviarCorreo($asunto, $contenido, $destinatario)
{
    try {

        require_once $_SERVER['DOCUMENT_ROOT']
            . '/WebCS_G6_Proyecto/PHPMailer/src/PHPMailer.php';

        require_once $_SERVER['DOCUMENT_ROOT']
            . '/WebCS_G6_Proyecto/PHPMailer/src/SMTP.php';

        $correoSalida = "goldenframecinemacr@gmail.com";
        $contrasennaSalida = "agahhzcyxvzhxmwk";

        $mail = new PHPMailer();
        $mail->CharSet = "UTF-8";

        $mail->IsSMTP();
        $mail->IsHTML(true);

        $mail->Host = "smtp.gmail.com";
        $mail->SMTPSecure = "tls";
        $mail->Port = 587;
        $mail->SMTPAuth = true;

        $mail->Username = $correoSalida;
        $mail->Password = $contrasennaSalida;

        $mail->SetFrom(
            $correoSalida,
            "Golden Frame Cinema"
        );

        $mail->Subject = $asunto;
        $mail->MsgHTML($contenido);
        $mail->AddAddress($destinatario);

        $mail->send();

        return true;

    } catch (Exception $e) {

        AddError($e, "EnviarCorreo");
        return false;
    }
}

function CerrarSesion()
{
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    session_destroy();

    header(
        "Location: /WebCS_G6_Proyecto/View/IniciarSesion.php"
    );
    exit();
}