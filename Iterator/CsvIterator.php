<?php

namespace DesignPatterns\Behavioral;

/**
 * Allows iterate over CSV file rows
 */
class CsvIterator implements \Iterator
{
    /**
     * File descriptor
     * @var resource
     */
    protected $file = null;

    /**
     * The current row, which is returned on each iteration
     * @var array
     */
    protected $currentRow = null;

    /**
     * The row counter
     * @var int
     */
    protected $rowCounter = null;

    /**
     * The delimiter for the CSV file, e.g. ',', ';'
     * @var string
     */
    protected $delimiter = null;

    /**
     * Tries to open the CSV file
     * @param string $filePath Path to the input CSV file
     * @param string $delimiter The delimiter like ',' or ';'
     * @throws \Exception
     */
    public function __construct(string $filePath, string $delimiter = ';')
    {
        if (!is_readable($filePath)) {
            throw new \Exception("The file cannot be read");
        }

        $this->file = fopen($filePath, 'rb');
        $this->delimiter = $delimiter;
    }

    /**
     * Resets the file pointer
     */
    public function rewind()
    {
        rewind($this->file);
        $this->rowCounter = 0;
    }

    /**
     * Returns the current CSV row
     * @return array The current CSV row as a 2-dimensional array
     */
    public function current()
    {
        $this->currentRow = fgetcsv($this->file, 4096, $this->delimiter);
        $this->rowCounter++;

        return $this->currentRow;
    }

    /**
     * Returns the current row number
     * @return int The current row number
     */
    public function key()
    {
        return $this->rowCounter;
    }

    /**
     * Checks if the end of file has been reached
     * @return boolean Returns true on EOF reached, false otherwise
     */
    public function next()
    {
        if (is_resource($this->file)) {
            return !feof($this->file);
        }

        return false;
    }

    /**
     * This method checks if the next row is a valid row
     * @return boolean If the next row is a valid row
     */
    public function valid()
    {
        if (!$this->next()) {
            if (is_resource($this->file)) {
                fclose($this->file);
            }

            return false;
        }

        return true;
    }
}


# Client code example
try {
    $csv = new CsvIterator(__DIR__ . '/example.csv');
    foreach ($csv as $key => $row) {
        echo $key . ':' . implode(', ', $row) . PHP_EOL;
    }
} catch (\Exception $e) {
    echo $e->getMessage();
}

/* Output:
1:Name, Value, isActive
2:First, 10, true
3:Second, 20, false
4:Third, 30, true */