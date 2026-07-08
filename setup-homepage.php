<?php
// Activate Elementor Flexbox Container feature
$experiments = get_option('elementor_experiments', []);
$experiments['container'] = 'active';
update_option('elementor_experiments', $experiments);

// Ensure active kit exists and configure global settings
$active_kit_id = get_option('elementor_active_kit');
if (!$active_kit_id) {
    $active_kit_id = wp_insert_post([
        'post_title' => 'Default Kit',
        'post_status' => 'publish',
        'post_type' => 'elementor_library',
        'post_name' => 'default-kit'
    ]);
    update_option('elementor_active_kit', $active_kit_id);
    wp_set_object_terms($active_kit_id, 'kit', 'elementor_library_type');
}

// Global Design System configuration (Kit Settings)
$kit_settings = [
    // Global Colors
    'system_colors' => [
        [
            '_id' => 'primary',
            'title' => 'Primary',
            'color' => '#0f172a' // Slate 900 (Dark Slate)
        ],
        [
            '_id' => 'secondary',
            'title' => 'Secondary',
            'color' => '#475569' // Slate 600 (Muted)
        ],
        [
            '_id' => 'text',
            'title' => 'Text',
            'color' => '#1e293b' // Slate 800 (Readable Body)
        ],
        [
            '_id' => 'accent',
            'title' => 'Accent',
            'color' => '#4f46e5' // Indigo 600 (Vibrant Brand Color)
        ]
    ],
    // Custom Colors
    'custom_colors' => [
        [
            '_id' => 'bg_light',
            'title' => 'Light Background',
            'color' => '#f8fafc' // Slate 50
        ],
        [
            '_id' => 'border_light',
            'title' => 'Light Border',
            'color' => '#e2e8f0' // Slate 200
        ]
    ],
    // Global Fonts
    'system_typography' => [
        [
            '_id' => 'primary',
            'title' => 'Primary (Headings)',
            'typography_font_family' => 'Outfit',
            'typography_font_weight' => '700'
        ],
        [
            '_id' => 'secondary',
            'title' => 'Secondary (Sub-headings)',
            'typography_font_family' => 'Outfit',
            'typography_font_weight' => '600'
        ],
        [
            '_id' => 'text',
            'title' => 'Text (Body)',
            'typography_font_family' => 'Inter',
            'typography_font_weight' => '400'
        ],
        [
            '_id' => 'accent',
            'title' => 'Accent (Links/Buttons)',
            'typography_font_family' => 'Inter',
            'typography_font_weight' => '500'
        ]
    ],
    // Spacing
    'container_width' => [
        'unit' => 'px',
        'size' => 1200
    ],
    'space_between_widgets' => [
        'unit' => 'px',
        'size' => 24
    ],
    // Global Buttons Style
    'button_background_color' => '#4f46e5',
    'button_text_color' => '#ffffff',
    'button_border_radius' => [
        'unit' => 'px',
        'top' => '8',
        'right' => '8',
        'bottom' => '8',
        'left' => '8',
        'isLinked' => true
    ],
    'button_padding' => [
        'unit' => 'px',
        'top' => '14',
        'right' => '28',
        'bottom' => '14',
        'left' => '28',
        'isLinked' => false
    ]
];
update_post_meta($active_kit_id, '_elementor_page_settings', $kit_settings);


// Helper functions for layout generation
function generate_elementor_id() {
    return substr(md5(uniqid(rand(), true)), 0, 7);
}

// Flexbox Container Helper
function create_container($direction = 'column', $elements = [], $custom_settings = []) {
    return [
        'id' => generate_elementor_id(),
        'elType' => 'container',
        'isInner' => false,
        'settings' => array_merge([
            'content_width' => 'full',
            'direction' => $direction,
            'gap' => ['size' => 24, 'unit' => 'px'],
            'padding' => [
                'unit' => 'px',
                'top' => '60',
                'right' => '24',
                'bottom' => '60',
                'left' => '24',
                'isLinked' => false
            ]
        ], $custom_settings),
        'elements' => $elements
    ];
}

