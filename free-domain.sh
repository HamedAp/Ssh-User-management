#!/bin/bash

if ! command -v jq &> /dev/null; then
    apt install jq -y
fi
SERVER_IPV4=$(curl -s -4 icanhazip.com)
DESEC_DOMAIN="manager.firewallfalcon.qzz.io"
DESEC_TOKEN="V55cFY8zTictLCPfviiuX5DHjs15"
RANDOM_SUBDOMAIN="vps-$(tr -dc a-z0-9 < /dev/urandom | head -c 8)"
FULL_DOMAIN="$RANDOM_SUBDOMAIN.$DESEC_DOMAIN"
HAS_IPV6="false"
API_DATA=$(printf '[{"subname": "%s", "type": "A", "ttl": 3600, "records": ["%s"]}]' "$RANDOM_SUBDOMAIN" "$SERVER_IPV4")
CREATE_RESPONSE=$(curl -s -w "%{http_code}" -X POST "https://desec.io/api/v1/domains/$DESEC_DOMAIN/rrsets/" \
    -H "Authorization: Token $DESEC_TOKEN" -H "Content-Type: application/json" \
--data "$API_DATA")
HTTP_CODE=${CREATE_RESPONSE: -3}
RESPONSE_BODY=${CREATE_RESPONSE:0:${#CREATE_RESPONSE}-3}
if [[ "$HTTP_CODE" -ne 201 ]]; then
    echo -e "${C_RED}❌ Failed to create DNS records. API returned HTTP $HTTP_CODE.${C_RESET}"
    if ! echo "$RESPONSE_BODY" | jq . > /dev/null 2>&1; then
        echo "Raw Response: $RESPONSE_BODY"
    else
        echo "Response: $RESPONSE_BODY" | jq
    fi
    return 1
fi
echo -e "\n${C_GREEN}✅ Successfully created domain: ${C_YELLOW}$FULL_DOMAIN${C_RESET}"
echo -e "\nWait 3 Minutes For Setting Domain"
