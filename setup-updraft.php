<?php
echo "Configuring UpdraftPlus Backup settings...\n";

// 1. Set Remote Storage Service to Google Drive
update_option('updraft_service', 'googledrive');

// 2. Configure Files Backup Schedule (Weekly, retaining 4 backups - covers 1 month)
update_option('updraft_interval', 'weekly');
update_option('updraft_retain', 4);

// 3. Configure Database Backup Schedule (Daily, retaining 30 backups - covers 1 month)
update_option('updraft_interval_db', 'daily');
update_option('updraft_retain_db', 30);

// 4. Set default Google Drive folder name
$googledrive_settings = [
    'folder' => 'WordPress_Ecommerce_Backups',
    'clientid' => '',
    'secret' => '',
    'tmp' => ''
];
update_option('updraft_googledrive', $googledrive_settings);

echo "UpdraftPlus successfully configured (Google Drive storage selected, Daily Database backups with 30-day retention, and Weekly File backups with 4-week retention enabled)!\n";
