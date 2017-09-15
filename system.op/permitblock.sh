#!/bin/bash

conf_path='/home/ubuntu/bitcoin/src/uki'
exec_path='/usr/local/bin/'

cmd_mine=''$exec_path'bitcoin-cli -regtest -datadir='$conf_path' generate 1'

function permitBlock()
{
  $cmd_mine
  echo $?
}
permitBlock
