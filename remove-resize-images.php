<?php
/**
 * remove resize images
 *
 * a simple code for remove all resized images in wordpress upload folder and compress images
 * Warning : Make a backup of the upload folder before you can do anything
 * upload and run the file in root of wordpress
 *
 * @author M.Motahari (nasimnet)
 * @since 1.0
 */
require_once("wp-load.php");

/**
 * compress images
 *
 * @param   string    $source
 * @param   string    $destination
 * @param   integer   $qj quality jpg (100 -10 )
 * @param   integer   $qp quality png (10 -1 )
 * @return  void
 */
function compress($source, $destination, $qj, $qp) {
    $info = getimagesize($source);

    if ($info['mime'] == 'image/jpeg') {
		$image = imagecreatefromjpeg($source);
		imagejpeg($image, $destination, $qj);
	}
    // TODO: review for png-24
	// elseif ($info['mime'] == 'image/png' ) {
	// 	$image = imagecreatefrompng($source);
	// 	imagepng($image, $destination, $qp);
	// }

    return $destination;
}

$uploades_dir = WP_CONTENT_DIR . '/uploads/';
$all_files    = array_filter(glob($uploades_dir .'*'), 'is_dir');
$upload_file  = range(2005, 2020);
$orginal_dir  = array();
$delete_count = $compress_count = 0;

foreach ( $all_files as $files ) {
	if ( in_array(basename($files), $upload_file)) {
		foreach (glob($files . '/*') as $dir) {
			$orginal_dir[] = $dir;
		}
	}
}

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

printf( '<p>delete %s images</p>', $delete_count );
printf( '<p>compress %s images</p>', $compress_count );
