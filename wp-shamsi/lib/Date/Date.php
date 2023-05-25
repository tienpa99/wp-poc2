<?php
/*
* Copyright Mohsen Shafiei released under the MIT license
* See license.txt
*/

namespace WpshDate;

use DateTime;

class WPSH_Date extends WPSH_DateAbstract
{
    public function __construct($time = null, $tz = null)
    {

        if ($this->newFromTimestamp($time)) {
            parent::__construct(null, self::safeCreateDateTimeZone($tz));
            $this->setTimestamp($time);
        } else {
            parent::__construct($time, self::safeCreateDateTimeZone($tz));
        }
    }

    /**
     * Check if class initialize from timestamp
     *
     * @param mixed $time
     * @return bool
     */
    protected function newFromTimestamp($time)
    {
        $timestampRegex = '/\A\d+\z/';
        preg_match($timestampRegex, $time, $output);
        if (!empty($output)) {
            return true;
        }

        return false;
    }

    /**
     * @return \WpshDate\WPSH_Jalali
     */
    public function toJalali()
    {
        list($year, $month, $day) = $this->gregorianToJalali($this->format('Y'), $this->format('m'), $this->format('d'));
        list($hour, $minute, $second) = array($this->format('H'), $this->format('i'), $this->format('s'));

        return new WPSH_Jalali("$year-$month-$day $hour:$minute:$second", $this->getTimezone());
    }

    /**
     * An aliases for toJalali method
     *
     * @return \WpshDate\WPSH_Jalali
     */
    public function toj()
    {
        return $this->toJalali();
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        switch (true) {
            case array_key_exists($name, $formats = array(
                'year' => 'Y',
                'month' => 'm',
                'day' => 'd',
                'daysInMonth' => 't'
            )):
                return $this->format($formats[$name]);
                break;
        }
    }

    /**
     * Equivalent to new Date()
     *
     * @return \WpshDate\WPSH_Jalali
     */
    public static function make($time)
    {
        return new WPSH_Date($time);
    }
}