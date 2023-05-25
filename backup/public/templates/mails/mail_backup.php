<?php

$content = fopen($VARS['flowFilePath'], "r");

echo "Archive name: ".$VARS['archiveName']."<br/>";
while(!feof($content)) {
	echo fgets($content). "<br />";
}

fclose($content);