// Custom Card Container Helper
function create_card_container($elements = [], $custom_settings = []) {
    return create_container('column', $elements, array_merge([
        'background_background' => 'classic',
        'background_color' => '#ffffff',
        'border_border' => 'solid',
        'border_width' => [
            'unit' => 'px',
            'top' => '1',
            'right' => '1',
            'bottom' => '1',
            'left' => '1',
            'isLinked' => true
        ],
        'border_color' => '#e2e8f0', // Slate 200
        'border_radius' => [
            'unit' => 'px',
            'top' => '12',
            'right' => '12',
            'bottom' => '12',
            'left' => '12',
            'isLinked' => true
        ],
        'box_shadow_box_shadow_type' => 'yes',
        'box_shadow_box_shadow' => [
            'horizontal' => 0,
            'vertical' => 4,
            'blur' => 6,
            'spread' => 0,
            'color' => 'rgba(15, 23, 42, 0.05)' // 5% Slate 900
        ],
        'padding' => [
            'unit' => 'px',
            'top' => '32',
            'right' => '32',
            'bottom' => '32',
            'left' => '32',
            'isLinked' => true
        ]
    ], $custom_settings));
}

// Widgets Helpers
function create_heading_widget($title, $size = 'xl', $align = 'center', $custom = []) {
    return [
        'id' => generate_elementor_id(),
        'elType' => 'widget',
        'widgetType' => 'heading',
        'settings' => array_merge([
            'title' => $title,
            'header_size' => 'h2',
            'size' => $size,
            'align' => $align,
        ], $custom),
        'elements' => []
    ];
}

function create_text_widget($content, $align = 'left') {
    return [
        'id' => generate_elementor_id(),
        'elType' => 'widget',
        'widgetType' => 'text-editor',
        'settings' => [
            'editor' => $content,
            'align' => $align,
        ],
        'elements' => []
    ];
}

function create_button_widget($text, $link = '#', $align = 'center') {
    return [
        'id' => generate_elementor_id(),
        'elType' => 'widget',
        'widgetType' => 'button',
        'settings' => [
            'text' => $text,
            'link' => ['url' => $link, 'is_external' => '', 'nofollow' => '', 'custom_attributes' => ''],
            'align' => $align,
            'size' => 'md',
        ],
        'elements' => []
    ];
}

function create_icon_box_widget($title, $text, $icon = 'fa fa-star') {
    return [
        'id' => generate_elementor_id(),
        'elType' => 'widget',
        'widgetType' => 'icon-box',
        'settings' => [
            'title_text' => $title,
            'description_text' => $text,
            'selected_icon' => [
                'value' => $icon,
                'library' => 'fa-solid'
            ],
            'icon_position' => 'top',
        ],
        'elements' => []
    ];
}

function create_accordion_widget($items) {
    return [
        'id' => generate_elementor_id(),
        'elType' => 'widget',
        'widgetType' => 'accordion',
        'settings' => [
            'tabs' => $items,
        ],
        'elements' => []
    ];
}

function create_shortcode_widget($shortcode) {
    return [
        'id' => generate_elementor_id(),
        'elType' => 'widget',
        'widgetType' => 'shortcode',
        'settings' => [
            'shortcode' => $shortcode
        ],
        'elements' => []
    ];
}


// Reusable Templates Helper
function create_elementor_template($title, $type, $content_data) {
    $exists = get_page_by_title($title, OBJECT, 'elementor_library');
    if ($exists) {
        $post_id = $exists->ID;
    } else {
        $post_id = wp_insert_post([
            'post_title' => $title,
            'post_status' => 'publish',
            'post_type' => 'elementor_library',
            'post_name' => sanitize_title($title)
        ]);
        wp_set_object_terms($post_id, $type, 'elementor_library_type');
    }
    update_post_meta($post_id, '_elementor_data', wp_slash(json_encode($content_data)));
    update_post_meta($post_id, '_elementor_edit_mode', 'builder');
    update_post_meta($post_id, '_elementor_template_type', $type);
    update_post_meta($post_id, '_elementor_version', '3.23.0');
    return $post_id;
}


