#!/bin/bash
#U_addr=$1
cmd_send='bitcoin-cli -regtest -datadir=/home/uki408/Documents/bitcoin/src/uki sendtoaddress '$1' 1 '$2''

function send_ballot() {
	$cmd_send
	echo $?
}
send_ballot
