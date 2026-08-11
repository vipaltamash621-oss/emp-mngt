#!/bin/bash

# Railway MySQL Setup Script
# This script adds MySQL service and connects it to your app

echo "🚀 Railway MySQL Setup Script"
echo "================================"

# Check if railway CLI is installed
if ! command -v railway &> /dev/null
then
    echo "❌ Railway CLI not found"
    echo "Install from: https://docs.railway.app/develop/cli"
    exit 1
fi

echo "✅ Railway CLI found"

# Login to Railway
echo ""
echo "📝 Logging into Railway..."
railway login

# Select project
echo ""
echo "🔍 Selecting Railway project..."
railway link

# Add MySQL service
echo ""
echo "📊 Adding MySQL service..."
railway add

# Display available services and ask user to select MySQL
echo ""
echo "Please select 'MySQL' from the list above"

echo ""
echo "✅ MySQL service should be added now"
echo ""
echo "Next steps:"
echo "1. Go to Railway Dashboard"
echo "2. MySQL Service → Connect → Select your App"
echo "3. Redeploy app"
echo "4. Check logs for 'MySQL is ready!' message"
echo ""
echo "Done! 🎉"
