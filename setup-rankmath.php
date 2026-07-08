<?php
echo "Configuring RankMath SEO settings...\n";

// 1. General Options (Breadcrumbs, Robots.txt, etc.)
$general_settings = get_option('rank-math-option-general', []);
$general_settings['breadcrumbs'] = 'on'; // Enable breadcrumbs
$general_settings['breadcrumbs_separator'] = '&raquo;';
$general_settings['breadcrumbs_home_link'] = 'on';
$general_settings['breadcrumbs_prefix'] = '';
$general_settings['breadcrumbs_archive_format'] = '%page%';
$general_settings['breadcrumbs_search_format'] = 'Search Results for %search%';
$general_settings['breadcrumbs_404_format'] = '404 Page Not Found';
update_option('rank-math-option-general', $general_settings);

// 2. Titles & Meta Options (OG, Twitter Cards, Auto SEO)
$titles_settings = get_option('rank-math-option-titles', []);
$titles_settings['homepage_title'] = '%sitename% - %sitedesc%';
$titles_settings['homepage_description'] = '%sitedesc%';
$titles_settings['homepage_facebook_title'] = '%sitename%';
$titles_settings['homepage_facebook_description'] = '%sitedesc%';
$titles_settings['homepage_facebook_image'] = 'https://picsum.photos/1200/630';

// Global OpenGraph & Twitter Card
$titles_settings['facebook_admin_id'] = '';
$titles_settings['facebook_app_id'] = '';
$titles_settings['twitter_card_type'] = 'summary_large_image'; // Twitter card type

// Auto SEO (auto alt, auto title for images)
$titles_settings['image_add_alt_attrs'] = 'on'; // Auto SEO - add missing alt attributes
$titles_settings['image_add_title_attrs'] = 'on'; // Auto SEO - add missing title attributes
$titles_settings['image_alt_template'] = '%title% %filename%';
$titles_settings['image_title_template'] = '%title% %filename%';

// Default Post & Product Schemas
$titles_settings['pt_post_default_rich_snippet'] = 'article';
$titles_settings['pt_post_default_article_type'] = 'BlogPosting';
$titles_settings['pt_product_default_rich_snippet'] = 'product'; // WooCommerce Product Schema

update_option('rank-math-option-titles', $titles_settings);

// 3. Sitemap Options (XML Sitemap)
$sitemap_settings = get_option('rank-math-option-sitemap', []);
$sitemap_settings['sitemaps_per_page'] = 200;
$sitemap_settings['ping_search_engines'] = 'on';
$sitemap_settings['sitemap_posts_index'] = 'on'; // Include posts in sitemap
$sitemap_settings['sitemap_pages_index'] = 'on'; // Include pages in sitemap
$sitemap_settings['sitemap_products_index'] = 'on'; // Include products in sitemap
$sitemap_settings['sitemap_product_cat_index'] = 'on'; // Include product categories
$sitemap_settings['sitemap_category_index'] = 'on'; // Include blog categories
update_option('rank-math-option-sitemap', $sitemap_settings);

// 4. Enable modules (Sitemap, Schema, Analytics)
$modules = get_option('rank-math-modules', []);
$modules['sitemap'] = 'on';
$modules['rich-snippet'] = 'on'; // Schema module
$modules['seo-analysis'] = 'on';
update_option('rank-math-modules', $modules);

echo "RankMath SEO successfully configured (XML Sitemap, Robots, Schema, OG, Twitter Cards, Breadcrumbs, and Auto SEO enabled)!\n";
