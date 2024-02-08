<?php
// declare(stricttypes=1);

class Autoloader {
    static function register() {
        spl_autoload_register(array(__CLASS__, 'autoload'));
    }

    static function autoload($fqcn) {
        $path = str_replace('\\', '/', $fqcn);


        $classFile = 'Classes/Objet/' . $path . '.php';
        if (file_exists($classFile)) {
            require_once $classFile;
            return;
        }
    }
}
?>