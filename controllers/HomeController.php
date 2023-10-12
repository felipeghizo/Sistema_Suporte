<?php

    namespace controllers;

    class homeController{

        public function index(){
            \views\mainview::render("home");
        }
    }

?>