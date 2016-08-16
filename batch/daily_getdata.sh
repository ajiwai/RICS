#!/bin/bash

root_path="/var/www/html/RICS"
# 情報取得(PHP)
/usr/bin/php $root_path"/app/index.php" 1 >> $root_path"/batch/daily_getdata.log"

# バックアップ
cd $root_path"/output"
mkdir rikunabi_bk
cp -rp rikunabi/* rikunabi_bk
mkdir mynavi_bk
cp -rp mynavi/* mynavi_bk
mkdir type_bk
cp -rp type/* type_bk

# バックアップをtar&gzip保存
tar cfvz rikunabi_bk.tar.gz rikunabi_bk
tar cfvz mynavi_bk.tar.gz mynavi_bk
tar cfvz type_bk.tar.gz type_bk

# バックアップ削除
rm -rf rikunabi_bk
rm -rf mynavi_bk
rm -rf type_bk

# UTF8に変換
cd $root_path
./batch/convert_utf8.sh $root_path"/output/rikunabi"
./batch/convert_utf8.sh $root_path"/output/mynavi"
./batch/convert_utf8.sh $root_path"/output/type"

# grep変換(bash)
cd $root_path"/batch"
./grep_files.sh

# 集計(PHP)
/usr/bin/php $root_path"/app/index.php" 2 >> daily_getdata.log
