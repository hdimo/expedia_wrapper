<?php
/**
 * Created by PhpStorm.
 * User: khaled
 * Date: 8/5/18
 * Time: 6:31 PM
 */

namespace Jkhaled\Expedia\Request;


abstract class AbstractExpediaRequest
{

    /** @var array */
    protected $apiExperience = [
        "PARTNER_CALL_CENTER" => "PARTNER_CALL_CENTER",
        "PARTNER_WEBSITE" => "PARTNER_WEBSITE",
        "PARTNER_MOBILE_WEB" => "PARTNER_MOBILE_WEB",
        "PARTNER_MOBILE_APP" => "PARTNER_MOBILE_APP",
        "PARTNER_BOT_CACHE" => "PARTNER_BOT_CACHE",
        "PARTNER_BOT_REPORTING" => "PARTNER_BOT_REPORTING",
        "PARTNER_AFFILIATE" => "PARTNER_AFFILIATE",
    ];

    /** @var array */
    protected $common_default_options = [
        'apiExperience' => 'PARTNER_AFFILIATE',
    ];

    /** @var string */
    protected $xml;

    /** @var array */
    protected $options;

    /**
     * AbstractExpediaRequest constructor.
     * @param array $options
     */
    public function __construct(array $options)
    {
        $this->options = array_merge($this->common_default_options, $options);
    }

    public function getOptions()
    {
        return $this->options;
    }

    /**
     * configure default option and required with  OptionResolver
     *
     * @param array $options
     * @return mixed
     */
    abstract protected function configureOptions(array $options);

}