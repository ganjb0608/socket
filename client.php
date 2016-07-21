<?php
set_time_limit(0);
$host = "127.0.0.1";
$port = 9621;

$pid = getmypid();
// 创建一个tcp流
$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP)
or die("socket_create() failed:" . socket_strerror(socket_last_error()));

echo "try to connect to $host:$port...\n";
$result = socket_connect($socket, $host, $port)
or die("socket_connect() failed:" . socket_strerror(socket_last_error()));

echo "my pid:".$pid."\n";
while (true){
	$in = fgets(STDIN);
	$in = "from $pid,it say:".$in;
	socket_write($socket, $in, strlen($in)) or die("socket_write() failed:" . socket_strerror($socket));
	echo "send success \n";
	$out = '';
	while($buf = socket_read($socket, 8192,PHP_NORMAL_READ)) {
		$out .= $buf;
		break;
	}
	echo "get from server：$out \n";
}
socket_close($socket);