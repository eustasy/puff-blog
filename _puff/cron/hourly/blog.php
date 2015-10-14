<?php



if ( is_writable($Sitewide['Root'].'blog.json') ) {

	foreach ( glob_recursive($Sitewide['Root'].'*.php', 0, true) as $File ) {
		$Page['Type'] = false;
		$Page['Published'] = false;
		$Page['Title'] = false;
		$Page['Tagline'] = false;
		$Page['Author'] = false;
		$Lines = file($File);
		foreach ($Lines as $Line) {
			if (strpos($Line, '$Page[\'Type\']') !== false) {
				$Line = explode('\'', $Line);
				$Page['Type'] = $Line[count($Line)-2];
			} else if (strpos($Line, '$Page[\'Title\']') !== false) {
				$Line = explode('\'', $Line);
				$Page['Title'] = $Line[count($Line)-2];
			} else if (strpos($Line, '$Page[\'Tagline\']') !== false) {
				$Line = explode('\'', $Line);
				$Page['Tagline'] = $Line[count($Line)-2];
			} else if (strpos($Line, '$Page[\'Author\']') !== false) {
				$Line = explode('\'', $Line);
				$Page['Author'] = $Line[count($Line)-2];
			} else if (strpos($Line, '$Page[\'Published\']') !== false) {
				$Line = explode('\'', $Line);
				$Page['Published'] = $Line[count($Line)-2];
			}
		}
		if ( in_array($Page['Type'], array('Article', 'Blog', 'Blog Post', 'BlogPost', 'Post')) ) {
			$URL = str_replace($Sitewide['Root'], '', $File);
			$URL = str_replace('index.php', '', $URL);
			require_once $Sitewide['Puff']['Functions'].'ends_with.php';
			if (
				$Sitewide['Settings']['Strip PHP from URLs'] &&
				ends_with($URL, '.php')
			) {
				$URL = substr($URL, 0, -4);
			}
			if ( $Page['Published'] ) {
				$Page['Published'] = date('Y-m-d\TH:i:sP', strtotime($Page['Published']));
			} else {
				$Page['Published'] = date('Y-m-d\TH:i:sP', filemtime($File));
			}
			if ( !$Page['Title'] ) {
				$Page['Title'] = $Sitewide['Page']['Title'];
			}
			if ( !$Page['Tagline'] ) {
				$Page['Tagline'] = $Sitewide['Page']['Tagline'];
			}
			if ( !$Page['Author'] ) {
				$Page['Author'] = $Sitewide['Page']['Author'];
			}
			$Item['Title']    = $Page['Title'];
			$Item['Link']     = $Sitewide['Settings']['Site Root'].$URL;
			$Item['Tagline']  = $Page['Tagline'];
			$Item['Author']   = $Page['Author'];
			$Item['Published']   = $Page['Published'];
			$Blog[$Page['Published'].' '.urlencode($Sitewide['Settings']['Site Root'].$URL)] = $Item;
		}
	}

	krsort($Blog);
	// var_dump($Blog);
	$Blog = json_encode($Blog, JSON_PRETTY_PRINT);

	$Result = file_put_contents($Sitewide['Root'].'blog.json', $Blog);
	if ( $Result ) {
		echo 'Success: Generation and Write of Blog successful.'."\n";
	} else {
		echo 'Error: Blog could not be written, but we thought it was writable.'."\n";
	}

} else {
	echo 'Error: '.$Sitewide['Root'].'blog.json not writeable.'."\n";
}
