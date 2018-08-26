<?php
/**
 * Created by PhpStorm.
 * User: khaled
 * Date: 8/5/18
 * Time: 6:30 PM
 */

namespace Jkhaled\Expedia\Request;


use Symfony\Component\OptionsResolver\OptionsResolver;

class HotelInfo extends AbstractExpediaRequest implements RequestInterface
{


    protected $available_hotel_options = [
        "DEFAULT",
        "HOTEL_SUMMARY",
        "HOTEL_DETAILS",
        "SUPPLIERS",
        "ROOM_TYPES",
        "ROOM_AMENITIES",
        "PROPERTY_AMENITIES",
        "HOTEL_IMAGE",
    ];

    protected $default_options = [
        'hotelId' => '',
        'options' => 'DEFAULT',
    ];

    public function __construct(array $options)
    {
        parent::__construct($this->default_options);
        $this->configureOptions($options);
    }

    public function getMethod(): string
    {
        return 'info';
    }

    public function prepareRequest()
    {
        $xml = <<<XML
<HotelInformationRequest>
    <hotelId>{$this->options['hotelId']}</hotelId>
    <options>{$this->options['options']}</options>
</HotelInformationRequest>
XML;
        $this->xml = $xml;
        return $this->xml;
    }

    /**
     * @inheritdoc
     */
    public function configureOptions(array $options)
    {
        $resolver = new OptionsResolver();
        $resolver->setDefaults($this->options);
        $resolver->setRequired('hotelId');
        $resolver->setAllowedTypes('hotelId', 'int');
        $this->options = $resolver->resolve($options);
    }

}