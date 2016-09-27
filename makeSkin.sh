#!/usr/bin/bash
USE_DOCKER="0"

if  ! [ command -v node >/dev/null 2>&1 ] ; then
    echo "No node command, using docker fallback"
    USE_DOCKER="1"
fi 

if ! [[ "$USE_DOCKER" -eq "0" ]] && [ command -v npm >/dev/null 2>&1 ]; then
    echo "No npm command, using docker fallback"
    USE_DOCKER="1"
fi


NODE_REQUIRED_VERSION="6" #lts
NODE_CURRENT_VERSION=$(node -v)
if ! [[ "$USE_DOCKER" -eq "0" ]] && [ false ] ; then #todo: add version check
    echo "NODE TOO OLD"
    USE_DOCKER="1"
fi 


NPM_REQUIRED_VERSION="3" #version running on node:6
NPM_CURRENT_VERSION=$(npm -v)
if ! [[ "$USE_DOCKER" -eq "0" ]] && [ false ] ; then #todo: add version check
    echo "NPM TOO OLD"
    USE_DOCKER="1"
fi 


NPM_COMMAND="npm install --dev"
BOWER_COMMAND="node_modules/bower/bin/bower install"
COMPILES_SASS_COMMAND="node_modules/gulp/bin/gulp.js sass"

USE_DOCKER="1"

if [[ "$USE_DOCKER" -eq "1" ]] ; then
    echo "PREPARE DOCKER COMMANDS"
    PREAMBLE="docker run --rm -ti -v $(pwd):/opt node:6"
    NPM_COMMAND="$PREAMBLE bash -c 'cd /opt && $NPM_COMMAND'"
    BOWER_COMMAND="$PREAMBLE bash -c 'cd /opt && $BOWER_COMMAND'" #won't work because the user is sudo
    COMPILES_SASS_COMMAND="$PREAMBLE bash -c 'cd /opt && $COMPILES_SASS_COMMAND'"
fi

eval $NPM_COMMAND
eval $BOWER_COMMAND
eval $COMPILES_SASS_COMMAND 