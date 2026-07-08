#!/bin/sh
echo "=== PHASE 4: Digital Product Store - Final ==="

# ─── 1. TẠO THÊM FILE DOWNLOAD MẪU ───
echo ""
echo ">>> Tao cac file download mau..."

# AI Agent package JSON
cat > /var/www/html/wp-content/uploads/downloads/ai-agent-package.md << 'EOF'
# AI Agent Package — Setup Guide

## Included Files
- agent-config.json (OpenAI GPT-4 config)
- prompt-templates.md (50+ curated prompts)
- integration-guide.pdf (Step-by-step setup)

## Quick Start
1. Import agent-config.json into your AI platform
2. Configure API keys in .env file
3. Test with sample prompts from prompt-templates.md

## Support
Email: support@example.com
Response time: 24 hours
EOF

# Monthly support welcome doc
cat > /var/www/html/wp-content/uploads/downloads/monthly-support-welcome.md << 'EOF'
# Monthly DevOps Support — Welcome Package

## Your Plan Includes
- 20 hours/month dedicated support
- Slack channel access (#your-project)
- Weekly progress reports
- Priority response: < 2 hours

## Getting Started
1. Join Slack workspace (invite link in email)
2. Fill out onboarding form
3. Schedule kickoff call via Calendly link

## Contact
Slack: @vuong-dev
Email: support@example.com
EOF

echo "Files download da tao"

# ─── 2. TẠO AI AGENT PACKAGE PRODUCT ───
echo ""
echo ">>> Tao AI Agent Package product..."

AI_ID=$(wp post create \
    --post_type=product \
    --post_title="AI Agent Starter Package" \
    --post_status=publish \
    --post_content="<p>Bộ cấu hình AI Agent hoàn chỉnh dành cho doanh nghiệp nhỏ và vừa.</p>
<h3>Bao gồm:</h3>
<ul>
<li>Config file cho GPT-4 / Claude / Gemini</li>
<li>50+ prompt templates được tối ưu</li>
<li>Hướng dẫn tích hợp vào website/app</li>
<li>1 buổi hỗ trợ online 30 phút</li>
</ul>
<h3>Phù hợp với:</h3>
<ul>
<li>Chăm sóc khách hàng tự động</li>
<li>Tư vấn sản phẩm AI</li>
<li>Xử lý đơn hàng thông minh</li>
</ul>" \
    --post_excerpt="Config AI Agent + 50 prompts + hướng dẫn tích hợp. Download ngay sau khi mua." \
    --porcelain)

wp post meta update $AI_ID _regular_price '89'
wp post meta update $AI_ID _sale_price '69'
wp post meta update $AI_ID _price '69'
wp post meta update $AI_ID _virtual 'yes'
wp post meta update $AI_ID _downloadable 'yes'
wp post meta update $AI_ID _stock_status 'instock'
wp post meta update $AI_ID _download_limit '3'
wp post meta update $AI_ID _download_expiry '180'
wp post meta update $AI_ID _downloadable_files '[{"id":"ai1","name":"ai-agent-package.md","file":"http://127.0.0.1:8080/wp-content/uploads/downloads/ai-agent-package.md"}]'
echo "AI Agent Package da tao (ID: $AI_ID)"

# ─── 3. TẠO MONTHLY SUPPORT SUBSCRIPTION ───
echo ""
echo ">>> Tao Monthly Support product..."

SUB_ID=$(wp post create \
    --post_type=product \
    --post_title="Monthly DevOps Support Package" \
    --post_status=publish \
    --post_content="<p>Gói hỗ trợ kỹ thuật hàng tháng dành cho doanh nghiệp cần đội ngũ DevOps outsource.</p>
<h3>Bao gồm mỗi tháng:</h3>
<ul>
<li>20 giờ hỗ trợ kỹ thuật dedicated</li>
<li>Quản lý server, CI/CD, Docker</li>
<li>Monitoring & alerting setup</li>
<li>Weekly báo cáo tiến độ</li>
<li>Priority Slack support (phản hồi &lt; 2 giờ)</li>
</ul>
<p><em>Gia hạn hàng tháng, hủy bất cứ lúc nào.</em></p>" \
    --post_excerpt="20 giờ hỗ trợ DevOps/tháng. Server, Docker, CI/CD. Slack support ưu tiên." \
    --porcelain)

wp post meta update $SUB_ID _regular_price '299'
wp post meta update $SUB_ID _price '299'
wp post meta update $SUB_ID _virtual 'yes'
wp post meta update $SUB_ID _downloadable 'yes'
wp post meta update $SUB_ID _stock_status 'instock'
wp post meta update $SUB_ID _sold_individually 'yes'
wp post meta update $SUB_ID _downloadable_files '[{"id":"sup1","name":"monthly-support-welcome.md","file":"http://127.0.0.1:8080/wp-content/uploads/downloads/monthly-support-welcome.md"}]'
echo "Monthly Support da tao (ID: $SUB_ID)"

# ─── 4. TẠO TRANG REFUND POLICY ───
echo ""
echo ">>> Tao trang Refund Policy..."

REFUND_EXISTS=$(wp post list --post_type=page --name='refund-policy' --format=ids)
if [ -z "$REFUND_EXISTS" ]; then
    REFUND_ID=$(wp post create \
        --post_type=page \
        --post_title="Refund & Returns Policy" \
        --post_status=publish \
        --post_name="refund-policy" \
        --post_content="<h2>Chính sách hoàn tiền</h2>
<p>Chúng tôi cam kết sự hài lòng của khách hàng. Vui lòng đọc kỹ chính sách hoàn tiền trước khi mua hàng.</p>

<h3>Sản phẩm Digital / Download</h3>
<p>Do tính chất của sản phẩm kỹ thuật số, chúng tôi <strong>không hoàn tiền</strong> sau khi file đã được tải về. Nếu file lỗi hoặc không đúng mô tả, vui lòng liên hệ trong vòng <strong>7 ngày</strong>.</p>

<h3>Dịch vụ (Service)</h3>
<p>Hoàn tiền 100% nếu hủy trước khi bắt đầu thực hiện. Hoàn tiền 50% nếu đã bắt đầu nhưng chưa hoàn thành 50% khối lượng công việc.</p>

<h3>Gói hỗ trợ hàng tháng</h3>
<p>Hủy bất cứ lúc nào, không tính phí hủy. Không hoàn tiền cho tháng hiện tại đã sử dụng.</p>

<h3>Liên hệ</h3>
<p>Email: support@example.com<br>Thời gian phản hồi: trong vòng 24 giờ làm việc.</p>" \
        --porcelain)
    echo "Refund Policy page da tao (ID: $REFUND_ID)"
else
    echo "Refund Policy da ton tai"
fi

# ─── 5. TẠO TRANG TERMS OF SERVICE ───
echo ""
echo ">>> Cap nhat Terms of Service..."
TERMS_ID=$(wp post list --post_type=page --name='terms' --format=ids)
if [ ! -z "$TERMS_ID" ]; then
    wp post update $TERMS_ID --post_content="<h2>Điều khoản dịch vụ</h2>
<p>Bằng cách sử dụng website và mua sản phẩm/dịch vụ, bạn đồng ý với các điều khoản sau:</p>

<h3>1. Sản phẩm kỹ thuật số</h3>
<p>Sau khi thanh toán, bạn được cấp quyền sử dụng cá nhân hoặc thương mại (theo license đi kèm). Không được phép phân phối lại hoặc bán lại sản phẩm.</p>

<h3>2. Dịch vụ</h3>
<p>Chúng tôi cam kết thực hiện đúng mô tả dịch vụ. Thời gian hoàn thành tính từ ngày nhận đủ thông tin từ khách hàng.</p>

<h3>3. Bảo mật</h3>
<p>Thông tin cá nhân của bạn được bảo mật theo chính sách GDPR và không được chia sẻ với bên thứ ba.</p>

<h3>4. Liên hệ</h3>
<p>Mọi thắc mắc: support@example.com</p>"
    echo "Terms of Service da cap nhat (ID: $TERMS_ID)"
fi

# ─── 6. GAN CHECKOUT TERMS PAGE ───
wp option update woocommerce_terms_page_id $TERMS_ID || true

# ─── 7. CAU HINH EMAIL NOTIFICATIONS ───
echo ""
echo ">>> Cau hinh WooCommerce email notifications..."
wp option update woocommerce_new_order_settings '{"enabled":"yes","subject":"","heading":"","additional_content":""}' --format=json || true
wp option update woocommerce_customer_completed_order_settings '{"enabled":"yes","subject":"","heading":"","additional_content":"Cam on ban da mua hang. Download link co trong email nay."}' --format=json || true
wp option update woocommerce_customer_invoice_settings '{"enabled":"yes","subject":"","heading":"","additional_content":""}' --format=json || true

# ─── 8. VERIFY TOÀN BỘ PRODUCTS ───
echo ""
echo ">>> Tong ket san pham:"
wp post list --post_type=product --fields=ID,post_title --format=table

echo ""
echo ">>> Tong ket trang:"
wp post list --post_type=page --fields=ID,post_title,post_name --format=table

echo ""
echo "=== PHASE 4 hoan tat - San sang deploy ==="
