<?php
namespace PeakAndPlate\Restrictions;

use DateTime;
use Exception;
use PeakAndPlate\Interfaces\Vehicle;
/**
 * Class Restriction
 *
 * @package PeakAndPlate\Restrictions
 */
class Restriction
{
    /**
     * Vehicle instance.
     *
     * @var Vehicle
     */
    private $Vehicle;

    /**
     * Date to be validated.
     *
     * @var string
     */
    private $date = '';

    /**
     * Time to be validated.
     *
     * @var string
     */
    private $time = '';

    /**
     * Default date time structure.
     *
     * @var array
     */
    private static $formatStructure = [
        'date' => 'YYYY-MM-DD',
        'time' => 'HH:MM',
    ];

    /**
     * Default regular expression for time validation based on:
     * @see https://stackoverflow.com/questions/7536755/regular-expression-for-matching-hhmm-time-format
     * for format: HH:MM
     *
     * @var string
     */
    private $regularExpressionForTime = '/^(0[0-9]|1[0-9]|2[0-3]):[0-5][0-9]$/';

    /**
     * Default regular expression for date validation based on:
     * @see https://www.sitepoint.com/community/t/validating-a-date-input-in-form/9544/2
     * for format: YYYY-MM-DD
     *
     * @var string
     */
    private $regularExpressionForDate = '/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/';

    /**
     * Days of Restriction.
     *
     * @var array
     */
    private static $restrictionDays = [
        'Mon' => [1, 2],
        'Tue' => [3, 4],
        'Wed' => [5, 6],
        'Thu' => [7, 8],
        'Fri' => [9, 0],
    ];

    /**
     * Peak and Plate restriction shifts.
     *
     * @var array
     */
    private static $shiftRestrictions = [
        'morning' => ['from' => '07:00', 'to' => '09:30'],
        'afternoonToEvening' => ['from' => '16:00', 'to' => '19:30'],
    ];

    /**
     * Restriction constructor.
     *
     * @param Vehicle $Vehicle
     */
    public function __construct(Vehicle $Vehicle)
    {
        $this->Vehicle = $Vehicle;
    }

    /**
     * Verifies that an argument isn't empty.
     *
     * @param string $value
     * @param string $argName
     *
     * @return Restriction
     *
     * @throws Exception
     */
    protected function isEmptyArgument($value, $argName)
    {
        if (empty($value)) {
            throw new Exception("The argument ${$argName} is required.");
        }

        return $this;
    }

    /**
     * Sets a value for the date attribute.
     *
     * @param $date
     *
     * @return $this
     * @throws Exception
     */
    public function setDate($date)
    {
        $this->validateArgument($date, 'date');
        $this->date = $date;

        return $this;
    }

    /**
     * Retrieves the date to be validated.
     *
     * @return string
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Retrieves the time to be validated.
     *
     * @return string
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * Sets a value for the time attribute.
     *
     * @param $time
     *
     * @return $this
     * @throws Exception
     */
    public function setTime($time)
    {
        $this->validateArgument($time, 'time');
        $this->time = $time;

        return $this;
    }

    /**
     * Sets the regularExpressionForDate.
     *
     * @param string $regex
     *
     * @return $this
     */
    public function setRegularExpressionForDate($regex = '')
    {
        $this->regularExpressionForDate = $regex;

        return $this;
    }

    /**
     * Retrieves the regularExpressionForDate.
     *
     * @return string
     */
    public function getRegularExpressionForDate()
    {
        return $this->regularExpressionForDate;
    }

    /**
     * Sets the regularExpressionForTime.
     *
     * @param string $regex
     *
     * @return $this
     */
    public function setRegularExpressionForTime($regex = '')
    {
        $this->regularExpressionForTime = $regex;

        return $this;
    }

    /**
     * Retrieves the regularExpressionForTime.
     *
     * @return string
     */
    public function getRegularExpressionForTime()
    {
        return $this->regularExpressionForTime;
    }

    /**
     * Verifies if a date is in a valid format.
     *
     * @param string $date
     *
     * @return bool
     */
    public function isDateInAValidFormat($date)
    {
        return preg_match($this->getRegularExpressionForDate(), $date);
    }

