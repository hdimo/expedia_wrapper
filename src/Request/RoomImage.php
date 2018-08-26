<?php
/**
 * Created by PhpStorm.
 * User: khaled
 * Date: 8/6/18
 * Time: 6:56 PM
 */

namespace Jkhaled\Expedia\Request;


use Symfony\Component\OptionsResolver\OptionsResolver;

class RoomImage extends AbstractExpediaRequest implements RequestInterface
{

    protected $default_options = [
        'hotelId'=>''
    ];

    public function __construct(array $options)
    {
        parent::__construct($this->default_options);
        $this->configureOptions($options);
    }

    protected function configureOptions(array $options)
    {
        $resolver = new OptionsResolver();
        $resolver->setDefined(array_keys($this->default_options));
        $resolver->setRequired(array_keys($this->default_options));
        $this->options = $resolver->resolve($options);
    }

    public function getMethod()
    {
        return 'roomImages';
    }

    public function prepareRequest()
    {
        $xml =  "<RoomImageRequest>".
                "<hotelId>{$this->options['hotelId']}</hotelId>".
                "</RoomImageRequest>";
        $this->xml = $xml;
        return $this->xml;
    }
}