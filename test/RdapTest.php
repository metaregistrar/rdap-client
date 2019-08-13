<?php
namespace Metaregistry\Rdap;

use Metaregistrar\RDAP\Rdap;

class RdapTest extends \PHPUnit\Framework\TestCase
{
    /**
     * just to test
     */
    public function testCase()
    {
        $this->assertFalse(false);
    }

    public function testRdap() {
        $protocol = Rdap::IPV4;

        $rdap = new Rdap($protocol);

        $this->assertInstanceOf(Rdap::class, $rdap);
    }
}
