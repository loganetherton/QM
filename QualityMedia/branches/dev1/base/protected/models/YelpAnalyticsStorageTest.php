<?php
/**
 * Long term storage model for Yelp Analytics info
 *
 * Each client will have its own collection of documents, with each document pertaining to a particular day of data
 *
 * Indexes: date and businessId, since those are the items being searched
 *
 * Class properties must be public for inserting and updating documents
 *
 * @property int $_id MongoID for the document
 *
 * @property int $businessId Corresponds to businessId in MySQL database
 * @property string $yelpBusinessId ID used by Yelp to identify this client
 * @property int $date Date for which data was collected, in this format: YYYYMMDD
 * @property array $num_ad_clicks_daily Daily data points for ad clicks for this client (30 days)
 * @property array $num_page_views_daily Daily data points for page views for this client (30 days)
 * @property array $num_customer_actions_daily Daily data points for customer actions for this client (30 days)
 * @property array $has_ads_daily Daily data points (boolean) whether client has ads (30 days)
 * @property array $monthlyTotals Arrays for 30 days totals for the following:
 *      @property array $num_page_views Total number of page views
 *      @property array $num_check_ins Total number of checks ins
 *      @property array $num_photos Total number of photos
 *      @property array $num_opentable_reservations Total number of open table reservations
 *      @property array $num_directions_and_map_views Total number of directions and map views
 *      @property array $num_cta_clicks Total number of clicks (to ads?)
 *      @property array $num_bookmarks Total number of bookmarks on this client
 *      @property array $num_business_url_visits Total number of visits to the business URL of this client
 *      @property array $num_mobile_page_views Total number of page views from mobile devices
 *      @property array $num_search_appearances Total number of times this client appeared in searches
 *      @property array $num_deals_sold Total number of deals sold for this client
 *      @property array $num_ad_clicks Total number of ad clicks
 *      @property array $num_calls Total number of calls
 *      @property array $num_directions Total number of directions retrieved for this client
 *      @property array $num_customer_actions Total number of customer actions for this client
 * @property int $arpu_this_day ARPU for this client for this day only
 * @property int $mobile_percent_this_day Percentage of page views that were from mobile devices for this day only
 * @property string $collectionName Name under which collections for this client will be stored
 *
 * @database qmTest
 *
 * @author Logan Etherton <logan@qualitymedia.com>
 */
class YelpAnalyticsStorageTest extends EMongoDocument
{
    /**
     * Logan note: Property declarations need to be public for Mongo to process it in getRawDocument()
     */
    private $_id; // MongoDB primary key
    public $businessId; // Corresponds to MySQL DB
    public $yelpBusinessId; // Yelp's business ID from their systems
    public $date;
    
    /**
     * From Yelp analytics PhantomJS script
     */
    // Daily data points    
    public $num_ad_clicks_daily = array();
    public $num_page_views_daily = array();
    public $num_customer_actions_daily = array();
    public $has_ads_daily = array();
    // Array of all 30 day totals
    public $monthlyTotals = array();
    // Daily totals
    public $arpu_this_day;
    public $mobile_percent_this_day;
    // Base collection name - actual collection name has businessId from MySQL database appended
    // For example: yelpAnalyticsStorage_Id_1, yelpAnalyticsStorage_Id_2, etc
    public $collectionName;
    
    /**
     * Returns the static instance for this model
     *
     * @param string $className
     * @return YelpAnalyticsStorage
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
    
    /**
     * Explicitly set the collection name for this client
     *
     * @param int $id ID corresponding to businessId in the MySQL database
     */
    public function setCollectionName($id)
    {
        $this->collectionName = 'yelpAnalyticsStorage_Id_' . $id;
    }
    
