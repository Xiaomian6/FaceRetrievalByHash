# FaceRetrievalByHash

 基于哈希算法的人脸检索
 
 Face retrieval based on hash algorithm
 
 > @zhouzhihui @xiaomian
 
 > 演示地址:
 
 >设计一个有效的hash算法，通过hash算法存储165张图片存入数据库，数据库采用MariaDB数据库，hash算法采用感知哈希算法（Perceptual hash algorithm），数据库及可视化界面均部署到云服务器，使用PHP及HTML完成可视化展示检索结果。

### 详细功能描述：
- 设计一个有效的hash算法用于人脸检索；
- 通过hash算法存储165张图片于数据库中，每一个图片的hash码位数为64位；
- 输入一个图片，检索数据库，并输出对应人脸图，以可视化界面的方式展示检索结果。要求检查结果具有准确性。

### 工程文件
- form.html 首页
- upload_file.php 选择上传匹配显示程序
- jpg.hph 读取jpg文件夹所有图像计算转化成PHA值与相对于图像一起存入数据库 (导入样本进数据库)

### 感知哈希算法
- 感知哈希算法（Perceptual hash algorithm）简称PHA，PHA是哈希算法的一类，主要用来做相似图片的搜索工作。PHA是一类比较哈希方法的统称。图片所包含的特征被用来生成一组指纹（不过它不是唯一的）,而这些指纹是可以进行比较的。
- 1.缩小尺寸去除高频和细节的最快方法是缩小图片，将图片缩小到8x8的尺寸，总共64个像素。不要保持纵横比，只需将其变成8*8的正方形。这样就可以比较任意大小的图片，摒弃不同尺寸、比例带来的图片差异。
- 2.简化色彩将8*8的小图片转换成灰度图像，将64个像素的颜色(red,green,blue)转换成一种颜色（黑白灰度）。
- 3.计算平均值计算所有64个像素的灰度平均值。
- 4.比较像素的灰度将每个像素的灰度，与平均值进行比较。大于或等于平均值，记为1；小于平均值，记为0。
- 5.计算hash值将上一步的比较结果，组合在一起，就构成了一个64位的整数，这就是这张图片的指纹。组合的次序并不重要，只要保证所有图片都采用同样次序就行了。(我设置的是从左到右，从上到下用二进制保存)。

