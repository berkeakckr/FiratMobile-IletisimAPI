<?php

namespace App\Socket;
/**
 * Class SocketIO
 * develope by psinetron (slybeaver)
 * Git: https://github.com/psinetron
 * web-site: http://slybeaver.ru
 *
 */
class SocketIO
{
    /**
     * @param null $host - $host of socket server
     * @param null $port - port of socket server
     * @param string $action - action to execute in sockt server
     * @param null $data - message to socket server
     * @param string $address - addres of socket.io on socket server
     * @param string $transport - transport type
     * @return bool
     */
    public function send($host = null, $port = null, $action = "message", $data = null, $address = "/socket.io/?EIO=2", $transport = 'websocket')
    {
        $fd = fsockopen($host, $port, $errno, $errstr);
        if (!$fd) {
            return false;
        } //Can't connect tot server
        $key = $this->generateKey();
        $out = "GET $address&transport=$transport HTTP/1.1\r\n";
        $out .= "Host: http://$host:$port\r\n";
        $out .= "Upgrade: WebSocket\r\n";
        $out .= "Connection: Upgrade\r\n";
        $out .= "Sec-WebSocket-Key: $key\r\n";
        $out .= "Sec-WebSocket-Version: 13\r\n";
        $out .= "Origin: *\r\n\r\n";

        fwrite($fd, $out);
        // 101 switching protocols, see if echoes key
        $result = fread($fd, 10000);

        preg_match('#Sec-WebSocket-Accept:\s(.*)$#mU', $result, $matches);
        $keyAccept = trim($matches[1]);
        $expectedResonse = base64_encode(pack('H*', sha1($key . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11')));
        $handshaked = ($keyAccept === $expectedResonse) ? true : false;
        if ($handshaked) {
            fwrite($fd, $this->hybi10Encode('42["' . $action . '", "' . addslashes($data) . '"]'));
            fread($fd, 1000000);
            return true;
        } else {
            return false;
        }
    }


}
