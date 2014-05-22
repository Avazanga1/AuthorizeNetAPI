<?php

namespace spec\Czopson\Authorize;

use Czopson\Authorize\AuthorizeNetAPIFactory;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;


require(getcwd() . '\vendor\authorizenet\authorizenet\AuthorizeNet.php');

class CustomerSpec extends ObjectBehavior
{
    function let(AuthorizeNetAPIFactory $apiFactory, \AuthorizeNetCIM $cim, \AuthorizeNetAIM $aim, \AuthorizeNetTD $td, \AuthorizeNetCIM_Response $cimResponse)
    {
        $apiFactory->getCIM('6Sq9N3nvR', '42sRN493D4n5nJ5X')->willReturn($cim);
        $apiFactory->getAIM('6Sq9N3nvR', '42sRN493D4n5nJ5X')->willReturn($aim);
        $apiFactory->getTD('6Sq9N3nvR', '42sRN493D4n5nJ5X')->willReturn($td);
        $cim->createCustomerProfile(Argument::any())->willReturn($cimResponse);

        $this->beConstructedWith('6Sq9N3nvR', '42sRN493D4n5nJ5X', true, $apiFactory);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Czopson\Authorize\Customer');
    }

    function it_creates_customer(\AuthorizeNetCIM_Response $cimResponse)
    {
        $name = 'Artur Czopek';
        $email = 'artur.czopek@avalton.com';
        $cimResponse->isOk()->willReturn(true);
        $cimResponse->getCustomerProfileId()->shouldBeCalled();

        $this->createCustomer($name, $email)->success->shouldReturn(true);
    }

    function it_not_creates_customer(\AuthorizeNetCIM_Response $cimResponse)
    {
        $name = 'Artur Czopek';
        $email = 'artur.czopek@avalton.com';
        $cimResponse->isOk()->willReturn(false);
        $cimResponse->getCustomerProfileId()->shouldNotBeCalled();

        $this->createCustomer($name, $email)->success->shouldReturn(false);
    }

    function it_creates_customer_and_assign_its_id(\AuthorizeNetCIM_Response $cimResponse)
    {
        $name = 'Artur Czopek';
        $email = 'artur.czopek@avalton.com';
        $cimResponse->isOk()->willReturn(true);
        $cimResponse->getCustomerProfileId()->willReturn('123');

        $this->createCustomer($name, $email);
        $this->getId()->shouldReturn('123');
    }
}
