#!/bin/bash

U_addr=$1

cmd_send='bitcoin-cli -regtest sendtoaddress '$U_addr' 1'

#echo $cmd_sed
function send_Money() { #(working)
	$cmd_send
	echo $?
}

send_Money
