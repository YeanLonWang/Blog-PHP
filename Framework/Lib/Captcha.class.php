<?php
namespace Lib;
class Captcha{
    private $w;         //画布宽度
    private $h;         //画布高度
    private $font;      //内置字体大小，1,2,3,4,5
    private $len;       //验证码长度
    public function __construct($len=4,$font=5,$w=80,$h=32) {
        $this->w=$w;
        $this->h=$h;
        $this->font=$font;
        $this->len=$len;
    }
    //生成随机字符串
    private function generalCode(){
        $char_array=array_merge(range(0,9),range('a','z'),range('A','Z'));
        // echo $this->len;die();
        $index_array=array_rand($char_array, $this->len);
        shuffle($index_array);		//打乱数组
        $str='';
        foreach($index_array as $i) {
                $str.=$char_array[$i];
        }
        $_SESSION['code']=$str;
        return $str;
    }
    //生成验证码
    public function generalCaptcha(){
        $str= $this->generalCode();
        $img=imagecreate($this->w, $this->h);
        imagecolorallocate($img,255,0,0);	//背景色
        $color=imagecolorallocate($img,255,255,255);	//前景色
        $x=(imagesx($img)-imagefontwidth($this->font)*strlen($str))/2;	//起始点的x坐标
        $y=(imagesy($img)-imagefontheight($this->font))/2;				//起始点的y坐标
        imagestring($img,$this->font,$x,$y,$str,$color);
        // header('content-type:image/png');
        imagepng($img);
    }
    //检查验证码输入是否正确(不区分大小写)
    public function checkCode($code){
        return strtoupper($code)==strtoupper($_SESSION['code']);
    }
}
/*namespace Lib;
class Captcha{
	private $w;     //画布宽度
	private $h;     //画布高度
	private $font;  //内置字体大小,1,2,3,4,5
	private $len;   //验证码长度
	public function __construct($len=4,$font=5,$w=80,$h=32){
		$this->w=$w;
		$this->h=$h;
		$this->font=$font;
		$this->len=$len;
	}
	// 生成随机字符串
	private function generalCode(){
		$char_array=array_merge(range(0,9),range('a','z'),range('A','Z'));
		$index_array=array_rand($char_array,$this->len);
		shuffle($index_array);
		$str='';
		foreach($index_array as $i){
			$str.=$char_array[$i];
		}
		$SESSION['code']=$str;
		return $str;
	}
	// 生成验证码
	public function generalCaptcha(){
		$str=$this->generalCode();
		$img=imagecreate($this->w,$this->h);
		imagecolorallocate($img,255,0,0);  //背景色
		$color=imagecolorallocate($img, 255, 255, 255);  //前景色
		$x=(imagesx($img)-imagefontwidth($this->font)*strlen($str))/2;  //起始点的x坐标
		$y=(imagesy($img)-imagefontheight($this->font))/2;  //起始点的y坐标
		imagestring($img,$this->font,$x,$y,$str,$color);
		ob_clean();
		header('content-type:image/png');
		imagepng($img);
		// 检查验证码输入是否正确
		public function checkCode($code){
			return strtoupper($code) == strtoupper($_SESSION['code']);
		}
	}
}*/

