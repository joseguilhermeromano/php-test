<?php

namespace Live\Collection;

/**
 * Memory collection
 *
 * @package Live\Collection
 */
class MemoryCollection implements CollectionInterface
{
    /**
     * Collection data
     *
     * @var array
     */
    protected $data;

    /**
     * Control Time expiration
     *
     * @var string
     */
    protected $timestamp;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->data = [];
        $this->timestamp = strtotime(date('Y-m-d H:i:s'));
    }

    /**
     * {@inheritDoc}
     */
    public function get(string $index, $defaultValue = null)
    {

        if (!$this->has($index)) {
            return $defaultValue;
        } else {
            $expirationTime = $this->data[$index]["expirationTime"];

            if ($this->isExpiredTime($expirationTime)) {
                return;
            }
        }

        return $this->data[$index]["value"];
    }

    /**
     * {@inheritDoc}
     */
    public function set(string $index, $value, $expirationTime = 0)
    {
        $this->data[$index] = array(  "value" => $value
                                    , "expirationTime" => $expirationTime);
    }

    /**
     * {@inheritDoc}
     */
    public function has(string $index)
    {
        return array_key_exists($index, $this->data);
    }

    /**
     * {@inheritDoc}
     */
    public function count(): int
    {
        return count($this->data);
    }

    /**
     * {@inheritDoc}
     */
    public function clean()
    {
        $this->data = [];
    }

    /**
     * Get Array all elements of collection
     *
     * @return @void
     */
    public function getAll()
    {
        return $this->data;
    }

    /**
     * Verify if index of collection is expired
     *
     * @param int $expirationTime
     * @return boolean
     */
    public function isExpiredTime(int $expirationTime):bool
    {
        if ($expirationTime == 0) {
            return false;
        }

        $differenceInSeconds = strtotime(date('Y-m-d H:i:s')) - $this->timestamp;
        return  $differenceInSeconds <= $expirationTime ? false : true;
    }
}
