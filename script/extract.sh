#!/bin/sh

# $1 est le nom de l'image
# $2 mdp

rm -f secret.txt
steghide extract -q -sf $1 -p $2
