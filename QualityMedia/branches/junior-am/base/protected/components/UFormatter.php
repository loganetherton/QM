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
}