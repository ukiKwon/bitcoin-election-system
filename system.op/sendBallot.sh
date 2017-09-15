#!/bin/bash
#U_addr=$1

conf_path='/home/ubuntu/bitcoin/src/uki'
exec_path='/usr/local/bin/'
cmd_send=''$exec_path'bitcoin-cli -regtest -datadir='$conf_path' sendtoaddress '$1' 1 '$2''

function send_ballot() {
	$cmd_send
	#echo $?
}
send_ballot