    /**
     * Format the date to ensure consistency in read and write operations
     *
     * @param string $date Date for the MongoDB document
     * @return int $dateFormatted Integer representing the date. Format: YYYYMMDD
     */
    public function formatDate($date, $endDate = false)
    {
        // Handle non-formatted dates from the datepicker
        preg_match('/\d{7}/', $date, $dateMatch);
        if ($date != 'today' && $date != '-1 month' && empty($dateMatch)) {
            $year = substr($date, 0, 4);
            $monthText = substr($date, 4, 3);
            switch ($monthText) {
                case 'Jan':
                    $month = '01';
                    break;
                case 'Feb':
                    $month = '02';
                    break;
                case 'Mar':
                    $month = '03';
                    break;
                case 'Apr':
                    $month = '04';
                    break;
                case 'May':
                    $month = '05';
                    break;
                case 'Jun':
                    $month = '06';
                    break;
                case 'Jul':
                    $month = '07';
                    break;
                case 'Aug':
                    $month = '08';
                    break;
                case 'Sep':
                    $month = '09';
                    break;
                case 'Oct':
                    $month = '10';
                    break;
                case 'Nov':
                    $month = '11';
                    break;
                case 'Dec':
                    $month = '12';
                    break;
            }
            $dayDate = $endDate ? '30' : '01';
            $date = new DateTime($year . $month . $dayDate);
            $dateFormatted = (int)$date->format('Ymd');
        } else {
            $date = new DateTime($date);
            $dateFormatted = (int)$date->format('Ymd');
        }
        
        return $dateFormatted;
    }
    
    /**
     * Create an index for the current client collection
     *
     * Create an index trigger file, if one has not already been created for this client, so that indexes are not created
     * on every MongoDB access. If the file doesn't exist, create an index for this client.
     *
     * @param int $id Client ID, corresponding to businessId in MySQL database
     */
    public function createIndexFile($id)
    {
        $indexFile = file_exists(Yii::app()->getBasePath() . '/mongoIndexes/' . $this->collectionName . '_index');
        
        // If the index verification file doesn't exist, create it. It only needs to be created once per client
        if (!$indexFile) {
            echo 'Creating a MongoDB index trigger file for this client. Client #' . $id;
            echo "\n";
            
            touch(Yii::app()->getBasePath() . '/mongoIndexes/' . $this->collectionName . '_index');
            // Call this command manually, the MongoYii implementation seems to be having issues
            Yii::app()->mongodb->execute('db.' . $this->collectionName . '.ensureIndex({\'date\': -1, \'businessId\': 1})');
        }
    }
    
    /**
     * Set attributes to save incoming Yelp analytics data
     *
     * @param array $attributes Yelp analytics attributes
     * @param bool $safeOnly Reads only those attributes declared safe in rules (none for this model)
     */
    public function setAttributes($attributes, $safeOnly = false)
    {
        $this->arpu_this_day = (int)$attributes['data']['arpu'];
        
        // Gotta decode the incoming JSON again
        $oneMonth = CJSON::decode($attributes['data']['one_month']);
        
        $this->mobile_percent_this_day = $oneMonth['data']['stats']['mobile_percent'];
        
        // Get the monthly totals and place them into the model
        foreach ($oneMonth['data']['stats']['totals'] as $k => $v) {
            $this->monthlyTotals[$k] = (int)$v;
        }
        
        // Get the monthly totals and place them into the model
        foreach ($oneMonth['data']['stats']['data_points'] as $k => $dailyDataPoint) {
            $this->{$k . '_daily'} = array();
            foreach ($dailyDataPoint as $v) {
                $this->{$k . '_daily'}[$v[0]] = (int)$v[1];
            }
        }
        
        // Get whether the business has ads for each day
        foreach ($oneMonth['data']['stats']['has_ads'] as $k => $v) {
            $this->has_ads_daily[$k] = (int)$v;
        }
    }
    
