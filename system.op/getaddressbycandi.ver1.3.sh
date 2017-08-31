#!/bin/bash
# argument[1] : the list of names

declare -a addr
declare -i CUR_ACCOUNT_NUM #the number of address in a account
declare -i REQ_ACCOUNT_NUM #the number requested to generated newly
declare -i GEN_ACCOUNT_NUM #the number going to be generated
declare -a REQ_CANDIDATE_LIST #the argument list of candidates
declare -a CUR_CANDIDATE_LIST #the number of current accounts
declare -i ACCOUNT_REGION=11 # {서울,경기,대전,강원,전북,전남,대구,부산,울산,경남,제주}

#DEFINE command
#1.making address of ACCOUNT_NAME
cmd_make='bitcoin-cli -regtest -datadir=/home/uki408/Documents/bitcoin/src/uki getnewaddress '
#2.check the address of ACCOUNT_NAME
cmd_list='bitcoin-cli -regtest -datadir=/home/uki408/Documents/bitcoin/src/uki getaddressesbyaccount '
#3.count the number of account.
cmd_account='bitcoin-cli -regtest -datadir=/home/uki408/Documents/bitcoin/src/uki listaccounts '

#.count the account of a wallet.
function count_account() {
		declare -i _num_account
		declare -a _list_account
		$cmd_account
		_list_account=$?
		_num_account=${#list_account[@]}
		echo ">> This wallet has $_num_account accounts"
		CUR_ACCOUNT_NUM=$_num_account
		return $_num_account
}
# calculate the num of accounts to be generated
function setGenAccounts() {
		declare -i _res
		#declare -a _accounts
		#_accounts=`$cmd_account | cut -d '"' -f2 -s` #JSON to string
		#_accounts=($_accounts) #string to array
		#_now_accounts=${#_accounts[@]}
		_now_accounts=${#CUR_CANDIDATE_LIST[@]}
		_req_accounts=$1
		GEN_ACCOUNT_NUM=`expr $_req_accounts - $_now_accounts`
		echo "$GEN_ACCOUNT_NUM"
}
#count the address of sepecified candidate.
function count_addr() {
		#echo -e "--- count_addr ---\n"
		declare -i list_count
		_account_name=$1
		_list_addr=($($cmd_list)$_account_name)
		#list_addr=($($cmd_list))
		_list_count=${#_list_addr[@]}
		#echo "list_count :$list_count"
		return $_list_count
}
function setAccountlist() {
		CUR_CANDIDATE_LIST=`$cmd_account | cut -d '"' -f2 -s` #JSON to string
		CUR_CANDIDATE_LIST=($CUR_CANDIDATE_LIST) #string to array
}
# check a account's duplication (Working)
function checkDup() {
		#echo -e "--- checkDup ---\n"
		_account=$1
		_list_account=`$cmd_account | cut -d '"' -f2 -s` #JSON to string
		_list_account=($_list_account) #string to array
    for val in "${_list_account[@]}";do
				#printf "comparing::$_account with $val \n"
				if [ "$_account" == "$val" ];then
					echo "true"	#alreay
				fi
		done
		echo "false" #can be registered
}
#generate the needed addresses of a account.
function gen_addr() {
		echo -e "--- gen_addr ---\n"
		declare -a ADDR_CANDI
		declare -i i=0
		declare -i count=0
		_list_accounts=$@ #the requested accounts
		_list_accounts=($_list_accounts) #str to Array
		#echo -e "GEN::the $_num_account_generated will be generated\n"
		echo -e "GEN::name_list are :${_list_accounts[@]} \n"
		echo -e "GEN::name_list num is :${#_list_accounts[@]} \n"

		# generate addresses by account
		# condition) No duplication ? -> Refuse addtion.
		# condition) Limit count of account -> Stop generation-loop.
		for _new_account in ${_list_accounts[@]};do
					if [ $count -gt $GEN_ACCOUNT_NUM ];then break; fi
					_isDup=$( checkDup "$_new_account" )
					echo -e "Dup? -> $_isDup " #the function checkDup is started.
					#Generate "ACCOUNT_REGION" addreses by a account
					if [ "$_isDup" == "false" ];then
								echo ">> this is a new candidate, $_new_account"
								_makeByaccount=$cmd_make$_new_account
								echo "-------------- The New addresses List below -----------------"
								for((index = 0; index < $ACCOUNT_REGION; ++index));do
										addr=`$_makeByaccount`
										echo ">> The newest ${ADDR_CANDI[$index]}=$addr"
								done
								#echo -e "${ADDR_CANDI[@]}\n"
								count=$(($count + 1))
					else
							echo ">> this is already registered!!!"
					fi
		done
}
function del_addr() {
		echo -e "--- Hi This is a del_addr()\n --- "

}
function _update() {
		echo "update is started now"


}
function update_account() {
		echo -e "Requesting = $REQ_CANDIDATE_LIST, Current = ${CUR_CANDIDATE_LIST[@]}\n"
		echo -e "Updating can be started from the CURRENT to REQUESTING.(yes/no)"
		read chk
		case $chk in
		yes|Yes|y)
				echo -e ">> Type master password:";
				read maspwd;
				`sudo -s '$maspwd'`
				if [ "$?" -eq 0 ];then
						_update
				else
						echo ">> you have no permission to access. Bye"
				fi
				;;
		no|No|n)
				echo -e ">> The requesting will be ignored. Currnent accounts are conserved\n"
				;;
		*)
				echo -e ">> You are wrong command. \n";;
		esac
}
function _Init() {
	echo -e ">> Configuration candidate addresses .........\n\n"
	REQ_ACCOUNT_NUM=$#
	REQ_CANDIDATE_LIST=$@ #check the candidate list from requesting
	setGenAccounts $REQ_ACCOUNT_NUM
	setAccountlist
}
function _Run() {
	# Main Process start
	if [ $REQ_ACCOUNT_NUM -gt 0 ] ;then		#Yes request
			echo -e ">> Main::The requested number of addresses is $REQ_ACCOUNT_NUM\n"
			echo -e ">> Main::The requested name list is ${REQ_CANDIDATE_LIST[@]}\n"
			#GEN_ACCOUNT_NUM=$( setGenAccounts $REQ_ACCOUNT_NUM ) #calculate the num of accounts to be generated
			setGenAccounts $REQ_ACCOUNT_NUM
			echo -e "UKI !!!! : $GEN_ACCOUNT_NUM \n"
			echo -e ">> $GEN_ACCOUNT_NUM accounts can be generated from this wallet\n"
			if [ $GEN_ACCOUNT_NUM -gt 0 ];then #generate new addresses
					gen_addr ${REQ_CANDIDATE_LIST[@]}
			elif [ $GEN_ACCOUNT_NUM -lt 0 ];then #too much addresses
					del_addr
			else	#enough address
					echo -e ">> Now we have enough addresses\n"
					update_account ${REQ_CANDIDATE_LIST[@]}
			fi
	else	#No request
			echo -e ">> There're any person to be candidates.\n"
	fi
	# Main Process exit
	echo -e ">> There's nothing to do, generating Mode is done.\n >> Bye"
}
#
# -------------------------------------MAIN ----------------------------------------
#
#
_Init $@
_Run
#
# -------------------------------------EXIT ----------------------------------------
#
#
