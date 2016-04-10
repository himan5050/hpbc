#!/bin/bash

ROOT_DIR=$PWD
cd $ROOT_DIR
for f in `find . -name "*.php"`
do
    newname=`echo $f | cut -c3-`
    filename="$ROOT_DIR/$newname"

    output=`/usr/sbin/php-fpm -l $filename`
    if [ $? != 0 ]
    then
	echo $filename
	echo $output
    fi
done

for f in `find . -name "*.inc"`
do
    newname=`echo $f | cut -c3-`
    filename="$ROOT_DIR/$newname"

    output=`/usr/sbin/php-fpm -l $filename`
    if [ $? != 0 ]
    then
        echo $filename
        echo $output
    fi
done

