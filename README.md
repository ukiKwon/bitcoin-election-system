# project : KBK_ELECTION
# last update : 2017-10-09
# writer : uki408

# Essential factor 
- Essential Development Environment
  (1) APM
  (2) mysql
  (3) bitcoin opensource
  
# system setting
- This project is based on Bitcoin open source. So you have to install bitcoin(https://github.com/bitcoin/bitcoin.git). We don't explain that Install guide.

- After the intallation is done, move '/system.op/bitcmd/*' into 'userPath/bitcoin/src/'. The contents is the shell script commanding the bitcoind, like start, stop and etc command.

  - The shell script must be modified because this project is not supported the completed automake. So see the script     'kbk'below.
  
     #!/bin/bash
     bitcoin-cli -regtest -datadir=uki $1 $2 $3 $4
     
     As you see, the default directory is 'uki'. So this have to be modified. The others is as same as 'kbk'. We feel sorry these unconveniences.
     
 - In sereis of these settings, then execute './start_kbk.sh'.

# web setting

- Recommend that git clone this project on '/var/www/html/'. The main index page is 'localhost/KBK_election/server.setting/index.php'. 

  - server.setting : the page(js, php, lib etc) are in.
  - system.op : the shell script directory executed by 'server.setting'.
    - All the script has 'conf_path' meaning these script path. so If the current path is dirrente from you path, Changes those rightly.


# mysql setting

- Currently, We uses 2 databaes. The configuration contents 
