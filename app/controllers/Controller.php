<?php 
namespace controllers;

use \core\template\Template as Template;

class Controller{

    protected $view;

    protected function __construct($template_dir = ''){
        $config = require(BASE_PATH.'/config/config.php');
        $this->view = new Template($config['template']['directory'].$template_dir, $config['template']['extension']);
    }

    protected function view($template_name, $data=[]) {
        $this->view->render($template_name, $data);
    }



}
