<?php

namespace Live\Collection;

use PHPUnit\Framework\TestCase;

class MemoryCollectionTest extends TestCase
{
    /**
     * @test
     * @doesNotPerformAssertions
     */
    public function objectCanBeConstructed()
    {
        $collection = new MemoryCollection();
        return $collection;
    }

    /**
     * @test
     * @depends objectCanBeConstructed
     * @doesNotPerformAssertions
     */
    public function dataCanBeAdded()
    {
        $collection = new MemoryCollection();
        $collection->set('index1', 'value');
        $collection->set('index2', 5);
        $collection->set('index3', true);
        $collection->set('index4', 6.5);
        $collection->set('index5', ['data']);
    }

     /**
     * @test
     * @depends dataCanBeAdded
     */
    public function dataCanBeRetrieved()
    {
        $collection = new MemoryCollection();
        $collection->set('index1', 'value');

        $this->assertEquals('value', $collection->get('index1'));
    }

    /**
     * @test
     * @depends objectCanBeConstructed
     */
    public function inexistentIndexShouldReturnDefaultValue()
    {
        $collection = new MemoryCollection();

        $this->assertNull($collection->get('index1'));
        $this->assertEquals('defaultValue', $collection->get('index1', 'defaultValue'));
    }

    /**
     * @test
     * @depends objectCanBeConstructed
     */
    public function newCollectionShouldNotContainItems()
    {
        $collection = new MemoryCollection();
        $this->assertEquals(0, $collection->count());
    }

    /**
     * @test
     * @depends dataCanBeAdded
     */
    public function collectionWithItemsShouldReturnValidCount()
    {
        $collection = new MemoryCollection();
        $collection->set('index1', 'value');
        $collection->set('index2', 5);
        $collection->set('index3', true);

        $this->assertEquals(3, $collection->count());
    }

    /**
     * @test
     * @depends collectionWithItemsShouldReturnValidCount
     */
    public function collectionCanBeCleaned()
    {
        $collection = new MemoryCollection();
        $collection->set('index', 'value');
        $this->assertEquals(1, $collection->count());

        $collection->clean();
        $this->assertEquals(0, $collection->count());
    }

    /**
     * @test
     * @depends dataCanBeAdded
     */
    public function addedItemShouldExistInCollection()
    {
        $collection = new MemoryCollection();
        $collection->set('index', 'value');

        $this->assertTrue($collection->has('index'));
    }

    /**
     * @test
     * @depends dataCanBeAdded
     */
    public function dataCanBeRetrievedWithGetAll()
    {
        $collection = new MemoryCollection();
        $collection->set('index1', 'value');
        $collection->set('index2', 5);
        $collection->set('index3', true);

        $array = $collection->getAll();

        $this->assertEquals($array['index1']['value'], 'value');
        $this->assertEquals($array['index2']['value'], 5);
        $this->assertEquals($array['index3']['value'], true);
    }

    /**
     * @test
     * @depends objectCanBeConstructed
     */
    public function isExpiredTimeCanBeReturnTrue()
    {
        $collection = new MemoryCollection();
        sleep(10);
        $return = $collection->isExpiredTime(5);
        $this->assertTrue($return);
    }

    /**
     * @test
     * @depends objectCanBeConstructed
     */
    public function isExpiredTimeCanBeReturnFalse()
    {
        $collection = new MemoryCollection();
        sleep(5);
        $return = $collection->isExpiredTime(10);
        $this->assertFalse($return);
    }

    /**
     * @test
     * @depends objectCanBeConstructed
     */
    public function expiredIndexShouldReturnNull()
    {
        $collection = new MemoryCollection();
        $collection->set('index1', 'value', 5);
        sleep(10);
        $return = $collection->get('index1');
        $this->assertNull($return);
    }
}
