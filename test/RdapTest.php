<?php

namespace Metaregistrar\RDAP;

use Metaregistrar\RDAP\Data\RdapEntity;
use Metaregistrar\RDAP\Data\RdapNameserver;
use Metaregistrar\RDAP\Data\RdapNotice;
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

    public function testDomainSearch(): void {
        $rdap = new Rdap(Rdap::DOMAIN);

        $response = $rdap->search('udag.com');

        $this->assertNotNull($response);

        $nameserver = $response->getNameservers();
        $this->assertIsArray($nameserver);

        $this->assertInstanceOf(RdapNameserver::class, $nameserver[0]);
        foreach ($response->getEntities() as $entity) {
            $this->assertInstanceOf(RdapEntity::class, $entity);
        }
    }

    public function testIpv4Search(): void {
        $rdap = new Rdap(Rdap::IPV4);

        $result = $rdap->search('8.8.4.4');

        $this->assertNotNull($result);

        $notices = $result->getNotices();
        $this->assertIsArray($notices);

        $this->assertInstanceOf(RdapNotice::class, $notices[0]);
        foreach ($result->getEntities() as $entity) {
            $this->assertInstanceOf(RdapEntity::class, $entity);
        }
    }
}
