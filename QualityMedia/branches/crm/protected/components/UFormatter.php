<?php
/**
 * Custom changes in CFormatter class (+ new formatters).
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class UFormatter extends CFormatter
{
    /**
     * @var string $currencyFormat The format string to be used to format a date using PHP date() function. Defaults to 'Y/m/d'.
     */
    public $currencyFormat = 'USD';

    /**
     * Formats the value as money value.
     * @param mixed $value The value to be formatted
     * @return string The formatted result
     */
    public function formatMoney($value)
    {
        return Yii::app()->getLocale()->getNumberFormatter()->formatCurrency($value, $this->currencyFormat);
    }

    /**
     * Format value as relative date.
     * @param string $value The value to be formatted
     * @return string The formatted result
     */
    public function formatRelativeDate($value)
    {
        return $this->relativeTime($value, 'formatDate');
    }

    /**
     * Format value as relative time.
     * @param string $value The value to be formatted
     * @return string The formatted result
     */
    public function formatRelativeTime($value)
    {
        return $this->relativeTime($value, 'formatTime');
    }

    /**
     * Format value as relative datetime.
     * @param string $value The value to be formatted
     * @return string The formatted result
     */
    public function formatRelativeDatetime($value)
    {
        return $this->relativeTime($value, 'formatDatetime');
    }

    /**
     * Formats the value as a relative time.
     * @param mixed $value The value to be formatted
     * @param string $formatMethod Method to use if relative time is too long
     * @param array $chunks Relative time chunks
     * @return string The formatted result
     */
    protected function relativeTime($value, $formatMethod)
    {
        if(!is_integer($value)) {
            $value = strtotime($value);
        }

        $time = time() - $value;

        $chunks = array(
            //    /         <       ago
            array(1,        60,     'second'),
            array(60,       3600,   'minute'),
            array(3600,     86400,  'hour'),
            array(86400,    604800, 'day'),
        );

        foreach($chunks as $chunk) {
            list($div, $range, $label) = $chunk;

            if($time < $range) {
                $count = abs((int)($time / $div));

                return $count == 1 ? "1 {$label} ago" : "{$count} {$label}s ago";
            }
        }

        return $this->$formatMethod($value);
    }
}