<?php
echo "Configuring FluentSMTP options...\n";

$settings = [
    'connections' => [
        'mailpit' => [
            'id' => 'mailpit',
            'title' => 'Mailpit Local SMTP',
            'provider' => 'smtp',
            'provider_settings' => [
                'host' => 'mailpit',
                'port' => '1025',
                'encryption' => 'none',
                'auth' => 'no',
                'sender_email' => 'admin@example.com',
                'sender_name' => 'WordPress Ecommerce'
            ],
            'status' => 'active'
        ]
    ],
    'mappings' => [
        'admin@example.com' => 'mailpit'
    ],
    'misc' => [
        'default_connection' => 'mailpit',
        'log_emails' => 'yes'
    ]
];

// Save options in database
update_option('fluent_mail_settings', $settings);

echo "FluentSMTP pre-configured to route emails to Mailpit (mailpit:1025) successfully!\n";
