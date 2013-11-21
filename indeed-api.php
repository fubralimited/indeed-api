<?php
/**
 * Indeed API Class
 *
 * PHP class to interact with the Indeed API
 * https://ads.indeed.com/jobroll/
 *
 * @package  indeed-api-class
 * @license  http://opensource.org/licenses/MIT
 * @version  1.0.0
 */
class IndeedAPI {

    /**
     * API version we are using
     *
     * @var  integer
     */
    private $version = 2;

    /**
     * Publisher ID for affiliates
     *
     * @var  integer
     */
    private $pubID;

    /**
     * Format which data is returns; either `json` or `xml`
     * Default is `json` for ease
     *
     * @var  string
     */
    private $format = 'json';

    /**
     * Root URL
     *
     * @var  string
     */
    private $rootURL = 'http://api.indeed.com/ads/apisearch';

    /**
     * URL of API
     *
     * @var  string
     */
    private $url = '';

    /**
     * Parameters that the feed will accept; see a list of descriptions at
     * https://ads.indeed.com/jobroll/xmlfeed
     *
     * @var  array
     */
    private $acceptedParams = array(
        'q',
        'l',
        'sort',
        'radius',
        'st',
        'jt',
        'start',
        'limit',
        'highlight',
        'filter',
        'latlong',
        'co',
        'chnl',
    );

    /**
     * Default parameters for the API
     *
     * @var  array
     */
    private $defaultParams = array();


    /**
     * Default constructor; sets the publisher ID and the format
     *
     * @param  integer  $pubID   Publisher ID from Indeed
     * @param  string   $format  Format of data
     */
    public function __construct( $pubID, $format = '' ) {

        // Pass in pubisher ID as integer
        $this->pubID = (int)$pubID;

        // Check that argument is either `json` or `xml`
        if( in_array( strtolower( $format ), array('json', 'xml') ) )
            $this->format = strtolower( $format );

    }


    /**
     * Set the default parameters
     *
     * @param  array  $params  Parameters you want to use
     */
    public function setDefaultParams( $params = array() ) {

        $this->defaultParams = $params;

    }


    /**
     * Get the default parameters
     *
     * @return  array
     */
    public function getDefaultParams() {

        return $this->defaultParams;

    }


    /**
     * Query Indeed for jobs
     *
     * @param   mixed    $params  Job search query or a number of different
     *                            parameters
     * @param   boolean  $raw     Return the raw query (document)
     *
     * @return  mixed
     */
    public function query( $params, $raw = false ) {

        $url = $this->rootURL .
            '?publisher=' . $this->pubID   .
            '&v='         . $this->version .
            '&format='    . $this->format;

        if( is_array( $params) ){
            $url .= $this->makeURI( $params );
        } elseif ( is_string( $params ) ) {
            $url .= $this->makeURI( array('q' => $params) );
        }

        $this->url = $url;

        if( $raw === false && $this->format === 'json') {
            $file   = file_get_contents( $url );
            $output = json_decode( $file );
        } elseif( $raw === false && $this->format === 'xml' ) {
            $output = simplexml_load_file( $url );
        } else {
            $output = file_get_contents( $url );
        }

        return $output;

    }


    /**
     * Returns the URL used to request the API
     *
     * @return  string
     */
    public function getURL() {

        return $this->URL;

    }


    /**
     * Builds the URI based on the passed parameters and default
     *
     * @param   array   $params  Parameters
     *
     * @return  string
     */
    private function makeURI( $params = array() ) {

        $params = array_merge( $this->defaultParams, $params );
        $uri    = '';

        foreach( $params as $key => $value ) {
            if( in_array( $key, $this->acceptedParams ) )
                $uri .= '&' . $key . '=' . urlencode( $value );
        }

        if( isset( $_SERVER['REMOTE_ADDR'] ) )
            '&userip=' . urlencode( $_SERVER['REMOTE_ADDR'] );

        if( isset( $_SERVER['HTTP_USER_AGENT'] ) )
            '&useragent=' . urlencode( $_SERVER['HTTP_USER_AGENT'] );

        return $uri;

    }

}
