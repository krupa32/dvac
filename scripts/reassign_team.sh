#!/bin/bash

if [[ $# -ne 2 ]]; then
	echo "Usage: reassign_team.sh <from_user_id> <to_user_id>"
	exit
fi

echo "Reassigning team of user $1 to $2"

echo "update users set reporting_to=$2 where reporting_to=$1" | mysql -u root -p dvac
