<?php

namespace core\error;

use \Exception;

ob_start();

class ExceptionHandler {

    public function __construct() {
        set_exception_handler(array($this, 'fire'));
    }

    public function fire($exception) {

        $config = require(BASE_PATH.'/config/config.php');

        if($config['application']['deployment'] == 'development') {
            $this->showException($exception);
        } else {
            ob_end_clean();
            // Show a human message to the user.
            echo '<h1>Server error</h1>';
            echo '<p>Please contact your administrator.';
        }
            // Save a copy for the sys-admins ;)
            error_log($exception->getMessage());
    }

    private function showException($exception) {
            echo "<p>{$exception->getCode()}</p>";
            echo "<h3>Line {$exception->getLine()} in file {$exception->getFile()}</h3>";
            echo "<pre>"; print_r($exception->getTrace()); echo "</pre>";
            echo "<h3>{$exception->getMessage()}</h3>";
    }

}