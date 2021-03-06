<?php


namespace BeerXML\Generator;


class Recipe extends Record
{
    protected $tagName = 'RECIPE';

    /**
     * @var \BeerXML\Record\Recipe
     */
    protected $record;

    /**
     * <TAG> => getterMethod
     *
     * @var array
     */
    protected $simpleValues = array(
        'NAME'       => 'getName',
        'VERSION'    => 'getVersion',
        'TYPE'    => 'getType',
        'BREWER'     => 'getBrewer',
        'BATCH_SIZE' => 'getBatchSize',
        'BOIL_SIZE'  => 'getBoilSize',
        'BOIL_TIME'  => 'getBoilTime',
    );

    /**
     * <TAG> => getterMethod
     *
     * @var array
     */
    protected $optionalSimpleValues = array(
        'ASST_BREWER'         => 'getAsstBrewer',
        'NOTES'               => 'getNotes',
        'TASTE_NOTES'         => 'getTasteNotes',
        'TASTE_RATING'        => 'getTasteRating',
        'OG'                  => 'getOg',
        'FG'                  => 'getFg',
        'FERMENTATION_STAGES' => 'getFermentationStages',
        'PRIMARY_AGE'         => 'getPrimaryAge',
        'PRIMARY_TEMP'        => 'getPrimaryTemp',
        'SECONDARY_AGE'       => 'getSecondaryAge',
        'SECONDARY_TEMP'      => 'getSecondaryTemp',
        'TERTIARY_AGE'        => 'getTertiaryAge',
        'TERTIARY_TEMP'       => 'getTertiaryTemp',
        'AGE'                 => 'getAge',
        'AGE_TEMP'            => 'getAgeTemp',
        'CARBONATION'         => 'getCarbonation',
        'PRIMING_SUGAR_NAME'  => 'getPrimingSugarName',
        'CARBONATION_TEMP'    => 'getCarbonationTemp',
        'PRIMING_SUGAR_EQUIV' => 'getPrimingSugarEquiv',
        'KEG_PRIMING_FACTOR'  => 'getKegPrimingFactor',
    );

    protected $complexValues = array(
        'BeerXML\Generator\Style'       => 'getStyle',
        'BeerXML\Generator\MashProfile' => 'getMash',
    );

    protected $complexValueSets = array(
        'HOPS'         => array('generator' => 'BeerXML\Generator\Hop', 'values' => 'getHops'),
        'FERMENTABLES' => array('generator' => 'BeerXML\Generator\Fermentable', 'values' => 'getFermentables'),
        'MISCS'        => array('generator' => 'BeerXML\Generator\Misc', 'values' => 'getMiscs'),
        'YEASTS'       => array('generator' => 'BeerXML\Generator\Yeast', 'values' => 'getYeasts'),
        'WATERS'       => array('generator' => 'BeerXML\Generator\Water', 'values' => 'getWaters'),
    );

    protected $displayInterface = 'BeerXML\Generator\IRecipeDisplay';

    protected $displayValues = array(
        'EST_OG'                 => 'getEstOg',
        'EST_FG'                 => 'getEstFg',
        'EST_COLOR'              => 'getEstColor',
        'IBU'                    => 'getIbu',
        'IBU_METHOD'             => 'getIbuMethod',
        'EST_ABV'                => 'getEstAbv',
        'ABV'                    => 'getAbv',
        'ACTUAL_EFFICIENCY'      => 'getActualEfficiency',
        'CALORIES'               => 'getCalories',
        'DISPLAY_BATCH_SIZE'     => 'getDisplayBatchSize',
        'DISPLAY_BOIL_SIZE'      => 'getDisplayBoilSize',
        'DISPLAY_OG'             => 'getDisplayOg',
        'DISPLAY_FG'             => 'getDisplayFg',
        'DISPLAY_PRIMARY_TEMP'   => 'getDisplayPrimaryTemp',
        'DISPLAY_SECONDARY_TEMP' => 'getDisplaySecondaryTemp',
        'DISPLAY_TERTIARY_TEMP'  => 'getDisplayTertiaryTemp',
        'DISPLAY_AGE_TEMP'       => 'getDisplayAgeTemp',
        'CARBONATION_USED'       => 'getCarbonationUsed',
        'DISPLAY_CARB_TEMP'      => 'getDisplayCarbTemp',
    );

    /**
     * @{inheritDoc}
     */
    protected function additionalFields()
    {
        $efficiency = $this->record->getEfficiency();
        if (!empty($efficiency) ||
            \BeerXML\Record\Recipe::TYPE_ALL_GRAIN == $this->record->getType() ||
            \BeerXML\Record\Recipe::TYPE_PARTIAL_MASH == $this->record->getType()
        ) {
            $this->xmlWriter->writeElement('EFFICIENCY', $efficiency);
        }

        if ($forcedCarb = $this->record->getForcedCarbonation()) {
            $this->xmlWriter->writeElement('FORCED_CARBONATION', $this->boolToString($forcedCarb));
        }

        $date = $this->record->getDate();
        if ($date instanceof \DateTime) {
            $this->xmlWriter->writeElement('DATE', $date->format('d M y'));
        }

        parent::additionalFields();
    }


}