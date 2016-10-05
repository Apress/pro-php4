<?php

class Resolver 
{
    var $hostName;
    var $domainName;
    var $ipAddress;
    var $mailXchanger;
    var $servPort;
    var $servName;

    function Resolver()
    {
        reset();
    }

    function getMx($domain)
    {
        if (!$domain) {
            log_err("Domain name is required to retrieve MX records");
            return -1;
        } elseif (($ret = $mailXchanger[$domain])) {
            return $ret;
        } elseif (getmxrr($domain, $mailXchanger) == false) {
            log_err("MX records could not be found found for " . $domainName);
             return -1;
        } else {
            $domainName[$domain] = $mailXchanger;
            return $mailXchanger;
        }
    }

    function getIpAddress($host)
    {
        if (!$host) {
            log_err("Host name is required to find IP addresses");	
            return -1;
        } elseif (($ret = $ipAddress[$host])) {
            return $ret;
        } elseif (($ret = gethostbynamel($host)) == false) {
            log_err("IP address could not be found found for " . $host);
            return -1;
        } else {
            $ipAddress[$host] = $ret;
            $hostName[$ret] = $host;
            return $ret;
        }
    }

    function getHostName($ipAddr)
    {
        if (!ereg("[1-254]\.[1-254]\.[1-254]\.[1-254]", $ipAddr)) {
            log_err("Incorrect IP address format");
            return -1;
        } elseif (($ret = $hostName[$ipAddr])) {
            return $ret;
        } elseif (($ret = gethostbyaddr($ipAddr)) == false) {
            log_err("Host name could not be found for " . $ipAddr);
            return -1;
        } else {
            $hostName[$ipAddr] = $ret;
            $ipAddress[$ret] = $ipAddr;
            return $ret;
        }
    }

    function getProtoByName($name)
    {
        if (!$name) {
            log_err("Protocol name is required to get 
                     the protocol number" );
            return -1;
        } elseif (($ret = $protoNumber[$name])) {
            return $ret;
        } elseif (($ret = getprotobyname($name)) == false) {
            log_err("Protocol number could not be found for " . $name);
            return -1;
        } else {
            $protoNumber[$name] = $ret;
            $protoName[$ret] = $name;
            return $ret;
        }
    }

    function getProtoByNumber($number)
    {
        if (!$number) {
            log_err("Protocol number is required to get 
                     the protocol name" );
            return -1;
        } elseif (($ret = $protoName[$number])) {
            return $ret;
        } elsif (($ret = getprotobynumber($number)) == false) {
            log_err("Protocol name could not be found for " . $number);
            return -1;
        } else {
            $protoName[$number] = $ret;
            $protoNumber[$ret] = $number;
            return $ret;
        }
    }

    function getServByName($name, $proto)
    {
        if (!($proto != "TCP" || $proto != "tcp" 
           || $proto != "UDP" || $proto != "udp")) {
            log_err("Protocol must either be TCP or UDP");
            return -1;
        }
        if (!$name) {
            log_err("Service name is required to get the port number" );
            return -1;
        } elseif (($ret = $servPort[$name])) {
            return $ret;
        } elseif (($ret = getservbyname($name)) == false) {
            log_err("Service port could not be found for 
                    " . $name . " and protocol " . $proto);
            return -1;
        } else {
            $servPort[$name] = $ret;
            $servName[$ret] = $name;
            return $ret;
        }
    }

    function dottedToIp($dotted)
    {
        if (!$dotted) {
            log_err("Dot formatted IP address is required to get 
                     long IP address");
            return -1;
        } elseif (!ereg("[1-254]\.[1-254]\.[1-254]\.[1-254]", $dotted)) {
            log_err("Incorrect IP address format");
            return -1;
        } elseif ($ret = $ipLong[$dotted]) {
            return $ret;
        } elseif (($ret = ip2long($dotted)) == false) {
            log_err("Long IP address could not be found for " . $dotted);
            return -1;
        } else {
            $ipLong[$dotted] = $ret;
            $ipDotted[$ret] = $dotted;
            return $ret;
        }
    }

    function ipToDotted($longIp)
    {
        if (!$longIp) {
            log_err("Long IP address is required to get 
                     dot formatted IP address");
            return -1;
        } elseif ($ret = $ipDotted[$longIp]) {
            return $ret;
        } elseif (($ret = long2ip($longIp)) == false) {
            log_err("Dotted IP address could not be found for " . $longIp);
            return -1;
        } else {
            $ipDotted[$longIp] = $ret;
            $ipLong[$ret] = $longIp;
            return $ret;
        }
    }

    function reset()
    {
        $hostName = 0;
        $domainName = 0;
        $ipAddress = 0;
        $mailXchanger = 0;
        $servPort = 0;
        $servName = 0;
        $ipDotted = 0;
        $ipLong = 0;
    }

    function log_err($msg)
    {
        echo $msg . "<BR>";
    }

}
?>