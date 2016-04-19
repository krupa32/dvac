#!/bin/bash

echo "select id,login,name from users where reporting_to=0" | mysql -t -u root -p dvac
