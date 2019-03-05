<?php
namespace PeakAndPlate\Tests;

use PeakAndPlate\Classes\Car;
use PHPUnit\Framework\TestCase;

/**
 * Class CarTest
 *
 * @package PeakAndPlate\Tests
 */
class CarTest extends TestCase
{
    /**
     * Car instance.
     *
     * @var Car
     */
    protected $Car;

    /**
     * Sets up the test instance.
     */
    protected function setUp()
    {
        $this->Car = new Car();
    }

    /**
     * Generates a number plate with this format: AVM-0001
     *
     * @return string
     */
    public function plateProvider()
    {
        $string = '';
        $number = rand(1000, 9999);

        for ($i = 0; $i < 3; $i++) {
            $string .= chr(rand(65,90));
        }

        return $string.'-'.$number;
    }

    /**
     * Returns an array with a plate number.
     *
     * @return array
     */
    public function arrayWithPlateNumberProvider()
    {
        return [[$this->plateProvider()]];
    }

    /**
     * Verifies if the plate number is an empty string when the Car
     * instance is created.
     *
     * @covers Car::getPlate()
     * @throws \Exception
     */
    public function testPlateIsInitiallyEmpty()
    {
        $this->assertEmpty($this->Car->getPlate());
    }

    /**
     * Validates that the plate is assigned correctly.
     *
     * @param $plateNumber
     * @throws \Exception
     *
     * @dataProvider arrayWithPlateNumberProvider
     * @covers Car::setPlate()
     *
     */
    public function testAssignAttributePlate($plateNumber)
    {
        $this->Car->setPlate($plateNumber);

        $this->assertAttributeSame($plateNumber, 'plate', $this->Car);
    }

    /**
     * Validates the regex returned form the Car class.
     *
     * @covers Car::getRegexForPlateValidation()
     * @throws \Exception
     */
    public function testCarHasValidPlateRegex()
    {
        $this->assertTrue(preg_match($this->Car->getRegexForPlateValidation(), null) !== false);
    }

    /**
     * Validates the regex supply form the Car class to satisfy a string format.
     *
     * @param string $plateNumber
     *
     * @dataProvider arrayWithPlateNumberProvider
     * @covers Car::validatePlateNumber()
     * @throws \Exception
     */
    public function testValidatePlateNumberSupply($plateNumber)
    {
        $this->Car->setPlate($plateNumber);

        $this->assertTrue($this->Car->validatePlateNumber());
    }

    /**
     * Verifies that the value returned form getLastDigitFromPlate is a number.
     *
     * @covers Car::getLastDigitFromPlate()
     * @throws \Exception
     */
    public function testLastDigitFromPlateShouldReturnNumber()
    {
        $this->Car->setPlate('AVM-0001');

        $this->assertTrue(is_numeric($this->Car->getLastDigitFromPlate()));
    }
}