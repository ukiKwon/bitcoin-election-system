#!/bin/bash

U_addr=$1

cmd_send='sudo bitcoin-cli -regtest sendtoaddress '$U_addr' 1.000188'

#echo $cmd_sed
function send_Money() { #(working)
i	echo "start"
	$cmd_send
	echo $?
}

send_Money
