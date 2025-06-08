<?php
namespace Deployer;

/**
 * 1. Deployer recipes we are using for this website
 */
require_once 'vendor/studio24/deployer-recipes/recipe/default.php';
require_once 'vendor/studio24/deployer-recipes/recipe/wordpress.php';

/**
 * 2. Deployment configuration variables
 */

// Project name
set('application', 'Wicklow Pride');

// Git repo
set('repository', 'git@github.com:Themolian/wordpress-wicklow-pride.git');

// Filesystem volume we're deploying to
set('disk_space_filesystem', '/');

// Shared files that need to persist between deployments
set('shared_files', [
    '.env'
]);

// Shared directories that need to persist between deployments
set('shared_dirs', [
    'web/assets',
    '.well-known',
]);

// Writable directories
set('writable_dirs', [
]);

// set('release_path', '~/Documents/deployer-test');
// set('current_path', '~/Documents/deployer-test/release/current');

set('ssh_multiplexing', false);

// set('git_ssh_command', 'ssh');

set('remote_user', 'u64425');
// set('http_user', 'wicklowpride.com');


/**
 * 3. Hosts
 */

host('production')
    ->set('hostname', 'ssh.gb.stackcp.com')
    ->set('http_user', 'wicklowpride.com')
    ->set('deploy_path', '~/public_html')
    ->set('url', 'https://www.wicklowpride.com');

host('staging')
    ->set('hostname', 'wicklowpride.com')
    ->set('http_user', 'u64425')
    ->set('deploy_path', '~/domains/staging.wicklowpride.com/deployments')
    ->set('url', 'https://staging.wicklowpride.com');

host('deptest')
->set('hostname', 'wicklowpride.com')
->set('http_user', 'u64425')
->set('deploy_path', '~/domains/deptest.wicklowpride.com/deployments')
->set('url', 'https://deptest.wicklowpride.com');


/**
 * 4. Deployment tasks
 *
 * Any custom deployment tasks to run
 */

 after('deploy:success', function() {
    run('cp ~/domains/staging.wicklowpride.com/deployments/current/* ~/domains/staging.wicklowpride.com/public_html -r');
 });
