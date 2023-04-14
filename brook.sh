#!/usr/bin/env bash

os=""
arch=""

if [ $(uname -s) = "Darwin" ]; then
    os="darwin"
fi
if [ $(uname -s) = "Linux" ]; then
    os="linux"
    if [ `cat /etc/*elease 2>/dev/null | grep 'CentOS Linux 7' | wc -l` -eq 1 ]; then
        echo "Requires CentOS version >= 8"
        exit;
    fi
fi
if [ $(uname -s | grep "MINGW" | wc -l) -eq 1 ]; then
    os="windows"
fi

if [ $(uname -m) = "x86_64" ]; then
    arch="amd64"
fi
if [ $(uname -m) = "arm64" ]; then
    arch="arm64"
fi
if [ $(uname -m) = "aarch64" ]; then
    arch="arm64"
fi

if [ "$os" = "" -o "$arch" = "" ]; then
    echo "Nami does not support your OS/ARCH yet. Please submit issue or PR to https://github.com/txthinking/nami"
    exit
fi

sfx=""
if [ $os = "windows" ]; then
    sfx=".exe"
fi

mkdir -p $HOME/.nami/bin
curl -L -o $HOME/.nami/bin/nami$sfx "https://github.com/txthinking/nami/releases/latest/download/nami_${os}_${arch}$sfx"
chmod +x $HOME/.nami/bin/nami
echo 'export PATH=$HOME/.nami/bin:$PATH' >> $HOME/.bashrc
echo 'export PATH=$HOME/.nami/bin:$PATH' >> $HOME/.bash_profile
echo 'export PATH=$HOME/.nami/bin:$PATH' >> $HOME/.zshenv
exec -l $SHELL
