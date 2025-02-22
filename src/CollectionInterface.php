<?php

namespace Live\Collection;

/**
 * Collection interface
 *
 * @package Live\Collection
 */
interface CollectionInterface
{
    /**
     * Returns a value by index
     *
     * @param string $index
     * @param mixed $defaultValue
     * @return mixed
     */
    public function get(string $index, $defaultValue = null);

    /**
     * Adds a value to the collection
     *
     * @param string $index
     * @param mixed $value
     * @param int $expirationTime
     * @return void
     */
    public function set(string $index, $value, $expirationTime = 0);

    /**
     * Checks whether the collection has the given index
     *
     * @param string $index
     * @return boolean
     */
    public function has(string $index);

    /**
     * Returns the count of items in the collection
     *
     * @return integer
     */
    public function count(): int;

    /**
     * Cleans the collection
     *
     * @return void
     */
    public function clean();
}
