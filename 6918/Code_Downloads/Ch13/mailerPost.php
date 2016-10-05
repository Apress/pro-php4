<?php

error_reporting(E_ALL);

if ($button != "Send") {
    include('mailer.html');
} else {
    $tmp = explode('@', $To);

if (!$tmp[0]) {
    usage();
} else {
    $serverName = $tmp[1];
}

$tmp = explode('@', $From);

if (!$tmp[0]) {
    usage();
} else {
    $clientName = $tmp[1];
}

$smtpServer = getmxrr($serverName, $mxhosts);

if ($smtpServer == false) {
    // $smtpServer = "localhost";
    errQuit("getmxrr() failed");
} 

$smtpServerIP = gethostbyname($smtpServer);
$smtpServerPort = getservbyname('smtp', 'tcp');

$socket = socket(AF_INET, SOCK_STREAM, 0);
if ($socket < 0) {
    errQuit("socket() failed: " . strerror($socket));
}

$conn = connect($socket, $smtpServerIP, $smtpServerPort);
if ($conn < 0) {
    errQuit("connect() failed: " . strerror($conn));
}

$msg = "HELO '$clientName'\r\n";
doProtocol($socket, $msg);

$msg = "MAIL FROM: '$From'\r\n";
doProtocol($socket, $msg);

$msg = "RCPT TO: '$To'\r\n";
doProtocol($socket, $msg);

$msg = "DATA\r\n";
doProtocol($socket, $msg);

$msg = "'$Message'\r\n.\r\n";
doProtocol($socket, $msg);

$msg = "QUIT\r\n";
doProtocol($socket, $msg);

close($socket);

echo("<h2>Message successfully sent to '$To' </h2>");
}

function doProtocol($socket, $msg)
{
    $ret = write($socket, $msg, strlen($msg));
    if ($ret < 0) {
        errQuit("write() failed: " . strerror($ret));
    }

    $out = " ";
    while(($ret = read($socket, $out, 4096)));


    if ($ret < 0) {
        errQuit("read() failed: " . strerror($ret));
    }

    return;
}

function errQuit($msg)
{
    echo $msg . "<br>";
    echo("<h3>Could not send message</h3>");
    exit(-1);
}

function usage()
{
    include('mailer.html');
    exit(-1);
}
?>
