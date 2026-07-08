#!/bin/sh
echo "=== Cau hinh WooCommerce Phase 2 ==="

# Gan dung trang WooCommerce
wp option update woocommerce_shop_page_id 14
wp option update woocommerce_cart_page_id 15
wp option update woocommerce_checkout_page_id 16
wp option update woocommerce_myaccount_page_id 17

# San pham digital/download
wp option update woocommerce_downloads_require_login 'yes'
wp option update woocommerce_downloads_grant_access_after_payment 'yes'
wp option update woocommerce_file_download_method 'force'

# Tax
wp option update woocommerce_calc_taxes 'yes'
wp option update woocommerce_prices_include_tax 'no'

# Customer account
wp option update woocommerce_enable_myaccount_registration 'yes'
wp option update woocommerce_enable_checkout_login_reminder 'yes'
wp option update woocommerce_enable_signup_and_login_from_checkout 'yes'

# Store address
wp option update woocommerce_store_address 'Ho Chi Minh City'
wp option update woocommerce_store_city 'Ho Chi Minh City'
wp option update woocommerce_default_country 'VN'
wp option update woocommerce_store_postcode '700000'

# Email sender
wp option update woocommerce_email_from_name 'WordPress Ecommerce'
wp option update woocommerce_email_from_address 'admin@example.com'

# Tao coupon mau WELCOME10
COUPON_EXISTS=$(wp post list --post_type=shop_coupon --name='welcome10' --format=ids)
if [ -z "$COUPON_EXISTS" ]; then
    COUPON_ID=$(wp post create \
        --post_type=shop_coupon \
        --post_title='WELCOME10' \
        --post_status=publish \
        --porcelain)
    wp post meta update $COUPON_ID discount_type 'percent'
    wp post meta update $COUPON_ID coupon_amount '10'
    wp post meta update $COUPON_ID usage_limit '100'
    wp post meta update $COUPON_ID individual_use 'yes'
    echo "Coupon WELCOME10 giam 10pct da tao (ID: $COUPON_ID)"
else
    echo "Coupon WELCOME10 da ton tai"
fi

# Coupon SALE20
COUPON2=$(wp post list --post_type=shop_coupon --name='sale20' --format=ids)
if [ -z "$COUPON2" ]; then
    C2_ID=$(wp post create \
        --post_type=shop_coupon \
        --post_title='SALE20' \
        --post_status=publish \
        --porcelain)
    wp post meta update $C2_ID discount_type 'percent'
    wp post meta update $C2_ID coupon_amount '20'
    wp post meta update $C2_ID usage_limit '50'
    echo "Coupon SALE20 giam 20pct da tao (ID: $C2_ID)"
fi

# Flush rewrite rules
wp rewrite flush

echo "=== WooCommerce Phase 2 hoan tat ==="