    /**
     * Make sure we have data for the full requested range
     *
     * equivalent to calling: db.collection.find().limit(1).sort({ts:1})
     */
    public function verifyDateRange($id, $startDate, $endDate)
    {
        // Find the very first document in the collection
        $criteriaFirstDoc = new EMongoCriteria(array(
                                             'sort' => array('date'=>1),
                                             'limit' => 1
                                             ));
        
        // Make sure we actually have data for this client
        if (!YelpAnalyticsStorage::model()->find($criteriaFirstDoc)->getNext()) {
            throw new CException('No data has been collected for this client');
        } else {
            // Find out when we first starting storing data for this client
            $firstDoc = YelpAnalyticsStorage::model()->find($criteriaFirstDoc)->getNext()->date;
        }

        // And find the most recent document
        $criteriaLastDoc = new EMongoCriteria(array(
                                             'sort' => array('date'=>-1),
                                             'limit' => 1
                                             ));
        
        // Determine the most recent document for this client
        $lastDoc = YelpAnalyticsStorage::model()->find($criteriaLastDoc)->getNext()->date;
        
        // Make sure the dates are properly formatted   
        if (strlen($startDate) != 8 || strlen($endDate) != 8) {
            throw new CException('The start and end dates need to follow this format: YYYYMMDD');
        // Make sure that the start date is not before today
        } elseif ($startDate < $firstDoc) {
            // Redirect the user to the first available document date
            return array('dateRangeStartInvalid' => true, 'firstDoc' => $firstDoc);
        } elseif ($endDate > $lastDoc) {
            $endDate--;
            // See if it's just that we haven't collected data for the current date yet
            if ($endDate > $lastDoc) {
                $lastDoc++;
                return array('dateRangeEndInvalid' => true, 'lastDoc' => $lastDoc);
            }
        } else {
            $startDate = $this->formatDate($startDate);
            $endDate = $this->formatDate($endDate);
        }
        
        return array('firstDoc' => $firstDoc, 'lastDoc' => $lastDoc);
    }
    
    /**
     * Set MongoCriteria for retrieving data for a specific date range
     *
     * @param int $id Client ID corresponding to businessId in MySQL database
     * @param int $dateStartFormatted Integer represeting the start date. Format: YYYYMMDD
     * @param int $dateEndFormatted Integer representing the end date. Format: YYYYMMDD
     *
     * @return EMongoCriteria $criteria Criteria for MongoDB query
     */
    public function setDateRangeRetrievalCriteria($id, $dateStartFormatted, $dateEndFormatted)
    {
        $dateStartFormatted = (int)$dateStartFormatted;
        $dateEndFormatted = (int)$dateEndFormatted;
        $criteria = new EMongoCriteria;
        $criteria->setSort(array('date' => -1));
        $dateRangeCriteria = $criteria->condition;
        $dateRangeCriteria['date']['$gte'] = $dateStartFormatted;
        $dateRangeCriteria['date']['$lte'] = $dateEndFormatted;
        $criteria->setCondition($dateRangeCriteria);
        return $criteria;
    }
    
    public function getData($id, $startDate, $endDate)
    {
        $criteria = $this->setDateRangeRetrievalCriteria($id, $startDate, $endDate);
        // Find all of the matching documents
        $documents = $this->find($criteria);
        // Create JSON string from the retrieved documents
        $combinedData = $this->setAttributesDateRangeJson($documents, $startDate, $endDate);
        // Remove the data that we don't need, and format everything
        return $this->parseDataForDisplay($combinedData);
    }
    
