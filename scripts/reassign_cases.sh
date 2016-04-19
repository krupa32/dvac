#!/bin/bash

if [[ $# -ne 2 ]]; then
	echo "Usage: reassign_cases.sh <from_user_id> <to_user_id>"
	exit
fi

echo "Reassigning cases from user $1 to $2"

echo "update cases set investigator=$2 where investigator=$1; \
      update cases set assigned_to=$2 where assigned_to=$1;" | mysql -u root -p dvac
