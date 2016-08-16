#!/bin/bash

root_path="/var/www/html/RICS"
# ���擾(PHP)
/usr/bin/php $root_path"/app/index.php" 1 >> $root_path"/batch/daily_getdata.log"

# �o�b�N�A�b�v
cd $root_path"/output"
mkdir rikunabi_bk
cp -rp rikunabi/* rikunabi_bk
mkdir mynavi_bk
cp -rp mynavi/* mynavi_bk
mkdir type_bk
cp -rp type/* type_bk

# �o�b�N�A�b�v��tar&gzip�ۑ�
tar cfvz rikunabi_bk.tar.gz rikunabi_bk
tar cfvz mynavi_bk.tar.gz mynavi_bk
tar cfvz type_bk.tar.gz type_bk

# �o�b�N�A�b�v�폜
rm -rf rikunabi_bk
rm -rf mynavi_bk
rm -rf type_bk

# UTF8�ɕϊ�
cd $root_path
./batch/convert_utf8.sh $root_path"/output/rikunabi"
./batch/convert_utf8.sh $root_path"/output/mynavi"
./batch/convert_utf8.sh $root_path"/output/type"

# grep�ϊ�(bash)
cd $root_path"/batch"
./grep_files.sh

# �W�v(PHP)
/usr/bin/php $root_path"/app/index.php" 2 >> daily_getdata.log
