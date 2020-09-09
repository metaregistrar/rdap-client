<?php declare(strict_types=1);

namespace Metaregistrar\RDAP\Data;

final class RdapRole extends RdapObject
{
    /**
     * @return void
     */
    public function dumpContents(): void
    {
        echo '- Role: ' . $this->getRole() . PHP_EOL;
    }

    /**
     * @return mixed
     */
    public function getRole()
    {
        return $this->{0};
    }

    /**
     * @return array
     */
    public function getRoles(): array
    {
        $roles = [];
        $stop = false;
        $i = 0;
        while (!$stop) {
            if (isset($this->{$i})) {
                $roles[] = $this->{$i};
                $i++;
            } else {
                $stop = true;
            }
        }
        return $roles;
    }
}