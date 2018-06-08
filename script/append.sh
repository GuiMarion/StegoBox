#!/bin/sh

#$1 = message
#$2 = image
#$3 = mdp

rm -f secret.txt
touch secret.txt
echo $1 > secret.txt

steghide embed -q -ef secret.txt -cf $2 -p $3

rm -f secret.txt
