<?php

namespace DesignPatterns\Behavioral;

/**
 * Template Method Design Pattern
 *
 * Defines the skeleton of an algorithm and allow subclasses to redefine certain steps of the algorithm
 * without changing its structure (call sequence).
 */

/**
 * Abstract class which defines the template method convert() and declares all its steps
 */
abstract class AbstractFileConverter
{
    /**
     * Template method
     */
    final public function convert()
    {
        $this->beforeSteps();
        $this->openFile();
        $this->validate();
        $this->makeConversion();
        $this->closeFile();
        $this->afterSteps();
    }

    /**
     * Default implementations of some steps
     */
    protected function openFile()
    {
        echo "Step1. Read from file..\n";
    }

    protected function closeFile()
    {
        echo "Step4. Close file descriptor..\n";

    }

    /**
     * These steps have to be implemented in subclasses
     */
    abstract protected function validate();

    abstract protected function makeConversion();

    /**
     * Optional methods provide additional extension points
     */
    protected function beforeSteps()
    {
    }

    protected function afterSteps()
    {
    }
}

/**
 * Concrete class implements all abstract operations of the abstract class.
 * They also overrides some operations with a default implementation
 */
class PDFFileConverter extends AbstractFileConverter
{
    protected function validate()
    {
        echo "Step2. Validate PDF file..\n";
    }

    protected function makeConversion()
    {
        echo "Step3. Convert PDF file..\n";
    }
}

/**
 * Another concrete class with self implementation of some steps
 */
class CSVFileConverter extends AbstractFileConverter
{
    protected function validate()
    {
        echo "Step2. Validate CSV file..\n";
    }

    protected function makeConversion()
    {
        echo "Step3. Convert CSV file..\n";
    }
}

# Client code example
(new PDFFileConverter())->convert();

/* Output:
Step1. Read from file..
Step2. Validate PDF file..
Step3. Convert PDF file..
Step4. Close a file descriptor.. */