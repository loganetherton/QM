<?php
/**
 * Yelp Api wrapper.
 *
 * @author Jakub Pospiech <jakub.pospiech@gmail.com>
 */
Yii::import('ext.yelp.lib.OAuth', true);

class YelpComponent extends CComponent
{
    const SEARCH_API    = 'http://api.yelp.com/v2/search';
    const BUSINESS_API  = 'http://api.yelp.com/v2/business';

    /**
     * @var string $consumerKey Yelp consumer key.
     */
    public $consumerKey;

    /**
     * @var string $consumerSecret Yelp consumer secret.
     */
    public $consumerSecret;

    /**
     * @var string $token Yelp token.
     */
    public $token;

    /**
     * @var string $tokenSecret Yelp token secret.
     */
    public $tokenSecret;

    /**
     * @var object $oAuthToken OAuth token.
     */
    protected $oAuthToken;

    /**
     * @var object $oAuthConsumer OAuth consumer.
     */
    protected $oAuthConsumer;

    /**
     * @var object $oAuthSignatureMethod OAuth signature method.
     */
    protected $oAuthSignatureMethod;

    /**
     * Initializes the YelpApi component.
     * @throws CException
     */
    public function init()
    {
        if($this->consumerKey === null) {
            throw new CException(Yii::t('yelp', 'The "consumerKey" property cannot be empty.'));
        }

        if($this->consumerSecret === null) {
            throw new CException(Yii::t('yelp', 'The "consumerSecret" property cannot be empty.'));
        }

        if($this->token === null) {
            throw new CException(Yii::t('yelp', 'The "token" property cannot be empty.'));
        }

        if($this->tokenSecret === null) {
            throw new CException(Yii::t('yelp', 'The "tokenSecret" property cannot be empty.'));
        }

        $this->oAuthConsumer = new OAuthConsumer($this->consumerKey, $this->consumerSecret);
        $this->oAuthToken = new OAuthToken($this->token, $this->tokenSecret);

        $this->oAuthSignatureMethod = new OAuthSignatureMethod_HMAC_SHA1;

		ini_set('max_execution_time', 0); 
    }

    /**
     * Search for local businesses.
     * @param array $params Search params
     * @return array Api results
     * @link http://www.yelp.com/developers/documentation/v2/search_api Yelp search API
     */
    public function search($params)
    {
        $url = self::SEARCH_API . $this->queryString($params);

        return $this->callYelpApi($url);
    }

    /**
     * Lookup business information by id.
     * @param string $id Business id
     * @return array Api results
     * @link http://www.yelp.com/developers/documentation/v2/business Yelp business API
     */
    public function business($id)
    {
        $url = self::BUSINESS_API . '/' . $id;

        return $this->callYelpApi($url);
    }

    /**
     * Call Yelp Api.
     * @param string $url URL to be called
     * @return array Api response
     */
    protected function callYelpApi($url)
    {
        $oAuthRequest = OAuthRequest::from_consumer_and_token($this->oAuthConsumer, $this->oAuthToken, 'GET', $url);
        $oAuthRequest->sign_request($this->oAuthSignatureMethod, $this->oAuthConsumer, $this->oAuthToken);

        $signedUrl = $oAuthRequest->to_url();

        $response = $this->remote($signedUrl);
		
		//print_r($response);

        return CJSON::decode($response);
    }

    /**
     * Returns the output of a remote URL. Any curl option may be used.
     * @see http://php.net/curl_setopt
     *
     *     // Do a simple GET request
     *     $data = YelpApi::remote($url);
     *
     *     // Do a POST request
     *     $data = YelpApi::remote($url, array(
     *         CURLOPT_POST       => true,
     *         CURLOPT_POSTFIELDS => http_build_query($array),
     *     ));
     *
     * @param string $url Remote URL
     * @param array $options Curl options
     * @return string Api response
     * @throws CException
     */
    protected function remote($url, $options = null)
    {
        // The transfer must always be returned
        $options[CURLOPT_RETURNTRANSFER] = true;

        // Open a new remote connection
        $remote = curl_init($url);

        // Set connection options
        if(!curl_setopt_array($remote, $options)) {
            throw new CException(Yii::t('yelp', 'Failed to set CURL options, check CURL documentation: :url',
                    array(':url'=>'http://php.net/curl_setopt_array')));
        }

        // Get the response
        $response = curl_exec($remote);

        // Get the response information
        $code = curl_getinfo($remote, CURLINFO_HTTP_CODE);

        if($code && $code < 200 || $code > 299) {
            if($code == 404) {
                // 404 has to be handeled separately because Yelp doesn't return it as an Api error.
                $error = 'Not Found';
            }
            elseif($code == 400) {
                
                $response = '';
            }else {
                $error = $response;
            }
        }
        elseif($response === false) {
            $error = curl_error($remote);
        }

        // Close the connection
        curl_close($remote);

        if(isset($error)) {
            throw new CException(Yii::t('yelp', 'Error fetching Yelp Api [Status :code] [Error: :error]',
                array(':code'=>$code, ':error'=>$error)));
			
        }

        return $response;
    }

    /**
     * Generate URL-encoded query string.
     *
     *     // Returns "?sort=title&limit=10"
     *     $query = YelpApi::query(array('sort'=>'title','limit'=>10));
     *
     * Typically you would use this when you are sorting query results,
     * or something similar.
     *
     * [!!] Parameters with a NULL value are left out.
     *
     * @param array $params Array of GET parameters
     * @return string Query string
     */
    protected function queryString(array $params = null)
    {
        if(empty($params)) {
            // No query parameters
            return '';
        }

        // Note: http_build_query returns an empty string for a params array with only NULL values
        $query = http_build_query($params, '', '&');

        // Don't prepend '?' to an empty string
        return ($query === '') ? '' : ('?'.$query);
    }
}