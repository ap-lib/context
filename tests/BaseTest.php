<?php declare(strict_types=1);

namespace AP\Normalizer\Tests;

use AP\Context\Context;
use PHPUnit\Framework\TestCase;
use UnexpectedValueException;

class User
{
    public function __construct(
        public int    $id,
        public string $email,
    )
    {
    }
}

final class BaseTest extends TestCase
{
    public function testSingleObject(): void
    {
        $user = new User(12, "name@gmail.com");

        // single object
        $context = new Context();
        $context->set($user);

        // retrieve mixed value by name
        $this->assertEquals(
            $user,
            $context->get(User::class),
        );

        // retrieve with type enforcement
        $this->assertEquals(
            $user,
            $context->getObject(User::class),
        );
    }

    public function testManySimilarObjects(): void
    {
        $realUserStorageName = "realUser";

        $user     = new User(12, "name@gmail.com");
        $realUser = new User(1, "adimn@gmail.com");

        $context = new Context();
        $context->set($user);
        $context->set($realUser, $realUserStorageName);

        // retrieve user by name
        $this->assertEquals(
            $user,
            $context->get(User::class),
        );

        // retrieve user with type enforcement
        $this->assertEquals(
            $user,
            $context->getObject(User::class),
        );

        // retrieve real user by name
        $this->assertEquals(
            $realUser,
            $context->get($realUserStorageName),
        );

        // retrieve real user with type enforcement
        $this->assertEquals(
            $realUser,
            $context->getObject(User::class, $realUserStorageName),
        );
    }

    public function testCustomData(): void
    {
        $userStorageName = "user";

        $user = ["id" => 12, "email" => "name@gmail.com"];

        $context = new Context();
        $context->set($user, $userStorageName);

        $this->assertEquals(
            $user,
            $context->get($userStorageName),
        );
    }

    public function testErrorType(): void
    {
        $this->expectException(UnexpectedValueException::class);

        $context = new Context();

        // save just an array by name User::class
        $context->set(["id" => 12, "email" => "name@gmail.com"], User::class);

        // try to get object User
        $context->getObject(User::class);
    }

    public function testErrorNotFountObject(): void
    {
        $this->expectException(UnexpectedValueException::class);

        $context = new Context();

        // not found user
        $context->getObject(User::class);
    }

    public function testErrorNotFountMixed(): void
    {
        $this->expectException(UnexpectedValueException::class);

        $context = new Context();

        // not found mixed data
        $context->get("randomName");
    }
}
