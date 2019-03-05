<?php
namespace PeakAndPlate\Tests;

use PeakAndPlate\Classes\Car;
use PHPUnit\Framework\TestCase;
use PeakAndPlate\Restrictions\Restriction;

/**
 * Class RestrictionTest
 *
 * @package PeakAndPlate\Tests
 */
class RestrictionTest extends TestCase
{
    /**
     * Restriction instance.
     *
     * @var Restriction
     */
    protected $Restriction;

    /**
     * Sets up the test instance.
     */
    protected function setUp()
    {
        $Car = new Car();
        $Car->setPlate('AVM-0001');

        $this->Restriction = new Restriction($Car);
    }

    /**
     * Retrieves a valid plate number for the current day.
     *
     * @return string
     */
    public function getCurrentValidPlate()
    {
        $day = date('D');

        if (array_key_exists($day, Restriction::getRestrictionDays())) {
            $lastPlateNumber = reset(Restriction::getRestrictionDays()[$day]);
        }

        return 'AVM-000' . $lastPlateNumber;
    }

    /**
     * Generates a random date.
     *
     * @return string
     */
    public function dateProvider()
    {
        return date("Y-m-d");
    }

    /**
     * Generates a random time.
     *
     * @return string
     */
    public function timeProvider()
    {
        return date("H:i");
    }

    /**
     * Provides an array with a random date.
     *
     * @return array
     */
    public function arrayWithDateProvider()
    {
        return [[$this->dateProvider()]];
    }

    /**
     * Validates the Restriction date set.
     *
     * @param $date
     * @throws \Exception
     *
     * @dataProvider arrayWithDateProvider
     * @covers Restriction::setDate()
     */
    public function testAssignAttributeDate($date)
    {
        $this->Restriction->setDate($date);

        $this->assertAttributeSame($date, 'date', $this->Restriction);
    }

    /**
     * Provides an array with a random time.
     *
     * @return array
     */
    public function arrayWithTimeProvider()
    {
        return [[$this->timeProvider()]];
    }

    /**
     * Validates the Restriction time set.
     *
     * @param $time
     * @throws \Exception
     *
     * @dataProvider arrayWithTimeProvider
     * @covers Restriction::setTime()
     */
    public function testAssignAttributeTime($time)
    {
        $this->Restriction->setTime($time);

        $this->assertAttributeSame($time, 'time', $this->Restriction);
    }

    /**
     * Validates that the regex for date is valid.
     *
     * @covers Restriction::getRegularExpressionForDate()
     * @throws \Exception
     */
    public function testRestrictionHasValidDateRegex()
    {
        $this->assertTrue(preg_match($this->Restriction->getRegularExpressionForDate(), null) !== false);
    }

    /**
     * Validates that the regex for time is valid.
     *
     * @covers Restriction::getRegExpTime()
     * @throws \Exception
     */
    public function testRestrictionHasValidTimeRegex()
    {
        $this->assertTrue(preg_match($this->Restriction->getRegularExpressionForTime(), null) !== false);
    }

    /**
     * Validates if a vehicle can roll based ond the Peak and Plate system.
     *
     * @throws \Exception
     */
    public function testPeakAndPlateAssertTrue()
    {
        $this->assertTrue($this->Restriction->canRollOnCity('2019-03-01', '12:34'));
    }

    /**
     * Validates if a vehicle can not roll based ond the Peak and Plate system.
     *
     * @throws \Exception
     */
    public function testPeakAndPlateAssertNotTrue()
    {
        $this->assertNotTrue($this->Restriction->canRollOnCity('2019-02-25', '17:21'));
    }
}