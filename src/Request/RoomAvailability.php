<?php
/**
 * Created by PhpStorm.
 * User: khaled
 * Date: 8/6/18
 * Time: 9:33 AM
 */

namespace Jkhaled\Expedia\Request;


use Jkhaled\Expedia\AllowedType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RoomAvailability extends AbstractExpediaRequest implements RequestInterface
{
    use RoomTrait;

    const SHOW_ROOMS = "SHOW_ROOMS";
    const VERIFY_SELECTED_ROOM = "VERIFY_SELECTED_ROOM";

    private $available_request_type = [
        self::VERIFY_SELECTED_ROOM,
        self::SHOW_ROOMS
    ];

    /** @var array $default_options */
    protected $default_options = [
        "type_of_request" => self::SHOW_ROOMS,
        "hotelId" => "",
        "date_in" => "",
        "date_out" => "",
        "rooms" => [],
        "includeHotelFeeBreakdown" => "false",
        "include_details" => "false",
    ];

    protected $default_option_show_rooms = [
        "includeRoomImages" => "true",
        "options" => '',
    ];

    protected $default_option_verify_room = [
        "rateCode" => '',
        "roomTypeCode" => '',
    ];

    /**
     * RoomAvailability constructor.
     *
     * @param array $options
     */
    public function __construct(array $options)
    {

        if (!isset($options['type_of_request']) || !in_array($options['type_of_request'], $this->available_request_type)) {
            throw new \InvalidArgumentException(sprintf("key '%s' is required and should be equal to '%s' or '%s'",
                'type_of_request',
                self::SHOW_ROOMS,
                self::VERIFY_SELECTED_ROOM
            ));
        }

        $this->default_options = self::SHOW_ROOMS == $options['type_of_request'] ?
            array_merge($this->default_options, $this->default_option_show_rooms) :
            array_merge($this->default_options, $this->default_option_verify_room);

        parent::__construct($this->default_options);
        $this->configureOptions($options);
    }

    /**
     * @inheritdoc
     */
    public function getMethod():string
    {
        return 'avail';
    }

    /**
     * @inheritdoc
     */
    protected function configureOptions(array $options)
    {
        $resolver = new OptionsResolver();
        $resolver->setDefaults($this->options);

        $resolver->setRequired(array_keys($this->default_options));
        $resolver->setAllowedTypes('hotelId', 'int');

        $resolver->setAllowedValues('date_in', function ($value) {
            return AllowedType::AllowedDate($value);
        });
        $resolver->setAllowedValues('date_out', function ($value) {
            return AllowedType::AllowedDate($value);
        });
        $resolver->setAllowedValues('rooms', function ($value) {
            return AllowedType::AllowedRoom($value);
        });

        if(self::VERIFY_SELECTED_ROOM == $options['type_of_request']){
            $resolver->setRequired(array_keys($this->default_option_verify_room));
        }

        $this->options = $resolver->resolve($options);
    }

    /**
     * @inheritdoc
     */
    public function prepareRequest()
    {
        $xml = "<HotelRoomAvailabilityRequest>
                    <hotelId>{$this->options['hotelId']}</hotelId>
                    <arrivalDate>{$this->options['date_in']}</arrivalDate>
                    <departureDate>{$this->options['date_out']}</departureDate>
                    <includeDetails>{$this->options['include_details']}</includeDetails>
                    <RoomGroup>
                        {$this->getRooms($this->options['rooms'])} 
                    </RoomGroup>
                    {$this->getXmlBasedOnTypeRequest()}
                </HotelRoomAvailabilityRequest>";
        $this->xml = $xml;
        return $this->xml;
    }

    /**
     * @return string
     */
    public function getXmlBasedOnTypeRequest(): string
    {
        $xml = "";
        if ($this->options['type_of_request'] == self::VERIFY_SELECTED_ROOM) {
            $xml = "<roomTypeCode>{$this->options['roomTypeCode']}</roomTypeCode>" .
                "<rateCode>{$this->options['rateCode']}</rateCode>";
        } else {
            $xml = "<includeRoomImages>{$this->options['includeRoomImages']}</includeRoomImages>" .
                "<includeHotelFeeBreakdown>{$this->options['includeHotelFeeBreakdown']}</includeHotelFeeBreakdown>" .
                "<options>{$this->options['options']}</options>";
        }
        return $xml;
    }

}