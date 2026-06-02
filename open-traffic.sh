#!/bin/bash
LOG="/var/log/using_db_cron.log"
FILE="/var/db/ovpm/openvpn-status.log"
CONFIG_FILE="/var/www/html/p/config.php"
get_config() {
    local key="$1"
    grep -E "^\s*\$${key}\s*=\s*[\"']" "$CONFIG_FILE" 2>/dev/null | \
    sed -E "s/.*\$${key}\s*=\s*['\"](.*)['\"].*/\1/" | head -n1
}
DB_NAME="ShaHaN"
MYSQL_CMD="/usr/bin/mysql"
mkdir -p "$(dirname "$LOG")"
echo "=== Script started at $(date) ===" >> "$LOG"
$MYSQL_CMD "$DB_NAME" -e "
CREATE TABLE IF NOT EXISTS TrafficLast (
    user VARCHAR(50) NOT NULL,
    ip VARCHAR(50) NOT NULL,
    rx BIGINT NOT NULL DEFAULT 0,
    tx BIGINT NOT NULL DEFAULT 0,
    PRIMARY KEY (user, ip)
) ENGINE=InnoDB;
" 2>> "$LOG" || echo "WARNING: Failed to create tables" >> "$LOG"
grep -E '^[^,]+,[0-9]+\.[0-9]+\.[0-9]+\.[0-9]+' "$FILE" 2>/dev/null | while IFS=',' read -r user ip rx tx rest; do
    [[ "$rx" =~ ^[0-9]+$ ]] || continue
    [[ "$tx" =~ ^[0-9]+$ ]] || continue
    user_safe=$(echo "$user" | tr -cd 'a-zA-Z0-9_-')
    [ -z "$user_safe" ] && continue
    read -r last_rx last_tx <<< $(
        $MYSQL_CMD "$DB_NAME" -e "
            SELECT rx, tx FROM TrafficLast 
            WHERE user='$user_safe' AND ip='$ip' LIMIT 1;
        " 2>/dev/null
    )
    last_rx=${last_rx:-0}
    last_tx=${last_tx:-0}
    diff_rx=$((rx - last_rx))
    diff_tx=$((tx - last_tx))
    [ "$diff_rx" -lt 0 ] && diff_rx=0
    [ "$diff_tx" -lt 0 ] && diff_tx=0
    dl_mb=$((diff_rx / 1024 / 1024))
    ul_mb=$((diff_tx / 1024 / 1024))
    total_mb=$((dl_mb + ul_mb))
    [ "$total_mb" -eq 0 ] && continue
    $MYSQL_CMD "$DB_NAME" -e "
        INSERT INTO Traffic (user, download, upload, total)
        VALUES ('$user_safe', $dl_mb, $ul_mb, $total_mb)
        ON DUPLICATE KEY UPDATE
            download = download + VALUES(download),
            upload   = upload + VALUES(upload),
            total    = total + VALUES(total);
    " 2>> "$LOG"
    $MYSQL_CMD "$DB_NAME" -e "
        INSERT INTO TrafficLast (user, ip, rx, tx)
        VALUES ('$user_safe', '$ip', $rx, $tx)
        ON DUPLICATE KEY UPDATE
            rx = $rx,
            tx = $tx;
    " 2>> "$LOG"
done
echo "=== Script completed at $(date) ===" >> "$LOG"
