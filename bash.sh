# bash script to copy /test-data/index.md to README.md

echo  'About to copy test-data/index.md to ../README.md';

cp -vf test-data/index.md README.md;

echo 'Done, check the work or deal with error messages.';
