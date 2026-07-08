<?php
global $wpdb;

echo "Setting up Fluent Forms integrations...\n";

// Define the Fluent Form fields JSON structure matching the fields requested
$form_fields = [
    'fields' => [
        [
            'element' => 'input_text',
            'attributes' => [
                'type' => 'text',
                'name' => 'business_name',
                'placeholder' => 'Enter your business name',
                'value' => ''
            ],
            'settings' => [
                'container_class' => '',
                'placeholder' => 'Enter your business name',
                'label' => 'Business Name',
                'label_placement' => 'top',
                'help_message' => '',
                'validation_rules' => [
                    'required' => [
                        'value' => true,
                        'message' => 'Business name is required'
                    ]
                ]
            ],
            'editor_options' => [
                'title' => 'Single Line Text'
            ]
        ],
        [
            'element' => 'input_text',
            'attributes' => [
                'type' => 'text',
                'name' => 'target_platform',
                'placeholder' => 'e.g. n8n, Make.com, HubSpot, Shopify',
                'value' => ''
            ],
            'settings' => [
                'container_class' => '',
                'placeholder' => 'e.g. n8n, Make.com, HubSpot, Shopify',
                'label' => 'Target Platform',
                'label_placement' => 'top',
                'help_message' => '',
                'validation_rules' => [
                    'required' => [
                        'value' => true,
                        'message' => 'Platform selection is required'
                    ]
                ]
            ],
            'editor_options' => [
                'title' => 'Single Line Text'
            ]
        ],
        [
            'element' => 'textarea',
            'attributes' => [
                'name' => 'current_workflow',
                'placeholder' => 'Describe how it works today...',
                'rows' => 4,
                'cols' => 2
            ],
            'settings' => [
                'container_class' => '',
                'placeholder' => 'Describe how it works today...',
                'label' => 'Current Workflow',
                'label_placement' => 'top',
                'help_message' => '',
                'validation_rules' => [
                    'required' => [
                        'value' => true,
                        'message' => 'Current workflow description is required'
                    ]
                ]
            ],
            'editor_options' => [
                'title' => 'Textarea'
            ]
        ],
        [
            'element' => 'textarea',
            'attributes' => [
                'name' => 'expected_workflow',
                'placeholder' => 'Describe your desired outcome...',
                'rows' => 4,
                'cols' => 2
            ],
            'settings' => [
                'container_class' => '',
                'placeholder' => 'Describe your desired outcome...',
                'label' => 'Expected Workflow',
                'label_placement' => 'top',
                'help_message' => '',
                'validation_rules' => [
                    'required' => [
                        'value' => true,
                        'message' => 'Expected outcome is required'
                    ]
                ]
            ],
            'editor_options' => [
                'title' => 'Textarea'
            ]
        ],
        [
            'element' => 'input_text',
            'attributes' => [
                'type' => 'text',
                'name' => 'deadline',
                'placeholder' => 'e.g. 2 weeks, ASAP, end of month',
                'value' => ''
            ],
            'settings' => [
                'container_class' => '',
                'placeholder' => 'e.g. 2 weeks, ASAP, end of month',
                'label' => 'Expected Deadline',
                'label_placement' => 'top',
                'help_message' => '',
                'validation_rules' => [
                    'required' => [
                        'value' => true,
                        'message' => 'Deadline is required'
                    ]
                ]
            ],
            'editor_options' => [
                'title' => 'Single Line Text'
            ]
        ],
        [
            'element' => 'input_text',
            'attributes' => [
                'type' => 'text',
                'name' => 'budget',
                'placeholder' => 'e.g. $500 - $1000',
                'value' => ''
            ],
            'settings' => [
                'container_class' => '',
                'placeholder' => 'e.g. $500 - $1000',
                'label' => 'Estimated Budget ($)',
                'label_placement' => 'top',
                'help_message' => '',
                'validation_rules' => [
                    'required' => [
                        'value' => true,
                        'message' => 'Budget estimation is required'
                    ]
                ]
            ],
            'editor_options' => [
                'title' => 'Single Line Text'
            ]
        ],
        [
            'element' => 'button',
            'settings' => [
                'container_class' => '',
                'btn_text' => 'Submit Setup Service Request',
                'button_ui' => [
                    'text' => 'Submit Setup Service Request',
                    'align' => 'left',
                    'type' => 'submit'
                ]
            ],
            'editor_options' => [
                'title' => 'Submit Button'
            ]
        ]
    ]
];

// Check if form table exists (Fluent Forms must be activated)
$table_name = $wpdb->prefix . 'fluentform_forms';
if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
    echo "Warning: Fluent Forms table is not initialized yet. Skipping form creation.\n";
    exit;
}

// Check if Setup Service Form already exists
$existing_form = $wpdb->get_row("SELECT * FROM $table_name WHERE title = 'Setup Service Form'");
if ($existing_form) {
    $form_id = $existing_form->id;
    echo "Setup Service Form already exists (ID: $form_id).\n";
} else {
    // Insert new form
    $wpdb->insert(
        $table_name,
        [
            'title' => 'Setup Service Form',
            'form_fields' => json_encode($form_fields),
            'status' => 'published',
            'has_payment' => 0,
            'type' => 'form',
            'created_by' => 1,
            'created_at' => current_time('mysql'),
            'updated_at' => current_time('mysql')
        ]
    );
    $form_id = $wpdb->insert_id;
    echo "Created Setup Service Form with ID: $form_id.\n";
}

