<?php

    define("HOST", "localhost");
    define("DATABASE", "suporte_personalizado");
    define("USER", "root");
    define("PASSWORD", "");
    define("BASE", "http://localhost/Dankicode/PHP/projetos/projeto01/");

    require 'C:\xampp\mysql\data\sistema_suporte\vendor/autoload.php';
    $autoload = function($class){
        include($class.".php");
    };

    spl_autoload_register($autoload);

    $HomeController     =   new \controllers\homeController();
    $ChamadoController  =   new \controllers\chamadoController();
    $AdminController    =   new \controllers\adminController();

    //HomeController
    Router::get("/", function() use ($HomeController){
        $HomeController->index();
    });

    //ChamadoController
    Router::get("/chamado", function() use ($ChamadoController){
        if(isset($_GET["token"])){
            if($ChamadoController->existeToken()){
                $info = $ChamadoController->getPergunta($_GET["token"]);
                $ChamadoController->index($info); 
            } else {
                die("O token esta setado porém não existe!");
            }
        } else {
            die("Apenas com o token chamado para você conseguir interagir!");
        }
    });

    //AdminController
    Router::get("/admin", function() use ($AdminController){
        $AdminController->index();
    })
?>