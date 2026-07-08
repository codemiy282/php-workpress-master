<?php
echo "Configuring Wordfence Security options...\n";

if (class_exists('wfConfig')) {
    // 1. Enable Firewall Protection
    wfConfig::set('firewallEnabled', '1');
    wfConfig::set('wafStatus', 'enabled'); // Put firewall in active block mode

    // 2. Limit Login & Brute Force Lockouts
    wfConfig::set('loginSec_lockoutOnFailedLogin', '1'); // Lockout on failed logins
    wfConfig::set('loginSec_maxFailedLoginAttempts', '5'); // Lockout after 5 failed login attempts
    wfConfig::set('loginSec_maxFailedLoginAttemptsLax', '10'); // Lockout after 10 failed username attempts
    wfConfig::set('loginSec_maxForgotPassAttempts', '5'); // Lockout after 5 password reset attempts
    wfConfig::set('loginSec_lockoutDuration', '1800'); // 30 minutes lockout duration
    wfConfig::set('loginSec_breachSecEnabled', '1'); // Enable leaked password checks

    // 3. Disable XML-RPC Authentication (highly recommended for performance and security)
    wfConfig::set('loginSec_disableXMLRPC', '1');
    wfConfig::set('loginSec_disableXMLRPC_auth', '1'); // Block XML-RPC authentication requests

    // 4. Two-Factor Authentication (2FA) Security
    wfConfig::set('loginSec_twoFactorEnabled', '1'); // Enable 2FA
    wfConfig::set('loginSec_twoFactorRequiredRoles', serialize(['administrator', 'editor'])); // Require 2FA for administrators and editors
    wfConfig::set('loginSec_twoFactorGracePeriod', '3'); // 3 days grace period to register 2FA

    // 5. Daily Security Scan
    wfConfig::set('scheduledScansEnabled', '1'); // Enable scheduled scanning
    wfConfig::set('schedMode', 'auto'); // Auto scheduling (will run daily based on Wordfence cloud scheduling)
    wfConfig::set('scan_interval', '86400'); // Daily interval

    echo "Wordfence Security successfully configured (Firewall, Brute Force limiters, 2FA requirements, XML-RPC block, and Daily scans enabled)!\n";
} else {
    echo "Warning: Wordfence plugin class 'wfConfig' not found. Ensure Wordfence is installed and activated first.\n";
}
