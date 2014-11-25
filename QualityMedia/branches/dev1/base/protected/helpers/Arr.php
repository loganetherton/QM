<?php
/**
 * Array helper.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class Arr
{
    /**
     * Retrieve a single key from an array. If the key does not exist in the
     * array, the default value will be returned instead.
     * @param array $array array to extract from
     * @param string $key key name
     * @param mixed $default default value
     * @return mixed
     */
    public static function get($array, $key, $default = NULL)
    {
        // isset() is a micro-optimization - it is fast but fails for null values.
        if(isset($array[$key])) {
            return $array[$key];
        }

        // Comparing $default is also a micro-optimization.
        if($default === null || array_key_exists($key, $array)) {
            return null;
        }

        return $default;
    }

    /**
     * Call a method on a list of objects.
     * This method simplifies a common type of mapping operation:
     *
     *      COUNTEREXAMPLE
     *      $names = array();
     *      foreach($objects as $key => $object) {
     *          $names[$key] = $object->getName();
     *      }
     *
     * You can express this more concisely with Arr::methodPull():
     *
     *      $names = Arr::methodPull($objects, 'getName');
     *
     * This method takes a third argument, which allows you to do the same but for the array's keys:
     *
     *      COUNTEREXAMPLE
     *      $names = array();
     *      foreach($objects as $object) {
     *          $names[$object->getID()] = $object->getName();
     *      }
     *
     * This is the Arr::methodPull() version:
     *
     *      $names = Arr::methodPull($objects, 'getName', 'getID');
     *
     * If you pass null as the second argument, the objects will be preserved:
     *
     *      COUNTEREXAMPLE
     *      $idMap = array();
     *      foreach($objects as $object) {
     *          $idMap[$object->getID()] = $object;
     *      }
     *
     * With Arr::methodPull():
     *
     *      $idMap = Arr::methodPull($objects, null, 'getID');
     *
     * @param array $list List of objects
     * @param string|null $method Determines which values will appear in the result array. Use null to preserve original objects
     * @param string|null $keyMethod Determines how keys will be assigned in the result array. Use null to preserve the original keys
     * @return array An array with keys and values derived according to whatever you passed as $method and $keyMethod
     */
    public static function methodPull($list, $method, $keyMethod = null)
    {
        $result = array();
        foreach($list as $key => $object) {
            if($keyMethod !== null) {
                $key = $object->$keyMethod();
            }

            if($method !== null) {
                $value = $object->$method();
            }
            else {
                $value = $object;
            }

            $result[$key] = $value;
        }

        return $result;
    }
}