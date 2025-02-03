<?php declare(strict_types=1);

namespace AP\Context;

use UnexpectedValueException;

class Context
{
    protected array $hashmap = [];

    /**
     * Retrieves a reference to a stored value by its name
     *
     * This method allows accessing stored data within the context by its name
     * If the requested name doesn't exist, an exception is thrown
     *
     * @param string $name The name of the stored value.
     * @return mixed A reference to the stored value.
     * @throws UnexpectedValueException If the requested name doesn't exist in the context.
     */
    public function &get(string $name): mixed
    {
        if (!key_exists($name, $this->hashmap)) {
            throw new UnexpectedValueException("Context not found by name `$name`");
        }
        return $this->hashmap[$name];
    }

    /**
     * Retrieves a stored object by its class type.
     *
     * This method fetches an object from the context, ensuring it is an instance of the specified class.
     *
     * @template T
     * @param class-string<T> $class The expected class of the stored object
     * @param string|null $name Optional name of the stored object. If null, the class name is used
     * @return T The stored object instance
     * @throws UnexpectedValueException If the object doesn't exist or is not an instance of the expected class
     */
    public function getObject(string $class, ?string $name = null): mixed
    {
        $obj = $this->get(is_null($name) ? $class : $name);
        if (!($obj instanceof $class)) {
            throw new UnexpectedValueException("Context entry `$name` does not implement `$class`");
        }
        return $obj;
    }

    /**
     * Stores a value in the context, optionally using a custom name
     *
     * If no name is provided and the data is an object, class name of the object will be used as the key
     * If the `replace` flag is set to `false`, an exception is thrown if the name already exists
     *
     * @param mixed $data The value to store in the context
     * @param string|null $name Optional name for the stored value. If null and data is an object, its class name is used
     * @param bool $replace Whether to replace an existing value if the name already exists, default: true
     * @return $this
     * @throws UnexpectedValueException If the name isn't a string or an object, or if `replace` is false and the name already exists
     */
    public function set(mixed $data, ?string $name = null, bool $replace = true): static
    {
        if (is_null($name) && is_object($data)) {
            $name = $data::class;
        }
        if (!is_string($name)) {
            throw new UnexpectedValueException("Name must be a string if data is not an object");
        }
        if (!$replace && key_exists($name, $this->hashmap)) {
            throw new UnexpectedValueException("Element with name `$name` already exists in the context storage");
        }
        $this->hashmap[$name] = $data;

        return $this;
    }
}