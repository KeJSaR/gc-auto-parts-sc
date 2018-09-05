<?php
namespace SCL\Lib;

defined("SCL_SAFETY_CONST") or die;

class Autoloader
{
    public function register()
    {
        spl_autoload_register(array($this, "load_class"));
    }

    public function load_class($path)
    {
        $root_path        = rtrim(SCL_ROOT_DIR, SCL_DS);
        $class_path       = substr($path, 4);
        $class_path_array = explode("\\", $class_path);

        foreach ($class_path_array as $path_part) {
            $root_path .= SCL_DS . $path_part;
        }

        $mapped_file = $this->load_mapped_file($root_path);

        if ($mapped_file) {
            return $mapped_file;
        }
        return false;
    }

    protected function load_mapped_file($path)
    {
        $file = $path . ".php";
        if ($this->require_file($file)) {
            return $file;
        }
        return false;
    }

    protected function require_file($file)
    {
        if (file_exists($file)) {
            require $file;
            return true;
        }
        return false;
    }
}
