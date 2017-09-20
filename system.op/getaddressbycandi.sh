#!/bin/bash
# argument[1] : the list of names

declare -a addr
declare -i CUR_ACCOUNT_NUM #the number of address in a account
declare -i REQ_ACCOUNT_NUM #the number requested to generated newly
declare -i GEN_ACCOUNT_NUM #the number going to be generated
declare -a REQ_CANDIDATE_LIST #the argument list of candidates
declare -a CUR_CANDIDATE_LIST #the number of current accounts
declare -i ACCOUNT_REGION=11 # {서울,경기,대전,강원,전북,전남,대구,부산,울산,경남,제주}

conf_path='/home/ubuntu/bitcoin/src/uki'
#conf_path='/home/uki408/Documents/bitcoin/src/uki'
exec_path='/usr/local/bin/'

#DEFINE command
#1.making address of ACCOUNT_NAME
cmd_make=''$exec_path'bitcoin-cli -regtest -datadir='$conf_path' getnewaddress '
#2.check the address of ACCOUNT_NAME
cmd_list=''$exec_path'bitcoin-cli -regtest -datadir='$conf_path' getaddressesbyaccount '
#3.count the number of account.
cmd_account=''$exec_path'bitcoin-cli -regtest -datadir='$conf_path' listaccounts '

#.count the account of a wallet.
function count_account() {
		declare -i _num_account
		declare -a _list_account
		$cmd_account
		_list_account=$?
		_num_account=${#list_account[@]}
		echo "</br> >> This wallet has $_num_account accounts"
		CUR_ACCOUNT_NUM=$_num_account
		return $_num_account
}
# calculate the num of accounts to be generated
function setGenAccounts() {
		declare -i _res
		_now_accounts=${#CUR_CANDIDATE_LIST[@]}
		_req_accounts=$1
		GEN_ACCOUNT_NUM=`expr $_req_accounts - $_now_accounts`
		echo "</br> $GEN_ACCOUNT_NUM"
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
		declare -a ADDR_CANDI
		declare -i i=0
		declare -i count=0
		_list_accounts=$@ #the requested accounts
		_list_accounts=($_list_accounts) #str to Array

		echo -e "</br>GEN::name_list are :${_list_accounts[@]} \n"
		echo -e "</br>GEN::name_list num is :${#_list_accounts[@]} \n</br>"

		# generate addresses by account
		# condition) No duplication ? -> Refuse addtion.
		# condition) Limit count of account -> Stop generation-loop.
		for _new_account in ${_list_accounts[@]};do
					if [ $count -gt $GEN_ACCOUNT_NUM ];then break; fi
					_isDup=$( checkDup "$_new_account" )
					echo -e "</br> Dup? -> $_isDup </br>" #the function checkDup is started.
					#Generate "ACCOUNT_REGION" addreses by a account
					if [ "$_isDup" = "false" ];then
								echo "</br>>> this is a new candidate, $_new_account"
								_makeByaccount=$cmd_make$_new_account
								echo "</br>-------------- The New addresses List below -----------------</br>"
								for((index = 0; index < $ACCOUNT_REGION; ++index));do
										addr=`$_makeByaccount`
										echo "</br>>> The newest ${ADDR_CANDI[$index]}=$addr</br>"
								done
								#echo -e "${ADDR_CANDI[@]}\n"
								count=$(($count + 1))
					else
							echo "</br>>> this is already registered!!!"
					fi
		done
}
function del_addr() {
		echo -e "</br>--- Hi This is a del_addr()\n --- </br>"

}
function _update() {
		echo "</br>update is started now</br>"


}
function update_account() {
		echo -e "</br>Requesting = $REQ_CANDIDATE_LIST, Current = ${CUR_CANDIDATE_LIST[@]}\n"
		echo -e "</br>Updating can be started from the CURRENT to REQUESTING.(yes/no)"
		read chk
		case $chk in
		yes|Yes|y)
				echo -e "</br>>> Type master password:";
				read maspwd;
				`sudo -s '$maspwd'`
				if [ "$?" -eq 0 ];then
						_update
				else
						echo "</br>>> you have no permission to access. Bye"
				fi
				;;
		no|No|n)
				echo -e "</br>>> The requesting will be ignored. Currnent accounts are conserved\n"
				;;
		*)
				echo -e "</br>>> You are wrong command. \n";;
		esac
}
function _Init() {
	echo -e "\n\n\n\n\n"
	nowdate=`date`
	echo -e "$nowdate\n"
	echo -e "</br> >> Configuration candidate addresses .........\n\n</br>"
	REQ_ACCOUNT_NUM=$#
	REQ_CANDIDATE_LIST=$@ #check the candidate list from requesting
	setGenAccounts $REQ_ACCOUNT_NUM
	setAccountlist
}
function _Run() {
	# Main Process start
	if [ $REQ_ACCOUNT_NUM -gt 0 ] ;then		#Yes request
			echo -e "</br>>> Main::The requested number of addresses is $REQ_ACCOUNT_NUM\n</br>"
			echo -e "</br>>> Main::The requested name list is ${REQ_CANDIDATE_LIST[@]}\n</br>"
			#GEN_ACCOUNT_NUM=$( setGenAccounts $REQ_ACCOUNT_NUM ) #calculate the num of accounts to be generated
			setGenAccounts $REQ_ACCOUNT_NUM
			echo -e "</br>>> $GEN_ACCOUNT_NUM accounts can be generated from this wallet\n</br>"
			if [ $GEN_ACCOUNT_NUM -gt 0 ];then #generate new addresses
					gen_addr ${REQ_CANDIDATE_LIST[@]}
			elif [ $GEN_ACCOUNT_NUM -lt 0 ];then #too much addresses
					del_addr
			else	#enough address
					echo -e "</br>>> Now we have enough addresses\n</br>"
					update_account ${REQ_CANDIDATE_LIST[@]}
			fi
	else	#No request
			echo -e "</br>>> There're any person to be candidates.\n</br>"
	fi
	# Main Process exit
	echo -e "</br>>> There's nothing to do, generating Mode is done.\n </br>>> Bye</br>"
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