    /**
     * Create a JSON string from the returned documents
     *
     * @param EMongoCursor $documents Collection of documents to be parsed
     * @param int $dateStartFormatted Integer representing the starting date. Format: YYYYMMDD
     * @param int $dateEndFormatted Integer representing the end date. Format: YYYYMMDD
     */
    public function setAttributesDateRangeJson($documents, $dateStartFormatted, $dateEndFormatted)
    {
        $jsonForJs = array();
        $dataInfo = $documents->getNext();
        $jsonForJs['businessId'] = $dataInfo['businessId'];
        $jsonForJs['yelpBusinessId'] = $dataInfo['yelpBusinessId'];
        $jsonForJs['dateStart'] = $dateStartFormatted;
        $jsonForJs['dateEnd'] = $dateEndFormatted;
        foreach ($documents as $document) {
            $jsonForJs[$document['date']]['num_ad_clicks_daily'] = $document['num_ad_clicks_daily'];
            $jsonForJs[$document['date']]['num_page_views_daily'] = $document['num_page_views_daily'];
            $jsonForJs[$document['date']]['num_customer_actions_daily'] = $document['num_customer_actions_daily'];
            $jsonForJs[$document['date']]['has_ads_daily'] = $document['has_ads_daily'];
            $jsonForJs[$document['date']]['monthlyTotals'] = $document['monthlyTotals'];
            $jsonForJs[$document['date']]['arpu_this_day'] = $document['arpu_this_day'];
            $jsonForJs[$document['date']]['mobile_percent_this_day'] = $document['mobile_percent_this_day'];
        }
        return $jsonForJs;
    }
    
