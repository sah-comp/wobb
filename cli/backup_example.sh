#!/bin/bash

# to be executeable dont forget to chmod +x this file.

# Crontab may look like this
# Run backup every day a 9pm
#0 21 * * * /Users/XYZ/Sites/APP/cli/backup.sh >/dev/null 2>&1

# Database credentials
user="root"
password="secret"
host="localhost"
db_name="mydatabase"
ftpuser="ftpusername"
ftppw="passwd"
ftppath="host/path/to/backups"

# Other options
backup_path="/Users/XYZ/path/to/Backups"
date=$(date +"%Y-%m-%d")

# Set default file permissions
umask 177

# Dump database into SQL file
/usr/local/bin/mysqldump --user=$user --password=$password --host=$host $db_name | gzip -c > $backup_path/$db_name-$date.sql.gz

# curl the backup file to an ftp server
curl --user $ftpuser:$ftppw --upload-file $backup_path/$db_name-$date.sql.gz ftp://$ftppath/$db_name-$date.sql.gz

# Delete files older than 30 days
find $backup_path/* -mtime +30 -exec rm {} \;
