#!/bin/bash

if [[ $# -ne 1 ]]; then
	echo "Usage: show_user_details.sh <id or login or name>"
	exit
fi

echo "Showing details of user $1"

if [[ $1 =~ ^[0-9]+$ ]]; then
	q="select id,login,name,grade,reporting_to from users where id=$1"
else
	q="select id,login,name,grade,reporting_to from users where login like '%$1%' or name like '%$1%'"
fi

echo $q | mysql -t -u root -p dvac
