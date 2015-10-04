<?php
	require_once __DIR__.'/_puff/sitewide.php';

error_reporting(E_ALL);
error_reporting(-1);
ini_set('error_reporting', E_ALL);

	$Page['Title'] = 'Blog';
	$Page['Description']  = 'All the latest news.';
	$Page['Tagline']  = 'All the latest news.';
	$Page['Type']  = 'Blog Index';

	$Blog = file_get_contents($Sitewide['Root'].'blog.json');
	$Blog = json_decode($Blog, true);
	$Blog_First = reset($Blog);

	// Dynamic
	$Page['Published'] = $Blog_First['Published'];
	require_once $Sitewide['Templates']['Header'];

	echo <<<H1
<h1>Blog</h1>
H1;

	foreach ( $Blog as $Key => $Post ) {
		// $Post['Title']
		// $Post['Link']
		// $Post['Tagline']
		// $Post['Author']
		// $Post['Published']
		echo '<h2 class="blog-post-title-link"><a href="'.$Post['Link'].'">'.$Post['Title'].'</a></h2>';
		if ( !empty($Post['Tagline']) ) {
			echo '<h3 class="blog-post-tagline">'.$Post['Tagline'].'</h3>';
		}
		echo '<p class="blog-post-attribution">Posted by '.$Post['Author'].' on '.date('l \t\h\e jS \o\f F, Y', strtotime($Post['Published'])).'.</p>';
		// var_dump($Post);
	}

	require_once $Sitewide['Templates']['Footer'];
