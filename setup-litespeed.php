<?php
echo "Configuring LiteSpeed Cache options...\n";

// Retrieve existing LiteSpeed Cache options or set defaults
$options = get_option('litespeed-cache-conf', []);

if (!is_array($options)) {
    $options = [];
}

// 1. Cache Settings
$options['cache'] = 1; // Enable Cache
$options['cache-priv'] = 1; // Cache logged in users
$options['cache-comment'] = 1; // Cache commentators
$options['cache-rest'] = 1; // Cache REST API
$options['cache-page_login'] = 1; // Cache login page

// 2. Lazy Load Settings
$options['img_lazy'] = 1; // Enable Lazy Load Images
$options['img_lazy_placeholder'] = 1; // Use lazy load placeholder graphics
$options['media-iframe_lazy'] = 1; // Lazy Load iframes/videos

// 3. WebP Settings
$options['img_webp'] = 1; // WebP generation
$options['img_webp_repl'] = 1; // Replace image URLs with WebP equivalents

// 4. Minification Settings
$options['css_minify'] = 1; // Minify CSS
$options['js_minify'] = 1; // Minify JS
$options['html_minify'] = 1; // Minify HTML

// 5. Critical CSS & Combining
$options['css_combine'] = 1; // Combine CSS files
$options['css_comb_inline'] = 1; // Include inline CSS in combine
$options['ccss'] = 1; // Enable Critical CSS (above-the-fold styling)
$options['css_inline_import'] = 1; // Inline CSS import directives

// 6. CDN Ready Settings
$options['cdn'] = 1; // Enable CDN options

// Save updated configurations back into WordPress
update_option('litespeed-cache-conf', $options);

echo "LiteSpeed Cache successfully configured (Lazy Load, WebP, Page Cache, CDN Ready, CSS/JS Minifications, and Critical CSS enabled)!\n";
