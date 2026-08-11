#!/bin/bash

# Fix Apache MPM conflicts - run this first
echo "Fixing Apache MPM configuration..."

# List what's currently enabled
echo "Currently enabled MPMs:"
ls -la /etc/apache2/mods-enabled/mpm_*.load 2>/dev/null || echo "None listed"

# Disable ALL MPM modules
echo "Disabling all MPM modules..."
a2dismod mpm_event 2>/dev/null || true
a2dismod mpm_worker 2>/dev/null || true
a2dismod mpm_winnt 2>/dev/null || true
a2dismod mpm_prefork 2>/dev/null || true

# Remove all symlinks
echo "Removing MPM symlinks..."
rm -f /etc/apache2/mods-enabled/mpm_event* 2>/dev/null
rm -f /etc/apache2/mods-enabled/mpm_worker* 2>/dev/null
rm -f /etc/apache2/mods-enabled/mpm_winnt* 2>/dev/null
rm -f /etc/apache2/mods-enabled/mpm_prefork* 2>/dev/null

# Enable ONLY prefork
echo "Enabling mpm_prefork..."
ln -sf /etc/apache2/mods-available/mpm_prefork.load /etc/apache2/mods-enabled/mpm_prefork.load
ln -sf /etc/apache2/mods-available/mpm_prefork.conf /etc/apache2/mods-enabled/mpm_prefork.conf

# Verify
echo "Verification:"
ls -la /etc/apache2/mods-enabled/mpm_*.* 2>/dev/null || echo "No MPMs found (will use default)"
echo "Testing Apache config..."
apache2ctl -t

echo "MPM fix complete!"
