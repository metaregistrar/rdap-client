<?php

namespace Metaregistry\Rdap;

use Metaregistrar\RDAP\Rdap;
use Metaregistrar\RDAP\RdapException;
use PHPUnit\Framework\TestCase;

final class RdapTest extends TestCase {
    /**
     * just to test
     */
    public function testCase(): void {
        $this->assertFalse(false);
    }

    /**
     * @return void
     * @throws \Metaregistrar\RDAP\RdapException
     */
    public function testEmptySearch(): void {
        $rdap = new Rdap(Rdap::IPV4);

        $this->expectException(RdapException::class);
        $rdap->search('');
    }

    /**
     * @return void
     * @throws \Metaregistrar\RDAP\RdapException
     */
    public function testNoConstructorParamter(): void {
        $this->expectException(RdapException::class);
        new Rdap('');
    }

    public function testSearch(): void {
        $rdap = new Rdap(Rdap::DOMAIN);

        $result = $rdap->search('udag.com');

        $this->assertNotNull($result);
    }
}
