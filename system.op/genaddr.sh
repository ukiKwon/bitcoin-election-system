#!/bin/bash

sudo touch ./log/genaddr.log

./getaddressbycandi.ver1.4.sh $@ >> genaddr.log;
