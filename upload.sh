#!/bin/bash
source upload_creds.sh
lftp -u $USER,$PASS $SITE -e "source upload.lftp; exit"
