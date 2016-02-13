<?php
	require_once __DIR__.'/_puff/sitewide.php';

	$Page['Title'] = 'Blog';
	$Page['Description']  = 'All the latest news.';
	$Page['Tagline']  = 'All the latest news.';
	$Page['Type']  = 'Blog Index';

	$Blog = file_get_contents($Sitewide['Root'].'blog.json');
	$Blog = json_decode($Blog, true);
	$Blog_First = reset($Blog);
	$Blog_Total = count($Blog);

	$Blog_Page = 1;
	if ( !empty($_GET['page']) ) {
		$_GET['page'] = $_GET['page'] * 1;
		if ( is_int($_GET['page']) ) {
			$Blog_Page = $_GET['page'];
		}
	}
	$Blog_Start = ( $Blog_Page * $Sitewide['Blog']['Items per Page'] ) - $Sitewide['Blog']['Items per Page'];
	$Blog_End = $Blog_Start + $Sitewide['Blog']['Items per Page'];

	$Blog = array_slice($Blog, $Blog_Start, $Blog_End);
	if ( $Blog_Start > 0 ) {
		$Page['Header'] .= '<link rel="previous" href="?page='.( $Blog_Page -  1 ).'">';
	}
	if ( $Blog_Total > $Blog_End ) {
		$Page['Header'] .= '<link rel="next" href="?page='.( $Blog_Page + 1 ).'">';
	}

	// Dynamic
	$Page['Published'] = $Blog_First['Published'];
	require_once $Sitewide['Templates']['Header'];

	echo <<<H1
<h1>Blog</h1>
H1;

	if ( !empty($Blog) ) {
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
	} else {
		echo '<h2>Sorry, there aren\'t any posts that far back.</h2>';
	}

	if ( $Blog_Start > 0 ) {
		echo '<a class="previous" href="?page='.( $Blog_Page - 1 ).'">Previous</a>';
	}
	if ( $Blog_Total > $Blog_End ) {
		echo '<a class="next" href="?page='.( $Blog_Page + 1 ).'">Next</a>';
	}

	require_once $Sitewide['Templates']['Footer'];
