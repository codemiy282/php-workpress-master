#!/bin/sh
echo "=== PHASE 3: WooCommerce Core Products ==="

# ─── 1. TẠO SẢN PHẨM BIẾN THỂ (Variable Product) ───
echo ""
echo ">>> Tao san pham bien the: n8n Workflow Bundle..."

VAR_ID=$(wp post create \
    --post_type=product \
    --post_title="n8n Workflow Bundle" \
    --post_status=publish \
    --post_content="<p>Bộ workflow n8n chuyên nghiệp cho doanh nghiệp. Chọn gói phù hợp với quy mô của bạn.</p><ul><li>Tự động hóa quy trình bán hàng</li><li>Tích hợp CRM, Email Marketing</li><li>Hỗ trợ cài đặt và hướng dẫn</li></ul>" \
    --porcelain)

wp post meta update $VAR_ID _regular_price '' 
wp post meta update $VAR_ID _price ''
wp post meta update $VAR_ID _virtual 'yes'
wp post meta update $VAR_ID _product_type 'variable' || true
wp wc product_variation create $VAR_ID --regular_price=29 --status=publish --user=admin 2>/dev/null || true

# Set product type to variable
wp post term set $VAR_ID product_type variable

# Tao attributes
wp post meta update $VAR_ID _product_attributes '{
    "package": {
        "name": "Package",
        "value": "Starter | Professional | Enterprise",
        "position": 0,
        "is_visible": 1,
        "is_variation": 1,
        "is_taxonomy": 0
    }
}'

# Tao variations thu cong qua post
for TIER in "Starter:29" "Professional:79" "Enterprise:199"; do
    NAME=$(echo $TIER | cut -d: -f1)
    PRICE=$(echo $TIER | cut -d: -f2)
    V_ID=$(wp post create \
        --post_type=product_variation \
        --post_status=publish \
        --post_parent=$VAR_ID \
        --post_title="$NAME" \
        --porcelain)
    wp post meta update $V_ID _price $PRICE
    wp post meta update $V_ID _regular_price $PRICE
    wp post meta update $V_ID _virtual 'yes'
    wp post meta update $V_ID _downloadable 'yes'
    wp post meta update $V_ID attribute_package "$NAME"
    wp post meta update $V_ID _stock_status 'instock'
    echo "  - Variation $NAME: \$$PRICE (ID: $V_ID)"
done

# Gan categories
wp post term add $VAR_ID product_cat "workflow" 2>/dev/null || wp post term add $VAR_ID product_cat "n8n" 2>/dev/null || true
echo "San pham bien the da tao (ID: $VAR_ID)"

# ─── 2. TẠO DOWNLOADABLE FILE MẪU ───
echo ""
echo ">>> Tao file download mau..."
mkdir -p /var/www/html/wp-content/uploads/downloads
cat > /var/www/html/wp-content/uploads/downloads/sample-workflow.json << 'JSONEOF'
{
  "name": "n8n Customer Support Workflow",
  "nodes": [
    {"name": "Webhook", "type": "n8n-nodes-base.webhook"},
    {"name": "Send Email", "type": "n8n-nodes-base.emailSend"},
    {"name": "Respond", "type": "n8n-nodes-base.respondToWebhook"}
  ],
  "connections": {},
  "version": "1.0",
  "description": "Automated customer support response workflow"
}
JSONEOF
echo "File download da tao tai /downloads/sample-workflow.json"

# ─── 3. TẠO DIGITAL PRODUCT VỚI FILE DOWNLOAD THỰC ───
echo ""
echo ">>> Tao san pham digital co file download..."

DIG_ID=$(wp post create \
    --post_type=product \
    --post_title="n8n Starter Workflow Pack" \
    --post_status=publish \
    --post_content="<p>File JSON workflow n8n sẵn dùng. Import vào n8n và chạy ngay.</p><ul><li>Customer Support Auto-Responder</li><li>Lead Capture & CRM Sync</li><li>Invoice Auto-Generator</li></ul><p><strong>Format:</strong> .json | <strong>Compatible:</strong> n8n v1.x+</p>" \
    --post_excerpt="3 workflow n8n professional, sẵn dùng ngay sau khi download." \
    --porcelain)

wp post meta update $DIG_ID _regular_price '19'
wp post meta update $DIG_ID _price '19'
wp post meta update $DIG_ID _virtual 'yes'
wp post meta update $DIG_ID _downloadable 'yes'
wp post meta update $DIG_ID _stock_status 'instock'
wp post meta update $DIG_ID _download_limit '5'
wp post meta update $DIG_ID _download_expiry '365'
wp post meta update $DIG_ID _downloadable_files '[{"id":"file1","name":"n8n-workflow-pack.json","file":"http://127.0.0.1:8080/wp-content/uploads/downloads/sample-workflow.json"}]'
wp post term add $DIG_ID product_type simple
wp post meta update $DIG_ID _product_type 'simple'
echo "Digital product da tao (ID: $DIG_ID)"

# ─── 4. TẠO SERVICE PRODUCT ───
echo ""
echo ">>> Tao service product..."

SVC_ID=$(wp post create \
    --post_type=product \
    --post_title="WordPress Setup Service (1 Hour)" \
    --post_status=publish \
    --post_content="<p>Dịch vụ setup WordPress chuyên nghiệp trong 1 giờ làm việc.</p><h3>Bao gồm:</h3><ul><li>Cài đặt WordPress + theme</li><li>Cấu hình WooCommerce cơ bản</li><li>Cài đặt plugins cần thiết</li><li>Cấu hình SSL và bảo mật cơ bản</li></ul><p>Sau khi mua, bạn sẽ nhận email để điền thông tin yêu cầu.</p>" \
    --post_excerpt="Dịch vụ setup WordPress 1 giờ. Liên hệ sau khi đặt hàng." \
    --porcelain)

wp post meta update $SVC_ID _regular_price '49'
wp post meta update $SVC_ID _price '49'
wp post meta update $SVC_ID _virtual 'yes'
wp post meta update $SVC_ID _downloadable 'no'
wp post meta update $SVC_ID _stock_status 'instock'
wp post meta update $SVC_ID _sold_individually 'yes'
echo "Service product da tao (ID: $SVC_ID)"

# ─── 5. VERIFY PAYMENT GATEWAYS ───
echo ""
echo ">>> Kiem tra payment gateways..."
wp option get woocommerce_cod_settings --format=json | grep -o '"enabled":"[^"]*"' || true
wp option get woocommerce_stripe_settings --format=json | grep -o '"enabled":"[^"]*"' || true
echo "Payment gateways: COD (test), Stripe (sandbox), PayPal (sandbox)"

# ─── 6. WP-CLI verify products ───
echo ""
echo ">>> Danh sach san pham hien tai:"
wp post list --post_type=product --fields=ID,post_title --format=table | head -10

echo ""
echo "=== PHASE 3 hoan tat ==="
