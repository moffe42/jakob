#!/bin/bash

read -p "Are you sure you want to detele data and results? [Y/n]" -n 1
if [[ $REPLY =~ ^[Y]$ ]]
then
    echo -e "\nDeleting 'data'"
    rm data/*.tsv
    echo "Deleting 'results'"
    rm results/*.txt
    exit
fi
echo -e "\nNothing was deleted"
