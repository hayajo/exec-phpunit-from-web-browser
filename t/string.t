<?php

require_once join(DIRECTORY_SEPARATOR, Array(__DIR__, '..', 'lib', 'string.php'));

class StringTest extends PHPUnit_Framework_TestCase
{
    var $abc;

    protected function setUp() {
        $this->abc = new String("abc");
    }

    protected function tearDown() {
        unset($this->abc);
    }

    public function testToString() {
        $result = $this->abc->toString("contains %s");
        $expected = "contains abc";
        $this->assertTrue($result == $expected);
    }

    public function testCopy() {
        $abc2 = $this->abc->copy();
        $this->assertEquals($abc2, $this->abc);
    }

    public function testAdd() {
        $abc2 = new String("123");
        $this->abc->add($abc2);
        $result = $this->abc->toString("%s");
        $expected = "abc123";
        $this->assertTrue($result == $expected);
    }
}

?>
