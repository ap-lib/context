<?php declare(strict_types=1);

namespace AP\Context\Tests;

use AP\Context\Context;
use PHPUnit\Framework\TestCase;

final class ArrayRefTest extends TestCase
{
    public function testArrayCopy(): void
    {
        $name = "original";
        $original = ["foo" => "boo"];

        $context = new Context();
        $context->set($original, $name);

        // copy array
        $copy = $context->get($name);
        $copy["foo"] = "changed";

        $this->assertEquals(
            ["foo" => "boo"],
            $context->get($name)
        );
    }

    public function testArrayRef(): void
    {
        $name = "original";
        $original = ["foo" => "boo"];

        $context = new Context();
        $context->set($original, $name);

        // ref to array, stored array will be changed on any changes on got array
        $ref = &$context->get($name);
        $ref["foo"] = "changed";

        $this->assertEquals(
            ["foo" => "changed"],
            $context->get($name)
        );
    }

    public function testArrayRefReset(): void
    {
        $name = "original";
        $original = ["foo" => "boo"];

        $context = new Context();
        $context->set($original, $name);

        // with ref to array you can totaly change a full array
        $ref = &$context->get($name);
        $ref = ["hello" => "world"];

        $this->assertEquals(
            ["hello" => "world"],
            $context->get($name)
        );
    }
}
