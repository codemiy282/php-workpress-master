<?php
// Load WordPress Administration API for media sideloading
require_once(ABSPATH . 'wp-admin/includes/media.php');
require_once(ABSPATH . 'wp-admin/includes/file.php');
require_once(ABSPATH . 'wp-admin/includes/image.php');

echo "Starting automated digital store product generation...\n";

// List of 20 sample products. The first 5 are the core showcase items from the user's request.
$products_data = [
    // 1. n8n Workflow (Core product)
    [
        'title' => 'n8n Customer Support Auto-Responder Workflow',
        'category' => 'Workflow',
        'price' => '49.00',
        'short_desc' => 'High-performance n8n workflow JSON blueprint to auto-reply emails using OpenAI GPT-4.',
        'desc' => 'Automate your customer support pipeline. This n8n workflow monitors your support inbox, drafts rich context-aware replies using ChatGPT, searches your vector database for answers, and saves drafts directly in Gmail or Outlook.',
        'seo_title' => 'n8n AI Customer Support Gmail Auto-Responder Workflow',
        'seo_desc' => 'Download an importable n8n workflow blueprint to auto-draft email support responses using AI.',
        'features' => [
            ['title' => 'Gmail & Outlook Sync', 'desc' => 'Monitors support addresses and drafts responses automatically.'],
            ['title' => 'Vector Database Search', 'desc' => 'Queries your knowledge base to ensure technical answer accuracy.'],
            ['title' => 'Sentiment Detection', 'desc' => 'Prioritizes angry customer tickets and flags them for human review.']
        ],
        'faq' => [
            ['q' => 'How do I import this workflow?', 'a' => 'Simply copy the JSON download file and paste it into your n8n canvas.'],
            ['q' => 'Which database does it query?', 'a' => 'It supports Pinecone, Qdrant, Supabase, and custom SQL tables.']
        ]
    ],
    // 2. AI Agent (Core product)
    [
        'title' => 'Autonomous Business Analyst AI Agent',
        'category' => 'AI Agent',
        'price' => '99.00',
        'short_desc' => 'Pre-configured autonomous agent trained on SQL database analysis and CSV generation.',
        'desc' => 'Empower your decision making. This AI agent runs autonomously on your servers to monitor sales data, generate weekly reports, answer natural language questions about your database, and export charts to Slack.',
        'seo_title' => 'Autonomous SQL Business Analyst AI Agent Config',
        'seo_desc' => 'Deploy a SQL analyst AI agent to query databases and export reports in natural language.',
        'features' => [
            ['title' => 'Natural Language SQL', 'desc' => 'Ask queries in English (e.g., "what was our highest margin product last month") and get database answers.'],
            ['title' => 'Automated PDF Reporting', 'desc' => 'Generates and email weekly PDF analytics summaries to stakeholder lists.'],
            ['title' => 'Slack & Teams Alerting', 'desc' => 'Pushes visual charts directly into Slack/Teams channels.']
        ],
        'faq' => [
            ['q' => 'Is my database password secure?', 'a' => 'Yes, the AI Agent runs locally within your container and never sends credentials to third parties.'],
            ['q' => 'What databases are compatible?', 'a' => 'MySQL, PostgreSQL, SQL Server, and SQLite.']
        ]
    ],
    // 3. Prompt Pack (Core product)
    [
        'title' => 'Ultimate Midjourney & ChatGPT Prompt Pack',
        'category' => 'Prompt',
        'price' => '25.00',
        'short_desc' => 'Over 1000+ cinematic Midjourney prompts and copy-paste marketing ChatGPT prompts.',
        'desc' => 'Accelerate your creative pipeline. This library includes cinematic photorealistic prompts, logo layouts, and structured text prompts for blog outlines, product copywriting, and customer email funnels.',
        'seo_title' => '1000+ Midjourney & ChatGPT Prompt Pack Library',
        'seo_desc' => 'Get the ultimate cinematic prompt library for copywriting, logo designs, and AI imaging.',
        'features' => [
            ['title' => '100% Tested Prompts', 'desc' => 'Every prompt is hand-verified to work on Midjourney v6 and GPT-4.'],
            ['title' => 'Parameter Cheat Sheets', 'desc' => 'Understand Midjourney aspect ratios, styles, and quality tags.'],
            ['title' => 'Dynamic Placeholders', 'desc' => 'Fill-in-the-blank structures to easily custom results.']
        ],
        'faq' => [
            ['q' => 'How are the prompts delivered?', 'a' => 'You get access to a structured Notion board and an offline PDF/Excel file.'],
            ['q' => 'Can I use the images commercially?', 'a' => 'Yes, all images generated with these prompt templates belong entirely to you.']
        ]
    ],
    // 4. Automation Service (Core product)
    [
        'title' => 'Custom API & Automation Integration Service',
        'category' => 'Service',
        'price' => '499.00',
        'short_desc' => 'Custom Make.com, n8n, or Python integration built for your business by expert developers.',
        'desc' => 'Eliminate manual data entry. Our senior engineers will design, test, and deploy a custom automation scenario or Python script connecting your business systems (CRM, e-commerce, ERP, payment gateways).',
        'seo_title' => 'Custom Make.com & n8n Automation Integration Service',
        'seo_desc' => 'Hire senior automation developers to build custom Make.com scenarios or n8n workflows.',
        'features' => [
            ['title' => '1-on-1 Consultation', 'desc' => 'A private discovery call to map your workflows and requirements.'],
            ['title' => 'Rigorous Testing', 'desc' => 'We test all edge cases on a sandbox environment before go-live.'],
            ['title' => 'Handover Documentation', 'desc' => 'Complete Loom video tutorials showing how to manage and scale the workflows.']
        ],
        'faq' => [
            ['q' => 'What if the API has no public documentation?', 'a' => 'We reverse-engineer private endpoints or build custom scrapers if needed.'],
            ['q' => 'Is post-launch support included?', 'a' => 'Yes, we provide 30 days of free bug-fixing support after handoff.']
        ]
    ],
    // 5. Monthly Support (Core product)
    [
        'title' => 'Dedicated Slack & DevOps Monthly Support',
        'category' => 'Support',
        'price' => '199.00',
        'short_desc' => 'On-demand Slack developer support for database and automation stack maintenance.',
        'desc' => 'Get peace of mind. Rent a dedicated DevOps engineer to keep your servers, database schemas, API connections, and automation runs healthy. This retainer includes priority support and regular updates.',
        'seo_title' => 'WooCommerce & Automation Monthly Support Retainer',
        'seo_desc' => 'Hire dedicated technical support for your WordPress, n8n, and DB systems.',
        'features' => [
            ['title' => 'Dedicated Slack Channel', 'desc' => 'Direct access to your developer for quick questions and bug reports.'],
            ['title' => 'Daily Backups & Audits', 'desc' => 'Automatic database dumps and security audits to avoid leaks.'],
            ['title' => '4 Hours of Code Edits', 'desc' => 'Includes 4 hours of custom PHP/JS or automation updates per month.']
        ],
        'faq' => [
            ['q' => 'What is your response time?', 'a' => 'We guarantee a 2-hour SLA response for critical site crashes.'],
            ['q' => 'Can I cancel my retainer?', 'a' => 'Yes, you can cancel or pause the monthly subscription at any time.']
        ]
    ],
    // 6. Automation
    [
        'title' => 'WooCommerce to HubSpot Sync Automation',
        'category' => 'Automation',
        'price' => '39.00',
        'short_desc' => 'Synchronize customers, deals, and coupon activities to HubSpot in real-time.',
        'desc' => 'Sync order tracking, deals pipelines, and cart statuses to HubSpot.',
        'seo_title' => 'WooCommerce HubSpot CRM Integration Blueprint',
        'seo_desc' => 'Automatically synchronize WooCommerce customers and sales to HubSpot CRM.',
        'features' => [
            ['title' => 'Real-Time Sync', 'desc' => 'Fires instantly on order checkouts.'],
            ['title' => 'Deal Mapping', 'desc' => 'Maps orders to Deal stages automatically.']
        ],
        'faq' => [
            ['q' => 'Does it handle refunds?', 'a' => 'Yes, it reverses deal amounts automatically.']
        ]
    ],
    // 7. Prompt
    [
        'title' => 'Midjourney V6 Architectural Design Prompts',
        'category' => 'Prompt',
        'price' => '19.00',
        'short_desc' => 'Generate photorealistic interior and exterior layouts in Midjourney v6.',
        'desc' => 'High-end design prompt structures for interior designers, architects, and estate agents.',
        'seo_title' => 'Midjourney v6 Architectural Interior Design Prompts',
        'seo_desc' => 'Create gorgeous, realistic architectural renders in Midjourney.',
        'features' => [
            ['title' => 'Modern Styles', 'desc' => 'Prompts for Scandinavian, Japandi, and Industrial styles.'],
            ['title' => 'Lighting Formulas', 'desc' => 'Formulas for realistic daylight and ambient exposures.']
        ],
        'faq' => [
            ['q' => 'Are aspect ratios included?', 'a' => 'Yes, optimized for widescreen render options.']
        ]
    ],
    // 8. Template
    [
        'title' => 'Personal Finance Budget Tracker Notion Template',
        'category' => 'Template',
        'price' => '15.00',
        'short_desc' => 'Track expenses, income, monthly goals, and investment pipelines in Notion.',
        'desc' => 'Simple, aesthetic budget calculator featuring database relationships and summary widgets.',
        'seo_title' => 'Aesthetic Expense Budget Tracker Notion Template',
        'seo_desc' => 'Track your personal finance goals with this premium Notion dashboard template.',
        'features' => [
            ['title' => 'Auto-Calculations', 'desc' => 'Database equations calculate net margins automatically.'],
            ['title' => 'Mobile Friendly', 'desc' => 'Includes optimized mobile views for quick entries.']
        ],
        'faq' => [
            ['q' => 'Is dark mode supported?', 'a' => 'Yes, looks stunning in both light and dark modes.']
        ]
    ],
    // 9. Wordpress
    [
        'title' => 'Ascend WooCommerce Elementor Template Kit',
        'category' => 'Wordpress',
        'price' => '35.00',
        'short_desc' => 'Premium, clean WooCommerce shop and product templates built for Elementor Pro.',
        'desc' => 'Upgrade your storefront with custom single-product layouts, shop grids, and header designs.',
        'seo_title' => 'Ascend E-commerce Shop Elementor Template Kit',
        'seo_desc' => 'Build custom WooCommerce layouts with this premium Elementor theme builder kit.',
        'features' => [
            ['title' => '10+ Page Layouts', 'desc' => 'Includes Home, Shop, Cart, and Custom Checkout grids.'],
            ['title' => '100% Responsive', 'desc' => 'Perfect layouts on mobile and desktop platforms.']
        ],
        'faq' => [
            ['q' => 'Are plugins included?', 'a' => 'It uses Elementor Pro features; no third-party extensions needed.']
        ]
    ],
    // 10. Shopify
    [
        'title' => 'Shopify Mini Cart Drawer Liquid Section',
        'category' => 'Shopify',
        'price' => '29.00',
        'short_desc' => 'Interactive Ajax slide-out cart drawer for any Shopify 2.0 theme.',
        'desc' => 'Boost conversions with a clean Ajax cart drawer that updates product quantities instantly.',
        'seo_title' => 'Shopify Ajax Cart Slide-Out Liquid Section',
        'seo_desc' => 'Add an Ajax cart drawer to your Shopify store without monthly app fees.',
        'features' => [
            ['title' => 'Ajax Updates', 'desc' => 'Change quantities or remove items without reloading.'],
            ['title' => 'Cross-Sells Section', 'desc' => 'Promote related products directly inside the drawer.']
        ],
        'faq' => [
            ['q' => 'Works on free themes?', 'a' => 'Yes, tested on Dawn, Sense, and craft themes.']
        ]
    ],
    // 11. Workflow
    [
        'title' => 'ActiveCampaign E-commerce Lead Scoring Workflow',
        'category' => 'Workflow',
        'price' => '39.00',
        'short_desc' => 'Identify high-value leads based on WooCommerce browsing and purchase activities.',
        'desc' => 'Nurture shop customers based on their purchase intents and cart actions.',
        'seo_title' => 'ActiveCampaign WooCommerce Lead Nurturing Automations',
        'seo_desc' => 'Score customer purchase intent automatically to boost email marketing conversions.',
        'features' => [
            ['title' => 'Automated Labeling', 'desc' => 'Assigns customer tags dynamically.'],
            ['title' => 'Deal Progression', 'desc' => 'Moves deals in pipeline based on engagement scores.']
        ],
        'faq' => [
            ['q' => 'Is it easy to customize?', 'a' => 'Yes, adjust trigger conditions in the visual automation builder.']
        ]
    ],
    // 12. AI Agent
    [
        'title' => 'Vector DB Search Assistant Config & Code',
        'category' => 'AI Agent',
        'price' => '79.00',
        'short_desc' => 'Node.js code to run a vector search assistant using Pinecone and OpenAI embeddings.',
        'desc' => 'Connect databases to AI models for semantic search utilities.',
        'seo_title' => 'Node.js OpenAI Pinecone Vector Assistant Code',
        'seo_desc' => 'Build a semantic search AI agent script with Pinecone database configurations.',
        'features' => [
            ['title' => 'Semantic Lookup', 'desc' => 'Finds matching context based on query meaning.'],
            ['title' => 'Fast Embeddings', 'desc' => 'Uses openAI ada-002 model configurations.']
        ],
        'faq' => [
            ['q' => 'Do I need a Pinecone API key?', 'a' => 'Yes, you need a free or paid Pinecone account.']
        ]
    ],
    // 13. Service
    [
        'title' => 'LiteSpeed Cache Speed Optimization Service',
        'category' => 'Service',
        'price' => '149.00',
        'short_desc' => 'Configure LiteSpeed server cache and minifications for optimal PageSpeed scores.',
        'desc' => 'Boost search visibility by optimizing files, CSS, Javascript, and media rendering pipelines.',
        'seo_title' => 'LiteSpeed Web Server Cache Configuration Service',
        'seo_desc' => 'Get a 95+ PageSpeed mobile score with expert LiteSpeed setups.',
        'features' => [
            ['title' => 'Redis Object Cache', 'desc' => 'Configured to reduce database queries.'],
            ['title' => 'Image Optimization', 'desc' => 'Compres and converts graphics to WebP.']
        ],
        'faq' => [
            ['q' => 'Does this require LiteSpeed Hosting?', 'a' => 'Yes, your hosting provider must run LiteSpeed Web Server.']
        ]
    ],
    // 14. Support
    [
        'title' => 'WooCommerce Checkout API Support (1 Hour)',
        'category' => 'Support',
        'price' => '99.00',
        'short_desc' => '1-on-1 private troubleshooting call with a developer to fix gateway and webhook bugs.',
        'desc' => 'Resolve failing checkouts or payment sync bugs with a senior engineer.',
        'seo_title' => 'WooCommerce Checkout & Webhook Troubleshooting Support',
        'seo_desc' => 'Hire an expert developer to troubleshoot payment gateway integration issues.',
        'features' => [
            ['title' => 'Immediate Debugging', 'desc' => 'We inspect errors logs and trace webhooks.'],
            ['title' => 'Security Audit', 'desc' => 'Checks connection protocols and SSL updates.']
        ],
        'faq' => [
            ['q' => 'Can we do it via Zoom?', 'a' => 'Yes, Zoom or Google Meet screen-sharing is supported.']
        ]
    ],
    // 15. Workflow
    [
        'title' => 'Customer Feedback Loop Make.com Blueprint',
        'category' => 'Workflow',
        'price' => '29.00',
        'short_desc' => 'Automate product review email invites and feedback updates in Slack.',
        'desc' => 'Collect customer reviews automatically. Sync purchase dates to scheduling triggers.',
        'seo_title' => 'WooCommerce Automatic Feedback Review Automations',
        'seo_desc' => 'Boost social proof with our automated customer review invite blueprints.',
        'features' => [
            ['title' => 'Delay Triggers', 'desc' => 'Sends requests 7 days after delivery.'],
            ['title' => 'Negative Filter', 'desc' => 'Flags bad ratings for manual support reviews.']
        ],
        'faq' => [
            ['q' => 'Integrates with Trustpilot?', 'a' => 'Yes, modules are included to forward feedback.']
        ]
    ],
    // 16. AI Agent
    [
        'title' => 'Llama-3 Fine-Tuning Jupyter Notebook Blueprint',
        'category' => 'AI Agent',
        'price' => '69.00',
        'short_desc' => 'Pre-configured notebook to fine-tune Llama-3 8B models using Unsloth.',
        'desc' => 'Train custom AI models on business datasets using fast, low-memory code frameworks.',
        'seo_title' => 'Llama-3 8B Fine-Tuning Unsloth Colab notebook',
        'seo_desc' => 'Train custom models on your datasets using Unsloth and Jupyter notebook.',
        'features' => [
            ['title' => 'Low Memory Use', 'desc' => 'Fine-tunes models on a single 16GB VRAM GPU.'],
            ['title' => 'GGUF Exporting', 'desc' => 'Direct instructions to export formats for Ollama.']
        ],
        'faq' => [
            ['q' => 'Dataset templates included?', 'a' => 'Yes, standard Alpaca instruction formats are included.']
        ]
    ],
    // 17. Prompt
    [
        'title' => 'Midjourney V6 Flat-Lay Product Prompts',
        'category' => 'Prompt',
        'price' => '14.00',
        'short_desc' => 'Generate beautiful flat-lay images for print-on-demand and social media.',
        'desc' => 'High-resolution mockups for accessories, apparel, and stationery mock presentations.',
        'seo_title' => 'Midjourney Flat-Lay Mockup Product Prompts',
        'seo_desc' => 'Generate photorealistic flat-lay graphics for your online shop catalog.',
        'features' => [
            ['title' => 'Dynamic Objects', 'desc' => 'Change objects easily by altering keywords.'],
            ['title' => 'Shadow Control', 'desc' => 'Tags to generate clean shadows and clean backgrounds.']
        ],
        'faq' => [
            ['q' => 'Compatible with Midjourney v5?', 'a' => 'Yes, but optimized for Midjourney v6.']
        ]
    ],
    // 18. Template
    [
        'title' => 'Software Project Manager Notion Template',
        'category' => 'Template',
        'price' => '24.00',
        'short_desc' => 'Organize sprints, backlog issues, team members, and client reviews in Notion.',
        'desc' => 'Agile sprint planning dashboard featuring Gantt timelines and issue databases.',
        'seo_title' => 'Agile Software Project Manager Notion Dashboard',
        'seo_desc' => 'Manage sprint releases and backlogs with this premium Notion template.',
        'features' => [
            ['title' => 'Sprint Planners', 'desc' => 'Track sprint progress with automated stats.'],
            ['title' => 'Client Dashboards', 'desc' => 'Shareable spaces for stakeholder updates.']
        ],
        'faq' => [
            ['q' => 'Supports sub-tasks?', 'a' => 'Yes, uses Notion sub-item database relationships.']
        ]
    ],
    // 19. Wordpress
    [
        'title' => 'Vibe Agency landing Page Divi Template',
        'category' => 'Wordpress',
        'price' => '34.00',
        'short_desc' => 'Importable single-page layout JSON configured for web design agencies.',
        'desc' => 'Launch agency sites fast with pre-designed grids, pricing pages, and headers.',
        'seo_title' => 'Divi Builder Digital Agency Landing Page Layout JSON',
        'seo_desc' => 'Build digital agency landing pages instantly with this Divi Builder JSON.',
        'features' => [
            ['title' => 'CSS Animations', 'desc' => 'Includes smooth hover card animations.'],
            ['title' => 'Responsive Grid', 'desc' => 'Fits mobile screens perfectly.']
        ],
        'faq' => [
            ['q' => 'Is support included?', 'a' => 'Yes, documentation and setup guides are included.']
        ]
    ],
    // 20. Shopify
    [
        'title' => 'Shopify AJAX Product Filter Liquid Section',
        'category' => 'Shopify',
        'price' => '49.00',
        'short_desc' => 'Add fast sidebar filtering options for collections without monthly apps.',
        'desc' => 'Filter products by price, color, size, and tags in real-time.',
        'seo_title' => 'Shopify AJAX Collections filter Liquid Code',
        'seo_desc' => 'Add fast AJAX filtering options to your collection pages without subscriptions.',
        'features' => [
            ['title' => 'Sidebar Layouts', 'desc' => 'Fully responsive slide-out filters.'],
            ['title' => 'Zero Subscriptions', 'desc' => 'Self-hosted Liquid and JS code.']
        ],
        'faq' => [
            ['q' => 'Works with Shopify Translate?', 'a' => 'Yes, supports localized language tags.']
        ]
    ]
];

