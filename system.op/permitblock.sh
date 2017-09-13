#!/bin/bash

cmd_mine='/usr/local/bin/bitcoin-cli -regtest -datadir=/home/uki408/Documents/bitcoin/src/uki generate 1'

function permitBlock()
{
  $cmd_mine
  echo $?
}
permitBlock