// Create Reusable Templates
echo "Generating reusable templates in library...\n";

// 1. Reusable Hero Template
$hero_template_data = [
    create_container('column', [
        create_heading_widget("Global Reusable Hero Title", 'xxl', 'left'),
        create_text_widget("<p>This is a globally managed Hero template. Edit this in your library to update all pages using it.</p>"),
        create_button_widget("Take Action", "#action", 'left')
    ], [
        'background_background' => 'classic',
        'background_color' => '#f8fafc',
        'padding' => [
            'unit' => 'px',
            'top' => '80',
            'right' => '40',
            'bottom' => '80',
            'left' => '40',
            'isLinked' => false
        ]
    ])
];
create_elementor_template("Reusable Hero Section", "section", $hero_template_data);

// 2. Reusable Feature Card Template
$card_template_data = [
    create_card_container([
        create_icon_box_widget("Template Feature", "This feature is styled using the global reusable template kit system.", "fas fa-cogs"),
        create_button_widget("Learn More", "#learn", 'left')
    ])
];
create_elementor_template("Reusable Feature Card", "section", $card_template_data);

// 3. Reusable Pricing Card Template
$pricing_template_data = [
    create_card_container([
        create_heading_widget("Pricing Tier", 'md', 'center'),
        create_text_widget("<h3 style='text-align: center; color: #4f46e5; margin: 15px 0;'>$99/mo</h3><p style='text-align: center;'>Standard license for commercial use.</p>", 'center'),
        create_button_widget("Subscribe Now", "#sub", 'center')
    ])
];
create_elementor_template("Reusable Pricing Card", "section", $pricing_template_data);


// Construct the Homepage
echo "Building Elementor Flexbox homepage...\n";

$homepage_data = [];

// 1. Hero Section (Flexbox Row layout with text column and image column)
$homepage_data[] = create_container('row', [
    // Column 1 (Left Content)
    create_container('column', [
        create_heading_widget("Accelerate Your Digital Commerce", 'xxl', 'left'),
        create_text_widget("<p>Build, scale, and optimize your online storefront with the ultimate WordPress & WooCommerce platform designed for high performance.</p>"),
        create_button_widget("Explore Marketplace", "#marketplace", 'left')
    ], ['padding' => ['unit' => 'px', 'top' => '0', 'right' => '0', 'bottom' => '0', 'left' => '0', 'isLinked' => true]]),
    
    // Column 2 (Right Mockup)
    create_container('column', [
        create_heading_widget("WooCommerce Storefront", 'lg', 'center', ['color' => '#4f46e5']),
        create_text_widget("<div style='border: 1px solid #e2e8f0; border-radius: 12px; background: #ffffff; padding: 24px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);'><h4 style='margin:0 0 10px 0;'>🔥 Best Seller</h4><p style='color:#64748b; font-size:14px; margin-bottom:15px;'>Digital Workflow Pack v2.0</p><span style='font-size:24px; font-weight:bold; color:#0f172a;'>$29.99</span></div>")
    ], ['padding' => ['unit' => 'px', 'top' => '0', 'right' => '0', 'bottom' => '0', 'left' => '0', 'isLinked' => true]])
], [
    'background_background' => 'classic',
    'background_color' => '#f8fafc', // Light bg
    'padding' => [
        'unit' => 'px',
        'top' => '100',
        'right' => '24',
        'bottom' => '100',
        'left' => '24',
        'isLinked' => false
    ]
]);

// 2. Features Section (Flexbox Row containing 3 Card Containers)
$homepage_data[] = create_container('row', [
    // Feature Card 1
    create_card_container([
        create_icon_box_widget("Lightning Fast", "Optimized with LiteSpeed Cache and high performance Nginx configurations for sub-second load times.", "fas fa-bolt")
    ]),
    // Feature Card 2
    create_card_container([
        create_icon_box_widget("Enterprise Security", "Fortified with Wordfence and secure Nginx configurations to prevent unauthorized access.", "fas fa-shield-alt")
    ]),
    // Feature Card 3
    create_card_container([
        create_icon_box_widget("Automated Backups", "Powered by UpdraftPlus to keep your database and media files secure and easily restorable.", "fas fa-sync")
    ])
], [
    'padding' => [
        'unit' => 'px',
        'top' => '80',
        'right' => '24',
        'bottom' => '40',
        'left' => '24',
        'isLinked' => false
    ]
]);

