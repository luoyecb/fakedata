#!/bin/bash

set -e

basename="fakedata.phar"
bin_path="/Users/guolinchao/bin"
phar_file=${bin_path}/${basename}

rm -rf ${phar_file}
cp ${basename} ${phar_file}

cd ${bin_path}

rm -rf fakedata
ln -s ${basename} fakedata

chmod +x ${basename}
