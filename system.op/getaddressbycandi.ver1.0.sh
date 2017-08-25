#!/bin/bash

declare -a addr
declare -i ACCOUNT_REGION

ACCOUNT_NAME="candidates"
ACCOUNT_TEMP="OUT_OF_CANDIDATES"
ACCOUNT_REGION=11 #{서울,경기,대전,강원,전북,전남,대구,부산,울산,경남,제주}

#define command
#1.making address of ACCOUNT_NAME
cmd_make='bitcoin-cli -regtest -datadir=uki getnewaddress '$ACCOUNT_NAME

#2.check the address of ACCOUNT_NAME
cmd_list='bitcoin-cli -regtest -datadir=uki getaddressesbyaccount '$ACCOUNT_NAME

#3.count the number of account
cmd_account='bitcoin-cli -regtest -datadir=uki listaccount'

#4.count of the addresse of ACCOUNT_NAME
function count_account() {
	declare -i count_account 
	list_account=($($cmd_account))
	count_account=${#list_account[@]}
	echo "count_account :$count_account"
	return $count_account
}
function count_addr() {
	declare -i list_count 
	list_addr=($($cmd_list))
	list_count=${#list_addr[@]}	
	#echo "list_count :$list_count"
	return $list_count
}

function get_addr() {
	#check address of the candidates
	echo "-------------- The New addresses List below -----------------"
	for((i=0; i<$1; ++i));
	do	
		addr=$($cmd_make)
		echo ">> The newest $ADDR_CANDI[$i]=$addr"
	done

	#check address of the candidates
	echo -e "${ADDR_CANDI[*]}\n"
	#check the toal address list of the candidate by RPC_calls
	echo "-------------- The total addresses List below -----------------"
	echo -e "$($cmd_list)\n"
}
function del_adrr() {
		
}
#create candidate address by the num of candidate($1)
echo -e " configuration candidate addresses .........\n\n" 

#check the number of addresses already
declare -i HAVE_NUM
declare -i REQ_NUM
declare -i MAKE_NUM

#count address we have already
count_addr
HAVE_NUM=$?
REQ_NUM=$1
MAKE_NUM=`expr $REQ_NUM - $HAVE_NUM`

# generate addresses
if [ $MAKE_NUM -gt 0 ] ;
then
	#generate addresses eeded to
	get_addr $MAKE_NUM
elif [ $MAKE_NUM -lt 0 ];
then 
	#delete addresses dont'need to
	echo -e ">>(OP_DEL)Too much addresses are here. So deletion is working!!!!\n\n"
else
	echo -e ">>(OP_NONE)There're alreay addresses enough.\n\n"
fi
