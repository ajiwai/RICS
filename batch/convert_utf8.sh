#!/bin/bash

function nkf_utf8() {

    for filepath in $1/*
    do
        if [ -f $filepath ] ; then
            nkf -w ${filepath} > ${filepath}".utf8.html"
            mv -f ${filepath}".utf8.html" ${filepath}
#            echo "filepath="${filepath}
        elif [ -d $filepath ] ; then
            for filepath2 in ${filepath}/*
            do
                if [ -f $filepath2 ] ; then
                    nkf -w ${filepath2} > ${filepath2}".utf8.html"
                    mv -f ${filepath2}".utf8.html" ${filepath2}
#                     echo "filepath2="${filepath2}
                fi
            done
        fi
    done

}

nkf_utf8 $1
