<?php
require_once(dirname(__FILE__).'/config.php');
require_once(DEDEINC.'/image.func.php');
/************************
//上传
function Upload(){  }
*************************/
if(empty($dopost))
{
	ini_set('html_errors', '0');
	if ( empty($Filedata) || !is_uploaded_file($Filedata) )
	{
		echo 'ERROR: Upload Error! ';
		exit(0);
	}
	$info = '';
	$srcInfo = @GetImageSize($Filedata, $info);
	if (!is_array($srcInfo))
	{
		echo "ERROR: Image info Error! ";
		exit(0);
	}
  if (!isset($_SESSION['file_info']))
	{
		$_SESSION['file_info'] = array();
	}
	if (!isset($_SESSION['bigfile_info']))
	{
		$_SESSION['bigfile_info'] = array();
	}
	if (!isset($_SESSION['fileid']))
	{
		$_SESSION['fileid'] = 1;
	}
	else
	{
		$_SESSION['fileid']++;
	}
	
	//检测文件类型
	$info = $ftype = '';
	$srcInfo = @GetImageSize($Filedata, $info);
	if(is_array($srcInfo))
	{
		switch ($srcInfo[2])
		{
			case 1:
				$ftype = 'image/gif';
				break;
			case 2:
				$ftype = 'image/jpeg';
				break;
			case 3:
				$ftype = 'image/png';
				break;
			case 6:
				$ftype = 'image/bmp';
				break;
		}
	}
	
	//不能识别类型的图片
	if($ftype=='')
	{
		echo "ERROR: Image type Error! ";
		exit(0);
	}
	
	//生成缩略图
	ob_start();
	ImageResizeNew($Filedata, $cfg_ddimg_width, $cfg_ddimg_height, '', false);
	$imagevariable = ob_get_contents();
	ob_end_clean();
	
	$_SESSION['file_info'][$_SESSION['fileid']] = $imagevariable;
	echo "FILEID:".$_SESSION['fileid'];
	
	//保存原图
	$okfilename = AdminUpload('Filedata', 'image', $_SESSION['fileid'], true, $ftype);
	$_SESSION['bigfile_info'][$_SESSION['fileid']] = $okfilename;
	
	exit(0);
}
/************************
//生成缩图
function GetThumbnail(){  }
*************************/
else if($dopost=='thumbnail')
{
	if( empty($id) )
	{
		header('HTTP/1.1 500 Internal Server Error');
		echo 'No ID';
		exit(0);
	}
	if (!is_array($_SESSION['file_info']) || !isset($_SESSION['file_info'][$id]))
	{
		header('HTTP/1.1 404 Not found');
		exit(0);
	}
	header('Content-type: image/jpeg');
	header('Content-Length: '.strlen($_SESSION['file_info'][$id]));
	echo $_SESSION['file_info'][$id];
	exit(0);
}
/************************
//删除指定ID的图片
*************************/
else if($dopost=='del')
{
	if(!isset($_SESSION['bigfile_info'][$id]))
	{
		echo '';
		exit();
	}
	$dsql->ExecuteNoneQuery("Delete From `#@__uploads` where url like '{$_SESSION['bigfile_info'][$id]}'; ");
	@unlink($cfg_basedir.$_SESSION['bigfile_info'][$id]);
	$_SESSION['file_info'][$id] = '';
	$_SESSION['bigfile_info'][$id] = '';
	echo "<b>已删除！</b>";
	exit();
}
/************************
//获取本地图片的缩略预览图
function GetddImg(){  }
*************************/
else if($dopost=='ddimg')
{
	//生成缩略图
	ob_start();
	ImageResizeNew($cfg_basedir.$img, $cfg_ddimg_width, $cfg_ddimg_height, '', false);
	$imagevariable = ob_get_contents();
	ob_end_clean();
	header('Content-type: image/jpeg');
	header('Content-Length: '.strlen($imagevariable));
	echo $imagevariable;
	exit();
}
/************************
//删除指定的图片(编辑图集时用)
*************************/
else if($dopost=='delold')
{
	$imgfile = $cfg_basedir.$picfile;
	if(!file_exists($imgfile) && !is_dir($imgfile) && ereg("^".$cfg_medias_dir, $imgfile))
	{
		@unlink($imgfile);
	}
	$dsql->ExecuteNoneQuery("Delete From `#@__uploads` where url like '{$picfile}'; ");
	echo "<b>已删除！</b>";
	exit();
}
?>