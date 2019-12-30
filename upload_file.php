<?php

// 允许上传的图片后缀
$allowedExts = array("gif", "jpeg", "jpg", "png");
$temp = explode(".", $_FILES["file"]["name"]);
echo $_FILES["file"]["size"];
$extension = end($temp);     // 获取文件后缀名
if ((($_FILES["file"]["type"] == "image/gif")
|| ($_FILES["file"]["type"] == "image/jpeg")
|| ($_FILES["file"]["type"] == "image/jpg")
|| ($_FILES["file"]["type"] == "image/pjpeg")
|| ($_FILES["file"]["type"] == "image/x-png")
|| ($_FILES["file"]["type"] == "image/png"))
&& ($_FILES["file"]["size"] < 204800)   // 小于 200 kb
&& in_array($extension, $allowedExts))
{
    if ($_FILES["file"]["error"] > 0)
    {
        echo "错误：: " . $_FILES["file"]["error"] . "<br>";
    }
    else
    {
        echo "上传文件名: " . $_FILES["file"]["name"] . "<br>";
        echo "文件类型: " . $_FILES["file"]["type"] . "<br>";
        echo "文件大小: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
        echo "文件临时存储的位置: " . $_FILES["file"]["tmp_name"] . "<br>";
        
        // 判断当前目录下的 upload 目录是否存在该文件
        // 如果没有 upload 目录，你需要创建它，upload 目录权限为 777
        if (file_exists("upload/" . $_FILES["file"]["name"]))
        {
            echo $_FILES["file"]["name"] . " 文件已经存在。 ";
        }
        else
        {
            // 如果 upload 目录不存在该文件则将文件上传到 upload 目录下
            move_uploaded_file($_FILES["file"]["tmp_name"], "upload/" . $_FILES["file"]["name"]);
            echo "文件存储在: " . "upload/" . $_FILES["file"]["name"];
        }
    }
}
else
{
    echo "非法的文件格式";
}
	 echo "<br>";
$servername = "localhost";
$username = "root";
$password = "123456789";
$dbname = "FaceHash";

		$src = "upload/".$_FILES["file"]["name"];
		echo "输入图片";
		echo "<img src=$src />";
		echo "<br>";
// 创建连接
$conn = new mysqli($servername, $username, $password, $dbname);
// 检测连接
if ($conn->connect_error) {
    die("连接失败: " . $conn->connect_error);
} 
 for($i=1;$i<=165;$i++){
	 $sql = "SELECT * FROM img_hash WHERE hash_id='".$i."'";
	 $result = $conn->query($sql);
	 $row = $result->fetch_assoc();

	 $hash2 = $row["phash"];
	 $hash1 = ImageHash::run("upload/" . $_FILES["file"]["name"]);

	if(strlen($hash1) !== strlen($hash2)) return false;
    $count = 0;
    $len = strlen($hash1);
    for($a = 0; $a < $len; $a++)
	{
		if($hash1[$a] !== $hash2[$a])
		{
			$count++;
		}
	}		 
	if($count <= 3)
	{
		echo "相似图片:";
		echo $row["filename"];
		$src = "jpg/".$row["filename"];
		echo "<img src=$src />";
		echo "<br>";
	}
 }

$conn->close();

class ImageHash {
  const FILE_NOT_FOUND = '-1';
  const FILE_EXTNAME_ILLEGAL = '-2';
  private function __construct() {}
  
  public static function run($src1) {
    static $self;
    if(!$self) $self = new static;
    $hash1 = $self->getHashValue($src1);
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
    return $hash;
  }
}

?>