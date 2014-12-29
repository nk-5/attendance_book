<?php
class ImagesController extends AppController {
      public $helpers    = array ( 'Html', 'Form', 'Session' );
      public $components = array ( 'Session' );

       public $primaryKey = 'id'; // 

    var $uses = array('Image');

    function imageAdd(){
        $user_id  = $this->Auth->user('id');
        $limit = 1024 * 1024;
        debug($this->data);

        // 画像の容量チェック
        if ($this->data['Images']['image']['size'] > $limit){
            $this->Session->setFlash('1MB以内の画像が登録可能です。');
            $this->redirect('../Appointments/whitebord');
        }
        // アップロードされた画像か
        if (!is_uploaded_file($this->data['Images']['image']['tmp_name'])){
            $this->Session->setFlash('アップロードに失敗しました。');
            $this->redirect('../Appointments/whitebord');
        }

     // 保存
        $imageFileName = $this->data['Images']['image']['name'];
        //元画像のサイズを取得
        $imagesize = getimagesize($this->data['Images']['image']['tmp_name']);
        $imageFilePath = 'app/Controller/Appointments/contents' . '/' . $imageFileName;

    //縮小画像を作成、保存
        $width = $imagesize[0];
        $height = $imagesize[1];

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
                'filename' => md5(microtime()) . '.jpg',
                'contents' => file_get_contents(dirname($_SERVER['SCRIPT_FILENAME']).'/img/thumbnails'.'/'.$this->data['Images']['image']['name']),
                'user_id'  => $user_id,
            )
        );

    $check_id = $this->Image->query("SELECT id FROM images WHERE user_id = \"$user_id\"");

    //テーブルにIDが存在していたら更新、なければ保存    I
    if($check_id[0]['images']['id'] != null){ 
        $update_image = array('Image' => array('user_id' => $user_id,'id' => $check_id[0]['images']['id'], 'filename' => md5(microtime()) . '.jpg','contents' => file_get_contents(dirname($_SERVER['SCRIPT_FILENAME']).'/img/thumbnails'.'/'.$this->data['Images']['image']['name'])));
        $this->Image->save($update_image, false);
        $this->Session->setFlash('画像を更新しました。');
    }else{
        $this->Image->save($image);
        $this->Session->setFlash('画像をアップロードしました。'); 
    }
        $this->redirect('../Appointments/whitebord');
    }
}