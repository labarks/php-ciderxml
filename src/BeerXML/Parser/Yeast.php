<?php


namespace BeerXML\Parser;


class Yeast extends Record
{
    protected $tagName = 'YEAST';

    /**
     * Tags that map to simple values and the corresponding setter method on the record class
     *
     * @var array
     */
    protected $simpleProperties = array(
        'NAME'            => 'setName',
        'VERSION'         => 'setVersion',
        'TYPE'            => 'setType',
        'FORM'            => 'setForm',
        'AMOUNT'          => 'setAmount',
        'LABORATORY'      => 'setLaboratory',
        'PRODUCT_ID'      => 'setProductId',
        'MIN_TEMPERATURE' => 'setMinTemperature',
        'FLOCCULATION'    => 'setFlocculation',
        'ATTENUATION'     => 'setAttenuation',
        'NOTES'           => 'setNotes',
        'BEST_FOR'        => 'setBestFor',
        'TIMES_CULTURED'  => 'setTimesCultured',
        'MAX_REUSE'       => 'setMaxReuse',
        'MAX_TEMPERATURE' => 'setMaxTemperature',
    );

    /**
     * @return IYeast
     */
    protected function createRecord()
    {
        $yeast = $this->recordFactory->getYeast();
        if ($yeast instanceof IYeastDisplay) {
            $this->simpleProperties = array_merge(
                $this->simpleProperties,
                array(
                    'DISPLAY_AMOUNT' => 'setDisplayAmount',
                    'DISP_MIN_TEMP'  => 'setDispMinTemp',
                    'DISP_MAX_TEMP'  => 'setDispMaxTemp',
                    'INVENTORY'      => 'setInventory',
                )
            );
        }
        return $yeast;
    }

    /**
     * @param IYeast $record
     */
    protected function otherElementEncountered($record)
    {
        if ('AMOUNT_IS_WEIGHT' == $this->xmlReader->name) {
            $value = ($this->xmlReader->readString() == 'TRUE');
            $record->setAmountIsWeight($value);
        } elseif ('ADD_TO_SECONDARY' == $this->xmlReader->name) {
            $value = ($this->xmlReader->readString() == 'TRUE');
            $record->setAddToSecondary($value);
        } elseif ('CULTURE_DATE' == $this->xmlReader->name && $record instanceof IYeastDisplay) {
            $dateTimeString = $this->xmlReader->readString();
            $record->setCultureDate($this->parseDateString($dateTimeString));
        }
    }
}
