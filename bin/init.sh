#!/bin/bash

# Formatting setup
BOLD=$(tput bold)
RED=$(tput setaf 1)
GREEN=$(tput setaf 2)
RESET=$(tput sgr0)

# Turn first parameter into a slug.
function slugify() {
  echo $1 | iconv -t ascii//TRANSLIT | sed -E 's/[~\^]+//g' | sed -E 's/[^a-zA-Z0-9]+/-/g' | sed -E 's/^-+\|-+$//g' | sed -E 's/^-+//g' | sed -E 's/-+$//g' | tr A-Z a-z
}

# Turn the first parameter into camel case.
function toCamelCase() {
  echo $1 | perl -nE 'say ucfirst join "", map {ucfirst lc} split /[^[:alnum:]]+/'
}

function toUpperCase() {
	echo "${$1^^}"
}

# Start off on a blank page.
clear

# Some welcome instructions.
echo "${BOLD}WELCOME TO THE WP PLUGIN SCAFFOLD SETUP SCRIPT! $RESET"
echo "This script will take you through converting the plugin base to a bespoke plugin project."
echo ""
echo "You'll be asked for some configuration details. Some are required and some will allow you to customize the setup slightly."
echo ""
echo "Required will be marked by $RED(required)$RESET and optional will have their default setting set in [brackets]."
echo ""

read -p "Press enter to get started." GET_STARTED

echo "${BOLD}What do you want the plugin to be called? $RED(required)$RESET"
echo "Note: This is a human readable name, not the slug."
read -p "Plugin Name: " PLUGIN_NAME
PLUGIN_SLUG=$(slugify "$PLUGIN_NAME")
PLUGIN_SLUG_UNDERSCORES=$(echo "$PLUGIN_SLUG" | sed 's|-|_|g')

echo "${BOLD}What's the name of the client? $RED(required)$RESET"
read -p "Client Name: " CLIENT_NAME

echo "${BOLD}Give the plugin a description that will be public? $RED(required)$RESET"
read -p "Plugin Description []: " PLUGIN_DESCRIPTION

echo "${BOLD}Give the plugin a URL where it will be used. Often the client's website URL. $RED(required)$RESET"
read -p "Plugin URL: " PLUGIN_URL

echo "${BOLD}What's the first version?$RESET"
read -p "Version [1.0.0]: " VERSION
VERSION=${VERSION:-"1.0.0"}

# Generating the namespace.
NAMESPACE='BernskioldMedia\\Clients\\'"$(toCamelCase "$CLIENT_NAME")"'\\'"$(toCamelCase "$PLUGIN_NAME")"
COMPOSER_NAMESPACE='BernskioldMedia\\\\Clients\\\\'"$(toCamelCase "$CLIENT_NAME")"'\\\\'"$(toCamelCase "$PLUGIN_NAME")"

echo ""
read -p "${BOLD}Press enter to confirm and start the process.$RESET" START_PROCESS

# Update package.json with the right info.
sed -i '' "s|wp-plugin-scaffold|$PLUGIN_SLUG|g" package.json
sed -i '' "s|A WordPress plugin scaffold that we use at Bernskiold Media when developing client specific plugins.|$PLUGIN_DESCRIPTION|g" package.json
sed -i '' "s|1.0.0|$VERSION|g" package.json

echo "${GREEN}Updated package.json...$RESET"

# Update composer.json with right info.
sed -i '' "s|bernskioldmedia/wp-plugin-scaffold|bm-clients/$CLIENT_NAME-plugin-$PLUGIN_SLUG|g" composer.json
sed -i '' "s|A WordPress plugin scaffold that we use at Bernskiold Media when developing client specific plugins.|$PLUGIN_DESCRIPTION|g" composer.json
sed -i '' 's|BernskioldMedia\\\\WP\\\\PluginScaffold|'"$COMPOSER_NAMESPACE"'|g' composer.json

echo "${GREEN}Updated composer.json...$RESET"

# Update the main plugin file.
sed -i '' "s|WP Plugin Scaffold|$PLUGIN_NAME|g" wp-plugin-scaffold.php
sed -i '' "s|A WordPress plugin scaffold that we use at Bernskiold Media when developing client specific plugins.|$PLUGIN_NAME|g" wp-plugin-scaffold.php
sed -i '' "s|https://website.com|$PLUGIN_URL|g" wp-plugin-scaffold.php
sed -i '' "s|1.0.0|$VERSION|g" wp-plugin-scaffold.php
sed -i '' "s|wp-plugin-scaffold|$PLUGIN_SLUG|g" wp-plugin-scaffold.php

echo "${GREEN}Set the plugin data...$RESET"

# Replace Textdomain in all .php files
find . -type f -name "*.php" -print0 | xargs -0 sed -i '' "s|wp-plugin-scaffold|$PLUGIN_SLUG|g"
echo "${GREEN}Set the textdomain in all files...$RESET"

# Replace Uppercase Prefix in all .php files
find . -type f -name "*.php" -print0 | xargs -0 sed -i '' "s|WP_PLUGIN_SCAFFOLD|$(toUpperCase PLUGIN_SLUG_UNDERSCORES)|g"
echo "${GREEN}Set the uppercase plugin prefix in all files...$RESET"

# Replace Namespace in all files
find . -type f -name "*.php" -print0 | xargs -0 sed -i '' 's|BernskioldMedia\\WP\\PluginScaffold|'"$NAMESPACE"'|g'
echo "${GREEN}Set the namespace in all files...$RESET"

# Replace 'wp_plugin_scaffold' with slug in all files.
find . -type f -name "*.php" -print0 | xargs -0 sed -i '' "s|wp_plugin_scaffold|$PLUGIN_SLUG_UNDERSCORES|g"
find . -type f -name "*.php" -print0 | xargs -0 sed -i '' "s|VERSION|$VERSION|g"
echo "${GREEN}Set all plugin details...$RESET"

# Update packages
npm update
echo "${GREEN}Updated NPM packages...$RESET"
composer update
echo "${GREEN}Updated Composer packages...$RESET"

npm run setup
echo "${GREEN}Installed packages & built assets...$RESET"

npm run i18n
echo "${GREEN}Generated translation template...$RESET"

# Rename main plugin file.
mv wp-plugin-scaffold.php "$PLUGIN_SLUG.php"
echo "${GREEN}Renamed main plugin file...$RESET"

# Rename theme directory
mv "$PWD" "${PWD%/*}/$PLUGIN_SLUG"
echo "${GREEN}Renamed plugin directory...$RESET"

echo ""
echo "$GREEN${BOLD}SUCCESS! Everything has been set up properly!$RESET"
echo "Don't forget to clean up the README file."
echo "You'll also need to set up the variables and imports in the stylesheets directory based on the parent theme."
