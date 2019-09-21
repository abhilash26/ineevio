<?php

use controllers\Controller;
namespace controllers;

class HomeController extends Controller {

    public function __construct(){
        parent::__construct();
        $this->view->title = "Home";
    }


    public function index(){
        $this->view->greeting = "Hello";
        $this->view('index');
    }    
}
