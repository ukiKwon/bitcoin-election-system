#!/bin/bash
#argument[1]= A name of candidate
#argument[2]= the index you want to needle

declare -a array_list

cmd_getadd='bitcoin-cli -regtest -datadir=/home/uki408/Documents/bitcoin/src/uki getaddressesbyaccount'

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
  echo ${array_list[$2]}
}
# get_canaddrs $1
get_canaddr $1 $2
