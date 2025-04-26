# Read yaml config file
eval "$(./lib/parse_yaml.sh wordpress_config.yml config_)"

# Set variables for output styles
bold=$(tput bold)
call="$(tput setaf 2)" # green
info="$(tput setaf 4)" # blue
warn="$(tput setaf 1)" # red
normal="$(tput sgr0)"

# Check for bad config data
if [ "$config_wordpress_database_table_prefix" == "empty" ]
then
    echo "${warn}[ERROR]${normal} You must define a Database Prefix for the new Wordpress install!"
    exit 1
fi

# Install to the projects web directory

echo "\n===================================="
echo "${info}[INFO]${normal} Config details from YAML file are as follows:"
echo "${bold}Host:${normal} $config_wordpress_database_host"
echo "${bold}Wordpress Table Prefix:${normal} $config_wordpress_database_table_prefix\n"

echo "${bold}Site Name:${normal} $config_wordpress_site_name"
echo "${bold}Site URL:${normal} $config_wordpress_site_url"
echo "${bold}Admin User Email:${normal} $config_wordpress_admin_email"
echo "${bold}Admin Username:${normal} $config_wordpress_admin_username"
echo "${bold}Admin User Password:${normal} $config_wordpress_admin_password\n"

echo "${info}[INFO]${normal} Are these values correct [y/n]?"
read SiteDetailsCorrect

if [ "$SiteDetailsCorrect" != "y" ] && [ "$SiteDetailsCorrect" != "Y" ]
then
	echo "${warn}[ERROR]${normal} Please modify the config accordingly and then re-run the script"
	exit 1
fi

ddev config --project-type="wordpress" --project-name="$config_wordpress_database_host" --webserver-type="apache-fpm" --nodejs-version="auto" --docroot="web"
ddev start
ddev wp core download --locale=en_GB --path=web/$config_wordpress_wp_core_directory

ddev wp core install --title="$config_wordpress_site_name" --url="$config_wordpress_site_url/$config_wordpress_wp_core_directory" --admin_user="$config_wordpress_admin_username" --admin_password="$config_wordpress_admin_password" --admin_email="$config_wordpress_admin_email" --path=web/$config_wordpress_wp_core_directory

# Copy index.php file to root
cp "web/$config_wordpress_wp_core_directory/index.php" web/index.php

# Edit index.php to point to correct path of wp-blog-header.php
perl -p -i -e "s/\/wp-blog-header/\/$config_wordpress_wp_core_directory\/wp-blog-header/g" web/index.php

# ddev wp option update siteurl https://$config_wordpress_site_url/$config_wordpress_wp_core_directory --path=web/$config_wordpress_wp_core_directory

# Attempt to setup WP Multi environment config
echo "${info}[INFO]${normal} Attempting to setup the multi environment config ... note that this may fail (due to the way this works it's a little unreliable)"

# Make a directory for the config files
mkdir config

echo "development" >> config/.env

# Make a directory for the multi environment config repo
mkdir env-config
cd env-config

# Move the config files to the relevant locations
git clone git@github.com:studio24/wordpress-multi-env-config.git .

mv wp-config.default.php ../config/wp-config.default.php
mv wp-config.development.php ../config/wp-config.development.php
mv wp-config.env.php ../config/wp-config.env.php
mv wp-config.load.php ../config/wp-config.load.php
mv wp-config.local.php ../config/wp-config.local.php
mv wp-config.production.php ../config/wp-config.production.php
mv wp-config.staging.php ../config/wp-config.staging.php

# Update path to wp-config.load.php path in wp-config.php
sed -ie 's/wp-config\.load\.php/\.\.\/\.\.\/config\/wp-config\.load\.php/' wp-config.php
mv wp-config.php ../web/wp-config.php

# Finish and remove env-config folder
cd ..
rm -rf env-config

