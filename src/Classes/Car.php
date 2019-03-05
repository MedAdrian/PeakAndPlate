<?php
namespace PeakAndPlate\Classes;

use PeakAndPlate\Interfaces\Vehicle;

/**
 * Class Car
 *
 * @package PeakAndPlate
 */
class Car implements Vehicle
{
    /**
     * Regular expression to validate a plate,
     * validates this default regex according to:
     * @see https://ecuadorec.com/placas-vehiculos-ecuador-tipos-letras-provincia/
     *
     * @var string
     */
    private $regularExpressionForPlateValidation = '/^([A-Z]{3})-([0-9]{3,4})/';

    /**
     * Plate number.
     *
     * @var string
     */
    private $plate = '';

    /**
     * Retrieves a regex to validate a plate number.
     *
     * @return string
     */
    public function getRegexForPlateValidation()
    {
        return $this->regularExpressionForPlateValidation;
    }

    /**
     * Sets a value for the regex to find a valid plate.
     *
     * @param string $regex
     *
     * @return $this
     */
    public function setRegexForPlateValidation($regex)
    {
        $this->regularExpressionForPlateValidation = $regex;

        return $this;
    }

    /**
     * Retrieves the plate number.
     *
     * @return string
     */
    public function getPlate()
    {
        return $this->plate;
    }

    /**
     * Sets a plate.
     *
     * @param $plate
     *
     * @return $this
     */

    public function setPlate($plate)
    {
        $this->plate = $plate;

        return $this;
    }

    /**
     * Retrieves the last digit of a plate.
     * not concatenated functions only vars must be passed by ref.
     *
     * @return string
     */
    public function getLastDigitFromPlate()
    {
        $plate = str_split($this->getPlate());

        return end($plate);
    }

    /**
     * Validates that the plate number meets a required format.
     * (phpUnit requires a bool type not int)
     *
     * @return bool
     */
    public function validatePlateNumber()
    {
        return (bool)preg_match($this->getRegexForPlateValidation(), $this->plate);
    }
}
