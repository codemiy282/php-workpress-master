#!/bin/sh
set -e

# Wait for WordPress to be fully available (database connection)
echo "Waiting for WordPress database connection..."
until wp db check; do
    sleep 2
done

echo "Database is ready!"

# Install WordPress if not already installed
if ! wp core is-installed; then
    echo "Installing WordPress..."
    wp core install \
        --url="http://localhost" \
        --title="WordPress Ecommerce" \
        --admin_user="admin" \
        --admin_password="Admin@123456" \
        --admin_email="admin@example.com" \
        --skip-email
    echo "WordPress installed successfully!"
else
    echo "WordPress is already installed."
fi

# Configure Timezone
echo "Configuring timezone..."
wp option update timezone_string "Asia/Ho_Chi_Minh"

# Configure Language
echo "Installing and activating Vietnamese language..."
wp core language install vi --activate

# Configure Permalinks
echo "Setting permalink structure to Post Name..."
wp rewrite structure '/%postname%/' --hard

# Disable Comments
echo "Disabling comments and pings by default..."
wp option update default_comment_status "closed"
wp option update default_ping_status "closed"

# Delete Default Plugins
echo "Deleting default plugins (akismet, hello dolly)..."
wp plugin delete hello akismet || true

# Delete Default Sample Page and Post
echo "Deleting sample post and page..."
# Get hello-world post ID
POST_ID=$(wp post list --post_type=post --name="hello-world" --format=ids)
if [ ! -z "$POST_ID" ]; then
    wp post delete $POST_ID --force
fi

# Get sample-page page ID
PAGE_ID=$(wp post list --post_type=page --name="sample-page" --format=ids)
if [ ! -z "$PAGE_ID" ]; then
    wp post delete $PAGE_ID --force
fi

# Get existing draft privacy-policy page ID and delete it
PRIV_ID=$(wp post list --post_type=page --name="privacy-policy" --format=ids)
if [ ! -z "$PRIV_ID" ]; then
    wp post delete $PRIV_ID --force
fi

# Create Pages
echo "Creating required pages..."
for title in "Home" "About" "Contact" "Privacy Policy" "Terms" "FAQ"; do
    # Check if page already exists
    # Simple slug generator
    SLUG=$(echo "$title" | tr '[:upper:]' '[:lower:]' | tr ' ' '-')
    EXISTS=$(wp post list --post_type=page --name="$SLUG" --format=ids)
    if [ -z "$EXISTS" ]; then
        wp post create --post_type=page --post_title="$title" --post_status=publish
        echo "Created page: $title"
    else
        echo "Page already exists: $title"
    fi
done

# Install Plugins
echo "Installing and activating plugins..."
PLUGINS="elementor woocommerce seo-by-rank-math litespeed-cache fluent-smtp fluentform advanced-custom-fields updraftplus wordfence duplicate-page svg-support woocommerce-gateway-stripe woocommerce-paypal-payments"

for plugin in $PLUGINS; do
    echo "Installing $plugin..."
    if ! wp plugin is-installed "$plugin"; then
        wp plugin install "$plugin" --activate
        echo "Successfully installed and activated: $plugin"
    else
        wp plugin activate "$plugin"
        echo "$plugin is already installed and activated."
    fi
done

# Disable auto-updates for plugins
echo "Disabling auto-updates for all plugins..."
wp plugin auto-updates disable --all || true

# Disable auto-updates for core
echo "Disabling automatic updater in wp-config.php..."
wp config set AUTOMATIC_UPDATER_DISABLED true --raw || true

# Verify plugin installation status
echo "Verifying plugin installation status..."
wp plugin list

# Configure WooCommerce
echo "Configuring WooCommerce settings..."
# Set Base Location to Vietnam
wp option update woocommerce_default_country "VN" || true

# Set Currency to USD
wp option update woocommerce_currency "USD" || true

# Disable Shipping Calculations (Since we sell virtual/digital products)
wp option update woocommerce_calc_shipping "no" || true
wp option update woocommerce_ship_to_destination "only_billing" || true

# Enable Coupons
wp option update woocommerce_enable_coupons "yes" || true

# Enable Taxes
wp option update woocommerce_calc_taxes "yes" || true

# Enable Downloads settings (Downloads require login, Grant access after payment)
wp option update woocommerce_file_download_method "force" || true
wp option update woocommerce_downloads_require_login "yes" || true
wp option update woocommerce_downloads_grant_access_after_payment "yes" || true