// 3. Services Section
$homepage_data[] = create_container('column', [
    create_heading_widget("Our Premium E-commerce Services", 'lg', 'center'),
    create_text_widget("<p style='text-align: center; max-width: 600px; margin: 0 auto;'>Everything you need to launch a high-converting store.</p>", 'center')
]);
$homepage_data[] = create_container('row', [
    create_card_container([
        create_icon_box_widget("Store Setup", "End-to-end configuration of your WooCommerce environment including payment gateways.", "fas fa-cog")
    ]),
    create_card_container([
        create_icon_box_widget("Custom Theme Design", "Bespoke storefront adjustments using Woodmart and Elementor page builder.", "fas fa-paint-brush")
    ]),
    create_card_container([
        create_icon_box_widget("SEO Optimization", "Fully optimized permalinks, schema, and meta data setup using RankMath.", "fas fa-search")
    ])
], ['padding' => ['unit' => 'px', 'top' => '20', 'right' => '24', 'bottom' => '60', 'left' => '24', 'isLinked' => false]]);

// 4. Workflow Marketplace Section
$homepage_data[] = create_container('column', [
    create_heading_widget("Workflow Marketplace", 'lg', 'center'),
    create_text_widget("<p style='text-align: center; max-width: 600px; margin: 0 auto;'>Automate your operations with pre-configured marketing and sales funnels.</p>", 'center')
]);
$homepage_data[] = create_container('row', [
    create_card_container([
        create_icon_box_widget("Abandoned Cart Automation", "Recover lost sales automatically using Fluent Forms and WooCommerce triggers.", "fas fa-shopping-cart")
    ]),
    create_card_container([
        create_icon_box_widget("Email Marketing Sync", "Connect customer purchases directly to your CRM with WP Mail SMTP integrations.", "fas fa-envelope-open-text")
    ])
], ['padding' => ['unit' => 'px', 'top' => '20', 'right' => '24', 'bottom' => '60', 'left' => '24', 'isLinked' => false]]);

// 5. Testimonials Section
$homepage_data[] = create_container('column', [
    create_heading_widget("What Our Clients Say", 'lg', 'center')
]);
$homepage_data[] = create_container('row', [
    create_card_container([
        create_text_widget("<blockquote style='font-style: italic; border-left: 4px solid #4f46e5; padding-left: 15px; margin:0;'>\"This platform has transformed our online sales. The site loads instantly and customer conversions are up by 40%.\"<br><br><strong>- Linh Nguyen, CEO of affi</strong></blockquote>")
    ]),
    create_card_container([
        create_text_widget("<blockquote style='font-style: italic; border-left: 4px solid #4f46e5; padding-left: 15px; margin:0;'>\"Extremely secure and stable. We didn't experience any downtime during our Black Friday sales campaign.\"<br><br><strong>- Minh Son, CTO of TechVibe</strong></blockquote>")
    ])
], ['padding' => ['unit' => 'px', 'top' => '20', 'right' => '24', 'bottom' => '60', 'left' => '24', 'isLinked' => false]]);

