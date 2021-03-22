<?php

namespace Live\Collection;

use PHPUnit\Framework\TestCase;

class FileCollectionTest extends TestCase
{
    /**
     * @test
     * @doesNotPerformAssertions
     */
    public function objectCanBeConstructed()
    {
        $fileCollection = new FileCollection();
        return $fileCollection;
    }

    /**
     * @test
     * @depends objectCanBeConstructed
     * @doesNotPerformAssertions
     */
    public function dataCanBeAdded()
    {
        $fileCollection = new FileCollection();
        $fileCollection->set('index1', 'value');
        $fileCollection->set('index2', 5);
        $fileCollection->set('index3', true);
        $fileCollection->set('index4', 6.5);
        $fileCollection->set('index5', ['data']);
    }

    /**
     * @test
     * @depends dataCanBeAdded
     */
    public function dataCanBeRetrieved()
    {
        $fileCollection = new FileCollection();
        $fileCollection->set('index1', 'value');

        $this->assertEquals('value', $fileCollection->get('index1'));
    }

    /**
     * @test
     * @depends objectCanBeConstructed
     */
    public function inexistentIndexShouldReturnDefaultValue()
    {
        $fileCollection = new FileCollection();
        $fileCollection->clean();
        $this->assertNull($fileCollection->get('index1'));
        $this->assertEquals('defaultValue', $fileCollection->get('index1', 'defaultValue'));
    }

    /**
     * @test
     * @depends objectCanBeConstructed
     */
    public function newCollectionShouldNotContainItems()
    {
        $fileCollection = new FileCollection();
        $fileCollection->clean();
        $this->assertEquals(0, $fileCollection->count());
    }

    /**
     * @test
     * @depends dataCanBeAdded
     */
    public function collectionWithItemsShouldReturnValidCount()
    {
        $fileCollection = new FileCollection();
        $fileCollection->clean();
        $fileCollection->set('index1', 'value');
        $fileCollection->set('index2', 5);
        $fileCollection->set('index3', true);

        $this->assertEquals(3, $fileCollection->count());
    }

    /**
     * @test
     * @depends collectionWithItemsShouldReturnValidCount
     */
    public function collectionCanBeCleaned()
    {
        $fileCollection = new FileCollection();
        $fileCollection->clean();
        $fileCollection->set('index', 'value');
        $this->assertEquals(1, $fileCollection->count());

        $fileCollection->clean();
        $this->assertEquals(0, $fileCollection->count());
    }

    /**
     * @test
     * @depends dataCanBeAdded
     */
    public function addedItemShouldExistInCollection()
    {
        $fileCollection = new FileCollection();
        $fileCollection->set('index', 'value');

        $this->assertTrue($fileCollection->has('index'));
    }
}
