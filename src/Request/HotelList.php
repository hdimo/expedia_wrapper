<?php

namespace Jkhaled\Expedia\Request;

use Symfony\Component\OptionsResolver\OptionsResolver;

use Jkhaled\Expedia\AllowedType;

class HotelList extends AbstractExpediaRequest implements RequestInterface
{
    use RoomTrait;

    /** @var array $default_options */
    private $default_options = [
        "date_in"=>"",
        "date_out"=>"",
        "city_code"=>"",
        "country_code"=>"",
        "rooms"=>[],
        "hotel_ids"=>"",
        "latitude"=>"",
        "longitude"=>"",
        'include_details' => true,
        'include_hotel_fee_breakdown' => true,
        'number_of_result' => 25,
        'sort' => 'BUDGET',
    ];

    /**
     * HotelList constructor.
     * @param array $options
     */
    public function __construct(array $options)
    {
        parent::__construct($this->default_options);
        $this->configureOptions($options);
    }

    /**
     * @inheritdoc
     */
    public function configureOptions(array $options)
    {
        $resolver = new OptionsResolver();
        $resolver->setDefaults($this->options);
        $resolver->setRequired(['date_in', 'date_out', 'rooms']);

        $resolver->setAllowedValues('date_in', function ($value){
            return AllowedType::AllowedDate($value);
        });

        $resolver->setAllowedValues('date_out', function ($value) {
            return AllowedType::AllowedDate($value);
        });
        //$resolver->setAllowedTypes('rooms', 'array');

        $this->options = $resolver->resolve($options);
    }

    /**
     * @inheritdoc
     */
    public function getMethod() : string
    {
        return 'list';
    }

    /**
     * @inheritdoc
     */
    public function prepareRequest() : string
    {

        $xml = <<<XML
<HotelListRequest>
    <apiExperience>{$this->options['apiExperience']}</apiExperience>
    <arrivalDate>{$this->options['date_in']}</arrivalDate>
    <departureDate>{$this->options['date_out']}</departureDate>
    <numberOfResults>{$this->options['number_of_result']}</numberOfResults>
    <RoomGroup>         
        {$this->getRooms($this->options['rooms'])}
    </RoomGroup>
    <includeDetails>true</includeDetails>
    <includeHotelFeeBreakdown>true</includeHotelFeeBreakdown>
    <sort>{$this->options['sort']}</sort>
    {$this->getLocation()}
</HotelListRequest>
XML;
        $xml = preg_replace('/(\>)\s*(\<)/m', '$1$2', $xml);
        $this->xml = $xml;
        return trim($this->xml);
    }

    /**
     * get xml location  based on parameter
     *
     * @return string
     */
    public function getLocation() : string
    {
        $locations = "";
        if(
            isset($this->options['city_code']) &&
            isset($this->options['country_code'])
        ){
            $locations = "<city>{$this->options['city_code']}</city>".
                         "<countryCode>{$this->options['country_code']}</countryCode>";
        }elseif (isset($this->options['hotel_ids'])){
            $hotel_ids = implode(",", $this->options['hotel_ids']);
            $locations = "<hotelIdList>{$hotel_ids}</hotelIdList>";
        }elseif(
            isset($this->options['latitude']) &&
            isset($this->options['longitude'])
        ){
            $locations = "<latitude>{$this->options['latitude']}</latitude>".
                        "<longitude>{$this->options['longitude']}</longitude>";
            if(isset($this->options['radius'])){
                $locations .= "<searchRadius>{$this->options['radius']}</searchRadius>";
            }
        }

        //TODO handle free text destination <destinationString>
        return $locations;
    }

}