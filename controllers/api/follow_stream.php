<?php
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
header('Connection: keep-alive');
header('X-Accel-Buffering: no');
header('Access-Control-Allow-Origin: *');

// Sende einen leeren Heartbeat, damit die Verbindung offen bleibt
while (true) {
    echo "data: heartbeat\n\n";
    ob_flush();
    flush();
    sleep(30);
}