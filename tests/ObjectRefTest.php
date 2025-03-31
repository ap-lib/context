<?php declare(strict_types=1);

namespace AP\Context\Tests;

use AP\Context\Context;
use PHPUnit\Framework\TestCase;

class User2
{
    public function __construct(
        public int    $id,
        public string $email,
    )
    {
    }
}

final class ObjectRefTest extends TestCase
{
    public function testObjCopy(): void
    {
        $user = new User2(1, "a@b.com");

        $context = new Context();
        $context->set($user);

        // if you want to copy an object, do clone
        $copy     = clone $context->getObject(User2::class);
        $copy->id = 2;

        $this->assertEquals(
            new User2(1, "a@b.com"),
            $context->getObject(User2::class)
        );
    }

    public function testObjRef(): void
    {
        $user = new User2(1, "a@b.com");

        $context = new Context();
        $context->set($user);

        $ref     = $context->getObject(User2::class);
        $ref->id = 2;

        $this->assertEquals(
            new User2(2, "a@b.com"),
            $context->getObject(User2::class)
        );
    }


    public function testObjRefReset(): void
    {
        $user = new User2(1, "a@b.com");

        $context = new Context();
        $context->set($user);

        $ref = $context->getObject(User2::class);

        // it will no works, because you reset ref to object to a new link to new object
        $ref = new User2(111, 'new@gmail.com');

        $this->assertEquals(
            new User2(1, "a@b.com"),
            $context->getObject(User2::class)
        );
    }
}
