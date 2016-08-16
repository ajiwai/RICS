#!/bin/bash
################################################################################
# 名　称：grep_files.sh
# 説　明：結果ファイルをGREPして件数を抽出
# 引数１：開始日付
# 戻り値：0:正常終了
# 作成者：kimura
# 作成日：2016/03/23
################################################################################

output_path="/var/www/html/RICS/output/"
output_file="counter.tsv"
output_file2="counter.tsv"

if [ $# -eq 1 ] ; then
    if [ "`date +'%Y%m%d' -d $1 2> /dev/null`" == $1 ]; then
        echo "$1 is ok"
        from_date=$1
    else
        echo "$1 is ng"
        from_date=`date '+%Y%m%d'`
    fi
else
    echo "argument is not"
    from_date=`date '+%Y%m%d'`
fi

################################################################################
# 関数名：grep_files
# 関数名：ディレクトリよりGREPして一時ファイルに出力
# 引数１：サイト名
# 引数２：検索文字列
# 引数３：検索文字列２
# 引数４：置換文字列２
# 戻り値：なし
################################################################################
function grep_files() {

    for filepath in $output_path$1/*
    do
        if [ -f $filepath ] ; then
            date=`echo $filepath | sed 's/^.*\(20[0-9]*\).*$/\1/g'`
            if [ $date -ge $from_date ]; then
                # echo "○file:"$filepath;
                if [ "$1" == "mynavi" ] ; then
                    cat $filepath | grep -e "value=.*href=\".*（[0-9]*）" | grep -e "value=.*href=\".*（[0-9]*）" | sed "s/^.*href=\"\/list\/\(.*\)\/[^>]*>\([^<]*\)<.*（\([0-9]*\)）.*$/\1\t\t\2\t\3/g" > $output_path$1/grep_tmp_result.txt
                    # 先頭に日付とカテゴリを挿入
                    rep="$1\t$date\t00\t"
                    cat $output_path$1/grep_tmp_result.txt | sed s/^/$rep/g >> $output_path$output_file
                elif [ "$1" == "type" ] ; then
                    cat $filepath | sed "/^\s*$/d" | grep -B 1 -e "class=\"mod-job-num\".*（[0-9]*件）" > $output_path$1/grep_tmp.txt
                    cat $output_path$1/grep_tmp.txt | sed "s/^.*<a href=\"\([^\"]*\)\">\([^<]*\).*（\([0-9]*\)件）.*$/\1\t\t\2\t\3/g" | sed "s/^--.*$//g" | sed "s/^.*<ul.*$//g" | sed "s/^.*<div.*$//g" | sed "/^\s*$/d" > ../output/type/grep_tmp2.txt
                    # 先頭に日付とカテゴリを挿入
                    rep="$1\t$date\t00\t"
                    cat $output_path$1/grep_tmp_result.txt | sed s/^/$rep/g >> $output_path$output_file
                else
                    cat $filepath | grep -e $2 > $output_path$1/grep_tmp.txt
                    cat $output_path$1/grep_tmp.txt | sed 's/\r/\n/g' > $output_path$1/grep_tmp2.txt
                    date=`echo $filepath | sed 's/^.*\(20[0-9]*\).*$/\1/g'`
                    grep_result $1 $3 $4 $date 00
                fi
            fi
        elif [ -d $filepath ] ; then
            for filepath2 in ${filepath}/*
            do
                date=`echo $filepath2 | sed 's/^.*\(20[0-9]*\).*$/\1/g'`
                cate=`echo $filepath2 | sed 's/^.*\/\([0-9][0-9]\)\/.*$/\1/g'`
                if [ -f $filepath2 ] ; then
                    if [ $date -ge $from_date ]; then
                    # echo "○file:"$filepath2;
                        if [ "$1" == "mynavi" ] ; then
                            cat $filepath2 | grep -e "value=.*href=\".*（[0-9]*）" | grep -e "value=.*href=\".*（[0-9]*）" | sed "s/^.*href=\"\/list\/\(.*\)\/[^>]*>\([^<]*\)<.*（\([0-9]*\)）.*$/\1\t\t\2\t\3/g" > $output_path$1/grep_tmp_result.txt
                            # 先頭に日付とカテゴリを挿入
                            rep="$1\t$date\t$cate\t"
                            cat $output_path$1/grep_tmp_result.txt | sed s/^/$rep/g >> $output_path$output_file
                        elif [ "$1" == "type" ] ; then
                            cat $filepath2 | sed "/^\s*$/d" | grep -B 1 -e "class=\"mod-job-num\".*（[0-9]*件）" > $output_path$1/grep_tmp.txt
                            cat $output_path$1/grep_tmp.txt | sed "s/^ *<a href=\"\([^\"]*\).*$/\1/g" | sed "s/^ *\([^<]*\).*（\([0-9]*\)件）.*$/\t\t\1\t\2/g" | sed "s/^--.*$//g"  | sed "s/^.*<p>.*$//g" | sed "/^\s*$/d" > $output_path$1/grep_tmp2.txt
                            sed ':loop; N; $!b loop; ;s/\/\n//g' ../output/type/grep_tmp2.txt > $output_path$1/grep_tmp3.txt
                            cat $output_path$1/grep_tmp3.txt | sed "s/\(\/job-1\/[0-9]*\)\/\(.*\)/\1\t\2/g" > $output_path$1/grep_tmp_result.txt
                            # 先頭に日付とカテゴリを挿入
                            rep="$1\t$date\t$cate\t"
                            cat $output_path$1/grep_tmp_result.txt | sed s/^/$rep/g >> $output_path$output_file
                        else
                            cat $filepath2 | grep -e $2 > $output_path$1/grep_tmp.txt
                            cat $output_path$1/grep_tmp.txt | sed 's/\r/\n/g' > $output_path$1/grep_tmp2.txt
                            date=`echo $filepath2 | sed 's/^.*\(20[0-9]*\).*$/\1/g'`
                            cate=`echo $filepath2 | sed 's/^.*\/\([0-9][0-9]\)\/.*$/\1/g'`
                            grep_result $1 $3 $4 $date $cate
                        fi
                    fi
                fi
            done
        fi
    done
    # 一時ファイルを削除
    rm $output_path$1/grep_tmp.txt
    rm $output_path$1/grep_tmp2.txt
    rm $output_path$1/grep_tmp3.txt
    rm $output_path$1/grep_tmp4.txt
    rm $output_path$1/grep_tmp_result.txt

}

################################################################################
# 関数名：grep_files_js
# 関数名：ディレクトリよりGREPして一時ファイルに出力
# 引数１：サイト名
# 戻り値：なし
################################################################################
function grep_files_js() {

    for filepath in $output_path$1/*
    do
        if [ -f $filepath ] ; then
            date=`echo $filepath | sed 's/^.*\(20[0-9]*\).*$/\1/g'`
        
            if [ $date -ge $from_date ]; then
                # echo "○file:"$filepath;
                cat $filepath | grep -e "^.*>[^><]*<\/a> <span class=\"bCnt\">\(\d*\).*$" > $output_path$1/grep_tmp.txt
                cat  $output_path$1/grep_tmp.txt | sed -e "s/<\/span>/<\/span>\n/g" > $output_path$1/grep_tmp2.txt
                cat  $output_path$1/grep_tmp2.txt | sed -e "s/(/【/g" | sed -e "s/)/】/g" > $output_path$1/grep_tmp3.txt
                cat  $output_path$1/grep_tmp3.txt | sed -e "s/^.*<a[^>]*> *\([^<]*\).*<span class=\"bCnt\">【\([0-9]*\)】.*$/\t\t\1\t\2/g" | sed "s/^.*<browse_done.*$//g" > $output_path$1/grep_tmp_result.txt
                # 先頭に日付とカテゴリを挿入
                rep="$1\t$date\t00\t"
                cat $output_path$1/grep_tmp_result.txt | sed s/^/$rep/g >> $output_path$output_file2
            fi
        elif [ -d $filepath ] ; then
            for filepath2 in ${filepath}/*
            do
                date=`echo $filepath2 | sed 's/^.*\(20[0-9]*\).*$/\1/g'`
                cate=`echo $filepath2 | sed 's/^.*\/\([0-9][0-9]\)\/.*$/\1/g'`
                if [ -f $filepath2 ] ; then
                    if [ $date -ge $from_date ]; then
                        # echo "○file:"$filepath2;
                        cat $filepath2 | grep -e "^.*>[^><]*<\/a> <span class=\"bCnt\">\(\d*\).*$" > $output_path$1/grep_tmp.txt
                        cat  $output_path$1/grep_tmp.txt | sed -e "s/<\/span>/<\/span>\n/g" > $output_path$1/grep_tmp2.txt
                        cat  $output_path$1/grep_tmp2.txt | sed -e "s/(/【/g" | sed -e "s/)/】/g" > $output_path$1/grep_tmp3.txt
                        cat  $output_path$1/grep_tmp3.txt | sed -e "s/^.*<a[^>]*> *\([^<]*\).*<span class=\"bCnt\">【\([0-9]*\)】.*$/\t\t\1\t\2/g" | sed "s/^.*<browse_done.*$//g" > $output_path$1/grep_tmp_result.txt
                        # 先頭に日付とカテゴリを挿入
                        rep="$1\t$date\t$cate\t"
                        cat $output_path$1/grep_tmp_result.txt | sed s/^/$rep/g >> $output_path$output_file2
                    fi
                fi
            done
        fi
    done
    # 一時ファイルを削除
    rm $output_path$1/grep_tmp.txt
    rm $output_path$1/grep_tmp2.txt
    rm $output_path$1/grep_tmp3.txt
    #rm $output_path$1/grep_tmp4.txt
    rm $output_path$1/grep_tmp_result.txt

}

###############################################################################
# 関数名：grep_result
# 関数名：一時ファイルをGREP置換して件数ファイルに出力する
# 引数１：サイト
# 引数２：検索文字列２
# 引数３：検索文字列２
# 引数４：日付（YYYYMMDD)
# 引数５：カテゴリ（2桁の数値）
# 戻り値：なし
################################################################################
function grep_result() {

    #if [ "$1" != "mynavi" ] && [ "$1" != "type" ] ; then
        cat $output_path$1/grep_tmp2.txt | sed "s/(/【/g" > $output_path$1/grep_tmp3.txt
        cat $output_path$1/grep_tmp3.txt | sed "s/)/】/g" > $output_path$1/grep_tmp4.txt
        cat $output_path$1/grep_tmp4.txt | sed s/$2/$3/g > $output_path$1/grep_tmp5.txt
    #fi
    tr -d ' ' <  $output_path$1/grep_tmp5.txt >  $output_path$1/grep_tmp_result.txt
    in=`cat $output_path$1/grep_tmp_result.txt`
    sed ':loop; N; $!b loop; ;s/^ *\n//g' ../output/type/grep_tmp_result.txt
    echo `wc $output_path$1/grep_tmp_result.txt`
    if [ -n "$in" ] ; then
        # 先頭に日付とカテゴリを挿入
        rep="$1\t$4\t$5\t"
        cat $output_path$1/grep_tmp_result.txt | sed s/^/$rep/g >> $output_path$output_file
    else
        echo "nothing! > "$in
    fi
}

#rm $output_path/$output_file
#rm $output_path/$output_file2

# rikunabi
grep_files rikunabi "\/lst\_jb.*\/\".*>.*（[0-9]*件）" "^.*href=\"\/\([^/]*\)\/\([^/]*\)[^>]*>\([^<]*\).*（\([0-9]*\)件）.*$" "\1\t\2\t\3\t\4"

# en-japan
grep_files en-japan "href=\".*\([0-9]*件\)" "^.*href=\"\/\([^/]*\)\/\">\([^<]*\).*【\([0-9]*\)件】.*$" "\1\t\t\2\t\3"

# mynavi
grep_files mynavi "^.*value=.*href=\".*（[0-9]*）" "^.*href=\"\/list\/\(.*\)\/[^>]*>\([^<]*\)<.*（\([0-9]*\)）.*\n" "\1\t\t\2\t\3\r"

# type
grep_files type "class=\"mod-job-num\".*（[0-9]*件）" "^ *\([^<]*\)<.*class=\"\(.*\)\".*（\([0-9]*\)件）.*$" "\2\t\t\1\t\3"

# JobStreet(Sg)
grep_files_js "JobStreet(Sg)"

# JobStreet(My)
grep_files_js "JobStreet(My)"

# JobStreet(id)
grep_files_js "JobStreet(id)"

# JobStreet(ph)
grep_files_js "JobStreet(ph)"

# JobStreet(vn)
grep_files_js "JobStreet(vn)"


