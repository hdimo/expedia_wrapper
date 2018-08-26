<?php

namespace spec\Jkhaled\Expedia;

use http\Env\Request;
use Jkhaled\Expedia\Expedia;
use Jkhaled\Expedia\Request\BookInterface;
use Jkhaled\Expedia\Request\RequestInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ExpediaSpec extends ObjectBehavior
{

    function it_is_initializable()
    {
        $this->shouldHaveType(Expedia::class);
    }

    function it_should_build_link_based_on_request_type(RequestInterface $requestObject, BookInterface $book)
    {
        $requestObject->getMethod()->willReturn('list');
        $this->setRequest($requestObject);
        $this->getLink()->shouldReturn('http://api.ean.com/ean-services/rs/hotel/v3/list');


        $book->implement(RequestInterface::class);
        $book->getMethod()->willReturn('book');
        $this->setRequest($book);
        $this->getLink()->shouldReturn('https://book.api.ean.com/ean-services/rs/hotel/v3/book');
    }

    function it_should_send_request(RequestInterface $requestObj)
    {

        $xml = '<HotelListRequest>
                <apiExperience>PARTNER_AFFILIATE</apiExperience>
                <arrivalDate>01/10/2019</arrivalDate>
                <departureDate>01/11/2019</departureDate>
                <numberOfResults>20</numberOfResults>
                <RoomGroup>
                    <Room>
                        <numberOfAdults>2</numberOfAdults>
                        <numberOfChildren>2</numberOfChildren>
                        <childAges>2</childAges>
                        <childAges>15</childAges>
                    </Room>
                    <Room>
                        <numberOfAdults>1</numberOfAdults>
                        <numberOfChildren>0</numberOfChildren>
                    </Room>
                </RoomGroup>
                <includeDetails>true</includeDetails>
                <includeHotelFeeBreakdown>true</includeHotelFeeBreakdown>
                <city>Paris</city>
                <countryCode>FR</countryCode>
            </HotelListRequest>';

        $requestObj->prepareRequest()->shouldBeCalled();
        $requestObj->prepareRequest()->willReturn($xml);
        $requestObj->getMethod()->willReturn('list');

        $this->setRequest($requestObj);
        $this->send()->shouldBe(true);
    }

}