# #edit wp-config.default.php with correct table prefix and salts
# orisaltline1="$(grep -Fn -m 1 "'AUTH_KEY'" web/wp-config.php | cut -f1 -d:)"
# newsaltline1="$(grep -Fn -m 1 "'AUTH_KEY'" config/wp-config.default.php | cut -f1 -d:)"
# newsaltline8=$((newsaltline1+8))

# #get filled in salt lines
# filledsaltlines="$(tail -n +$((orisaltline1)) web/wp-config.php | head -n +9)"

# #remove empty salt lines
# sed -i -e "${newsaltline1}, ${newsaltline8}d" config/wp-config.default.php
# #add filled in salt lines
# ex -sc "${newsaltline1}i|${filledsaltlines}" -cx config/wp-config.default.php

# Adding WordPress core directory to wp-config.env.php
replace "'path'   => ''" "'path'   => '/$config_wordpress_wp_core_directory'" -- config/wp-config.env.php
replace "wp_" "$config_wordpress_database_table_prefix" -- config/wp-config.default.php

#adding the local domain name to wp-config.env.php
replace "domain.local" "$config_wordpress_site_url" -- config/wp-config.env.php


#adding the dev Db params
replace "define('DB_HOST', '')" "define('DB_HOST', 'ddev-$config_wordpress_database_host-db')" -- config/wp-config.development.php
replace "define('DB_NAME', '')" "define('DB_NAME', 'db')" -- config/wp-config.development.php
replace "define('DB_USER', '')" "define('DB_USER', 'db')" -- config/wp-config.development.php
replace "define('DB_PASSWORD', '')" "define('DB_PASSWORD', 'db')" -- config/wp-config.local.php


# Move wp-content into content dir
mkdir web/content
cd web

mv -v $config_wordpress_wp_core_directory/wp-content/* content
rm -rf $config_wordpress_wp_core_directory/wp-content

cd ..
# Changes the WP Content Dir so that themes and uploads are pointing to the right place and don't error on the dashboard

echo "define ('WP_CONTENT_FOLDERNAME', 'content');" >> content-settings.txt
echo "define( 'WP_CONTENT_DIR', rtrim(__DIR__ .'/').'/'. WP_CONTENT_FOLDERNAME );" >> content-settings.txt

mv web/wp-config.php web/wp-config-old.php
sed -e "13r content-settings.txt" web/wp-config-old.php >> web/wp-config.php

rm -rf content-settings.txt
rm -rf web/wp-config-old.php

echo "define('WP_CONTENT_URL',  rtrim(WP_HOME,'/') .'/'. WP_CONTENT_FOLDERNAME);" >> config/wp-config.default.php

#cleanup backup wp-config.default.php file
rm -f config/wp-config.default.php-e

# TODO: Add new version of wp-starter-theme without fewbricks

# Cleanup (remove) old install script files
echo "${info}[INFO]${normal} Would you like to remove the shell and yml files?"
read PurgeConfigFiles

if [ "$PurgeConfigFiles" != "n" ] && [ "$PurgeConfigFiles" != "N" ]
then
  echo "${info}[INFO]${normal} Cleaning up install files"
  rm wordpress.sh
  rm wordpress_config.yml
  rm README.md
  rm -rf lib
fi

echo ""
echo ""
echo "==== * ====================================================================================================================="
echo "     *   ${call}[INFO]${normal} Installation has finished, please note you will need to complete the following manual steps:"
echo "     *"
echo "     *    - If the site is up and running at this stage, delete the original wp-config file (now named $config_wordpress_wp_core_directory/wp-config-old.php). If the multi-environment setup failed, you will find the data required to set it up in that original config file."
echo "     *    - Download and install the ACF Pro plugin here: https://www.advancedcustomfields.com/my-account/ - login details in 1password."
echo "     *    - Activate ACF Pro"
echo "==== * ====================================================================================================================="
echo ""
echo "${info}[INFO]${normal} If you've had any problems, please add them to the issue queue for this installer in GitHub: https://github.com/studio24/wordpress-installation-script/issues/new"

