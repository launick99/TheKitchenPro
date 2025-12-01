<?php
    require_once '../functions/autoload.php';

    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    Autorizacion::logOut();

    header("Location: ../?section=inicio");
    exit;