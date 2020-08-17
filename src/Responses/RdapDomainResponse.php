<?php declare(strict_types=1);
/*
 * This file is part of the Ngen - CSIRT Incident Report System.
 *   (c) CERT UNLP <support@cert.unlp.edu.ar>
 *  This source file is subject to the GPL v3.0 license that is bundled
 *  with this source code in the file LICENSE.
 */

namespace Metaregistrar\RDAP\Responses;

class RdapDomainResponse extends RdapResponse
{
    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name ?: $this->getLDHName();
    }
}
