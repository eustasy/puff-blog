<?php

if ( is_writable($Sitewide['Root'].'feed.xml') ) {
	$Feed = '<rss version="2.0" xmlns:dc="http://purl.org/dc/elements/1.1/">
	<channel>
		<title>'.$Sitewide['Settings']['Site Title'].'</title>
		<link>'.$Sitewide['Settings']['Site Root'].'</link>
		<description>'.$Sitewide['Settings']['Alternative Site Title'].'</description>
		<language>en-us</language>';

	foreach (glob_recursive($Sitewide['Root'].'*.php', 0, true) as $File) {
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
			if ( $Page['Published'] ) {
				$Published = $Page['Published'];
			} else {
				$Published = date('Y-m-d', filemtime($File));
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
			$Feed .= '
		<item>
			<title>'.$Page['Title'].'</title>
			<link>'.$Sitewide['Settings']['Site Root'].$URL.'</link>
			<description>'.$Page['Tagline'].'</description>
			<dc:creator>'.$Page['Author'].'</dc:creator>
			<dc:date>'.$Published.'</dc:date>
		</item>';
		}
	}

	$Feed .= '
	</channel>
</rss>';

	// var_dump($Feed);

	$Result = file_put_contents($Sitewide['Root'].'feed.xml', $Feed);
	if ( $Result ) {
		echo 'Success: Generation and Write of Feed successful.'."\n";
	} else {
		echo 'Error: Feed could not be written, but we thought it was writable.'."\n";
	}

} else {
	echo 'Error: '.$Sitewide['Root'].'feed.xml not writeable.'."\n";
}
