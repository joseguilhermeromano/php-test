<?php

namespace Live\Collection;

use PHPUnit\Framework\TestCase;

class ExceptionsFileCollectionTest extends TestCase
{
    public function testErrorExtensionNotAllowed()
    {
        $this->expectException(\Exception::class);
        $file = new FileCollection("data.csv");
        $this->expectExceptionMessage('csv is not a allowed extension.');
    }

    public function testErrorInvalidJsonContentString()
    {
        $jsonData = '{"test:123}';
        $path = "src/out_files/";
        $fileName= "invalid.json";

        if (!is_dir($path)) {
            mkdir($path);
        }
        
        file_put_contents($path.$fileName, $jsonData);

        $this->expectException(\Exception::class);
        
        $file = new FileCollection($fileName);
        $this->expectExceptionMessage('The content of the file data.json is invalid.');
    }

    public static function tearDownAfterClass()
    {
        $file = "src/out_files/invalid.json";
        unlink($file);
    }
}
