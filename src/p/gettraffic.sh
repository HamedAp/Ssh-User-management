#!/bin/bash


    TIMEOUT=60
    IFACE=venet0
    TIMESTAMP=`date +%d%H%M%S`
    OUTPUT=$TIMESTAMP.log


    sh -ic "{ /usr/sbin/nethogs -v 3 -t $IFACE | grep sshd >$OUTPUT; \
    kill 0; } | { sleep $TIMEOUT; \
    kill 0; }" 3>&1 2>/dev/null