# Configure Stripe Payment Gateway in Sandbox/Test Mode
echo "Configuring Stripe Gateway (Sandbox)..."
wp option update woocommerce_stripe_settings '{"enabled":"yes","testmode":"yes","test_publishable_key":"pk_test_sample_key_928174","test_secret_key":"sk_test_sample_key_928174"}' --format=json || true

# Configure PayPal Payment Gateway in Sandbox Mode
echo "Configuring PayPal Gateway (Sandbox)..."
wp option update woocommerce_paypal_payments_settings '{"enabled":"yes","sandbox":"yes","test_mode":"yes"}' --format=json || true

# Enable Cash on Delivery as Test Payment gateway for instant checkout verification
echo "Configuring Test Payment gateway..."
wp option update woocommerce_cod_settings '{"enabled":"yes","title":"Test Payment (Cash on Delivery)","description":"Select this to run free test transactions and download virtual products instantly."}' --format=json || true

# Set up Auto-Complete for Virtual & Downloadable Products (mu-plugin)
echo "Installing auto-complete orders plugin..."
mkdir -p /var/www/html/wp-content/mu-plugins
cat << 'EOF' > /var/www/html/wp-content/mu-plugins/autocomplete-orders.php
<?php
/*
Plugin Name: Auto-Complete Virtual Orders & Test Gateways
Description: Automatically completes WooCommerce orders for virtual/downloadable products to allow instant testing of downloads.
Version: 1.0
Author: Senior Developer
*/

add_action('woocommerce_thankyou', function($order_id) {
    if (!$order_id) return;
    $order = wc_get_order($order_id);
    if ($order && $order->has_status('processing')) {
        $order->update_status('completed');
    }
});
EOF

# Copy Custom My Account mu-plugin
echo "Installing custom My Account portal plugin..."
cp /custom-my-account.php /var/www/html/wp-content/mu-plugins/custom-my-account.php


# Install default WooCommerce pages
echo "Installing default WooCommerce pages..."
wp wc tool run install_pages --user=admin

# Create Product and Post Categories
echo "Creating categories..."
for cat in Workflow "AI Agent" Automation Prompt Template Wordpress Shopify Service Support n8n AI SEO "Prompt Engineering"; do
    # Create WooCommerce product category
    SLUG=$(echo "$cat" | tr '[:upper:]' '[:lower:]' | tr ' ' '-')
    PROD_EXISTS=$(wp term list product_cat --field=slug | grep -Fx "$SLUG" || true)
    if [ -z "$PROD_EXISTS" ]; then
        wp term create product_cat "$cat"
        echo "Created WooCommerce product category: $cat"
    fi

    # Create WordPress post category
    POST_EXISTS=$(wp term list category --field=slug | grep -Fx "$SLUG" || true)
    if [ -z "$POST_EXISTS" ]; then
        wp term create category "$cat"
        echo "Created post category: $cat"
    fi
done

# Install Theme (Woodmart or fallback Hello Elementor)
echo "Configuring theme..."
if [ -f "/var/www/html/woodmart.zip" ]; then
    echo "Found woodmart.zip! Installing Woodmart theme..."
    wp theme install /var/www/html/woodmart.zip --activate
    echo "Woodmart theme installed and activated."
else
    echo "woodmart.zip not found in workspace root. Installing Hello Elementor as fallback..."
    wp theme install hello-elementor --activate
    echo "Hello Elementor theme installed and activated."
fi

# Run Homepage setup script
echo "Running Elementor homepage generation..."
wp eval-file /setup-homepage.php

# Run Product generation script
echo "Running WooCommerce sample product generation..."
wp eval-file /generate-products.php

# Run Fluent Forms setup script
echo "Running Fluent Forms integrations..."
wp eval-file /setup-fluentform.php

# Run FluentSMTP setup script
echo "Running FluentSMTP configurations..."
wp eval-file /setup-fluentsmtp.php

# Run Blog post generation script
echo "Running sample blog post generation..."
wp eval-file /generate-blog-posts.php

# Run RankMath setup script
echo "Running RankMath SEO configurations..."
wp eval-file /setup-rankmath.php

# Run LiteSpeed Cache setup script
echo "Running LiteSpeed Cache speed optimizations..."
wp eval-file /setup-litespeed.php

# Run Wordfence setup script
echo "Running Wordfence Security configurations..."
wp eval-file /setup-wordfence.php

# Run UpdraftPlus setup script
echo "Running UpdraftPlus Backup configurations..."
wp eval-file /setup-updraft.php

echo "WordPress configuration and plugin setup completed successfully!"
