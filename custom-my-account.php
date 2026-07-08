<?php
/*
Plugin Name: WooCommerce Custom My Account Portal
Description: Adds custom tabs (Support, Invoices, Request Setup, Subscription) to the WooCommerce My Account page.
Version: 1.0
Author: Senior Developer
*/

// 1. Register new endpoints for My Account page
add_action('init', function() {
    add_rewrite_endpoint('support-portal', EP_PAGES);
    add_rewrite_endpoint('invoices-list', EP_PAGES);
    add_rewrite_endpoint('request-setup', EP_PAGES);
    add_rewrite_endpoint('subscriptions-portal', EP_PAGES);
});

// 2. Add endpoints to the My Account menu
add_filter('woocommerce_account_menu_items', function($items) {
    $new_items = [];
    
    $new_items['dashboard'] = isset($items['dashboard']) ? $items['dashboard'] : 'Dashboard';
    $new_items['orders'] = isset($items['orders']) ? $items['orders'] : 'Orders';
    $new_items['downloads'] = isset($items['downloads']) ? $items['downloads'] : 'Downloads';
    
    // Custom items
    $new_items['subscriptions-portal'] = 'Subscriptions';
    $new_items['invoices-list'] = 'Invoices';
    $new_items['request-setup'] = 'Request Setup';
    $new_items['support-portal'] = 'Priority Support';
    
    // Rest of defaults
    if (isset($items['edit-address'])) $new_items['edit-address'] = $items['edit-address'];
    if (isset($items['payment-methods'])) $new_items['payment-methods'] = $items['payment-methods'];
    if (isset($items['edit-account'])) $new_items['edit-account'] = $items['edit-account'];
    if (isset($items['customer-logout'])) $new_items['customer-logout'] = $items['customer-logout'];
    
    return $new_items;
}, 99);

// 3. Render contents for each custom tab

// Support Content
add_action('woocommerce_account_support-portal_endpoint', function() {
    echo '<h3 style="color:#0f172a;">✉️ Priority Support Portal</h3>';
    echo '<p style="color:#475569;">Have a question about your workflow, prompt pack, or custom setup? Open a direct ticket with our support engineers below.</p>';
    echo '<div style="border: 1px solid #e2e8f0; padding: 24px; border-radius: 8px; background: #f8fafc; margin-top:20px;">';
    echo '<h4 style="margin-top:0; color:#0f172a;">Submit a Support Ticket</h4>';
    echo '<form style="margin-top:15px; display:flex; flex-direction:column; gap:12px;">';
    echo '<label style="font-weight:bold; font-size:14px; color:#1e293b;">Subject</label>';
    echo '<input type="text" placeholder="e.g. Issue importing n8n workflow" style="padding:10px; border:1px solid #cbd5e1; border-radius:6px; width:100%;" />';
    echo '<label style="font-weight:bold; font-size:14px; color:#1e293b;">Message Details</label>';
    echo '<textarea placeholder="Please describe your issue in detail..." rows="5" style="padding:10px; border:1px solid #cbd5e1; border-radius:6px; width:100%;"></textarea>';
    echo '<button type="submit" style="background:#4f46e5; color:white; border:none; padding:12px 20px; border-radius:6px; font-weight:bold; cursor:pointer; width:fit-content;" onclick="alert(\'Support ticket submitted successfully! Check Mailpit at http://localhost:8025 for status alerts.\'); return false;">Submit Ticket</button>';
    echo '</form>';
    echo '</div>';
});

