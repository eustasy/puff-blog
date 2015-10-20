<?php
	require_once __DIR__.'/_puff/sitewide.php';
	$Sitewide['Blog']['Items per Page'] = 1;

	$Page['Title'] = 'Blog';
	$Page['Description']  = 'All the latest news.';
	$Page['Tagline']  = 'All the latest news.';
	$Page['Type']  = 'Blog Index';

	$Blog = file_get_contents($Sitewide['Root'].'blog.json');
	$Blog = json_decode($Blog, true);
	$Blog_First = reset($Blog);

	// TODO This makes a super-global safe, but it would be best changed.
	if ( !empty($_GET['BlogIndexPage']) ) {
		$_GET['BlogIndexPage'] = $_GET['BlogIndexPage'] * 1;
		if ( is_int($_GET['BlogIndexPage']) ) {
			$Offset = $_GET['BlogIndexPage'] * $Sitewide['Blog']['Items per Page'];
			var_dump($Offset);
			$Blog = array_slice($Blog, $Offset, $Sitewide['Blog']['Items per Page']);
		} else {
			$Blog = array_slice($Blog, 0, $Sitewide['Blog']['Items per Page']);
		}
	}

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
		// var_dump($Post);
		echo '<h2 class="blog-post-title-link"><a href="'.$Post['Link'].'">'.$Post['Title'].'</a></h2>';
		if ( !empty($Post['Tagline']) ) {
			echo '<h3 class="blog-post-tagline">'.$Post['Tagline'].'</h3>';
		}
		echo '<p class="blog-post-attribution">Posted by '.$Post['Author'].' on '.date('l \t\h\e jS \o\f F, Y', strtotime($Post['Published'])).'.</p>';
	}

	// TODO Pagination Buttons

	require_once $Sitewide['Templates']['Footer'];
