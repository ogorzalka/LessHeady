#!/bin/bash

#!/bin/bash
# generate the loader file
echo Generate the list of lessheady mixins
php index.php
echo Yeah! Generated!

test_path="../tests"

for f in ${test_path}/source/*.less; 
    do 
        target_path=${f//\/source\//\/output\/} 
        target_css="${target_path%.less}.css"
        echo "Converting $f into ${target_css} file..."
        lessc $f > ${target_css} 
done

echo Alright, everything is done !

exit 0