    /**
     * Verifies if time is in a valid format.
     *
     * @param string $time
     *
     * @return bool
     */
    public function isTimeInAValidFormat($time)
    {
        return preg_match($this->getRegularExpressionForTime(), $time);
    }

    /**
     * Retrieves an array with the days of application for the regulation.
     *
     * @return array
     */
    public static function getRestrictionDays()
    {
        return static::$restrictionDays;
    }

    /**
     * Retrieves an array with the shifts of regulations depending on the day.
     *
     * @return array
     */
    public static function getShiftApplications()
    {
        return static::$shiftRestrictions;
    }

    /**
     * Verifies through calendar and shifts if a Vehicle can roll.
     *
     * @param $date
     * @param $time
     *
     * @return bool
     * @throws Exception
     */
    public function canRollOnCity($date, $time)
    {
        return $this->setDateTime($date, $time)->canRoll();
    }

    /**
     * Verifies date and time.
     *
     * @param string $value
     * @param string $argName
     *
     * @return Restriction
     *
     * @throws Exception
     */
    protected function validateArgument($value, $argName)
    {
        $this->isEmptyArgument($value, $argName)->hasValidFormat($value, $argName);

        return $this;
    }

    /**
     * Verifies that a given value satisfies the required format.
     *
     * @param $value
     * @param $argName
     *
     * @return Restriction
     *
     * @throws Exception
     */
    protected function hasValidFormat($value, $argName)
    {
        $format = $this->getValidFormat($argName);
        $method = "is{$argName}InAValidFormat";

        if (!$this->$method($value)) {
            throw new Exception(
                "The argument {$argName} does not have a valid format, valid format i.e: '{$format}'."
            );
        }

        return $this;
    }

    /**
     * Returns a string with a format depending on the argument passed.
     *
     * @param $argName
     *
     * @return string
     */
    protected function getValidFormat($argName)
    {
        $formats = static::$formatStructure;

        return array_key_exists($argName, $formats) ? $formats[$argName] : '';
    }

    /**
     * Sets the date and time.
     *
     * @param string $date
     * @param string $time
     *
     * @return $this
     * @throws Exception
     */
    protected function setDateTime($date, $time)
    {
        return $this->setDate($date)->setTime($time);
    }

    /**
     * Verifies if a vehicle can circulate.
     *
     * @return bool
     */
    protected function canRoll()
    {
        return $this->hasRestrictionsForCalendar()
            ? $this->verifyVehicleCirculationByTime()
            : true;
    }

    /**
     * Retrieves an array with the restricted digits to circulate.
     *
     * @return array
     */
    protected function getRestrictedDigits()
    {
        $digits = [];
        $day = $this->getCurrentDay();

        if (array_key_exists($day, static::getRestrictionDays())) {
            $digits = static::getRestrictionDays()[$day];
        }

        return $digits;
    }

    /**
     * Returns a string with the current day.
     *
     * @return string
     */
    private function getCurrentDay()
    {
        $date = new DateTime($this->getDate());

        return $date->format('D');
    }

    /**
     * Validates if the last digit of the plate
     * can make a Vehicle circulate on the current day.
     *
     * @return bool
     */
    public function hasRestrictionsForCalendar()
    {
        $restrictionDigits = $this->getRestrictedDigits();
        $lastPlateDigit = $this->Vehicle->getLastDigitFromPlate();

        return in_array($lastPlateDigit, $restrictionDigits);
    }

    /**
     * Validates if a vehicle can circulate in any of the shifts.
     *
     * @return bool
     */
    public function verifyVehicleCirculationByTime()
    {
        $time = strtotime($this->getTime());
        $restrictions = static::getShiftApplications();

        $canCirculateMorning = $this->canCirculateOnShift($time, $restrictions['morning']);
        $canCirculateAfternoonToEvening = $this->canCirculateOnShift($time, $restrictions['afternoonToEvening']);

        return $canCirculateMorning && $canCirculateAfternoonToEvening;
    }

    /**
     * Validates if a vehicle can circulate in a specified time period.
     *
     * @param $time
     * @param $restrictions
     *
     * @return bool
     */
    private function canCirculateOnShift($time, $restrictions)
    {
        return $time < strtotime($restrictions['from'])
            || $time > strtotime($restrictions['to']);
    }
}
