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

for dir in bower_components build node_modules scripts styles
do
  test ! -d $dir && mkdir $dir
done
for file in package.json bower.json gulpfile.js
do
  test ! -f $file && touch $file
done

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
  node:8 \
  /bin/bash <<EOF
set -e
set -x
export MY_TMP_GROUP=node-tmp
export MY_TMP_USER=node-tmp
if [[ \$(getent group `id -g` | wc -c) -eq 0 ]]
then
  groupadd --gid `id -g` \$MY_TMP_GROUP
fi
if [[ \$(getent passwd `id -u` | wc -c) -eq 0 ]]
then
  useradd -d /tmp/ --uid "`id -u`" --gid "`id -g`" \$MY_TMP_USER
fi
cd /opt/
getent group  `id -g`
getent passwd `id -u`
su -s /bin/bash -c 'npm install && node_modules/bower/bin/bower install && node_modules/gulp/bin/gulp.js sass' \`id -un `id -u`\`
EOF
