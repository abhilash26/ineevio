<?php 

namespace controllers;

class DefaultController extends Controller
{

    public function __construct() {
        parent::__construct();
    }

    public function error($template_name) {
        $this->view("errors.{$template_name}");
    }
}
        