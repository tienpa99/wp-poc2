<?php
$archiveName = $VARS['archiveName'];

if ($VARS['succeeded'] == true) {
	echo 'The '.$archiveName.' archive upload succeeded.';
}
else {
	echo 'The '.$archiveName.' archive upload failed.';
}