// Invoices Content
add_action('woocommerce_account_invoices-list_endpoint', function() {
    echo '<h3 style="color:#0f172a;">📄 PDF Invoices</h3>';
    echo '<p style="color:#475569;">Download official tax invoices for your shop purchases.</p>';
    
    // Retrieve user orders
    $orders = wc_get_orders([
        'customer' => get_current_user_id(),
        'limit' => 10
    ]);
    
    if (empty($orders)) {
        echo '<div style="border: 1px dashed #cbd5e1; padding: 30px; text-align: center; border-radius: 8px; color: #64748b; margin-top:20px;">No invoices found. Place your first order to generate an invoice.</div>';
    } else {
        echo '<table style="width:100%; border-collapse:collapse; margin-top:20px; font-size:14px;">';
        echo '<thead style="background:#f8fafc; border-bottom:2px solid #e2e8f0;">';
        echo '<tr>';
        echo '<th style="text-align:left; padding:12px; color:#475569;">Invoice</th>';
        echo '<th style="text-align:left; padding:12px; color:#475569;">Date</th>';
        echo '<th style="text-align:left; padding:12px; color:#475569;">Amount</th>';
        echo '<th style="text-align:center; padding:12px; color:#475569;">Action</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        foreach ($orders as $order) {
            $date = $order->get_date_created()->date('M d, Y');
            $total = $order->get_formatted_order_total();
            $num = $order->get_order_number();
            echo '<tr style="border-bottom:1px solid #e2e8f0;">';
            echo '<td style="padding:12px; font-weight:bold; color:#0f172a;">#INV-' . $num . '</td>';
            echo '<td style="padding:12px; color:#64748b;">' . $date . '</td>';
            echo '<td style="padding:12px; color:#0f172a; font-weight:bold;">' . $total . '</td>';
            echo '<td style="padding:12px; text-align:center;">';
            echo '<a href="#" style="background:#4f46e5; color:white; padding:6px 12px; border-radius:4px; text-decoration:none; font-size:12px; font-weight:bold;" onclick="alert(\'Downloading PDF Invoice #INV-' . $num . '\'); return false;">Download PDF</a>';
            echo '</td>';
            echo '</tr>';
        }
        echo '</tbody>';
        echo '</table>';
    }
});

// Request Setup Content
add_action('woocommerce_account_request-setup_endpoint', function() {
    echo '<h3 style="color:#0f172a;">📋 Request Custom Setup</h3>';
    echo '<p style="color:#475569; margin-bottom:20px;">Fill out the requirements specification form below. Our development team will review it and follow up within 24 hours.</p>';
    
    // Find Setup Service Form ID
    global $wpdb;
    $table_name = $wpdb->prefix . 'fluentform_forms';
    $form_id = $wpdb->get_var("SELECT id FROM $table_name WHERE title = 'Setup Service Form'");
    
    if ($form_id) {
        echo do_shortcode("[fluentform id=\"$form_id\"]");
    } else {
        echo '<div style="border:1px solid #fecaca; background:#fef2f2; color:#b91c1c; padding:15px; border-radius:8px;">Warning: Setup form is not initialized. Please run the deployment migrations.</div>';
    }
});

// Subscription Content
add_action('woocommerce_account_subscriptions-portal_endpoint', function() {
    echo '<h3 style="color:#0f172a;">🔄 Manage Subscriptions</h3>';
    echo '<p style="color:#475569;">View and configure your active recurring support plans.</p>';
    
    echo '<div style="border: 1px solid #e2e8f0; border-radius: 8px; background: #ffffff; padding: 24px; margin-top:20px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">';
    echo '<div style="display:flex; justify-content:between; align-items:center; flex-wrap:wrap; gap:10px; border-bottom:1px solid #f1f5f9; padding-bottom:15px; margin-bottom:15px;">';
    echo '<div>';
    echo '<h4 style="margin:0; color:#0f172a;">Dedicated Slack & DevOps Monthly Support</h4>';
    echo '<span style="font-size:12px; background:#dcfce7; color:#15803d; padding:3px 8px; border-radius:99px; font-weight:bold;">Active</span>';
    echo '</div>';
    echo '<div style="font-weight:bold; color:#0f172a; font-size:18px;">$199.00 / month</div>';
    echo '</div>';
    echo '<div style="font-size:14px; color:#475569; display:flex; flex-direction:column; gap:8px;">';
    echo '<div><strong>Next Billing Date:</strong> ' . date('M d, Y', strtotime('+1 month')) . '</div>';
    echo '<div><strong>Payment Method:</strong> Visa ending in 4242 (Stripe Test Mode)</div>';
    echo '</div>';
    echo '<div style="margin-top:20px; display:flex; gap:12px;">';
    echo '<a href="#" style="background:#4f46e5; color:white; padding:8px 16px; border-radius:6px; text-decoration:none; font-weight:bold; font-size:13px;" onclick="alert(\'Redirecting to secure Stripe Customer Portal...\'); return false;">Update Payment</a>';
    echo '<a href="#" style="background:white; color:#e11d48; border:1px solid #fca5a5; padding:8px 16px; border-radius:6px; text-decoration:none; font-weight:bold; font-size:13px;" onclick="confirm(\'Are you sure you want to cancel this plan?\') ? alert(\'Plan cancelled successfully\') : null; return false;">Cancel Subscription</a>';
    echo '</div>';
    echo '</div>';
});

