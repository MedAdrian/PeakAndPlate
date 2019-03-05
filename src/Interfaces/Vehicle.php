<?php
namespace PeakAndPlate\Interfaces;

/**
 * Interface Vehicle
 *
 * To be implemented on most land vehicles rolling on gye.
 */
interface Vehicle
{
    /**
     * Retrieves a regex to validate the plate number.
     *
     * @return string
     */
    public function getRegexForPlateValidation();

    /**
     * Sets a value for the regex to find a plate.
     *
     * @param string $regex
     */
    public function setRegexForPlateValidation($regex);

    /**
     * Retrieves the plate number.
     *
     * @return string
     */
    public function getPlate();

    /**
     * Sets a plate.
     *
     * @param string $plate
     */
    public function setPlate($plate);

    /**
     * Retrieves the last digit of a plate.
     *
     * @return string
     */
    public function getLastDigitFromPlate();

    /**
     * Validates that the plate number meets a required format.
     *
     * @return bool
     */
    public function validatePlateNumber();
}
