#!/bin/sh

# $1 est le nom de l'image
# $2 mdf

rm -f secret.txt
steghide extract -q -sf ../img/$1 -p $2 
