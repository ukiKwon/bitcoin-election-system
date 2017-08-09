# KBK_CONF
# last update : 2017-08-09
# writer : uki408

# Contents 
- There's are two .conf file for two nodes. Its purpose is connect to each others in regtest mode of bitcoind. The point of them is rpc infomation and 'addnode' tag.

# How to use?
- Two .conf files exists in thie directory. You have to rename it and then move file to ~/.bitcoin or user defined folder. Renaming is to get rid of the last tag, like '.node1' or '.node2'. So node1 will have a bitcoin.conf(<-bitcoin.conf.node1) and the other is bitcoin.conf(<-bitcoinf.conf.node2).

- Next step is to configure its contents. Modify the addnode to Ip you want to connect. The rpc is optional but necesaary. This means to do not delete and be a option to change rpc.

  
# Plan to do 0
- I'm testing now .conf's configuration. And These Instruction may be changed also along with the result.

# Testing network 
- This network is sed on aws EC2 instances.
