<?php
/**
 * Text helper class. Provides simple methods for working with text.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class Text
{
    /**
     * Generates a random string of a given type and length.
     *
     *     $str = Text::random(); // 8 character random string
     *
     * The following types are supported:
     *
     * alnum
     * :  Upper and lower case a-z, 0-9 (default)
     *
     * alpha
     * :  Upper and lower case a-z
     *
     * hexdec
     * :  Hexadecimal characters a-f, 0-9
     *
     * numeric
     * :  numbers in range 0-9
     *
     * nozero
     * :  numbers in range 1-9
     *
     * distinct
     * :  Uppercase characters and numbers that cannot be confused
     *
     * @param string $type A type of pool
     * @param integer $length Length of string to return
     * @return string
     */
    public static function random($type = 'alnum', $length = 8)
    {
        switch($type)
        {
            case 'alnum':
                $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                break;
            case 'alpha':
                $pool = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                break;
            case 'hexdec':
                $pool = '0123456789abcdef';
                break;
            case 'numeric':
                $pool = '0123456789';
                break;
            case 'nozero':
                $pool = '123456789';
                break;
            case 'distinct':
                $pool = '2345679ACDEFHJKLMNPRSTUVWXYZ';
                break;
            default:
                throw new CException('Type is not supported');
                break;
        }

        $pool = str_split($pool, 1);

        // Largest pool key
        $max = count($pool) - 1;

        $str = '';
        for($i = 0; $i < $length; $i++) {
            // Select a random character from the pool and add it to the string
            $str .= $pool[mt_rand(0, $max)];
        }

        return $str;
    }

    /**
     * Limits a phrase to a given number of characters.
     *
     *     $text = Text::limit_chars($text);
     *
     * @param string $str Phrase to limit characters of
     * @param integer $limit Number of characters to limit to
     * @param string $endChar End character or entity
     * @param boolean $preserveWords Enable or disable the preservation of words while limiting
     * @return string
     */
    public static function limitChars($str, $limit = 100, $endChar = null, $preserveWords = true)
    {
        $endChar = ($endChar === null) ? '...' : $endChar;

        $limit = (int)$limit;

        if(trim($str) === '' || strlen($str) <= $limit) {
            return $str;
        }

        if($limit <= 0) {
            return $endChar;
        }

        if($preserveWords === false) {
            return rtrim(substr($str, 0, $limit)).$endChar;
        }

        // Don't preserve words. The limit is considered the top limit.
        // No strings with a length longer than $limit should be returned.
        if(!preg_match('/^.{0,'.$limit.'}\s/us', $str, $matches)) {
            return $endChar;
        }

        return rtrim($matches[0]).((strlen($matches[0]) === strlen($str)) ? '' : $endChar);
    }

    /**
     * Truncate the middle portion of a string.
     * @param string $str Phrase to limit characters of
     * @param integer $limit Number of characters to limit to
     * @param string $separator Separator
     * @return string
     */
    public static function truncateChars($str, $limit = 100, $separator = '...')
    {
        if(trim($str) === '' || strlen($str) <= $limit) {
            return $str;
        }

        $separatorLength = strlen($separator);
        $maxLength = $limit - $separatorLength;
        $start = $maxLength / 2 ;
        $trunc =  strlen($str) - $maxLength;

        return substr_replace($str, $separator, $start, $trunc);
    }

    /**
     * Convert a phrase to a URL-safe title.
     * @param string $text Text to be sligified
     * @return string parsed text
     */
    public static function slugify($text)
    {
        // replace non letter or digits by -
        $text = preg_replace('~[^\\pL\d]+~u', '-', $text);

        // trim
        $text = trim($text, '-');

        // transliterate
        if(function_exists('iconv')) {
            $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        }

        // lowercase
        $text = strtolower($text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        if(empty($text)) {
            return 'n-a';
        }

        return $text;
    }
}