<?php

namespace Jkhaled\Expedia;

use Jkhaled\Expedia\Request\BookInterface;
use Jkhaled\Expedia\Request\RequestInterface;

class Expedia
{

    protected $protocol = 'http://';
    protected $protocol_ssl = 'https://';
    protected $url = 'api.ean.com/ean-services/rs/hotel/v3/';
    protected $hostBook = "book.api.ean.com/ean-services/rs/hotel/v3/";

    protected $key = "3v8k7mtirn35adrr5num6gr1mf";
    protected $secret = "1rv9nlimd3qri";
    protected $cid = "497429";
    protected $sig;

    protected $minor_rev = 99;
    protected $locale = 'en_US';
    protected $currency_code = 'USD';

    protected $customer_session_id;
    protected $customer_ip_address;
    protected $customer_user_agent;

    protected $request;
    protected $response;

    /** @var string  logging connection information */
    protected $verbose_log;

    /**
     * @return int
     */
    public function getMinorRev(): int
    {
        return $this->minor_rev;
    }

    /**
     * @param int $minor_rev
     */
    public function setMinorRev(int $minor_rev)
    {
        $this->minor_rev = $minor_rev;
    }

    /**
     * @return string
     */
    public function getLocale(): string
    {
        return $this->locale;
    }

    /**
     * @param string $locale
     */
    public function setLocale(string $locale)
    {
        $this->locale = $locale;
    }

    /**
     * @return string
     */
    public function getCurrencyCode(): string
    {
        return $this->currency_code;
    }

    /**
     * @param string $currency_code
     */
    public function setCurrencyCode(string $currency_code)
    {
        $this->currency_code = $currency_code;
    }

    /**
     * @return mixed
     */
    public function getCustomerSessionId()
    {
        return $this->customer_session_id;
    }

    /**
     * @param mixed $customer_session_id
     */
    public function setCustomerSessionId($customer_session_id)
    {
        $this->customer_session_id = $customer_session_id;
    }

    /**
     * @return mixed
     */
    public function getCustomerIpAddress()
    {
        return $this->customer_ip_address;
    }

    /**
     * @param mixed $customer_ip_address
     */
    public function setCustomerIpAddress($customer_ip_address)
    {
        $this->customer_ip_address = $customer_ip_address;
    }

    /**
     * @return mixed
     */
    public function getCustomerUserAgent()
    {
        return $this->customer_user_agent;
    }

    /**
     * @param mixed $customer_user_agent
     */
    public function setCustomerUserAgent($customer_user_agent)
    {
        $this->customer_user_agent = $customer_user_agent;
    }

    public function setRequest(RequestInterface $request)
    {
        $this->request = $request;
        return $this;
    }

    public function getRawRequest(): string
    {
        return $this->request->prepareRequest();
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function getLink(): string
    {
        if($this->request instanceof BookInterface){
            return $this->protocol_ssl . $this->hostBook. $this->request->getMethod();
        }
        return $this->protocol . $this->url . $this->request->getMethod();
    }

    public function send() :bool
    {
        $query = $this->getQuery();
        $url = $this->getLink() . '?' . $query;
        $headers = [
            "Accept: application/json"
        ];

        $isSecured = ($this->request instanceof  BookInterface);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_ENCODING, "gzip");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $isSecured);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, $isSecured);
        curl_setopt($ch, CURLOPT_POST, $isSecured);

        curl_setopt($ch, CURLOPT_FAILONERROR, true);
        curl_setopt($ch, CURLOPT_VERBOSE, true);

        $verbose = fopen('php://temp', 'rw+');
        curl_setopt($ch, CURLOPT_STDERR, $verbose);

        $result = curl_exec($ch);
        if (curl_error($ch)) {
            $error_msg = curl_error($ch);
        }
        curl_close($ch);

        !rewind($verbose);
        $this->verbose_log = stream_get_contents($verbose);

        if (isset($error_msg)) {
            // TODO - Handle cURL error accordingly
            return false;
        }
        $response = json_decode($result, true);
        $this->response = current($response);
        return true;
    }

    public function getQuery(): string
    {
        $this->generateSignature();

        $data = [
            'cid' => $this->cid,
            'apiKey' => $this->key,
            'sig' => $this->sig,
            'minorRev' => $this->minor_rev,

            'customerUserAgent' => $this->customer_user_agent,
            'customerIpAddress' => $this->customer_ip_address,

            'locale' => $this->locale,
            'currencyCode' => $this->currency_code,
            'xml' => $this->getRawRequest(),
        ];

        $query = http_build_query($data);
        return $query;
    }

    /**
     * @return string
     */
    public function generateSignature(): string
    {
        $timestamp = gmdate('U');
        $this->sig = md5($this->key . $this->secret . $timestamp);
        return $this->sig;
    }
}
