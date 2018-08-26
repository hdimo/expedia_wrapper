<?php
/**
 * Created by PhpStorm.
 * User: khaled
 * Date: 8/9/18
 * Time: 9:29 AM
 */

namespace Jkhaled\Expedia\Request;


use Symfony\Component\OptionsResolver\OptionsResolver;

class GetReservation extends AbstractExpediaRequest implements RequestInterface, BookInterface
{

    private $default_option = [
        "email"=>"",
        "itineraryId"=>"",
    ];

    public function __construct(array $options)
    {
        parent::__construct($this->default_option);
        $this->configureOptions($options);
    }

    public function getMethod(): string
    {
        return 'itin';
    }

    public function prepareRequest()
    {
        $xml = "
        <HotelItineraryRequest>
            <email>{$this->options['email']}</email>
            <itineraryId>{$this->options['itineraryId']}</itineraryId>
        </HotelItineraryRequest>";
        $this->xml = $xml;
    }

    protected function configureOptions(array $options)
    {
        $resolver = new OptionsResolver();
        $resolver->setDefined(array_keys($this->default_option));
        $resolver->setRequired(['email']);
        $this->options = $resolver->resolve($options);
    }
}