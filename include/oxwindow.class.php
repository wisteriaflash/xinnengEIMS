<?php
if(!defined('DEDEINC'))
{
	exit("Request Error!");
}
require_once(DEDEINC."/dedetag.class.php");

class OxWindow
{
	var $myWin = "";
	var $myWinItem = "";
	var $checkCode = "";
	var $formName = "";
	var $tmpCode = "//checkcode";
	var $hasStart = false;
	
	//初始化为含表单的页面
	function Init($formaction="",$checkScript="js/blank.js",$formmethod="POST",$formname="myform")
	{
		$this->myWin .= "<script language='javascript'>\r\n";
		if($checkScript!="" && file_exists($checkScript))
		{
			$fp = fopen($checkScript,"r");
			$this->myWin .= fread($fp,filesize($checkScript));
			fclose($fp);
		}
		else
		{
			$this->myWin .= "<!-- function CheckSubmit()\r\n{ return true; } -->";
		}
		$this->myWin .= "</script>\r\n";
		$this->formName = $formname;
		$this->myWin .= "<form name='$formname' method='$formmethod' onSubmit='return CheckSubmit();' action='$formaction'>\r\n";
	}

	//增加隐藏域
	function AddHidden($iname,$ivalue)
	{
		$this->myWin .= "<input type='hidden' name='$iname' value='$ivalue'>\r\n";
	}

	function StartWin($mywinType)
	{
		$this->myWin .= "<div class='msg {$mywinType}'>\r\n";
	}

	//增加一个两列的行
	function AddItem($iname,$ivalue)
	{
		$this->myWinItem .= "<p>\r\n";
		$this->myWinItem .= "$iname\r\n";
		$this->myWinItem .= "$ivalue\r\n";
		$this->myWinItem .= "</p>\r\n";
	}

	//增加一个单列的消息行
	function AddMsgItem($ivalue,$height="100",$col="2")
	{
		if($height!=""&&$height!="0")
		{
			$height = " height='$height'";
		}
		else
		{
			$height="";
		}
		if($col!=""&&$col!=0)
		{
			$colspan="colspan='$col'";
		}
		else
		{
			$colspan="";
		}
		$this->myWinItem .= "<p>\r\n";
		$this->myWinItem .= "$ivalue </br>\r\n";
		$this->myWinItem .= "</p>\r\n";
	}

	//增加单列的标题行
	function AddTitle($title,$col="2")
	{
		global $cfg_plus_dir;
		if($col!=""&&$col!="0")
		{
			$colspan="colspan='$col'";
		}
		else
		{
			$colspan="";
		}
		$this->myWinItem .= "<strong>$title</strong><br />\r\n";
	}

	//结束Window
	function CloseWin($isform=true)
	{
		if(!$isform)
		{
			$this->myWin .= "</div>\r\n";
		}
		else
		{
			$this->myWin .= "</div></form>\r\n";
		}
	}


	//增加自定义JS脚本-
	function SetCheckScript($scripts)
	{
		$pos = strpos($this->myWin,$this->tmpCode);
		if($pos>0)
		{
			$this->myWin = substr_replace($this->myWin,$scripts,$pos,strlen($this->tmpCode));
		}
	}
	
	function GetWinButtom($wintype)
	{
		$wintypestr = array('save'=>'保存','back'=>'返回','ok'=>'确定','reset'=>'重置','search'=>'搜索');
		return $wintypestr[$wintype];
	}


	//获取窗口
	function GetWindow($wintype="save",$msg="",$isform=true,$mywintype="stat1")
	{
		global $cfg_plus_dir;
		$this->StartWin($mywintype);
		$this->myWin .= $this->myWinItem;
		if($wintype!="")
		{
			if($wintype!="hand")
			{
				$this->myWin .= "
				<p><button type='submit' class='btn1'>".$this->GetWinButtom($wintype)."</button>&nbsp;
				<button type='button' class='btn1' onClick='this.form.reset();return false;'>重置</button>&nbsp;
				<button type='button' class='btn1' onClick='history.go(-1);'>返回</button>";
			}
			else
			{
				if($msg!='')
				{
					$this->myWin .= "<tr><td bgcolor='#f7ffdb'>$msg</td></tr>";
				}
				else
				{
					$this->myWin .= '';
				}
			}
		}
		$this->CloseWin($isform);
		return $this->myWin;
	}

	//显示页面
	function Display($modfile="")
	{
		global $cfg_templets_dir,$wecome_info,$cfg_basedir;
		if(empty($wecome_info))
		{
			$wecome_info = "DedeEIMS OX 通用对话框：";
		}
		$ctp = new DedeTagParse();
		if($modfile=='')
		{
			$ctp->LoadTemplate($cfg_basedir.$cfg_templets_dir.'/plus/win_templet.htm');
		}
		else
		{
			$ctp->LoadTemplate($modfile);
		}
		$emnum = $ctp->Count;
		for($i=0;$i<=$emnum;$i++)
		{
			if(isset($GLOBALS[$ctp->CTags[$i]->GetTagName()]))
			{
				$ctp->Assign($i,$GLOBALS[$ctp->CTags[$i]->GetTagName()]);
			}
		}
		$ctp->Display();
		$ctp->Clear();
	}
}

/*------
显示一个不带表单的普通提示
-------*/
function ShowMsgWin($msg,$title)
{
	$win = new OxWindow();
	$win->AddTitle($title);
	$win->AddMsgItem("<div style='padding-left:20px;line-height:150%'>{$msg}</div>");
	$winform = $win->GetWindow("hand","&nbsp;",false,"stat1");
	$win->Display();
}

?>