<?php


namespace BeerXML\Generator;


interface IMashStepDisplay extends IMashStep
{

    /**
     * Calculated volume of mash to decoct.  Only applicable for a decoction step.  Includes the units as in "7.5 l" or
     * "2.3 gal"
     *
     * @return string
     */
    public function getDecoctionAmt();

    /**
     * Textual description of this step such as "Infuse 4.5 gal of water at 170 F" – may be either generated by the
     * program or input by the user.
     *
     * @return string
     */
    public function getDescription();

    /**
     * Infusion amount along with the volume units as in "20 l" or "13 qt"
     *
     * @return string
     */
    public function getDisplayInfuseAmt();

    /**
     * Step temperature in user defined temperature units.  For example "154F" or "68 C"
     *
     * @return string
     */
    public function getDisplayStepTemp();

    /**
     * The calculated infusion temperature based on the current step, grain, and other settings.  Applicable only for an
     * infusion step.  Includes the units as in "154 F" or "68 C"
     *
     * @return string
     */
    public function getInfuseTemp();

    /**
     * The total ratio of water to grain for this step AFTER the infusion along with the units, usually expressed in
     * qt/lb or l/kg.
     *
     * Note this value must be consistent with the required infusion amount and amounts added in earlier steps and is
     * only relevant as part of a <MASH> profile.  For example "1.5 qt/lb" or "3.0 l/kg"
     * @return string
     */
    public function getWaterGrainRatio();
}