#!/bin/bash
#U_addr=$1

exec_path='/usr/local/bin/'
conf_path='/home/ubuntu/bitcoin/src/uki'
cmd_send=''$exec_path'bitcoin-cli -regtest -datadir='$conf_path' sendtoaddress '$1' 1 '$2''

function send_ballot() {
	$cmd_send
	echo $?
}
send_ballot
