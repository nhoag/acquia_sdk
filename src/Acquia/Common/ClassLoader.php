<?php

/**
 * @file
 * A PSR-0 class loader compatible with PHP 5.2
 * Based on SimpleClassLoader http://stackoverflow.com/a/12355171
 */

class Acquia_Common_ClassLoader
{
    private $_name_space;
    private $_include_path;
    private $_file_extension = '.php';

    /**
     * Creates a new <tt>Acquia_Common_PsrClassLoader</tt> that loads classes of the
     * specified namespace.
     *
     * @param string $name_space The namespace to use.
     * @param string $include_path The includePath to use.
     */
    public function __construct($name_space = null, $include_path = null)
    {
        $this->_name_space = $name_space;
        $this->_include_path = $include_path;
    }

    /**
     * Installs this class loader on the SPL autoload stack.
     */
    public function register()
    {
        spl_autoload_register(array($this, 'loadClass'));
    }

    /**
     * Uninstalls this class loader from the SPL autoload stack.
     */
    public function unregister()
    {
        spl_autoload_unregister(array($this, 'loadClass'));
    }

    /**
     * Loads the given class or interface.
     *
     * @param string $class_name The name of the class to load.
     * @return void
     */
    public function loadClass($class_name)
    {
        fprintf(STDERR, "Looking for $class_name around {$this->_name_space}\n");
        if (strpos($class_name, $this->_name_space) === 0) {
            $class_path = str_replace('_', DIRECTORY_SEPARATOR, $class_name);
            $class_file = "{$this->_include_path}/{$class_path}{$this->_file_extension}";
            if (file_exists($class_file)) {
                require_once $class_file;
            }
        }
    }
}

