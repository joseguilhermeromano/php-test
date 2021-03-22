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

    /**
     * Array of extensions permitted
     *
     * @var array
     */
    protected static $extAlloweds = array('json');

    /**
     * Full path with name and extension of file
     *
     * @var string
     */
    private $fileName;

    /**
     * Extension file
     *
     * @var string
     */
    private $ext;

    /**
     * collection data of data.json file
     *
     * @var MemoryCollection
     */
    private $fileContents;

    /**
     * Constructor
     *
     * @param mixed $fileName
     * @return void
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
        return $this->fileContents->get($index, $defaultValue);
    }

    /**
     * {@inheritDoc}
     */
    public function set(string $index, $value, $expirationTime = 0)
    {
        $this->fileContents->set($index, $value, $expirationTime);
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
        return $this->fileContents->has($index);
    }

    /**
     * {@inheritDoc}
     */
    public function count():int
    {
        return $this->fileContents->count();
    }

    /**
     * {@inheritDoc}
     */
    public function clean()
    {
        file_put_contents($this->fileName, "");

        $this->fileContents->clean();
    }

    /**
     * Check if file extension is allowed
     *
     * @return bool
     */
    public function isFileAllowed():bool
    {
        $this->ext = pathinfo($this->fileName, PATHINFO_EXTENSION);

        if (!in_array($this->ext, self::$extAlloweds)) {
            return false;
        }

        return true;
    }

    /**
     * Check the format valid of Json
     *
     * @param string
     * @return bool
     */
    public function isJsonValid(string $string):bool
    {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }

    /**
     * Set contents in object of type MemoryCollection
     *
     * @return void
     */
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

            foreach ($content as $key => $values) {
                $collection->set($key, $values["value"]);
            }
        }

        $this->fileContents = $collection;
    }
}
