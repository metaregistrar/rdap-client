<?php
namespace Metaregistry\Rdap;

use Metaregistrar\RDAP\Rdap;
use Metaregistrar\RDAP\RdapException;

class RdapTest extends \PHPUnit\Framework\TestCase
{
    /**
     * just to test
     */
    public function testCase()
    {
        $this->assertFalse(false);
    }

    public function testEmptySearch() {
        $rdap = new Rdap(Rdap::IPV4);

        $this->expectException(RdapException::class);
        $rdap->search('');
    }
}
