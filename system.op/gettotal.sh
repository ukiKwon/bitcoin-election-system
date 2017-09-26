#!/bin/bash
# argument[1] : the name of a account

C_name=$1

declare -a array_list
declare -a addr

conf_path='/home/uki408/Documents/bitcoin/src/uki'
exec_path='/usr/local/bin/'

cmd_getadd=''$exec_path'bitcoin-cli -regtest -datadir='$conf_path' getreceivedbyaccount '$C_name''

function get_test() {
	#start command
	array_list=`$cmd_getadd`
	array_list=($array_list)
        echo  ${array_list[@]}
}

get_test
