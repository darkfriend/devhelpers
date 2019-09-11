<?php
/**
 * PHP curl helper package
 * @author darkfriend <hi@darkfriend.ru>
 * @version 1.0.0
 * @since 1.4.0
 */

namespace darkfriend\devhelpers;


class CurlHelper
{
    /** @var self */
    private static $_instance;
    /** @var resource */
    private $_ch;

    /** @var array */
    protected $headers = [];
    /** @var int */
    protected $timeout = 60;
    /** @var bool */
    protected $debug = false;
    /** @var string */
    protected $debugFile = '';

    /** @var int */
    public $lastCode = 0;
    /** @var string */
    public $lastHeaders = '';
    /** @var string */
    public $lastError;

    /**
     * Singleton
     * @param bool $newSession create new instance
     * @param array $options
     * @return self
     * @throws \Exception
     */
    public static function getInstance($newSession = false, $options = [])
    {
        if (!function_exists('curl_init')) {
            throw new \Exception('Curl is not found!');
        }
        if (!self::$_instance || $newSession) {
            self::$_instance = new self($options);
        }
        return self::$_instance;
    }

    /**
     * CurlHelper constructor.
     * @param array $options
     */
    protected function __construct($options = [])
    {
        if ($options) {
            foreach ($options as $key => $option) {
                if (substr($key, 0, 1) == '_') continue;
                if (isset($this->{$key})) {
                    $this->{$key} = $option;
                }
            }
        }
    }

    /**
     * Add http headers
     * @param array $headers key=>$value
     * @return $this
     * @example [[$headers]] ['Accept-Language'=>'en-US']
     */
    public function addHeaders(array $headers)
    {
        $this->headers = array_merge($this->headers, $headers);
        return $this;
    }

    /**
     * Set headers to curl
     * @return $this
     */
    protected function setHeaders()
    {
        if (empty($this->headers['Accept-Language'])) {
            $this->headers['Accept-Language'] = 'ru';
        }
        $headers = [];
        foreach ($this->headers as $key => $header) {
            if ($header) {
                $headers[] = "$key: $header";
            }
        }
        curl_setopt(
            $this->_ch,
            CURLOPT_HTTPHEADER,
            $headers
        );
        return $this;
    }

    /**
     * Return curl-resource and set headers
     * @param string $method http-method
     * @return false|resource
     * @throws \Exception
     */
    protected function setCurl($method = 'post')
    {
        if (!function_exists('curl_init')) {
            throw new \Exception('curl is not found!');
        }
        if (empty($this->_ch)) {
            $this->_ch = curl_init();
            curl_setopt($this->_ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($this->_ch, CURLOPT_FOLLOWLOCATION, true);
            if ($method == 'post') {
                curl_setopt($this->_ch, CURLOPT_POST, 1);
            }
            curl_setopt($this->_ch, CURLOPT_HEADER, 1);
            curl_setopt($this->_ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($this->_ch, CURLOPT_TIMEOUT, $this->timeout);
            curl_setopt($this->_ch, CURLOPT_CONNECTTIMEOUT, $this->timeout);
        }
        return $this->_ch;
    }

    /**
     * Do request
     * @param string $url
     * @param array $data request data
     * @param string $method request method (post)
     * @param string $requestType request content-type (text)
     * @param string $responseType response content-type (json)
     * @return mixed
     * @throws \Exception
     */
    public function request($url, $data = [], $method = 'post', $requestType = '', $responseType = 'json')
    {
        $this->clear();
        $this->setCurl($method);
        if ($method == 'get' && $data) {
            $data = http_build_query($data);
            $url .= '?' . $data;
        }
        curl_setopt($this->_ch, CURLOPT_URL, $url);

        if ($requestType == 'json' && $data) {
            $data = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }
        if ($method == 'post') {
            $data = http_build_query($data);
            curl_setopt($this->_ch, CURLOPT_POSTFIELDS, $data);
        }

        if ($requestType == 'json') {
            $this->headers['Content-Type'] = 'application/json; charset=utf-8';
        } elseif (!empty($requestType)) {
            $this->headers['Content-Type'] = "$requestType; charset=utf-8";
        }

        $this->setHeaders();

        if ($this->debug) {
            $this->debug([
                'debugEvent' => 'before-request',
                'url' => $url,
                'requestHeaders' => $this->headers,
                'requestData' => $data,
                'requestType' => $requestType,
                'responseType' => $responseType,
            ]);
        }

        $response = curl_exec($this->_ch);

        $header_size = curl_getinfo($this->_ch, CURLINFO_HEADER_SIZE);
        $this->lastHeaders = substr($response, 0, $header_size);
        $this->lastCode = (int)curl_getinfo($this->_ch, CURLINFO_HTTP_CODE);

        curl_close($this->_ch);

        $body = substr($response, $header_size);

        if ($this->debug) {
            $this->debug([
                'debugEvent' => 'after-request',
                'url' => $url,
                'requestHeaders' => $this->headers,
                'requestData' => $data,
                'code' => $this->lastCode,
                'responseHeaders' => $this->lastHeaders,
                'requestType' => $requestType,
                'responseType' => $responseType,
                'body' => $body,
            ]);
        }

        if ($responseType == 'json' && $body) {
            $body = \json_decode($body, true);
        }

        if ($this->debug) {
            $this->debug([
                'debugEvent' => 'result',
                'body' => $body,
            ]);
        }

        return $body;
    }

    /**
     * Clear last request
     */
    protected function clear()
    {
        $this->lastCode = 0;
        $this->lastError = '';
        $this->lastHeaders = '';
        unset($this->_ch);
    }

    /**
     * Debug
     * @param mixed $data
     * @return void
     */
    protected function debug($data)
    {
        DebugHelper::traceInit('curl', DebugHelper::TRACE_MODE_APPEND, $this->debugFile);
        DebugHelper::trace($data);
    }
}