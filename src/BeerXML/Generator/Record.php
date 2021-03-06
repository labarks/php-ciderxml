<?php


namespace BeerXML\Generator;


use BeerXML\Record\Yeast;

abstract class Record
{
    /**
     * @var \XMLWriter
     */
    protected $xmlWriter;

    protected $record;

    /**
     * @var string
     */
    protected $tagName;

    /**
     * <TAG> => getterMethod()
     *
     * @var array
     */
    protected $simpleValues = array();

    /**
     * <TAG> => getterMethod()
     *
     * @var array
     */
    protected $optionalSimpleValues = array();

    protected $complexValues = array();

    /**
     * <TAG> => array('generator' => 'BeerXML\Generator\Class', 'values' => 'getRecords')
     *
     * @var array
     */
    protected $complexValueSets = array();

    /**
     * The interface for the optional display fields in Appendix A of the spec
     *
     * @var string
     */
    protected $displayInterface;

    /**
     * <TAG> => getterMethod()
     *
     * For the optional tags from the display fields in Appendix A of the spec
     *
     * @var array
     */
    protected $displayValues = array();

    /**
     * @param \XMLWriter $xmlWriter
     */
    public function setXmlWriter($xmlWriter)
    {
        $this->xmlWriter = $xmlWriter;
    }

    /**
     * @param IHop|IFermentable|IEquipment|IYeast|IMisc|IWater|IStyle|IMashStep|IMashProfile|IRecipe $record
     */
    public function setRecord($record)
    {
        $this->record = $record;
    }

    /**
     * Construct the record in XMLWriter
     *
     * @return null
     */
    public function build()
    {
        $this->xmlWriter->startElement($this->tagName);
        // Simple required elements
        foreach ($this->simpleValues as $tag => $method) {
            $this->xmlWriter->writeElement($tag, $this->record->{$method}());
        }

        foreach ($this->optionalSimpleValues as $tag => $method) {
            $value = $this->record->{$method}();
            if (!empty($value)) {
                $this->xmlWriter->writeElement($tag, $value);
            }
        }

        foreach ($this->complexValues as $generatorClass => $method) {
            $value = $this->record->{$method}();
            if ($value) {
                /** @var Record $generator */
                $generator = new $generatorClass();
                $generator->setXmlWriter($this->xmlWriter);
                $generator->setRecord($value);
                $generator->build();
            }
        }

        foreach ($this->complexValueSets as $tag => $complex) {
            $this->xmlWriter->startElement($tag);
            $method         = $complex['values']; // getter method, should return an array
            $generatorClass = $complex['generator']; // Should be a subclass of BeerXML\Generator\Record

            $values    = $this->record->{$method}();
            $generator = new $generatorClass();
            $generator->setXmlWriter($this->xmlWriter);
            foreach ($values as $record) {
                $generator->setRecord($record);
                $generator->build();
            }
            $this->xmlWriter->endElement();
        }

        // If the given record implements the interface for the display fields, write those too
        if ($this->record instanceof $this->displayInterface) {
            foreach ($this->displayValues as $tag => $method) {
                $value = $this->record->{$method}();
                if (!empty($value)) {
                    $this->xmlWriter->writeElement($tag, $value);
                }
            }
        }

        // Call the parser to allow it to add any other weird fields
        $this->additionalFields();

        $this->xmlWriter->endElement();
    }

    /**
     * Converts a boolean to the string format expected by BeerXML
     *
     * @param boolean $bool
     * @return string 'TRUE' or 'FALSE'
     */
    protected function boolToString($bool)
    {
        return ($bool) ? 'TRUE' : 'FALSE';
    }

    /**
     * Runs before closing out the build sequence, to add fields that require logic
     */
    protected function additionalFields()
    {

    }
}