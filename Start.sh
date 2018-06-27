#!/bin/sh

#shclient -4;

#str= ifconfig | grep -m1 "inet"

str="inet adr:10.250.100.241 Bcast"

echo "Hi, you can find the webserver on : \n"

echo "http://"
echo -n $str | cut -d':' -f 2 | cut -d' ' -f 1 
echo ":8080"
