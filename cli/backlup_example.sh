#!/bin/bash

# Crontab may look like this
# Run backup every day a 9pm
#0 21 * * * /Users/XYZ/Sites/wobb/cli/backup.sh >/dev/null 2>&1

# Database credentials
user="root"
password=""
host="localhost"
db_name="s"

# Other options
backup_path="/Users/XYZ//backups"
date=$(date +"%Y-%m-%d")

# Set default file permissions
umask 177

# Dump database into SQL file
/usr/local/mysql/bin/mysqldump --user=$user --password=$password --host=$host $db_name > $backup_path/$db_name-$date.sql

# Delete files older than 30 days
#find $backup_path/* -mtime +30 -exec rm {} \;
