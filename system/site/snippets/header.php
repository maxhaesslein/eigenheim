<?php

// Version: alpha.7

if( ! defined( 'EH_ABSPATH' ) ) exit;

?><!DOCTYPE html>
<!--
___________.__                     .__           .__         
\_   _____/|__| ____   ____   ____ |  |__   ____ |__| _____  
 |    __)_ |  |/ ___\_/ __ \ /    \|  |  \_/ __ \|  |/     \ 
 |        \|  / /_/  >  ___/|   |  \   Y  \  ___/|  |  Y Y  \
/_______  /|__\___  / \___  >___|  /___|  /\___  >__|__|_|  /
        \/   /_____/      \/     \/     \/     \/         \/ 
-->
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1.0">
	<title><?= get_config('site_title') ?></title>
<?php
	$author = get_author_information();
	if( ! empty( $author['display_name'] ) ) :
	?>
	<meta name="author" content="<?= $author['display_name'] ?>">
<?php
	endif;
	?>

<?php
	print_stylesheets();
	?>

	<link rel="authorization_endpoint" href="https://indieauth.com/auth">
	<link rel="token_endpoint" href="https://tokens.indieauth.com/token">
	<link rel="me authn" href="mailto:<?= get_config('auth_mail') ?>">
	<link rel="micropub" href="<?= micropub_get_endpoint( true ).'/' ?>">
<?php
	$microsub_endpoint = get_config('microsub');
	if( $microsub_endpoint ) :
	?>
	<link rel="microsub" href="<?= $microsub_endpoint ?>">
<?php
	endif;
	?>

	<link rel="alternate" type="application/rss+xml" title="<?= get_config('site_title') ?> RSS Feed" href="<?= url('feed/rss') ?>">
	<link rel="alternate" type="application/json" title="<?= get_config('site_title') ?> JSON Feed" href="<?= url('feed/json') ?>">
	
</head>
<body>

	<header>
		<h1><a href="<?= url() ?>"><?= get_config('site_title') ?></a></h1>
		<?php
		$navigation = get_navigation();
		if( $navigation ) :
		?><nav>
			<ul>
			<?php
			foreach( $navigation as $page ) :
				$classes = array();
				if( $page['is_current_page'] ) $classes[] = 'current-page';
				?>	<li<?= get_class_attribute($classes) ?>><a href="<?= $page['permalink'] ?>"><?= $page['title'] ?></a></li>
			<?php
			endforeach;
			?></ul>
		</nav><?php
		endif;
		?>
	
	</header>

	<main>
