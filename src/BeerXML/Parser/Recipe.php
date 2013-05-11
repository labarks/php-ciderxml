<?php


namespace BeerXML\Parser;


use BeerXML\Exception\BadData;

class Recipe extends Record
{
    protected $tagName = 'RECIPE';

    /**
     * Tags that map to simple values and the corresponding setter method on the record class
     *
     * @var array
     */
    protected $simpleProperties = array(
        'AGE'                 => 'setAge',
        'AGE_TEMP'            => 'setAgeTemp',
        'ASST_BREWER'         => 'setAsstBrewer',
        'BATCH_SIZE'          => 'setBatchSize',
        'BOIL_SIZE'           => 'setBoilSize',
        'BOIL_TIME'           => 'setBoilTime',
        'BREWER'              => 'setBrewer',
        'CARBONATION'         => 'setCarbonation',
        'CARBONATION_TEMP'    => 'setCarbonationTemp',
        'DATE'                => 'setDate',
        'EFFICIENCY'          => 'setEfficiency',
        'FERMENTATION_STAGES' => 'setFermentationStages',
        'FG'                  => 'setFg',
        'KEG_PRIMING_FACTOR'  => 'setKegPrimingFactor',
        'NAME'                => 'setName',
        'NOTES'               => 'setNotes',
        'OG'                  => 'setOg',
        'PRIMARY_AGE'         => 'setPrimaryAge',
        'PRIMARY_TEMP'        => 'setPrimaryTemp',
        'PRIMING_SUGAR_EQUIV' => 'setPrimingSugarEquiv',
        'PRIMING_SUGAR_NAME'  => 'setPrimingSugarName',
        'SECONDARY_AGE'       => 'setSecondaryAge',
        'SECONDARY_TEMP'      => 'setSecondaryTemp',
        'TASTE_NOTES'         => 'setTasteNotes',
        'TASTE_RATING'        => 'setTasteRating',
        'TERTIARY_AGE'        => 'setTertiaryAge',
        'TERTIARY_TEMP'       => 'setTertiaryTemp',
        'TYPE'                => 'setType',
        'VERSION'             => 'setVersion',
    );

    /**
     * @return IRecipeWriter
     */
    protected function createRecord()
    {
        return $this->recordFactory->getRecipe();
    }

    /**
     * @param IRecipeWriter $record
     */
    protected function otherElementEncountered($record)
    {
        if ('FORCED_CARBONATION' == $this->xmlReader->name) {
            $value = ($this->xmlReader->readString() == 'TRUE');
            $record->setForcedCarbonation($value);
        }
    }
}
