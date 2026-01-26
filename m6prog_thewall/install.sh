#!/bin/bash

# The Wall - Complete Installation Guide
# Dit is een gids voor handmatige installatie en troubleshooting

# Maak dit script executable met: chmod +x install.sh

set -e

echo "╔════════════════════════════════════════════════════════╗"
echo "║     🖼️  THE WALL - Complete Installation Guide        ║"
echo "╚════════════════════════════════════════════════════════╝"
echo ""

# Ask for confirmation
read -p "Continue with installation? (y/n) " -n 1 -r
echo
if [[ ! $REPLY =~ ^[Yy]$ ]]; then
    echo "Installation cancelled."
    exit 1
fi

echo ""
echo "Step 1: Making scripts executable..."
chmod +x setup.sh reseed.sh test.sh 2>/dev/null || true
echo "✅ Done"

echo ""
echo "Step 2: Running setup..."
./setup.sh

echo ""
echo "Step 3: Seeding test data..."
./reseed.sh

echo ""
echo "Step 4: Running tests..."
./test.sh

echo ""
echo "╔════════════════════════════════════════════════════════╗"
echo "║          ✅ Installation Complete!                    ║"
echo "╚════════════════════════════════════════════════════════╝"
echo ""
