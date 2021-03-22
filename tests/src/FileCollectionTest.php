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

    /**
     * @test
     * @depends objectCanBeConstructed
     */
    public function checkIfIsFileAllowedShouldReturnTrue()
    {
        $fileCollection = new FileCollection();
        $return = $fileCollection->isFileAllowed();
        $this->assertTrue($return);
    }

    /**
     * @test
     * @depends objectCanBeConstructed
     */
    public function checkIfIsFileAllowedShouldReturnException()
    {
        $this->expectException(\Exception::class);
        $fileCollection = new FileCollection("data.txt");
        $this->expectExceptionMessage('txt is not a allowed extension.');
    }

    /**
     * @test
     * @depends objectCanBeConstructed
     */
    public function checkIsJsonValidShouldReturnTrue()
    {
        $fileCollection = new FileCollection();
        $json = array(1, 2, 3);
        $return = $fileCollection->isJsonValid(json_encode($json));
        $this->assertTrue($return);
    }

    /**
     * @test
     * @depends objectCanBeConstructed
     */
    public function checkIsJsonValidShouldReturnFalse()
    {
        $fileCollection = new FileCollection();
        $json = '{"teste:{1,2,3}}';
        $return = $fileCollection->isJsonValid($json);
        $this->assertFalse($return);
    }

    /**
     * @test
     * @depends objectCanBeConstructed
     */
    public function checkSetFileContents()
    {
        $value1 = array("value" => "value1", "expirationTime" => 0);
        $value2 = array("value" => "value2", "expirationTime" => 0);
        $value3 = array("value" => "value3", "expirationTime" => 0);
        $data = array("index1" => $value1, "index2" => $value2, "index3" => $value3);
        $path = "src/out_files/";
        $fileName= "data.json";
        $jsonData = json_encode($data, JSON_PRETTY_PRINT);

        if (!is_dir($path)) {
            mkdir($path);
        }
        
        file_put_contents($path.$fileName, $jsonData);
        
        $fileCollection = new FileCollection($fileName);
        $this->assertEquals('value2', $fileCollection->get('index2'));
    }
}
