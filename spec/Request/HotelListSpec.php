<?php

namespace spec\Jkhaled\Expedia\Request;

use Jkhaled\Expedia\Request\HotelList;
use PhpSpec\ObjectBehavior;

use Jkhaled\Expedia\Request\RequestInterface;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;

class HotelListSpec extends ObjectBehavior
{
    function let()
    {
        $options = [
            'date_in' => '01/10/2019',
            'date_out' => '01/10/2019',
            'number_of_result' => 20,
            'rooms' => [],
            'include_details' => true,
            'include_hotel_fee_breakdown' => false,
            'city_code' => 'Paris',
            'country_code' => 'FR'
        ];
        $this->beConstructedWith($options);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(HotelList::class);
    }

    function it_should_implement_request_interface()
    {
        $this->shouldImplement(RequestInterface::class);
    }

    function it_should_return_method()
    {
        $this->getMethod()->shouldReturn('list');
    }

    function it_should_get_location_base_on_search_param()
    {
        $param = [
            "city_code" => "Paris",
            "country_code" => "FR",
        ];
        $expected = "<city>Paris</city>" . "<countryCode>FR</countryCode>";
        $this->getLocation()->shouldBe($expected);
    }


    function it_should_validate_option()
    {
        $data = [
            "date_in" => "",
            "date_out" => "",
            "rooms" => "",
        ];
        $this
            ->shouldThrow(InvalidOptionsException::class)
            ->during('configureOptions', [$data]);
    }

    function it_should_return_room()
    {
        $rooms = [
            [
                'adult' => '2',
                'child' => [
                    2, 15
                ]
            ], [
                'adult' => 1,
                'child' => []
            ]
        ];


        $expected = '<Room><numberOfAdults>2</numberOfAdults><numberOfChildren>2</numberOfChildren><childAges>2</childAges><childAges>15</childAges></Room><Room><numberOfAdults>1</numberOfAdults><numberOfChildren>0</numberOfChildren></Room>';

        $this->getRooms($rooms)->shouldBeEqualTo($expected);


    }

}
