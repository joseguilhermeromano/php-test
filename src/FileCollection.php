<?php

namespace Live\Collection;

/**
 * File Collection
 *
 * @package Live\Collection
 */
class FileCollection implements CollectionInterface
{
    /**
     * file output path
     *
     * @var string
     */
    protected static $path = "src/out_files/";
    protected static $extAlloweds = array('json');
    private $fileName;
    private $ext;
    private $fileContents;

    /**
     * Constructor
     */
    public function __construct(?string $fileName = "data.json")
    {
        $this->fileName = self::$path.$fileName;

        if (!$this->isFileAllowed()) {
            throw new \Exception("{$this->ext} is not a allowed extension.");
        }

        if (!file_exists($this->fileName)) {
            $path = self::$path;

            if (!is_dir($path)) {
                mkdir($path, 0755);
            }
            
            $newFile = fopen($this->fileName, "w+");
            fclose($newFile);
        }

        if (empty($this->fileContents)) {
            $this->setFileContents();
        }
    }

    /**
     * {@inheritDoc}
     */
    public function get(string $index, $defaultValue = null)
    {
        if (empty($this->fileContents)) {
            $this->setFileContents();
        }

        return $this->fileContents->get($index, $defaultValue);
    }

    /**
     * {@inheritDoc}
     */
    public function set(string $index, $value)
    {
        $this->fileContents->set($index, $value);
        $collection = $this->fileContents->getAll();
        $json = json_encode($collection, JSON_PRETTY_PRINT);

        if (!$this->isJsonValid($json)) {
            throw new \Exception("The content of the file '{$fileName}' is invalid.");
        }

        file_put_contents($this->fileName, $json);
    }

    /**
     * {@inheritDoc}
     */
    public function has(string $index)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function count():int
    {
    }

    /**
     * {@inheritDoc}
     */
    public function clean()
    {
    }

    public function isFileAllowed()
    {
        $this->ext = pathinfo($this->fileName, PATHINFO_EXTENSION);

        if (!in_array($this->ext, self::$extAlloweds)) {
            return false;
        }

        return true;
    }

    public function isJsonValid($string)
    {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }

    public function setFileContents()
    {

        $collection = new MemoryCollection();
        
        $content = file_get_contents($this->fileName);

        if (!empty($content)) {
            if (!$this->isJsonValid($content)) {
                $fileName = basename($this->fileName);
                throw new \Exception("The content of the file '{$fileName}' is invalid.");
            }

            $content = json_decode($content, true);

            foreach ($content as $key => $value) {
                $collection->set($key, $value);
            }
        }

        $this->fileContents = $collection;
    }

    public function __destruct()
    {
        if (file_exists($this->fileName)) {
            unlink($this->fileName);
        }
    }
}
