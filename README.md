# rdap-client
A php client to query rdap (formerly whois) services

No additional libraries needed - just plain object-oriented PHP.

Please note that the RDAP standard is not fully implemented by all registries yet.
The bootstrap file for domains is still empty, rendering this client unfit to query domain names at this time.

This client implements the following Internet Standards:

http://tools.ietf.org/html/rfc7480 HTTP Usage in the Registration Data Access Protocol (RDAP)
http://tools.ietf.org/html/rfc7481 Security Services for the Registration Data Access Protocol (RDAP)
http://tools.ietf.org/html/rfc7482 Registration Data Access Protocol (RDAP) Query Format
http://tools.ietf.org/html/rfc7483 JSON Responses for the Registration Data Access Protocol (RDAP)
http://tools.ietf.org/html/rfc7484 Finding the Authoritative Registration Data (RDAP) Service
http://tools.ietf.org/html/rfc7485 Inventory and Analysis of WHOIS Registration Objects

Bootstrap files from the IANA website:
https://data.iana.org/rdap/dns.json for the domain name space
https://data.iana.org/rdap/asn.json for the AS numbers space
https://data.iana.org/rdap/ipv4.json for the IPv4 address space
https://data.iana.org/rdap/ipv6.json for the IPv6 address space

RDAP json values:
http://www.iana.org/assignments/rdap-json-values/rdap-json-values-1.csv

RDAP extensions library:
https://www.iana.org/assignments/rdap-extensions/rdap-extensions.xhtml