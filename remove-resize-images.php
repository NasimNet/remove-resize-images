<?php
require_once("wp-load.php");

function compress($source, $destination, $qj, $qp) {

    $info = getimagesize($source);
    if ($info['mime'] == 'image/jpeg') {
		$image = imagecreatefromjpeg($source);
		imagejpeg($image, $destination, $qj);
	}
	// elseif ($info['mime'] == 'image/png' ) {
	// 	$image = imagecreatefrompng($source);
	// 	imagepng($image, $destination, $qp);
	// }

    return $destination;
}

$uploades_dir = WP_CONTENT_DIR . '/uploads/';
$all_files =  array_filter(glob($uploades_dir .'*'), 'is_dir');
$upload_file = range(2005, 2020);
$orginal_dir = array();

foreach ( $all_files as $files ) {
	if ( in_array(basename($files), $upload_file)) {
		foreach (glob($files . '/*') as $dir) {
			$orginal_dir[] = $dir;
		}
	}
}

// delete resize images
$delete_count = 0;
$compress_count = 0;

foreach ( $orginal_dir as $orginal_files) {
	foreach (glob($orginal_files . '/*') as $file) {
		$name = basename($file);

		if ( preg_match('.-\d+x\d*.', $name) ) {
			unlink($file);
			$delete_count ++;
		} else {
			compress($file, $file, 70, 5);
			$compress_count ++;
		}
	}
}

echo 'delete ' . $delete_count . ' images <br>' ;
echo 'compress ' . $compress_count . ' images <br>' ;
