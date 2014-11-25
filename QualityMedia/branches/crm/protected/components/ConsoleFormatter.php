<?php
/**
 * Console output formatter.
 *
 * @author Jakub Pospiech <jakub.pospiech@gmail.com>
 */
class ConsoleFormatter
{
    /**
     * @var array $colorCodes Color codes.
     */
    protected $colorCodes = array(
        'black'   => 0,
        'red'     => 1,
        'green'   => 2,
        'yellow'  => 3,
        'blue'    => 4,
        'magenta' => 5,
        'cyan'    => 6,
        'white'   => 7,
        'default' => 9,
    );

    /**
     * @var boolean $disableANSI Whether ANSI should be disabled
     */
    protected $disableANSI;

    /**
     * Disable ANSI manually.
     * @param boolean Whether ANSI should be disabled
     */
    protected function disableANSI($disable)
    {
        $this->disableANSI = $disable;
    }

    /**
     * Get ANSI status.
     */
    protected function getDisableANSI()
    {
        if($this->disableANSI === null) {
            if(DIRECTORY_SEPARATOR != '/') {
                // Windows environment
                $this->disableANSI = true;
            }
            elseif(function_exists('posix_isatty') && !posix_isatty(STDOUT)) {
                $this->disableANSI = true;
            }
            else {
                $this->disableANSI = false;
            }
        }

        return $this->disableANSI;
    }

    /**
     * Replace color code.
     * @param array $matches Color matches
     * @return string String with replaced colors
     */
    protected function replaceColorCode($matches)
    {
        $codes      = $this->colorCodes;
        $offset     = 30 + $codes[$matches[2]];
        $default    = 39;

        if($matches[1] == 'bg') {
            $offset += 10;
            $default += 10;
        }

        return chr(27).'['.$offset.'m'.$matches[3].chr(27).'['.$default.'m';
    }

    /**
     * Format string.
     * @param
     */
    public function formatString($format /* ... */)
    {
        $colors = implode('|', array_keys($this->colorCodes));

        // Sequence should be preceded by start-of-string or non-backslash escaping.
        $boldRegExp      = '/(?<![\\\\])\*\*(.*)\*\*/sU';
        $underlineRegExp = '/(?<![\\\\])__(.*)__/sU';
        $invertRegExp    = '/(?<![\\\\])##(.*)##/sU';

        if($this->getDisableANSI()) {
            $format = preg_replace($boldRegExp, '\1', $format);
            $format = preg_replace($underlineRegExp, '\1', $format);
            $format = preg_replace($invertRegExp, '\1', $format);
            $format = preg_replace('@<(fg|bg):('.$colors.')>(.*)</\1>@sU', '\3', $format);
        }
        else {
            $esc        = chr(27);
            $bold       = $esc.'[1m'.'\\1'.$esc.'[m';
            $underline  = $esc.'[4m'.'\\1'.$esc.'[m';
            $invert     = $esc.'[7m'.'\\1'.$esc.'[m';

            $format = preg_replace($boldRegExp, $bold, $format);
            $format = preg_replace($underlineRegExp, $underline, $format);
            $format = preg_replace($invertRegExp, $invert, $format);
            $format = preg_replace_callback('@<(fg|bg):('.$colors.')>(.*)</\1>@sU', array($this, 'replaceColorCode'), $format);
        }

        // Remove backslash escaping
        $format = preg_replace('/\\\\(\*\*.*\*\*|__.*__|##.*##)/sU', '\1', $format);

        $args = func_get_args();
        $args[0] = $format;

        return call_user_func_array('sprintf', $args);
    }
}