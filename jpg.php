<?php

class ImageHash {

  const FILE_NOT_FOUND = '-1';

  const FILE_EXTNAME_ILLEGAL = '-2';

  private function __construct() {}

  public static function run($src1) {

    static $self;

    if(!$self) $self = new static;

    //if(!is_file($src1) || !is_file($src2)) exit(self::FILE_NOT_FOUND);

    $hash1 = $self->getHashValue($src1);

    //$hash2 = $self->getHashValue($src2);

    //if(strlen($hash1) !== strlen($hash2)) return false;

    //$count = 0;

    //$len = strlen($hash1);

    //for($i = 0; $i < $len; $i++) if($hash1[$i] !== $hash2[$i]) $count++;

    //return $count <= 10 ? true : false;
    return $hash1;
  }

  public function getImage($file) {

    $extname = pathinfo($file, PATHINFO_EXTENSION);

    if(!in_array($extname, ['jpg','jpeg','png','gif'])) exit(self::FILE_EXTNAME_ILLEGAL);

    $img = call_user_func('imagecreatefrom'. ( $extname == 'jpg' ? 'jpeg' : $extname ) , $file);

    return $img;

  }

  public function getHashValue($file) {

    $w = 8;

    $h = 8;

    $img = imagecreatetruecolor($w, $h);

    list($src_w, $src_h) = getimagesize($file);

    $src = $this->getImage($file);

    imagecopyresampled($img, $src, 0, 0, 0, 0, $w, $h, $src_w, $src_h);

    imagedestroy($src);

    $total = 0;

    $array = array();

    for( $y = 0; $y < $h; $y++) {

      for ($x = 0; $x < $w; $x++) {

        $gray = (imagecolorat($img, $x, $y) >> 8) & 0xFF;

        if(!isset($array[$y])) $array[$y] = array();

        $array[$y][$x] = $gray;

        $total += $gray;

      }

    }

    imagedestroy($img);

    $average = intval($total / ($w * $h * 2));

    $hash = '';

    for($y = 0; $y < $h; $y++) {

      for($x = 0; $x < $w; $x++) {

        $hash .= ($array[$y][$x] >= $average) ? '1' : '0';

      }

    }

    var_dump($hash);

    return $hash;

  }

}

function getDirContent($path){
  if(!is_dir($path)){
    return false;
  }
  //readdir方法
  /* $dir = opendir($path);
  $arr = array();
  while($content = readdir($dir)){
    if($content != '.' && $content != '..'){
      $arr[] = $content;
    }
  }
  closedir($dir); */
 
  //scandir方法
  $arr = array();
  $data = scandir($path);
  foreach ($data as $value){
    if($value != '.' && $value != '..'){
      $arr[] = $value;
    }
  }
  return $arr;
}
$arr1 = getDirContent('./');

$servername = "localhost";
$username = "root";
$password = "123456789";
$dbname = "FaceHash";
 
// 创建连接
$conn = new mysqli($servername, $username, $password, $dbname);
// 检测连接
if ($conn->connect_error) {
    die("连接失败: " . $conn->connect_error);
} 
for($i=0;$i<=164;$i++)
{
	$imghash = ImageHash::run($arr1[$i]);
	$sql = "INSERT INTO img_hash(filename,phash)VALUES('".$arr1[$i]."','".$imghash."')";
	if ($conn->query($sql) === TRUE) {
    echo "新记录插入成功";
	} 
	else {
    echo "Error: " . $sql . "<br>" . $conn->error;
	}
}
$conn->close();
 

 

