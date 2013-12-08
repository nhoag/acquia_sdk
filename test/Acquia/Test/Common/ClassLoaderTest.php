<?php

class Acquia_Test_Common_ClassLoaderTest extends PHPUnit_Framework_TestCase
{
    public function testClassLoader()
    {
        $this->assertFalse(class_exists('MockNamespace_Namespace_Foo'));
        $test_class_loader = new Acquia_Common_ClassLoader('MockNamespace', 'test');
        $test_class_loader->register();
        $this->assertTrue(class_exists('MockNamespace_Namespace_Foo'));
        $test_class_loader->unregister();
        $this->assertFalse(class_exists('MockNamespace_Namespace_Bar'));
    }
}

