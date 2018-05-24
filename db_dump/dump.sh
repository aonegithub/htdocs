cd /var/www/vhosts/awugo.com/httpdocs/awugo/db_dump/
stamp=$(date +%y%m%d)
mysqldump -uroot -p1q2w3eh3y45 awugo > db_backup_$stamp.sql
bzip2 -z -9 -f db_backup_$stamp.sql