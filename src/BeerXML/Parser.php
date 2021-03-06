<?php


namespace BeerXML;


use BeerXML\Generator\IEquipment;
use BeerXML\Generator\IFermentable;
use BeerXML\Generator\IHop;
use BeerXML\Generator\IMashProfile;
use BeerXML\Generator\IMisc;
use BeerXML\Generator\IRecipe;
use BeerXML\Generator\IStyle;
use BeerXML\Generator\IWater;
use BeerXML\Generator\IYeast;
use BeerXML\Parser\RecordFactory;
use BeerXML\Parser\Record;

/**
 * Parses BeerXML 1.0 files into PHP record classes
 *
 * <code>
 * <?php
 * $parser = new \BeerXML\Parser();
 * $result = $parser->parse(file_get_contents('http://www.beerxml.com/recipes.xml'));
 * foreach ($result as $recipe) {
 *     echo "Found beer recipe " . $recipe->getName() . "\n";
 * }
 * </code>
 *
 * @package BeerXML
 */
class Parser
{
    /**
     * @var \XMLReader
     */
    private $xmlReader;

    /**
     * @var RecordFactory
     */
    private $recordFactory;

    /**
     * @var Parser\Record
     */
    private $recipeParser;

    private $tagParsers = array(
        'RECIPE'      => '\BeerXML\Parser\Recipe',
        'HOP'         => '\BeerXML\Parser\Hop',
        'FERMENTABLE' => '\BeerXML\Parser\Fermentable',
        'YEAST'       => '\BeerXML\Parser\Yeast',
        'MISC'        => '\BeerXML\Parser\Misc',
        'WATER'       => '\BeerXML\Parser\Water',
        'EQUIPMENT'   => '\BeerXML\Parser\Equipment',
        'STYLE'       => '\BeerXML\Parser\Style',
        'MASH_STEP'   => '\BeerXML\Parser\MashStep',
        'MASH'        => '\BeerXML\Parser\Mash'
    );

    /**
     * @param RecordFactory $factory
     */
    public function __construct(RecordFactory $factory = null)
    {
        $this->xmlReader = new \XMLReader();
        if (is_null($factory)) {
            $factory = new RecordFactory();
        }
        $this->recordFactory = $factory;
    }

    /**
     * Parse a beer XML, returning an array of the record objects found
     *
     * @param string $xml
     * @return IRecipe[]|IEquipment[]|IFermentable[]|IHop[]|IMashProfile[]|IMisc[]|IStyle[]|IWater[]|IYeast[]
     */
    public function parse($xml)
    {
        $this->xmlReader->XML($xml);
        $records = array();

        while ($this->xmlReader->read()) {
            // Find records
            if ($this->xmlReader->nodeType == \XMLReader::ELEMENT && isset($this->tagParsers[$this->xmlReader->name])) {
                $recordParser = new $this->tagParsers[$this->xmlReader->name];
                /** @var $recordParser Record */
                $recordParser->setXmlReader($this->xmlReader);
                $recordParser->setRecordFactory($this->recordFactory);
                $records[] = $recordParser->parse();
            }
        }

        $this->xmlReader->close();

        return $records;
    }

}
