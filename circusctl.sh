#!/bin/bash
endpoint=$(grep "^endpoint" circus.conf | sed "s/^.* = \(.*\)$/\1/g")
timeout 10s bin/circusctl --endpoint $endpoint $*
