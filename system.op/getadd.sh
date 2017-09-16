#!/bin/bash
#argument[1]= A name of candidate
#argument[2]= the index you want to needle

declare -a array_list

#conf_path='/home/ubuntu/bitcoin/src/uki'
conf_path='/home/uki408/Documents/bitcoin/src/uki'
exec_path='/usr/local/bin/'

cmd_getadd=''$exec_path'bitcoin-cli -regtest -datadir='$conf_path' getaddressesbyaccount'

# made by Mushu92
function get_canaddrs() { #(working)
	#start command
	array_list=`$cmd_getadd $1`
	array_list=($array_list)
	echo  ${array_list[@]}
}
# updated by uki408
function get_canaddr() {
	array_list=`$cmd_getadd $1 | cut -d '"' -f2 -s` #JSON to string
	array_list=($array_list) #string to array
	sz=${#array_list[@]}
	if [ $sz -ne 0 ]; then
		echo ${array_list[$2]}
	else
		echo $sz
	fi
}
# get_canaddrs $1
get_canaddr $1 $2
