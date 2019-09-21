<?php 

namespace core\template;

class Template
{

    protected $template_dir;
    protected $template_ext;
    public $data;

    
    public function __construct($template_dir, $template_ext) {

        $this->template_dir = BASE_PATH. $template_dir;
        $this->template_ext = $template_ext;
        $this->data = [];

        // Check if template file exists
        if(!file_exists($this->template_dir) || !(is_dir($this->template_dir))) {
            throw new \Exception("No specified template directory exists");
            return;
        }
    }

    public function render($template_filename, $data=[]) {

        if (!empty($template_filename) && (strpos($template_filename, '.') !== false)) {
            $template_filename = str_replace('.', DIRECTORY_SEPARATOR, $template_filename);
        }
        // Create complete template path
        $template_file_path = $this->template_dir. DIRECTORY_SEPARATOR. $template_filename . $this->template_ext;
        // Check if template file exists
        if(!file_exists($template_file_path)) {
            throw new \Exception("No specified template file exists");
            return;
        }

        $data = array_merge($this->data, $data);
        require_once $template_file_path;
    }

    public function __set($name, $value) {
        $this->data[$name] = $value;
    }
    public function __get($name) {
        return $this->data[$name];
    }

} // end of class
