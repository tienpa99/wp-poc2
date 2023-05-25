<?php
global $wis_compatibility;

define( 'WIG_COMPONENT_VERSION', $wis_compatibility->get_plugin_version() );
define( 'WIG_COMPONENT_URL', WIS_COMPONENTS_URL . '/instagram' );
define( 'WIG_COMPONENT_DIR', WIS_COMPONENTS_DIR . '/instagram' );
define( 'WIG_COMPONENT_VIEWS_DIR', WIG_COMPONENT_DIR . '/html_templates' );

define( 'WIS_INSTAGRAM_CLIENT_ID', '2758407337627658' );
define( 'WIS_FACEBOOK_CLIENT_ID', '8542636415777224' );
//define( 'WIS_INSTAGRAM_CLIENT_ID', '1883092561894221' );  // test APP ID
//define( 'WIS_FACEBOOK_CLIENT_ID', '852118372457929' ); // test APP ID

define( 'WIG_PROFILES_OPTION', 'account_profiles' );
define( 'WIG_BUSINESS_PROFILES_OPTION', 'account_profiles_new' );

define( 'WIG_USERS_SELF_URL', 'https://graph.instagram.com/me' );
define( 'WIG_USERS_SELF_MEDIA_URL', 'https://graph.instagram.com/' );

require_once WIG_COMPONENT_DIR . '/includes/functions.php';
require_once WIG_COMPONENT_DIR . '/includes/class-instagram-feed.php';
require_once WIG_COMPONENT_DIR . '/includes/class-instagram-profiles.php';
require_once WIG_COMPONENT_DIR . '/includes/class-instagram-widget.php';
