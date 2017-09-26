#!/bin/bash
# argument[1] : the address you want to know

declare -a array_list
declare -a addr

#conf_path='/home/ubuntu/bitcoin/src/uki'
conf_path='/home/uki408/Documents/bitcoin/src/uki'
exec_path='/usr/local/bin/'

cmd_getadd=''$exec_path'bitcoin-cli -regtest -datadir='$conf_path' getreceivedbyaddress '$1''

function getBalbyaddr() { #(working)
	#start command
	balByaddress=`$cmd_getadd`
	isfail=$?
	if [ "$isfail" -eq "0" ];then
		echo "$balByaddress" 
	else
		echo "-1" 
	fi
}
getBalbyaddr
