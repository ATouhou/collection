<?php namespace Collection;

use Collection\Contracts\JsonableContract;
use Countable;
use IteratorAggregate, ArrayIterator;
use ArrayAccess;
use Closure;

class Collection implements JsonableContract, Countable, IteratorAggregate, ArrayAccess {

    /**
     * The items stored.
     *
     * @var array
     */
    protected $items = [];

    /**
     * The constructor.
     *
     * @param array $items
     * @return Collection
     */
    public function __construct(array $items = [])
    {
        $this->items = $items;
    }

    /**
     * Get all of the items.
     *
     * @return array
     */
    public function all()
    {
        return $this->items;
    }

    /**
     * Get an item by key.
     *
     * @param mixed $key
     * @param mixed $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        return array_key_exists($key, $this->items) ? $this->items[$key] : $default;
    }

    /**
     * Push a value onto the end.
     *
     * @param mixed $value
     * @return void
     */
    public function push($value)
    {
        $this->items[] = $value;
    }

    /**
     * Put a value by key.
     *
     * @param mixed $key
     * @param mixed $value
     * @return void
     */
    public function put($key, $value)
    {
        $this->items[$key] = $value;
    }

    /**
     * Determine whether an item exists by key.
     *
     * @param mixed $key
     * @return boolean
     */
    public function has($key)
    {
        return array_key_exists($key, $this->items);
    }

    /**
     * Flatten all items in the collection and return a new one.
     *
     * @return Collection
     */
    public function flatten()
    {
        $items = [];

        foreach ($this->items as $item)
        {
            if (is_array($item))
            {
                $items = array_merge($items, (new static($item))->flatten()->all());

                continue;
            }

            $items[] = $item;
        }

        return new static($items);
    }

    /**
     * Reverse all items and wrap them into a Collection instance.
     *
     * @return Collection
     */
    public function reverse()
    {
        return new static(array_reverse($this->items));
    }

    /**
     * Run iterator over each of the items.
     *
     * @param Closure $iterator
     * @return Collection
     */
    public function map(Closure $iterator)
    {
        return new static(array_map($iterator, $this->items));
    }

    /**
     * Transform the items using iterator.
     *
     * @param Closure $iterator
     * @return void
     */
    public function transform(Closure $iterator)
    {
        $this->items = array_map($iterator, $this->items);
    }

    /**
     * Get only unique items.
     *
     * @return Collection
     */
    public function unique()
    {
        return new static(array_unique($this->items));
    }

    /**
     * Remove an item by key.
     *
     * @param mixed $key
     * @return void
     */
    public function remove($key)
    {
        unset ($this->items[$key]);
    }

    /**
     * Reset the keys.
     *
     * @return void
     */
    public function values()
    {
        $this->items = array_values($this->items);
    }

    /**
     * Get and remove the last item.
     *
     * @return mixed|null
     */
    public function pop()
    {
        return array_pop($this->items);
    }

    /**
     * Determine if the collection has no items.
     *
     * @return boolean
     */
    public function isEmpty()
    {
        return count($this->items) == 0;
    }

    /**
     * Get an array with the values of a key.
     *
     * @return array
     */
    public function pluck($key)
    {
        $items = [];

        foreach ($this->items as $item)
        {
            $items[] = $item[$key];
        }

        return $items;
    }

    /**
     * Count the number of items.
     *
     * @return integer
     */
    public function count()
    {
        return count($this->items);
    }

    /**
     * Get an iterator for the items.
     *
     * @return ArrayIterator
     */
    public function getIterator()
    {
        return new ArrayIterator($this->items);
    }

    /**
     * Unset the item at a given offset.
     *
     * @param mixed $offset
     * @return void
     */
    public function offsetUnset($offset)
    {
        unset ($this->items[$offset]);
    }

    /**
     * Determine whether an item exists at a given offset.
     *
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->items);
    }

    /**
     * Set the item at a given offset.
     *
     * @param mixed $offset
     * @param mixed $value
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        if (is_null($offset))
        {
            $this->items[] = $value;

            return null;
        }

        $this->items[$offset] = $value;
    }

    /**
     * Get an item at a given offset.
     *
     * @param mixed $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->items[$offset];
    }

    /**
     * Convert the object into a serializable structure.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->items;
    }

    /**
     * Get the collection as JSON.
     *
     * @param int $options
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->items, $options);
    }

    /**
     * Convert the object into a string.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toJson();
    }

}
