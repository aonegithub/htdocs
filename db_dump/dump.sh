cd /var/www/vhosts/awugo.com/httpdocs/db_dump/
stamp=$(date +%Y_%m_%d_%H_%M_%S)
mysqldump -uroot -p1q2w3eh3y45 awugo > $stamp.sql
bzip2 -z -9 -f $stamp.sql