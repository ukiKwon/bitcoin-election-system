# writer : uki408
# update_date : 2017-09-14

# 1.Contents

#$1.getaddressbycandi.sh made by uki408
- getaddressbycandi.sh : Generate the number of regions addresses per candidates. Its aruments are from AS( Authentication Server ).
	- USAGE
	$ ./getaddressbycandi.sh "A list of candidates"

	- Then, This shell makes 11 addresses per candidates.(11 = the number of regions in Korea)

#$2.genaddr.sh made by uki408 ( now this has a Issue of permission )
- genaddr.sh : It calls the getaddressbycandi.sh. This is more uper level shell script.And then the
			   result of the execution will saved genaddr.log. You can check it in the server.setting				 folder.
	-USAGE
	$ ./genaddr.sh "A list of candidates"

#$3.getaddr.sh made by Mushu92
- ./getaddr.sh : It just return a list of addresses from
a candidate.
	- USAGE
	$ getaddr.sh "a account of this wallet"

#$4.sendBallot.sh made by Mushu92
- sendBallot.sh : It send 1 coin from this wallet to the target address.
	-USAGE
	$ ./sendMoney.sh "A address"
#5.permitblock.sh
- permitblock.sh : It will create a block if the manager who can access this system click the button on the kweb site.Then this shell will be operated.
	-USAGE
	$ ./permitblock.sh 
- But this needs to be automatic.So this system is going to use a 'crontab'
	#How to?
	- Edit the configuration and then this linux program will adapt immediat		ley If you don't mistake its syntax
	#ex
	- */6 * * * * /userPath/permitblock.sh
	: every 6 min, all day, all week, all month.  

# TO DO
- show Transaction list and then monitoring thos.
