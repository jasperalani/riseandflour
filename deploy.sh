FTP_USER="jasperwindsurf@riseandflour.co.uk"
FTP_HOST="ftp.riseandflour.co.uk"
LOCAL_DIR="./src/"

# Check for dry-run flag
DRY_RUN=""
if [ "$1" = "--dry-run" ]; then
    DRY_RUN="--dry-run"
    echo "Running in DRY-RUN mode. No files will be transferred."
fi

echo "Enter password: "
read _PASSWORD

# webm swf png mp4 m4v jpg gif
ftp-deploy \
    --server $FTP_HOST \
    --username $FTP_USER \
    --password $_PASSWORD \
    --local-dir $LOCAL_DIR $DRY_RUN \
    --server-dir /riseandflour.co.uk/public_html/wp-content/themes/riseandflour/
