<?php
/**
 * Created by PhpStorm.
 * User: khaled
 * Date: 8/6/18
 * Time: 8:09 PM
 */

namespace Jkhaled\Expedia\Request;


use Symfony\Component\OptionsResolver\OptionsResolver;

class BookReservation extends AbstractExpediaRequest implements RequestInterface, BookInterface
{
    use RoomTrait;

    protected $default_options = [

        'hotelId' => '',
        'date_in' => '',
        'date_out' => '',

        "supplierType" => '',
        "rateKey" => '',
        "roomTypeCode" => '',
        "rateCode" => '',
        "chargeableRate" => '',

        "rooms" => [],
        "reservationInfo" => [
            "email" => "",
            "firstName" => "",
            "lastName" => "",
            "homePhone" => "",
            "creditCardType" => "",
            "creditCardNumber" => "",
            "creditCardIdentifier" => "",
            "creditCardExpirationMonth" => "",
            "creditCardExpirationYear" => "",
        ],
        "addressInfo" => [
            "address1" => "",
            "city" => "",
            "stateProvinceCode" => "",
            "countryCode" => "",
            "postalCode" => "",
        ]
    ];

    public function __construct(array $options)
    {
        parent::__construct($this->default_options);
        $this->configureOptions($options);
    }

    public function getMethod():string
    {
        return 'res';
    }

    protected function configureOptions(array $options)
    {
        $resolver = new OptionsResolver();
        $resolver->setDefaults($this->default_options);
        $resolver->setRequired(array_keys($this->default_options));

        $options['reservationInfo'] = $this->resolveOption($options['reservationInfo']);
        $options['addressInfo'] = $this->resolveOption($options['addressInfo']);

        $this->options = $resolver->resolve($options);
        return $this->options;
    }

    protected function resolveOption(array $args): array
    {
        $resolver = new OptionsResolver();
        $keys = array_keys($args);
        $resolver->setDefined($keys);
        $resolver->setRequired($keys);
        return $resolver->resolve($args);
    }

    public function prepareRequest()
    {

        $xml = "<HotelRoomReservationRequest>
                <hotelId>{$this->options['hotelId']}</hotelId>
                <arrivalDate>{$this->options['date_in']}</arrivalDate>
                <departureDate>{$this->options['date_out']}</departureDate>
                <supplierType>{$this->options['supplierType']}</supplierType>
                <rateKey>{$this->options['rateKey']}</rateKey>
                <roomTypeCode>{$this->options['roomTypeCode']}</roomTypeCode> 
                <rateCode>{$this->options['rateCode']}</rateCode>
                <chargeableRate>{$this->options['chargeableRate']}</chargeableRate>
                <RoomGroup>
                    {$this->getRooms($this->options['rooms'])}
                </RoomGroup>
                <ReservationInfo>
                    <email>{$this->options['reservationInfo']['email']}</email>
                    <firstName>{$this->options['reservationInfo']['firstName']}</firstName>
                    <lastName>{$this->options['reservationInfo']['lastName']}</lastName>
                    <homePhone>{$this->options['reservationInfo']['homePhone']}</homePhone>
                    <workPhone>{$this->options['reservationInfo']['workPhone']}</workPhone>
                    <creditCardType>{$this->options['reservationInfo']['creditCardType']}</creditCardType>
                    <creditCardNumber>{$this->options['reservationInfo']['creditCardNumber']}</creditCardNumber>
                    <creditCardIdentifier>{$this->options['reservationInfo']['creditCardIdentifier']}</creditCardIdentifier>
                    <creditCardExpirationMonth>{$this->options['reservationInfo']['creditCardExpirationMonth']}</creditCardExpirationMonth>
                    <creditCardExpirationYear>{$this->options['reservationInfo']['creditCardExpirationYear']}</creditCardExpirationYear>
                </ReservationInfo>
                <AddressInfo>
                    <address1>{$this->options['addressInfo']['address1']}</address1>
                    <city>{$this->options['addressInfo']['city']}</city>
                    <stateProvinceCode>{$this->options['addressInfo']['stateProvinceCode']}</stateProvinceCode>
                    <countryCode>{$this->options['addressInfo']['countryCode']}</countryCode>
                    <postalCode>{$this->options['addressInfo']['postalCode']}</postalCode>
                </AddressInfo>
            </HotelRoomReservationRequest>";
        $this->xml = $xml;
        return $this->xml;
    }
}