<?php

class Acquia_Test_Common_ClassLoaderTest extends PHPUnit_Framework_TestCase
{
    public function testClassLoader()
    {
        $test_class_loader = new Acquia_Common_ClassLoader('MockNamespace', 'test');
        $test_class_loader->register();
        $this->assertInstanceOf('MockNamespace_Namespace_Foo', new MockNamespace_Namespace_Foo);
    }
}

