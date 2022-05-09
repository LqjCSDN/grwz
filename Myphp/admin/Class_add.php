<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>班级添加</title>
<script src="../scripts/Com.js"></script>
<style type="text/css">
<!--
.STYLE1 {color: #FF0000}
#table2 {
	width: 500px;
	margin: 0 auto;
}
body,td,th {
	font-size: 14px;
}

-->
</style>
<div style='Display:none'>
<?php 
include "../Fun.php";      //选择数据库
include "../IsLogin.php";  //判断用户是否登录
?>
</div>
</head>

<body>
<?php 
if (isset($_REQUEST["add"]))
{
  $test=1;    //只要$test=0，则表单信息就无法提交
  $classid=$_REQUEST["classid"];
  $enrollyear=$_REQUEST["enrollyear"];
  $majorname=$_REQUEST["majorname"];
  $classname=$_REQUEST["classname"];
  $num=$_REQUEST["num"];
  //若正则表达式含^、$，只有正则表达式与字符串完全匹配，该函数才返回1。
  if($classid=="") {$classid1="必须输入班级序号！";$test=0;}
  elseif(preg_match('/^\d{6}$/',$classid)==0)  {$classid1="班级序号必须为6位数字！";$test=0;}
      else { $sql="select * from class where classid='$classid'";
	         $result=mysql_query($sql);
			 if (mysql_num_rows($result)>=1) {$classid1="输入的班级序号已经存在,请重输!";$test=0;}
	       }
  if ($enrollyear=="") {$enrollyear1="必须选择入学年份!";$test=0;}
  if ($majorname=="") {$majorname1="必须输入专业名称!";$test=0;}
  if ($classname=="") {$classname1="必须选择班名!";$test=0;}
  if ($num=="") {$num1="必须输入班人数!";$test=0;}
  if ($test==1)  
  { $sql="insert into class values('$classid',$enrollyear,'$majorname','$classname',$num)";
    mysql_query($sql);
    echo "<script language='javascript'> alert('插入成功!');</script>";
  }
}
?>
<table border="0" cellpadding="0" cellspacing="0" width="100%" id="table1">
  <tr>
    <td> <form action="" method="post" name="form1" >
    <table border="1" cellpadding="4" cellspacing="0" width="500px" id="table2" bordercolor="#328EBE">
     
        <tr>
          <td colspan="2" align="center" bgcolor="#328EBE"> 添加班级信息</td>
        </tr>
		<tr>
          <td width="25%" align="center" >班级序号</td>
          <td width="75%" align="left"><input type="text" name="classid" id="classid" /><?php echo "<font size='2' color='FF0000'>".@$classid1."</font>";?></td>
         
        </tr>
		<tr>
         <?php $x=getdate();
			   $year=$x["year"];
		 ?>
          <td width="25%" align="center">入学年份</td>
          <td width="75%" align="left"><select name="enrollyear" id="enrollyear">
            <option value="">请选择年份</option>
            <option><?php echo $year-3;?></option>
            <option><?php echo $year-2;?></option>
            <option><?php echo $year-1;?></option>
            <option><?php echo $year;?></option>
           
          </select><?php echo "<font size='2' color='FF0000'>".@$enrollyear1."</font>";?>
          </td>
        
        </tr>
        <tr>
          <td width="25%" align="center">专业名称</td>
          <td width="75%" align="left"><input type="text" name="majorname" size="25" value="" id="majorname"/><?php echo "<font size='2' color='FF0000'>".@$majorname1."</font>";?> </td>
		 
        </tr>
		<tr>
          <td width="25%" align="center">班名</td>
          <td width="75%" align="left"><select name="classname" id="classname">
            <option value="">请选择班名</option>
            <option>班</option>
            <option>1班</option>
            <option>2班</option>
            <option>3班</option>
            <option>4班</option>
            <option>5班</option>
          </select><?php echo "<font size='2' color='FF0000'>".@$classname1."</font>";?></td>
		 
        </tr>
        <tr>
          <td width="25%" align="center">班人数</td>
          <td width="75%" align="left"><input type="text" name="num" size="25" value=""/><?php echo "<font size='2' color='FF0000'>".@$num1."</font>";?></td>
		 
        </tr>        <tr>
          <td colspan="2" align="center" bgcolor="#328EBE">
		  <input  type="submit" name="add" value="添加" />
		  <input  type="reset" name="back2" value="返回" onclick="location.href='Class.php'"/>	
		  	  
		  </td>
        </tr>
      
    </table></form></td>
  </tr>
</table>

</body>
</html>
