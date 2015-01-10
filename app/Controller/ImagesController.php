<?php
class ImagesController extends AppController {
    public $helpers    = array ( 'Html', 'Form', 'Session' );
    public $components = array ( 'Session' );

    public $primaryKey = 'id'; // 

    var $uses = array('Image');

    function imageAdd(){
        $user_id  = $this->Auth->user('id');
        $limit = 1024 * 1024 * 10;
        debug($this->data);

        // 画像の容量チェック
        if ($this->data['Images']['image']['size'] > $limit){
            $this->Session->setFlash('10MB以内の画像が登録可能です。');
            $this->redirect('../users/view/'.$user_id);
        }
        // アップロードされた画像か
        if (!is_uploaded_file($this->data['Images']['image']['tmp_name'])){
            $this->Session->setFlash('アップロードに失敗しました。');
            $this->redirect('../users/view/'.$user_id);
        }

     // 保存
        $imageFileName = $this->data['Images']['image']['name'];
        //元画像のサイズを取得
        $imagesize = getimagesize($this->data['Images']['image']['tmp_name']);
        $imageFilePath = 'app/Controller/Appointments/contents' . '/' . $imageFileName;

    //縮小画像を作成、保存
        $width = $imagesize[0];
        $height = $imagesize[1];

        if($width > 120){
        //元ファイルを画像タイプによって作る
            switch ($imagesize['mime']) {
                case 'image/gif':
                    $srcImage_ = imagecreatefromgif($this->data['Images']['image']['tmp_name']);
                    break;
                case 'image/jpeg':
                    $srcImage_ = imagecreatefromjpeg($this->data['Images']['image']['tmp_name']);
                    break;
                case 'image/png':
                    $srcImage_ = imagecreatefrompng($this->data['Images']['image']['tmp_name']);
                    break;
             }

     //新しいサイズを作る
        $profile_Height = round($height * 120 / $width);

     //プロフィール画像を生成
        $profile_Image = imagecreatetruecolor(120,$profile_Height);
        imagecopyresampled($profile_Image,$srcImage_,0,0,0,0,120,$profile_Height,$width,$height);

        //プロフィール画像を保存する
        switch ($imagesize['mime']) {
            case 'image/gif':
                imagegif($profile_Image,dirname($_SERVER['SCRIPT_FILENAME']).'/img/images'.'/'.$imageFileName);
                break;
            case 'image/jpeg':
                imagejpeg($profile_Image,dirname($_SERVER['SCRIPT_FILENAME']).'/img/images'.'/'.$imageFileName);
                break;
            case 'image/png':
                imagepng($profile_Image,dirname($_SERVER['SCRIPT_FILENAME']).'/img/images'.'/'.$imageFileName);
                break;
        }
    }

        if($width > 36){
        //元ファイルを画像タイプによって作る
            switch ($imagesize['mime']) {
                case 'image/gif':
                    $srcImage = imagecreatefromgif($this->data['Images']['image']['tmp_name']);
                    break;
                case 'image/jpeg':
                    $srcImage = imagecreatefromjpeg($this->data['Images']['image']['tmp_name']);
                    break;
                case 'image/png':
                    $srcImage = imagecreatefrompng($this->data['Images']['image']['tmp_name']);
                    break;
             }

     //新しいサイズを作る
        $thumbHeight = round($height * 36 / $width);

     //縮小画像を生成
        $thumbImage = imagecreatetruecolor(36,$thumbHeight);
        imagecopyresampled($thumbImage,$srcImage,0,0,0,0,36,$thumbHeight,$width,$height);

    //縮小画像を保存する
        switch ($imagesize['mime']) {
            case 'image/gif':
                imagegif($thumbImage,dirname($_SERVER['SCRIPT_FILENAME']).'/img/thumbnails'.'/'.$imageFileName);
                break;
            case 'image/jpeg':
                imagejpeg($thumbImage,dirname($_SERVER['SCRIPT_FILENAME']).'/img/thumbnails'.'/'.$imageFileName);
                break;
            case 'image/png':
                imagepng($thumbImage,dirname($_SERVER['SCRIPT_FILENAME']).'/img/thumbnails'.'/'.$imageFileName);
                break;
        }
     }

    $image = array(
            'Image' => array(
                // 'filename' => md5(microtime()) . '.jpg',
                'filename' => $imageFileName,
                'contents' => file_get_contents(dirname($_SERVER['SCRIPT_FILENAME']).'/img/thumbnails'.'/'.$this->data['Images']['image']['name']),
                'user_id'  => $user_id,
            )
        );

    $check_id = $this->Image->query("SELECT id FROM images WHERE user_id = \"$user_id\"");

    //テーブルにIDが存在していたら更新、なければ保存    I
    if($check_id[0]['images']['id'] != null){ 
        $update_image = array('Image' => array('user_id' => $user_id,'id' => $check_id[0]['images']['id'], 'filename' => $imageFileName,'contents' => file_get_contents(dirname($_SERVER['SCRIPT_FILENAME']).'/img/thumbnails'.'/'.$this->data['Images']['image']['name'])));
        $this->Image->save($update_image, false);
        $this->Session->setFlash('画像を更新しました。');
    }else{
        $this->Image->save($image);
        $this->Session->setFlash('画像をアップロードしました。'); 
    }
        // $this->redirect('../Appointments/whitebord');
        $this->redirect('../users/view/'.$user_id);
    }

    // $this->set('profile_image',$profile_Image);
}