// Loop through each product and build it
$index = 1;
foreach ($products_data as $data) {
    echo "Creating digital product $index of 20: {$data['title']}...\n";

    // Build the Features List HTML
    $features_html = '';
    foreach ($data['features'] as $feat) {
        $features_html .= '
        <div class="feature-card" style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 20px; background: #ffffff;">
            <h4 style="margin-top: 0; color: #4f46e5; margin-bottom: 8px;">✔️ ' . esc_html($feat['title']) . '</h4>
            <p style="margin: 0; color: #475569; font-size: 14px;">' . esc_html($feat['desc']) . '</p>
        </div>';
    }

    // Build the FAQ list HTML
    $faq_html = '';
    foreach ($data['faq'] as $faq) {
        $faq_html .= '
        <dt style="font-weight: bold; color: #0f172a; margin-top: 20px; margin-bottom: 6px; font-size: 16px;">❓ ' . esc_html($faq['q']) . '</dt>
        <dd style="margin-left: 0; color: #475569; margin-bottom: 20px; border-left: 3px solid #e2e8f0; padding-left: 12px;">' . esc_html($faq['a']) . '</dd>';
    }

    // Generate mock URLs for screenshots
    $picsum_id = 10 + $index;
    $mock_img1 = "https://picsum.photos/id/{$picsum_id}/800/600";
    $mock_img2 = "https://picsum.photos/id/" . ($picsum_id + 50) . "/800/600";

    // Complete rich sales page description HTML
    $sales_page_content = '
    <div class="product-sales-page" style="font-family: \'Inter\', sans-serif; color: #1e293b; line-height: 1.6; max-width: 800px; margin: 0 auto;">
        
        <!-- Sales Hero -->
        <div class="sales-hero" style="background: #f8fafc; padding: 32px; border-radius: 12px; border: 1px solid #e2e8f0; margin-bottom: 30px;">
            <h2 style="color: #0f172a; margin-top: 0; font-size: 24px; font-weight: 800;">' . esc_html($data['title']) . '</h2>
            <p style="font-size: 16px; color: #475569; margin-bottom: 20px;">' . esc_html($data['short_desc']) . '</p>
            <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                <a href="#purchase-section" style="background: #4f46e5; color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: bold; font-size: 14px;">Buy Now - Instant Access</a>
                <a href="#features-section" style="background: white; color: #334155; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: bold; border: 1px solid #cbd5e1; font-size: 14px;">Explore Features</a>
            </div>
        </div>

        <div style="margin-bottom: 30px;">
            <p style="font-size: 15px; color: #334155;">' . esc_html($data['desc']) . '</p>
        </div>

        <!-- Features -->
        <div id="features-section" style="margin-bottom: 40px;">
            <h3 style="color: #0f172a; border-bottom: 2px solid #e2e8f0; padding-bottom: 8px; margin-bottom: 20px; font-size: 20px;">Product Features</h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 16px;">
                ' . $features_html . '
            </div>
        </div>

        <!-- Screenshots -->
        <div style="margin-bottom: 40px;">
            <h3 style="color: #0f172a; border-bottom: 2px solid #e2e8f0; padding-bottom: 8px; margin-bottom: 20px; font-size: 20px;">Screenshots & Previews</h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 16px;">
                <img src="' . $mock_img1 . '" alt="Dashboard Screen 1" style="width: 100%; border-radius: 8px; border: 1px solid #cbd5e1;" />
                <img src="' . $mock_img2 . '" alt="Dashboard Screen 2" style="width: 100%; border-radius: 8px; border: 1px solid #cbd5e1;" />
            </div>
        </div>

        <!-- Refund Policy -->
        <div style="background: #fffbeb; border: 1px solid #fef3c7; border-radius: 12px; padding: 20px; margin-bottom: 40px;">
            <h4 style="color: #b45309; margin-top: 0; margin-bottom: 8px; display: flex; align-items: center; gap: 8px; font-size: 16px;">
                🛡️ Risk-Free 14-Day Refund Policy
            </h4>
            <p style="color: #78350f; margin: 0; font-size: 14px;">We stand behind the quality of our digital assets. If the product does not perform as described or has technical defects, contact our support team within 14 days of purchase for a full refund.</p>
        </div>

        <!-- FAQ -->
        <div style="margin-bottom: 40px;">
            <h3 style="color: #0f172a; border-bottom: 2px solid #e2e8f0; padding-bottom: 8px; margin-bottom: 15px; font-size: 20px;">Frequently Asked Questions</h3>
            <dl style="margin: 0;">
                ' . $faq_html . '
            </dl>
        </div>

        <!-- Support -->
        <div style="background: #f0fdf4; border: 1px solid #dcfce7; border-radius: 12px; padding: 20px; margin-bottom: 40px;">
            <h4 style="color: #15803d; margin-top: 0; margin-bottom: 8px; font-size: 16px;">✉️ Dedicated Customer Support Included</h4>
            <p style="color: #166534; margin: 0; font-size: 14px;">All buyers get lifetime access to our support ticket system. Need help setting this up or running the script? Reach out and we will help you. Average response time is under 12 hours.</p>
        </div>

        <!-- Purchase Call to Action -->
        <div id="purchase-section" style="text-align: center; background: #0f172a; color: white; padding: 32px; border-radius: 12px; margin-bottom: 30px;">
            <h3 style="margin-top: 0; color: white; font-size: 20px;">Ready to Automate Your Business?</h3>
            <p style="color: #94a3b8; max-width: 500px; margin: 0 auto 20px auto; font-size: 14px;">Get instant download access to files and setup manuals immediately after your checkout.</p>
            <div style="font-size: 28px; font-weight: bold; margin-bottom: 12px; color: #ffffff;">$' . $data['price'] . '</div>
            <span style="font-size: 13px; color: #64748b;">Secured download link sent to your email after payment.</span>
        </div>

    </div>';

    // Create the WooCommerce product post
    $product_id = wp_insert_post([
        'post_title'    => $data['title'],
        'post_content'  => $sales_page_content,
        'post_excerpt'  => $data['short_desc'],
        'post_status'   => 'publish',
        'post_type'     => 'product',
        'post_name'     => sanitize_title($data['title'])
    ]);

    if (!$product_id || is_wp_error($product_id)) {
        echo "Error creating product: {$data['title']}\n";
        continue;
    }

    // Set Product Category
    $term = get_term_by('name', $data['category'], 'product_cat');
    if ($term) {
        wp_set_object_terms($product_id, $term->term_id, 'product_cat');
    }

    // Configure as Virtual & Downloadable Product
    update_post_meta($product_id, '_visibility', 'visible');
    update_post_meta($product_id, '_stock_status', 'instock');
    update_post_meta($product_id, '_virtual', 'yes');
    update_post_meta($product_id, '_downloadable', 'yes');
    update_post_meta($product_id, '_manage_stock', 'no');
    
    // Set Price
    update_post_meta($product_id, '_price', $data['price']);
    update_post_meta($product_id, '_regular_price', $data['price']);

    // Mock Download File link
    $file_url = 'http://localhost/downloads/' . sanitize_title($data['title']) . '-package.zip';
    $download_id = md5($file_url);
    $files = [
        $download_id => [
            'id' => $download_id,
            'name' => 'Instant Digital Download (' . $data['category'] . ')',
            'file' => $file_url
        ]
    ];
    update_post_meta($product_id, '_downloadable_files', $files);
    update_post_meta($product_id, '_download_limit', '-1');
    update_post_meta($product_id, '_download_expiry', '-1');

    // RankMath SEO Configuration
    update_post_meta($product_id, 'rank_math_title', $data['seo_title']);
    update_post_meta($product_id, 'rank_math_description', $data['seo_desc']);
    update_post_meta($product_id, 'rank_math_focus_keyword', strtolower($data['category']));

    // Sideload images from Lorem Picsum for gallery & main thumb
    $main_image_url = $mock_img1;
    $gallery_image_url1 = $mock_img2;
    $gallery_image_url2 = "https://picsum.photos/id/" . ($picsum_id + 100) . "/800/600";

    echo "Attaching mock media and gallery to {$data['title']}...\n";
    
    // 1. Download and set primary thumbnail
    $attach_id = media_sideload_image($main_image_url, $product_id, $data['title'], 'id');
    if (!is_wp_error($attach_id) && is_numeric($attach_id)) {
        set_post_thumbnail($product_id, $attach_id);
        
        // 2. Download and set gallery images
        $gallery_ids = [];
        $gal_id1 = media_sideload_image($gallery_image_url1, $product_id, $data['title'] . ' Gallery 1', 'id');
        if (!is_wp_error($gal_id1) && is_numeric($gal_id1)) {
            $gallery_ids[] = $gal_id1;
        }
        $gal_id2 = media_sideload_image($gallery_image_url2, $product_id, $data['title'] . ' Gallery 2', 'id');
        if (!is_wp_error($gal_id2) && is_numeric($gal_id2)) {
            $gallery_ids[] = $gal_id2;
        }

        if (!empty($gallery_ids)) {
            update_post_meta($product_id, '_product_image_gallery', implode(',', $gallery_ids));
        }
    } else {
        echo "Skipping image downloads for this product due to network constraints.\n";
    }

    $index++;
}

echo "Automated digital store product generation completed. 20 products created successfully!\n";
