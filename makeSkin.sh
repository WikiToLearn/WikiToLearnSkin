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

docker run -i \
  -e HOME=/tmp/ \
  --rm \
  -v wikitolearnskin-npm-home:/tmp/ \
  -v $(pwd)/bower_components:/opt/bower_components \
  -v $(pwd)/build:/opt/build \
  -v $(pwd)/node_modules:/opt/node_modules \
  -v $(pwd)/bower.json:/opt/bower.json \
  -v $(pwd)/package.json:/opt/package.json \
  -v $(pwd)/scripts:/opt/scripts \
  -v $(pwd)/styles:/opt/styles \
  -v $(pwd)/gulpfile.js:/opt/gulpfile.js \
  -v $(pwd)/makeSkin.sh:/opt/makeSkin.sh \
  node:8 \
  /bin/bash <<EOF
export MY_TMP_GROUP=node
export MY_TMP_USER=node
if getent group ! `id -g`
then
  groupadd --gid `id -g` \$MY_TMP_GROUP
fi
if getent passwd ! `id -u`
then
  useradd -d /tmp/ --uid "`id -u`" --gid "`id -g`" \$MY_TMP_USER
fi
cd /opt/
su -s /bin/bash -c 'npm install && node_modules/bower/bin/bower install && node_modules/gulp/bin/gulp.js sass' \`id -un `id -u`\`
EOF
