#!/usr/bin/bash
USE_DOCKER="0"

if  ! command -v node >/dev/null 2>&1 ; then
    echo "No node command, using docker fallback"
    USE_DOCKER="1"
fi 

if [[ "$USE_DOCKER" -eq "0" ]] && [ ! command -v npm >/dev/null 2>&1 ]; then
    echo "No npm command, using docker fallback"
    USE_DOCKER="1"
fi

if [[ "$USE_DOCKER" -eq "0" ]] ; then #node and npm found
    NODE_REQUIRED_VERSION="6.0" #lts
    NODE_CURRENT_VERSION="$(node -v | cut -c 2-)"
    echo $NODE_CURRENT_VERSION
    if [[ "$USE_DOCKER" -eq "0" ]] && [ "$NODE_REQUIRED_VERSION" != "`echo "$NODE_CURRENT_VERSION\n$NODE_REQUIRED_VERSION" | sort -V | head -n1`" ] ; then #todo: add version check
        echo "node is too old, using docker fallback"
        USE_DOCKER="1"
    else
        echo "node version is ok"
    fi 


    NPM_REQUIRED_VERSION="3.0" #version running on node:6
    NPM_CURRENT_VERSION="$(npm -v)"
    echo $NPM_CURRENT_VERSION
    if [[ "$USE_DOCKER" -eq "0" ]] && [ "$NPM_REQUIRED_VERSION" != "`echo "$NPM_CURRENT_VERSION\n$NPM_REQUIRED_VERSION" | sort -V | head -n1`" ] ; then #todo: add version check
        echo "npm is too old, using docker fallback"
        USE_DOCKER="1"
    else
        echo "npm version is ok"
    fi 

    exit 1
fi

NPM_COMMAND="npm install --dev"
BOWER_COMMAND="node_modules/bower/bin/bower install"
COMPILES_SASS_COMMAND="node_modules/gulp/bin/gulp.js sass"

USE_DOCKER="1" #debuggin

if [[ "$USE_DOCKER" -eq "1" ]] ; then
    echo "Preparing docker commands"
    PREAMBLE="docker run --rm -ti -v $(pwd):/opt node:6"
    NPM_COMMAND="$PREAMBLE bash -c 'cd /opt && $NPM_COMMAND'"
    BOWER_COMMAND="$PREAMBLE bash -c 'cd /opt && $BOWER_COMMAND --allow-root'"
    COMPILES_SASS_COMMAND="$PREAMBLE bash -c 'cd /opt && $COMPILES_SASS_COMMAND'"
fi

eval $NPM_COMMAND
eval $BOWER_COMMAND
eval $COMPILES_SASS_COMMAND 