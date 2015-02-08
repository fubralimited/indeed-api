<?php
/**
 * Indeed API Class
 * https://github.com/fubralimited/indeed-api
 *
 * PHP class to interact with the Indeed API
 * https://ads.indeed.com/jobroll/
 *
 * @package  indeed-api-class
 * @license  http://opensource.org/licenses/MIT
 * @version  1.0.8
 */
class IndeedAPI {

    /**
     * API version we are using
     * @var  integer
     */
    private $version = 2;

    /**
     * Publisher ID for affiliates
     * @var  integer
     */
    private $pubID;

    /**
     * URL of API server
     * @var  string
     */
    private $API_URL = 'http://api.indeed.com/ads/apisearch';

    /**
     * @var string
     */
    private $lastQueryURL = '';

    /**
     * Default parameters for the API
     * @var  array
     * @see https://ads.indeed.com/jobroll/xmlfeed
     */
    private $defaultParams = array(
        'q' => '',
        'l' => '',
        'sort' => 'date',
        'radius' => '15',
        'st' => '',
        'jt' => '',
        'start' => '0',
        'limit' => '20',
        'highlight' => '0',
        'filter' => '1',
        'fromage' => '1',
        'latlong' => '1',
        'co' => 'us',
        'chnl' => '',
        'format' => 'json'
    );


    /**
     * Default constructor; sets the publisher ID and the format
     * @param  integer  $pubID   Publisher ID from Indeed
     * @param  string   $format  Format of data
     */
    public function __construct( $pubID, $format = '' )
    {
        // Pass in pubisher ID as integer
        $this->pubID = (int)$pubID;
        // Check that argument is either `json` or `xml`
        if( in_array(strtolower($format), array('json', 'xml')) ) {
            $this->setParams( array('format' => strtolower($format)) );
        }
    }


    /**
     * Set the default parameters
     * @param  array  $params  Parameters you want to use
     */
    public function setParams($params = array())
    {
        $this->defaultParams = array_merge($this->defaultParams, $params);
    }


    /**
     * Get the default parameters
     * @return  array
     */
    public function getParams()
    {
        return $this->defaultParams;
    }


    /**
     * Query Indeed for jobs
     * @param   mixed    $params  Job search query or a number of different parameters
     * @param   boolean  $raw     Return the raw query (document)
     * @return  mixed
     */
    public function query($params, $raw=false)
    {
        $url = $this->API_URL . '?publisher=' . $this->pubID . '&v=' . $this->version;

        if (is_array($params)) {
            $url .= $this->makeURI($params);
        } elseif (is_string($params)) {
            $url .= $this->makeURI(array('q' => $params));
        }

        $this->lastQueryURL = $url;

        try {
            if ($raw === false && $this->defaultParams['format'] === 'json') {
                $results = file_get_contents($url);
                $results = json_decode($results, true); // as array
            } elseif ($raw === false && $this->defaultParams['format'] === 'xml') {
                $results = simplexml_load_file($url);
            } else {
                $results = file_get_contents($url);
            }
        } catch(ErrorException $error) {
            return $error;
        }

        return $results;
    }


    /**
     * Returns the URL used in last request to API
     * @return  string
     */
    public function getLastUrl() {
        return $this->lastQueryURL;
    }


    /**
     * Builds the URI based on the passed and default parameters
     * @param   array   $params  Parameters
     * @return  string
     */
    private function makeURI($params = array())
    {
        $params = array_merge($this->defaultParams, $params);
        $uri = '';

        foreach ($params as $key => $value) {
            if (isset($this->defaultParams[$key])) {
                $uri .= '&' . $key . '=' . urlencode($value);
            }
        }
        $uri .= '&userip=';
        if (isset($_SERVER['REMOTE_ADDR'])) {
            $uri .= urlencode($_SERVER['REMOTE_ADDR']);
        } elseif (isset($_SERVER['SERVER_ADDR'])) {
            $uri .= urlencode($_SERVER['SERVER_ADDR']);
        } else {
            $uri .= urlencode('1.2.3.4');
        }
        $uri .= '&useragent=' . (isset($_SERVER['HTTP_USER_AGENT']) ?
                urlencode($_SERVER['HTTP_USER_AGENT']) : urlencode('Mozilla/5.0(Firefox)'));

        return $uri;
    }

}
