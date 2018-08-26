<?php
/**
 * Created by PhpStorm.
 * User: khaled
 * Date: 8/8/18
 * Time: 3:38 PM
 */

namespace Jkhaled\Expedia\Request;


use Symfony\Component\OptionsResolver\OptionsResolver;

class CancelReservation extends AbstractExpediaRequest implements RequestInterface, BookInterface
{

    private $available_message_reason = [
        "HOC", //"Hotel asked me to cancel",
        "COP", //"Change of plans",
        "FBP", //"Found a better price",
        "FBH", //"Found a better hotel",
        "CNL", //"Decided to cancel my plans",
        "NSY", //"Rather not say",
        "OTH", //"Other",
    ];

    protected $default_option = [
         'itineraryId'=>'',
         'email'=>'',
         'reason'=>'',
         'confirmationNumber'=>'',
    ];

    public function __construct(array $options)
    {
        parent::__construct($this->default_option);
        $this->configureOptions($options);
    }

    public function getMethod(): string
    {
        return 'cancel';
    }

    public function prepareRequest()
    {
        $xml = "<HotelRoomCancellationRequest>
                    <itineraryId>{$this->options['itineraryId']}</itineraryId>
                    <email>{$this->options['email']}</email>
                    <reason>{$this->options['reason']}</reason>
                    <confirmationNumber>{$this->options['confirmationNumber']}</confirmationNumber>
                </HotelRoomCancellationRequest>";
        $this->xml = $xml;
        return $this->xml;
    }


    protected function configureOptions(array $options)
    {
        $resolver = new OptionsResolver();
        $resolver->setDefined(array_keys($this->default_option));
        $resolver->setRequired(['itineraryId','email','confirmationNumber']);
        $resolver->setAllowedValues('reason', function($value){
            return  in_array($value, $this->available_message_reason);
        });
        $this->options = $resolver->resolve($options);
    }
}