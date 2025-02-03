# AP\Context

[![MIT License](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE)

The Context library provides a lightweight context storage system for managing and retrieving shared data across different parts of an application. It supports dynamic storage, object retrieval, and type enforcement.

## Installation

```bash
composer require ap-lib/normalizer
```

## Features

- Store and retrieve arbitrary values using string keys.
- Store objects and retrieve them with type enforcement.
- Prevent accidental overwrites with an optional `replace` flag.
- Retrieve stored values by name or class.
- Exception handling for invalid operations.

## Requirements

- PHP 8.3 or higher

## Getting started

### Storing and Retrieving a Single Object
If you store an object without a name, its class name is automatically used as the key:

```php
use AP\Context\Context;

class User {
    public function __construct(public int $id, public string $email) {}
}

$context = new Context();

$user = new User(12, "name@gmail.com");

// Store the object using its class name
$context->set($user);

// Retrieve the object by class name
$retrievedUser = $context->get(User::class);
var_dump($retrievedUser === $user); // true

// Retrieve with type enforcement
$retrievedUser = $context->getObject(User::class);
var_dump($retrievedUser === $user); // true
```
### Storing and Retrieving Multiple Similar Objects
You can store multiple objects of the same class using custom names:

```php
$context = new Context();

$user = new User(12, "name@gmail.com");
$realUser = new User(1, "admin@gmail.com");

$context->set($user);                     // Stored with class name
$context->set($realUser, "realUser");      // Stored with custom name

// Retrieve the default user
$retrievedUser = $context->get(User::class);
var_dump($retrievedUser === $user); // true

// Retrieve with type enforcement
$retrievedUser = $context->getObject(User::class);
var_dump($retrievedUser === $user); // true

// Retrieve the real user by custom name
$retrievedRealUser = $context->get("realUser");
var_dump($retrievedRealUser === $realUser); // true

// Retrieve the real user with type enforcement
$retrievedRealUser = $context->getObject(User::class, "realUser");
var_dump($retrievedRealUser === $realUser); // true
```

### Storing and Retrieving Custom Data
You can store and retrieve non-object values, such as arrays, strings, or numbers, using a custom name:

```php
$context = new Context();

$userData = ["id" => 12, "email" => "name@gmail.com"];

// Store the array with a custom name
$context->set($userData, "user");

// Retrieve the stored array
$retrievedData = $context->get("user");
var_dump($retrievedData === $userData); // true
```

## Error Handling

### Type Enforcement Errors
If you try to retrieve an object with `getObject()`, but the stored data does not match the expected class, an exception is thrown:
```php
$context = new Context();

// Store an array using the class name
$context->set(["id" => 12, "email" => "name@gmail.com"], User::class);

// This will throw an UnexpectedValueException because the stored data is not a User object
$context->getObject(User::class);
```

### Handling Missing 
If you try to retrieve an object that has not been stored, an exception is thrown:
```php
$context = new Context();

// This will throw an UnexpectedValueException because no User object exists
$context->getObject(User::class);

// This will throw an UnexpectedValueException because "randomName" does not exist too
$context->get("randomName");
```