// 6. Pricing Section (Flexbox Row containing 3 pricing card layouts)
$homepage_data[] = create_container('column', [
    create_heading_widget("Flexible Pricing Plans", 'lg', 'center')
]);
$homepage_data[] = create_container('row', [
    create_card_container([
        create_heading_widget("Starter", 'md', 'center'),
        create_text_widget("<h3 style='text-align: center; color:#4f46e5;'>$49/mo</h3><p style='text-align: center;'>For small shops starting their journey.</p>", 'center'),
        create_button_widget("Get Started", "#starter", 'center')
    ]),
    create_card_container([
        create_heading_widget("Professional", 'md', 'center'),
        create_text_widget("<h3 style='text-align: center; color:#4f46e5;'>$99/mo</h3><p style='text-align: center;'>For growing brands looking to scale.</p>", 'center'),
        create_button_widget("Upgrade to Pro", "#pro", 'center')
    ], [
        'border_color' => '#4f46e5', // Brand highlighting
        'border_width' => ['unit' => 'px', 'top' => '2', 'right' => '2', 'bottom' => '2', 'left' => '2', 'isLinked' => true]
    ]),
    create_card_container([
        create_heading_widget("Enterprise", 'md', 'center'),
        create_text_widget("<h3 style='text-align: center; color:#4f46e5;'>$249/mo</h3><p style='text-align: center;'>For large operations requiring custom infrastructure.</p>", 'center'),
        create_button_widget("Contact Sales", "#enterprise", 'center')
    ])
], ['padding' => ['unit' => 'px', 'top' => '20', 'right' => '24', 'bottom' => '60', 'left' => '24', 'isLinked' => false]]);

// Blog Section
$homepage_data[] = create_container('column', [
    create_heading_widget("Latest Insights & News", 'lg', 'center'),
    create_text_widget("<p style='text-align: center; max-width: 600px; margin: 0 auto;'>Learn how to scale your business automations and e-commerce growth.</p>", 'center'),
    create_shortcode_widget("[custom_homepage_blog]")
], [
    'padding' => [
        'unit' => 'px',
        'top' => '60',
        'right' => '24',
        'bottom' => '40',
        'left' => '24',
        'isLinked' => false
    ]
]);

// 7. FAQ Section
$faq_items = [
    [
        'tab_title' => 'How does the platform handle site performance?',
        'tab_content' => 'We integrate LiteSpeed Cache and high performance Nginx fastcgi buffers to optimize code execution and database query speeds.',
    ],
    [
        'tab_title' => 'Is this environment secure for transactions?',
        'tab_content' => 'Yes, our setup blocks direct PHP execution in uploads folders, hides Nginx metadata, and comes pre-configured with Wordfence firewall rules.',
    ],
    [
        'tab_title' => 'Can I import custom demo templates later?',
        'tab_content' => 'Yes, you can import Woodmart starter templates and modify pages using the drag-and-drop Elementor editor.',
    ]
];
$homepage_data[] = create_container('column', [
    create_heading_widget("Frequently Asked Questions", 'lg', 'center'),
    create_accordion_widget($faq_items)
], [
    'padding' => [
        'unit' => 'px',
        'top' => '60',
        'right' => '24',
        'bottom' => '80',
        'left' => '24',
        'isLinked' => false
    ]
]);

// 8. Footer Section (Flexbox centered container)
$homepage_data[] = create_container('column', [
    create_text_widget("<p style='text-align: center; color: #64748b; margin: 0;'>&copy; " . date('Y') . " WordPress Ecommerce. All rights reserved. Optimized with Elementor Kit.</p>", 'center')
], [
    'background_background' => 'classic',
    'background_color' => '#0f172a', // Dark theme footer
    'padding' => [
        'unit' => 'px',
        'top' => '40',
        'right' => '24',
        'bottom' => '40',
        'left' => '24',
        'isLinked' => false
    ]
]);


// Find or create homepage
$home_page = get_page_by_path('home');
if (!$home_page) {
    $post_id = wp_insert_post([
        'post_title'   => 'Home',
        'post_status'  => 'publish',
        'post_type'    => 'page',
        'post_name'    => 'home'
    ]);
} else {
    $post_id = $home_page->ID;
}

// Set as static front page
update_option('show_on_front', 'page');
update_option('page_on_front', $post_id);

// Write Elementor Layout Data
update_post_meta($post_id, '_elementor_data', wp_slash(json_encode($homepage_data)));
update_post_meta($post_id, '_elementor_edit_mode', 'builder');
update_post_meta($post_id, '_elementor_template_type', 'wp-page');
update_post_meta($post_id, '_elementor_version', '3.23.0');

echo "Homepage successfully built with Elementor Flexbox Containers and reusable kit styles!\n";
