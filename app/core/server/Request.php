<?php

namespace core\server;

class Request 
{
    private $input;
    private $query;
    private $server;
    private $header;
    // private $session;

    function __construct()
    {

        $this->input = [];
        $this->query = [];
        $this->server = [];
        $this->header = [];
        // $this->session = [];

        // Get all server variables
        foreach($_SERVER as $key => $value) {
            $value = trim($value);
            $value = ($value === '')? NULL: $value;
            $this->server[trim($key)] = $value;
        }

        // get all input (trim and show null instead of empty strings)
        foreach($_REQUEST as $key => $value) {
            $value = trim($value);
            $value = ($value === '')? NULL: $value;
            $this->input[trim($key)] = $value;
        }

        // Get all query input
        foreach($_GET as $key => $value) {
            $value = trim($value);
            $value = ($value === '')? NULL: $value;
            $this->query[trim($key)] = $value;
        }

        foreach(getallheaders() as $key => $value) {
            $value = trim($value);
            $value = ($value === '')? NULL: $value;
            $this->header[trim($key)] = $value;
        }

    }

    public function path() {
        return $this->server['REQUEST_URI'];
    }

    public function is($match)
    {
        //trim
        $match = trim($match);
        if(strpos($match, '*') !== false)
        {
            $match = str_replace('/','\/', $match);
            $match = str_replace('*','(.*)', $match);
            $match = "/{$match}/";
            return preg_match($match, $this->server['REQUEST_URI']);
        } else {
            return $match == $this->server['REQUEST_URI'];
        }
    }

    public function url() {
        return explode('?', $this->server['REQUEST_URI'])[0];
    }

    public function fullUrl() {
        return $this->server['REQUEST_URI'];
    }

    public function segment($segment_index ='') {
        $uri_segments =  explode('/', $this->fullUrl());
        array_shift($uri_segments);
        if($segment_index  == '') {
            return $uri_segments;
        } else {
            return $uri_segments[$segment_index];
        }
    }

    public function method() {
        return strtolower($this->server['REQUEST_METHOD']);
    }

    public function isMethod($method) {
        return $this->method() === strtolower(trim($method));
    }

    ////////////////////////////////////////////////////////////////////////////////


    public function all() {
        return $this->input;
    }

    public function input($key, $default) {

        if(empty($key)) {
            return $this->all(); // Send all inputs
        } else {
             if(!isset($this->input[$key]) || $this->input[$key]=== NULL) {
                return (isset($default)) ? $default : NULL ; // Send the default value
            } else {
                return $this->input[$key]; // Send the value of the key
            }
        }
    }
    public function query($key, $default) {

        if(empty($key)) {
            return $this->all(); // Send all query
        } else {
             if(!isset($this->query[$key]) || $this->query[$key]=== NULL) {
                return (isset($default)) ? $default : NULL ; // Send the default value
            } else {
                return $this->query[$key]; // Send the value of the key
            }
        }
    }

    public function __get($key) {
        if(array_key_exists($key, $this->input)) {
            return $this->input[$key];
        }
        return NULL;
    }

    public function only($key_array) {
        $return_array = [];
        foreach($key_array as $key) {
            if(array_key_exists($key, $this->input)) {
                $return_array[$key] = $this->input[$key];
            } else {
                $return_array[$key] = NULL;
            } 
        }
        return $return_array;
    }

    public function except($key_array) {
        $return_array = $this->input;
            foreach($key_array as $key) {
                if(array_key_exists($key, $this->input)) {
                    unset($return_array[$key]);
                }
            }
        return $return_array;
    }

    public function has($key) {
        if(array_key_exists($key, $this->input)) {
            return true;
        }
        return false;
    }

    public function filled($key)
    {
         if(array_key_exists($key, $this->input) && (!empty($this->input[$key]) || $this->input[$key] === 0)) {
            return true;
        }
        return false;
    }

    //////////////////////////////////////////////////////////////////////////////////

    public function header($key, $default) {

        if(empty($key)) {
            return $this->all(); // Send all headers
        } else {
             if(!isset($this->header[$key]) || $this->header[$key]=== NULL) {
                return (isset($default)) ? $default : NULL ; // Send the default value
            } else {
                return $this->header[$key]; // Send the value of the key
            }
        }
    }








    
} // End of class