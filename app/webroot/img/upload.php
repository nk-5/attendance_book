<?php

require_once('config.php');

// $_FILES['image'];//form name
// var_dump($_FILES['image']);
// exit;

//error check
if($_FILES['image']['error'] != UPLOAD_ERR_OK){
	echo "error!!".$_FILES['image']['tmp_name'];
	exit;
}

$size = filesize($_FILES['image']['tmp_name']);
if(!$size || $size > MAX_FILE_SIZE){
	echo "file size is big";
	exit;		
}


//save file name

//拡張子を決め、名前を決める
$imagesize = getimagesize($_FILES['image']['tmp_name']);
// var_dump($imagesize);

switch ($imagesize['mime']) {
	case 'image/gif':
		$ext = '.gif';
		break;
	case 'image/jpeg':
		$ext = '.jpg';
		break;
	case 'image/png':
		$ext = '.png';
		break;
	default:
		echo "GIF/JPEG/PNG only!";
		exit;
}

$imageFileName = sha1(time().mt_rand()) . $ext;
// var_dump($imageFileName);

// //元画像を保存

$imageFilePath = IMAGES_DIR . '/' . $imageFileName;

$rs = move_uploaded_file($_FILES['image']['tmp_name'],$imageFilePath);
if(!$rs){
	echo "not upload!";
	exit;
}


 // 縮小画像を作成、保存

$width = $imagesize[0];
$height = $imagesize[1];

if($width > THUMBNAIL_WIDTH){
	//元ファイルを画像タイプによって作る
	switch ($imagesize['mime']) {
	case 'image/gif':
		$srcImage = imagecreatefromgif($imageFilePath);
		break;
	case 'image/jpeg':
		$srcImage = imagecreatefromjpeg($imageFilePath);
		break;
	case 'image/png':
		$srcImage = imagecreatefrompng($imageFilePath);
		break;
}

	//新しいサイズを作る
	$thumbHeight = round($height * THUMBNAIL_WIDTH / $width);

	//縮小画像を生成
	$thumbImage = imagecreatetruecolor(THUMBNAIL_WIDTH,$thumbHeight);
	imagecopyresampled($thumbImage,$srcImage,0,0,0,0,72,$thumbHeight,$width,$height);


	//縮小画像を保存する
	switch ($imagesize['mime']) {
	case 'image/gif':
		imagegif($thumbImage,THUMBNAIL_DIR.'/'.$imageFileName);
		break;
	case 'image/jpeg':
		imagejpeg($thumbImage,THUMBNAIL_DIR.'/'.$imageFileName);
		break;
	case 'image/png':
		imagepng($thumbImage,THUMBNAIL_DIR.'/'.$imageFileName);
		break;
	}
}


// index.phpに飛ばす
header('Location:http://'.$_SERVER['SERVER_NAME'].'/git_test/attendance_book/app/View/Appointments/');
exit;
