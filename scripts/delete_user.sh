#!/bin/bash

if [[ $# -ne 1 ]]; then
	echo "Usage: delete_users.sh <id>"
	exit
fi

echo "Deleting user $1"
echo "delete from users where id=$1" | mysql -u root -p dvac
