#!/usr/bin/bash
set -x
USE_DOCKER="0"

if  ! [ command -v node >/dev/null 2>&1 ] ; then
    echo "No node command, using docker fallback"
    USE_DOCKER="1"
fi 

if ! [[ "$USE_DOCKER" -eq "0" ]] && [ command -v npm >/dev/null 2>&1 ]; then
    echo "No npm command, using docker fallback"
    USE_DOCKER="1"
fi


NODE_REQUIRED_VERSION="4" #lts
NODE_CURRENT_VERSION=$(node -v)
if ! [[ "$USE_DOCKER" -eq "0" ]] && [ false ] ; then #todo: add version check
    echo "NODE TOO OLD"
    USE_DOCKER="1"
fi 


NPM_REQUIRED_VERSION="3" #lts
NPM_CURRENT_VERSION=$(npm -v)
if ! [[ "$USE_DOCKER" -eq "0" ]] && [ false ] ; then #todo: add version check
    echo "NPM TOO OLD"
    USE_DOCKER="1"
fi 


NPM_COMMAND="npm install --dev"
BOWER_COMMAND="node_modules/bower/bin/bower install"
COMPILES_SASS_COMMAND="node_modules/gulp/bin/gulp.js sass"

if [[ "$USE_DOCKER" -eq "1" ]] ; then
    echo "PREPARE DOCKER"
    #prepare docker commands 
    #todo
    #all will have to mount the current directory 
fi

eval $NPM_COMMAND
eval $BOWER_COMMAND
eval $COMPILES_SASS_COMMAND 