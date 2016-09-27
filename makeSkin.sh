#!/bin/bash

if [ -n "$BASH_SOURCE" ] ; then
    HACKING_DIR=$(dirname "$BASH_SOURCE")
elif [ $(basename -- "$0") = "env-setup" ]; then
    HACKING_DIR=$(dirname "$0")
elif [ -n "$KSH_VERSION" ] && echo $KSH_VERSION | grep -qv '^@(#)PD KSH'; then
    eval "HACKING_DIR=\$(dirname \"\${.sh.file}\")"
else
    echo "Can't change directory"
    exit 1
fi
cd "$HACKING_DIR"

USE_DOCKER="0"
NODE_REQUIRED_VERSION="6.0" #lts
NPM_REQUIRED_VERSION="3.0" #version running on node:6
NPM_COMMAND="npm install --dev"
BOWER_COMMAND="node_modules/bower/bin/bower install"
COMPILES_SASS_COMMAND="node_modules/gulp/bin/gulp.js sass"

if  ! command -v node >/dev/null 2>&1 ; then
    echo "No node command, using docker fallback"
    USE_DOCKER="1"
fi

if [[ "$USE_DOCKER" -eq "0" ]] && [ ! command -v npm >/dev/null 2>&1 ]; then
    echo "No npm command, using docker fallback"
    USE_DOCKER="1"
fi

if [[ "$USE_DOCKER" -eq "0" ]] ; then #node and npm found
    NODE_CURRENT_VERSION="$(node -v | cut -c 2-)"

    if [ "$NODE_REQUIRED_VERSION" != `echo -e "$NODE_CURRENT_VERSION\n$NODE_REQUIRED_VERSION" | sort -V | head -n1` ] ; then #todo: add version check
        echo "node is too old, using docker fallback"
        USE_DOCKER="1"
    else
        echo "node version is ok"
    fi

    if [[ "$USE_DOCKER" -eq "0" ]] ; then #node and npm found
        NPM_CURRENT_VERSION="$(npm -v)"
        echo $NPM_CURRENT_VERSION
        if [ "$NPM_REQUIRED_VERSION" != `echo -e "$NPM_CURRENT_VERSION\n$NPM_REQUIRED_VERSION" | sort -V | head -n1` ] ; then #todo: add version check
            echo "npm is too old, using docker fallback"
            USE_DOCKER="1"
        else
            echo "npm version is ok"
        fi
    fi
fi

if [[ "$USE_DOCKER" -eq "1" ]] ; then
    echo "Preparing docker commands"
    docker run -e HOME=/tmp/ --rm -v $(pwd):/opt node:6  /bin/bash -c "groupadd --gid `id -g` node && useradd -d /tmp --uid "`id -u`" --gid "`id -g`" node && su -s /bin/bash -c 'cd /opt && $NPM_COMMAND && $BOWER_COMMAND && $COMPILES_SASS_COMMAND' node"
else
    $NPM_COMMAND && $BOWER_COMMAND && $COMPILES_SASS_COMMAND
fi