    /**
     * Trim the 30 day data points down to only the requested date range
     *
     * @param array $data Incoming data for each day for this client
     * @return array
     */
    public function parseDataForDisplay($data)
    {
        /**
        * This file is part of the array_column library
        *
        * For the full copyright and license information, please view the LICENSE
        * file that was distributed with this source code.
        *
        * @copyright Copyright (c) 2013 Ben Ramsey <http://benramsey.com>
        * @license http://opensource.org/licenses/MIT MIT
        */

        if (!function_exists('array_column')) {
            
            function array_column($input = null, $columnKey = null, $indexKey = null)
            {
                // Using func_get_args() in order to check for proper number of
                // parameters and trigger errors exactly as the built-in array_column()
                // does in PHP 5.5.
                $argc = func_num_args();
                $params = func_get_args();
        
                if ($argc < 2) {
                    trigger_error("array_column() expects at least 2 parameters, {$argc} given", E_USER_WARNING);
                    return null;
                }
        
                if (!is_array($params[0])) {
                    trigger_error('array_column() expects parameter 1 to be array, ' . gettype($params[0]) . ' given', E_USER_WARNING);
                    return null;
                }
        
                if (!is_int($params[1])
                    && !is_float($params[1])
                    && !is_string($params[1])
                    && $params[1] !== null
                    && !(is_object($params[1]) && method_exists($params[1], '__toString'))
                ) {
                    trigger_error('array_column(): The column key should be either a string or an integer', E_USER_WARNING);
                    return false;
                }
        
                if (isset($params[2])
                    && !is_int($params[2])
                    && !is_float($params[2])
                    && !is_string($params[2])
                    && !(is_object($params[2]) && method_exists($params[2], '__toString'))
                ) {
                    trigger_error('array_column(): The index key should be either a string or an integer', E_USER_WARNING);
                    return false;
                }
                
                $paramsInput = $params[0];
                $paramsColumnKey = ($params[1] !== null) ? (string) $params[1] : null;
                
                $paramsIndexKey = null;
                if (isset($params[2])) {
                    if (is_float($params[2]) || is_int($params[2])) {
                        $paramsIndexKey = (int) $params[2];
                    } else {
                        $paramsIndexKey = (string) $params[2];
                    }
                }
        
                $resultArray = array();
                
                foreach ($paramsInput as $row) {
        
                    $key = $value = null;
                    $keySet = $valueSet = false;
        
                    if ($paramsIndexKey !== null && array_key_exists($paramsIndexKey, $row)) {
                        $keySet = true;
                        $key = (string) $row[$paramsIndexKey];
                    }
                    
                    
                    if ($paramsColumnKey === null) {
                        $valueSet = true;
                        $value = $row;
                    } elseif (is_array($row) && array_key_exists($paramsColumnKey, $row)) {
                        $valueSet = true;
                        $value = $row[$paramsColumnKey];
                    }
                    
                    if ($valueSet) {
                        if ($keySet) {
                            $resultArray[$key] = $value;
                        } else {
                            $resultArray[] = $value;
                        }
                    }
                }
                return $resultArray;
            }
        }
        
        /**
         * Changes array keys in multi-dimensional array from YYYY-MM-DD to YYYYMMDD
         *
         * @param array $badlyFormattedDataArray
         */
        // Make sure this function doesn't get defined multiple times
        if (!function_exists('formatArrayKeys')) {
            function formatArrayKeys(&$badlyFormattedDataArray)
            {
                $badlyFormattedDataArray = array_combine(array_map(function($string)
                                            {
                                                return str_replace("-","",$string);
                                            },
                                            array_keys($badlyFormattedDataArray)),
                                            array_values($badlyFormattedDataArray)
                                            );
                foreach($badlyFormattedDataArray as $k=>$v)
                {
                    if(is_array($v)) {
                        formatArrayKeys($badlyFormattedDataArray[$k]);
                    }
                }
            }
        }
        
        $startDate = $data['dateStart'];
        $endDate = $data['dateEnd'];
        // Sort the data by date
        ksort($data);
        
        // Format the documents from each day
        foreach ($data as $key => $dataPoint) {
            if (is_array($dataPoint)) {
                formatArrayKeys($data[$key]);
            }
        }
        // Remove all of the data points we won't actually be using for display from the 30 daily totals
        foreach ($data as $k=>$document) {
            // No, no, don't do --$k, we need $k to be left alone here to change the actual $data array
            $theDayBefore = $k -1;
            $dateToFilter = array($theDayBefore);
            if (!is_array($document)) {
                continue;
            }
            foreach ($document as $key=>$value) {
                if ($key == 'monthlyTotals') {
                    foreach ($value as $a=>$b) {
                        $data[$a][$k] = $b;
                    }
                } elseif (is_array($value)) {
                    $data[$k][$key] = array_intersect_key($document[$key], array_flip($dateToFilter));
                }
            }
        }
        
        $data['arpu_this_day'] = array();
        $data['mobile_percent_this_day'] = array();
        // Finally, place each of the individual data sets into their own key
        // If only I had read up on how CActiveDataProvider worked *before* structuring the initial array, grumble, grumble...
        foreach ($data as $k=>$v) {
            if (is_array($v) && !empty($v)) {
                if ((isset($v['arpu_this_day']) && !empty($v['arpu_this_day'])) &&
                    (isset($v['mobile_percent_this_day']) && !empty($v['mobile_percent_this_day']))
                    ) {
                    $data['arpu_this_day'][$k] = $v['arpu_this_day'];
                    $data['mobile_percent_this_day'][$k] = $v['mobile_percent_this_day'];
                }
            }
        }
        
        $finalSortDaily = array('num_ad_clicks_daily', 'num_page_views_daily', 'num_customer_actions_daily', 'has_ads_daily');
        foreach ($finalSortDaily as $final) {
            $columnReturn = array_column($data, $final);
            foreach ($columnReturn as $k=>$v) {
                $data[$final][key($v)] = array_shift($v);
            }
        }
        foreach ($data as $k=>$v) {
            if( preg_match("/\d{8}/", $k)) {
                unset($data[$k]);
            }
        }
        
        return $data;
    }
    
    
    /**
     * Prepare createdAt and updatedAt attributes before saving a record.
     * This method is invoked before saving a record (after validation, if any).
     * The default implementation raises the {@link onBeforeSave} event.
     * Use {@link isNewRecord} to determine whether the saving is
     * for inserting or updating record.
     * Make sure you call the parent implementation so that the event is raised properly.
     * @return boolean whether the saving should be executed. Defaults to true.
     */
    protected function beforeSave()
    {
        if ($this->getIsNewRecord()) {
            echo 'Inserting new MongoDB document';
            echo "\n";
        } else {
            echo 'Updating existing MongoDb document';
            echo "\n";
        }
    
        return parent::beforeSave();
    }
    
    protected function afterSave()
    {
        echo 'Saved MongoDb document just fine';
        echo "\n";
        
        return parent::afterSave();
    }
}