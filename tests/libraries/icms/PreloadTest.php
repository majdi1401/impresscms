<?php

namespace ImpressCMS\Tests\Libraries\ICMS;

use PHPUnit\Framework\TestCase;

/**
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/

class PreloadTest extends TestCase {

    /**
     * Test if is available
     */
    public function testAvailability() {
        foreach ([
            'icms_preload_Handler',
            'icms_preload_Item',
            'icms_preload_LibrariesHandler'
        ] as $class) {
            $this->assertTrue(class_exists($class, true), $class . ' does\'t exist');
        }
    }

    /**
     * Gets instance of class from classname
     *
     * @param string $class     ClassName
     *
     * @return object
     */
    private function getClassInstance($class) {
        $instance = $this->getMockBuilder($class)
                    ->disableOriginalConstructor()
                    ->getMock();
        return $instance;
    }

    /**
     * Test methods availability
     */
    public function testMethodsAvailability() {
        foreach ([
            'icms_preload_Handler' => [
                'addPreloadEvents',
                'triggerEvent',
                'getClassName'
            ],
            'icms_preload_LibrariesHandler' => [
                'triggerEvent',
                'getLibraryBootFilePath',
                'getFunctionName'
            ]
        ] as $class => $methods) {
            foreach ($methods as $method) {
                $this->assertTrue(method_exists($class, $method), 'Static method ' . $method . ' doesn\'t exists for class ' . $class);
            }
        }
    }

    /**
     * Test static method availability
     */
    public function testStaticMethodsAvailability() {
        foreach ([
            'icms_preload_Handler' => [
                'getInstance'
            ],
            'icms_preload_LibrariesHandler' => [
                'getInstance'
            ]
        ] as $class => $methods) {
            $instance = $this->getClassInstance($class);
            foreach ($methods as $method) {
                $this->assertTrue(method_exists($instance, $method), 'Method ' . $method . ' doesn\'t exists for class ' . $class);
            }
        }
    }

    /**
     * Tests variables availability and types
     */
    public function testVariables() {
        foreach ([
            'icms_preload_LibrariesHandler' => [
                '_librariesArray' => 'assertIsArray'
            ]
        ] as $class => $variables) {
            $instance = $this->getClassInstance($class);
            foreach ($variables as $variable => $func) {
                $this->$func($instance->$variable, '$' . $variable . ' is not of correct type');
            }
        }
    }

}