// Generate the "Setup Service" Product
$product_title = 'Custom Workflow Setup & Consulting Service';
$existing_product = get_page_by_title($product_title, OBJECT, 'product');

// Form shortcode representation
$form_shortcode = "[fluentform id=\"$form_id\"]";

$product_content = '
<div class="product-sales-page" style="font-family: \'Inter\', sans-serif; color: #1e293b; line-height: 1.6; max-width: 800px; margin: 0 auto;">
    
    <!-- Sales Hero -->
    <div class="sales-hero" style="background: #f8fafc; padding: 32px; border-radius: 12px; border: 1px solid #e2e8f0; margin-bottom: 30px;">
        <h2 style="color: #0f172a; margin-top: 0; font-size: 24px; font-weight: 800;">Bespoke Workflow Setup Service</h2>
        <p style="font-size: 16px; color: #475569; margin-bottom: 20px;">Get your custom automation pipelines built by senior integration developers. Submit your details below to start.</p>
    </div>

    <!-- Requirements Form -->
    <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 32px; margin-bottom: 40px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
        <h3 style="color: #0f172a; margin-top: 0; margin-bottom: 20px; border-bottom: 2px solid #f1f5f9; padding-bottom: 10px; font-size: 18px;">📋 Tell Us About Your Project</h3>
        ' . $form_shortcode . '
    </div>

    <!-- Features -->
    <div style="margin-bottom: 40px;">
        <h3 style="color: #0f172a; border-bottom: 2px solid #e2e8f0; padding-bottom: 8px; margin-bottom: 20px; font-size: 20px;">What We Deliver</h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 16px;">
            <div class="feature-card" style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 20px; background: #ffffff;">
                <h4 style="margin-top: 0; color: #4f46e5; margin-bottom: 8px;">✔️ Turnkey Deployment</h4>
                <p style="margin: 0; color: #475569; font-size: 14px;">We build, configure, and launch the workflows directly on your environment.</p>
            </div>
            <div class="feature-card" style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 20px; background: #ffffff;">
                <h4 style="margin-top: 0; color: #4f46e5; margin-bottom: 8px;">✔️ Error Handling & Alerts</h4>
                <p style="margin: 0; color: #475569; font-size: 14px;">Robust alert mechanisms that message your Slack/Discord if runs fail.</p>
            </div>
        </div>
    </div>

    <!-- Refund Policy -->
    <div style="background: #fffbeb; border: 1px solid #fef3c7; border-radius: 12px; padding: 20px; margin-bottom: 40px;">
        <h4 style="color: #b45309; margin-top: 0; margin-bottom: 8px; display: flex; align-items: center; gap: 8px; font-size: 16px;">
            🛡️ Risk-Free 14-Day Refund Policy
        </h4>
        <p style="color: #78350f; margin: 0; font-size: 14px;">If we determine during review that your project is technically unfeasible, or if the delivered setup does not perform as outlined, you get a full 100% refund.</p>
    </div>

    <!-- Support -->
    <div style="background: #f0fdf4; border: 1px solid #dcfce7; border-radius: 12px; padding: 20px; margin-bottom: 40px;">
        <h4 style="color: #15803d; margin-top: 0; margin-bottom: 8px; font-size: 16px;">✉️ Dedicated Consulting & Support</h4>
        <p style="color: #166534; margin: 0; font-size: 14px;">Includes 30 days of direct developer support and Loom instructions explaining the architecture.</p>
    </div>
</div>';

if ($existing_product) {
    wp_update_post([
        'ID'           => $existing_product->ID,
        'post_content' => $product_content
    ]);
    echo "Updated Setup Service Product with form integration.\n";
} else {
    $product_id = wp_insert_post([
        'post_title'    => $product_title,
        'post_content'  => $product_content,
        'post_excerpt'  => 'Get a custom automation setup on n8n, Make.com, or Shopify built by expert developers.',
        'post_status'   => 'publish',
        'post_type'     => 'product',
        'post_name'     => 'custom-workflow-setup-service'
    ]);
    
    // Set Product Category to Service
    $term = get_term_by('name', 'Service', 'product_cat');
    if ($term) {
        wp_set_object_terms($product_id, $term->term_id, 'product_cat');
    }

    // Configure WooCommerce fields
    update_post_meta($product_id, '_visibility', 'visible');
    update_post_meta($product_id, '_stock_status', 'instock');
    update_post_meta($product_id, '_virtual', 'yes');
    update_post_meta($product_id, '_manage_stock', 'no');
    update_post_meta($product_id, '_price', '299.00'); // Standard consulting base price
    update_post_meta($product_id, '_regular_price', '299.00');

    // RankMath SEO
    update_post_meta($product_id, 'rank_math_title', 'Custom Automation & Workflow Setup Service');
    update_post_meta($product_id, 'rank_math_description', 'Hire senior developer to configure n8n, Make.com, or custom APIs. Submit your requirements.');
    
    echo "Created Setup Service Product with form integration.\n";
}
