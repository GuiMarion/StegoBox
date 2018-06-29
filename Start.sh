#!/bin/sh

echo "Hi, you can find the webserver on : \n"

echo "http://"
ifconfig | grep "inet" -m1 | cut -d ':' -f 2 | cut -d ' ' -f 1
echo ":8080"