// 4. Force Flush rewrite rules on plugin activation/first run
add_action('wp_loaded', function() {
    $rules = get_option('rewrite_rules');
    if (!isset($rules['my-account/support-portal/?$'])) {
        flush_rewrite_rules();
    }
});

// 5. Register custom homepage blog section shortcode
add_shortcode('custom_homepage_blog', function() {
    $posts = get_posts([
        'numberposts' => 3,
        'post_status' => 'publish'
    ]);
    
    if (empty($posts)) {
        return '<p style="text-align:center; color:#64748b;">No blog articles found. Please run the product/blog migration script.</p>';
    }
    
    $html = '<div class="homepage-blog-grid" style="display:grid; grid-template-columns:repeat(auto-fit, minmax(285px, 1fr)); gap:24px; margin-top:30px; font-family:\'Inter\', sans-serif; width:100%;">';
    
    foreach ($posts as $post) {
        $title = esc_html($post->post_title);
        $excerpt = esc_html(wp_trim_words($post->post_content, 18, '...'));
        $date = esc_html(get_the_date('M d, Y', $post->ID));
        $link = esc_url(get_permalink($post->ID));
        $img = get_the_post_thumbnail_url($post->ID, 'medium');
        if (!$img) {
            $img = 'https://picsum.photos/seed/' . $post->ID . '/600/400';
        }
        
        $cats = get_the_category($post->ID);
        $cat_name = !empty($cats) ? esc_html($cats[0]->name) : 'Article';
        
        $html .= '
        <div class="blog-card" style="background:#ffffff; border:1px solid #e2e8f0; border-radius:12px; overflow:hidden; display:flex; flex-direction:column; box-shadow:0 4px 6px -1px rgba(15,23,42,0.05); transition:transform 0.2s, box-shadow 0.2s; width:100%;" onmouseover="this.style.transform=\'translateY(-4px)\'; this.style.boxShadow=\'0 10px 15px -3px rgba(15,23,42,0.1)\';" onmouseout="this.style.transform=\'none\'; this.style.boxShadow=\'0 4px 6px -1px rgba(15,23,42,0.05)\';">
            <div style="height:180px; overflow:hidden; background:#f1f5f9;">
                <img src="' . $img . '" alt="' . $title . '" style="width:100%; height:100%; object-fit:cover;" />
            </div>
            <div style="padding:24px; display:flex; flex-direction:column; flex-grow:1; text-align:left;">
                <span style="font-size:12px; color:#4f46e5; font-weight:bold; text-transform:uppercase; margin-bottom:8px; display:block;">' . $cat_name . '</span>
                <h4 style="margin:0 0 10px 0; color:#0f172a; font-size:18px; font-weight:700; line-height:1.4;">
                    <a href="' . $link . '" style="color:#0f172a; text-decoration:none;">' . $title . '</a>
                </h4>
                <p style="color:#475569; font-size:14px; margin:0 0 20px 0; line-height:1.6; flex-grow:1;">' . $excerpt . '</p>
                <div style="display:flex; justify-content:space-between; align-items:center; border-top:1px solid #f1f5f9; padding-top:15px; font-size:13px; color:#64748b;">
                    <span>' . $date . '</span>
                    <a href="' . $link . '" style="color:#4f46e5; text-decoration:none; font-weight:bold;">Read More &rarr;</a>
                </div>
            </div>
        </div>';
    }
    
    $html .= '</div>';
    return $html;
});
