<?php
set_time_limit(0);
$host = '127.0.0.1';
$port = 9621;
echo "master prcess start,It's pid:".getmypid()."\n";
$socket = socket_create(AF_INET,SOCK_STREAM, SOL_TCP);
if(!$socket){
	die("create scoket fail:".socket_strerror(socket_last_error()));
}
socket_set_block($socket) or die("set block fail: ".socket_strerror(socket_last_error()));
socket_bind($socket,$host,$port) or die("bind fail :".socket_strerror(socket_last_error()));
echo "Binding the socket on $host:$port ... \n";
socket_listen($socket,4) or die("listen fail: ".socket_strerror(socket_last_error()));
echo "begin listen at $port...\n";
for ($i=0;$i<=1;$i++){
	if(pcntl_fork() == 0){
		echo "new process,pid:".getmypid()."\n";	
		while (true){
			echo "waiting connection...\n";
			$msgSocket = socket_accept($socket);
			echo "receive one connection ....\n";
			echo "new process,It's pid:".getmypid()."\n";
			while(true){
				echo "waiting data....\n";
				if(false === ($buf = socket_read($msgSocket, 2048,PHP_BINARY_READ))){
					echo getmypid()." connection break..... \n";
					break;
				}
				echo $buf;
				socket_write($msgSocket, "ggg\n",strlen("ggg\n"));
			}
			socket_close($msgSocket);
		}
	}
}

socket_close($socket);