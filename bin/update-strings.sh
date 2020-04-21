#!/bin/bash

NEW_NAMESPACE=$1
NEW_SLUG=$2
NEW_NAME=$3

# Replace namespace in PHP files
OLD_PHP_NAMESPACE='namespace JPToolkit\\ExtensionTemplate'
NEW_PHP_NAMESPACE="namespace ${NEW_NAMESPACE/\\/\\\\}"
find ./includes/ -iname "*.php" -type f -exec sed -i "s|${OLD_PHP_NAMESPACE}|${NEW_PHP_NAMESPACE}|g" {} \;
find ./tests/ -iname "*.php" -type f -exec sed -i "s|${OLD_PHP_NAMESPACE}|${NEW_PHP_NAMESPACE}|g" {} \;

# Replace namespace in composer file
OLD_COMPOSER_NAMESPACE='JPToolkit\\\\ExtensionTemplate'
NEW_COMPOSER_NAMESPACE=${NEW_NAMESPACE/\\/\\\\\\\\}
find ./ -iname "composer.json" -type f -exec sed -i "s|${OLD_COMPOSER_NAMESPACE}|${NEW_COMPOSER_NAMESPACE}|g" {} \;

# Replace slugs
OLD_SLUG='jp-toolkit-extension-template';
find ./nbproject -iname "*" -type f -exec sed -i "s|${OLD_SLUG}|${NEW_SLUG}|g" {} \;
find ./tests -iname "*.php" -type f -exec sed -i "s|${OLD_SLUG}|${NEW_SLUG}|g" {} \;
find ./tests -iname "*.pot" -type f -exec sed -i "s|${OLD_SLUG}|${NEW_SLUG}|g" {} \;
find ./tests -iname "*.po" -type f -exec sed -i "s|${OLD_SLUG}|${NEW_SLUG}|g" {} \;
sed -i "s|${OLD_SLUG}|${NEW_SLUG}|g" ./jp-toolkit-extension-template.php
sed -i "s|${OLD_SLUG}|${NEW_SLUG}|g" ./.gitattributes

# Replace Name
OLD_NAME='JP Toolkit Extension Template'
find ./nbproject -iname "*" -type f -exec sed -i "s|${OLD_NAME}|${NEW_NAME}|g" {} \;
find ./tests -iname "*.pot" -type f -exec sed -i "s|${OLD_NAME}|${NEW_NAME}|g" {} \;
find ./tests -iname "*.po" -type f -exec sed -i "s|${OLD_NAME}|${NEW_NAME}|g" {} \;
sed -i "s|${OLD_NAME}|${NEW_NAME}|g" ./jp-toolkit-extension-template.php
sed -i "s|${OLD_NAME}|${NEW_NAME}|g" ./README.md

mv "${OLD_SLUG}.php" "${NEW_SLUG}.php"
