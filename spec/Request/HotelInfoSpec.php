<?php

namespace spec\Jkhaled\Expedia\Request;

use Jkhaled\Expedia\Request\HotelInfo;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class HotelInfoSpec extends ObjectBehavior
{

    function let()
    {
        $options =['hotelId'=>122212];
        $this->beConstructedWith($options);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(HotelInfo::class);
    }

    function it_validate_option()
    {
        $given = [
            'hotelId'=>122212,
            'options'=>'DEFAULT'
        ];
        $expected = ['apiExperience => "PARTNER_AFFILIATE"'];
        $this->getOptions()->shouldHaveCount(3);
    }
}
