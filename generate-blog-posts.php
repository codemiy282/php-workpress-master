<?php
require_once(ABSPATH . 'wp-admin/includes/media.php');
require_once(ABSPATH . 'wp-admin/includes/file.php');
require_once(ABSPATH . 'wp-admin/includes/image.php');

echo "Starting automated blog post generation...\n";

// List of categories and their corresponding articles
$articles_data = [
    [
        'title' => 'Why Business Automation is No Longer Optional in 2026',
        'category' => 'Automation',
        'content' => 'In 2026, the gap between automated businesses and manual ones has widened to a chasm. Businesses that automate manual data entry, lead syncing, and customer notifications save an average of 15 hours per employee weekly. 

This article explores how simple webhooks, visual automation builders like Make.com, and script-based cron jobs can cut operating costs by up to 40% and eliminate human entry errors entirely. We recommend starting with simple workflows, like syncing contact forms to CRMs, before moving on to complex multi-app data transfers.',
        'seo_title' => 'Why Business Automation is Crucial in 2026 | Scalability Guide',
        'seo_desc' => 'Read our latest guide on how business automation saves time and cuts costs for agencies.'
    ],
    [
        'title' => 'Getting Started with n8n: The Ultimate Open-Source Zapier Alternative',
        'category' => 'n8n',
        'content' => 'n8n has taken the automation world by storm. Unlike closed-source alternatives, n8n offers a fair-code license, allowing you to self-host the editor on your own Docker containers without limits on execution volume.

This walkthrough outlines how to install n8n using Docker, configure your first Gmail and SQL database triggers, design logic branches with conditional nodes, and securely handle API credential managers. If you are looking to build complex, multi-step zaps without paying per-run fees, n8n is the ultimate choice.',
        'seo_title' => 'Getting Started with n8n | Open-Source Zapier Alternative',
        'seo_desc' => 'Learn how to self-host n8n using Docker and build unlimited automation workflows.'
    ],
    [
        'title' => 'How to Leverage LLMs in Your Day-to-Day Business Tasks',
        'category' => 'AI',
        'content' => 'Large Language Models (LLMs) like GPT-4 and Claude 3.5 Sonnet are not just text generators—they are intelligent reasoning engines. The businesses winning today are those integrating LLMs directly into their daily operations.

From auto-drafting client email replies to summarizing 100-page financial CSVs, we break down actionable strategies to build custom GPT systems, connect vector databases for internal knowledge queries, and set up autonomous agents that monitor business pipelines.',
        'seo_title' => 'How to Leverage LLMs for Business | AI Agent Workflows',
        'seo_desc' => 'Discover practical methods to automate data analysis and email drafting using LLMs.'
    ],
    [
        'title' => 'Speeding Up WordPress: Best Cache Plugins and Tweaks',
        'category' => 'Wordpress',
        'content' => 'Site speed is a critical SEO factor and direct conversion driver. A store that takes longer than 2 seconds to load suffers a 50% drop in checkouts.

In this developer guide, we inspect the best optimizations for high-traffic WordPress sites: configuring LiteSpeed server cache, setting up Redis database caching to reduce SQL query queues, minifying JS/CSS assets, and offloading heavy uploads to object storage. Follow these steps to hit a 95+ score on PageSpeed mobile.',
        'seo_title' => 'WordPress Speed Optimization Guide | Sub-1s Load Times',
        'seo_desc' => 'Optimise WordPress page speeds with LiteSpeed cache, Redis, and CSS minifications.'
    ],
    [
        'title' => 'On-Page SEO Checklist for E-commerce Sites',
        'category' => 'SEO',
        'content' => 'E-commerce SEO is highly competitive but highly rewarding. To rank product pages above retail giants, your site architecture and on-page metadata must be flawless.

Our developers have compiled the ultimate checklist: formatting clean, keyword-rich permalinks (/%postname%/), writing compelling RankMath title tags, deploying FAQ schemas to target rich snippets, adding descriptive image alt texts, and linking categories to index paths correctly. Read the full post to rank higher on search engines.',
        'seo_title' => 'E-commerce On-Page SEO Checklist | RankMath Guide',
        'seo_desc' => 'Use our complete SEO checklist to optimize your e-commerce product pages and rank higher.'
    ],
    [
        'title' => 'Advanced Prompting Techniques: Chain-of-Thought and Few-Shot Learning',
        'category' => 'Prompt Engineering',
        'content' => 'Getting accurate outputs from LLMs requires more than writing "please write an article". Prompt engineering is the science of structuring instructions to align the model\'s reasoning pathway.

We explain the mechanics of Chain-of-Thought (forcing the model to show its reasoning step-by-step before answering) and Few-Shot Learning (providing 3-5 high-quality examples of inputs and desired outputs). Master these paradigms to generate perfect JSON arrays or parse unstructured support emails.',
        'seo_title' => 'Advanced Prompt Engineering: Chain-of-Thought & Few-Shot',
        'seo_desc' => 'Learn how to construct advanced ChatGPT and Claude prompts for structured data outputs.'
    ]
];

$index = 1;
foreach ($articles_data as $data) {
    echo "Creating blog post $index of 6: {$data['title']}...\n";

    // Ensure category exists
    $term = get_term_by('name', $data['category'], 'category');
    if (!$term) {
        $new_term = wp_insert_term($data['category'], 'category');
        $term_id = is_array($new_term) ? $new_term['term_id'] : $new_term;
    } else {
        $term_id = $term->term_id;
    }

    // Create the blog post
    $post_id = wp_insert_post([
        'post_title'    => $data['title'],
        'post_content'  => $data['content'],
        'post_status'   => 'publish',
        'post_type'     => 'post',
        'post_name'     => sanitize_title($data['title']),
        'post_category' => [$term_id]
    ]);

    if (!$post_id || is_wp_error($post_id)) {
        echo "Error creating blog post: {$data['title']}\n";
        continue;
    }

    // Configure RankMath SEO for blog posts
    update_post_meta($post_id, 'rank_math_title', $data['seo_title']);
    update_post_meta($post_id, 'rank_math_description', $data['seo_desc']);
    update_post_meta($post_id, 'rank_math_focus_keyword', strtolower($data['category']));

    // Sideload a unique featured image from Picsum
    $picsum_id = 200 + $index;
    $image_url = "https://picsum.photos/id/{$picsum_id}/800/533";

    echo "Downloading and attaching featured image for {$data['title']}...\n";
    $attach_id = media_sideload_image($image_url, $post_id, $data['title'], 'id');
    if (!is_wp_error($attach_id) && is_numeric($attach_id)) {
        set_post_thumbnail($post_id, $attach_id);
        echo "Successfully set featured image.\n";
    } else {
        echo "Skipping image download for this article due to network constraints.\n";
    }

    $index++;
}

echo "Automated blog post generation completed. 6 articles created successfully!\n";
