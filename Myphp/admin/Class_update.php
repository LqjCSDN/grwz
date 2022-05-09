<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>班级修改</title>
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
$classid=$_REQUEST["id"]; //班级序号是主键,不能修改。
if (isset($_REQUEST["update"]))
{
  $test=1;    //只要$test=0，则表单信息就无法提交
 
  $enrollyear=$_REQUEST["enrollyear"];
  $majorname=$_REQUEST["majorname"];
  $classname=$_REQUEST["classname"];
  $num=$_REQUEST["num"];
 
  
  if ($enrollyear=="") {$enrollyear1="必须选择入学年份!";$test=0;}
  if ($majorname=="") {$majorname1="必须输入专业名称!";$test=0;}
  if ($classname=="") {$classname1="必须选择班名!";$test=0;}
  if ($num=="") {$num1="必须输入班人数!";$test=0;}
  if ($test==1)  
  { $sql="update class set enrollyear=$enrollyear,majorname='$majorname',classname='$classname',num=$num where classid='$classid'";
    mysql_query($sql);
    echo "<script language='javascript'> alert('修改成功!');</script>";
  }
}

$sql="select * from class where classid='$classid'";
$result=mysql_query($sql);
$row=mysql_fetch_assoc($result);
$classid=$row["classid"];
$enrollyear=$row["enrollyear"];
$majorname=$row["majorname"];
$classname=$row["classname"];
$num=$row["num"];
?>
<table border="0" cellpadding="0" cellspacing="0" width="100%" id="table1">
  <tr>
    <td> <form action="" method="post" name="form1" >
    <table border="1" cellpadding="4" cellspacing="0" width="500px" id="table2" bordercolor="#328EBE">
     
        <tr>
          <td colspan="2" align="center" bgcolor="#328EBE"> 修改班级信息</td>
        </tr>
		<tr>
          <td width="25%" align="center" >班级序号</td>
          <td width="75%" align="left"><input name="id" type="text" id="id" value="<?php echo $classid;?>" readonly="readonly"/></td>
         
        </tr>
		<tr>
         <?php $x=getdate();
			   $year=$x["year"];
		 ?>
          <td width="25%" align="center">入学年份</td>
          <td width="75%" align="left"><select name="enrollyear" id="enrollyear">
            <option value="">请选择年份</option>
            <option <?php if ($enrollyear==$year-3) echo "selected";?>><?php echo $year-3;?></option>
            <option <?php if ($enrollyear==$year-2) echo "selected";?>><?php echo $year-2;?></option>
            <option <?php if ($enrollyear==$year-1) echo "selected";?>><?php echo $year-1;?></option>
            <option <?php if ($enrollyear==$year) echo "selected";?>><?php echo $year;?></option>
           
          </select><?php echo "<font size='2' color='FF0000'>".@$enrollyear1."</font>";?>
          </td>
        
        </tr>
        <tr>
          <td width="25%" align="center">专业名称</td>
          <td width="75%" align="left"><input type="text" name="majorname" size="25" value="<?php echo $majorname;?>" id="majorname"/><?php echo "<font size='2' color='FF0000'>".@$majorname1."</font>";?> </td>
		 
        </tr>
		<tr>
          <td width="25%" align="center">班名</td>
          <td width="75%" align="left"><select name="classname" id="classname">
            <option value="">请选择班名</option>
            <option <?php if ($classname=="班") echo "selected";?>>班</option>
            <option <?php if ($classname=="1班") echo "selected";?>>1班</option>
            <option <?php if ($classname=="2班") echo "selected";?>>2班</option>
            <option <?php if ($classname=="3班") echo "selected";?>>3班</option>
            <option <?php if ($classname=="4班") echo "selected";?>>4班</option>
            <option <?php if ($classname=="5班") echo "selected";?>>5班</option>
          </select><?php echo "<font size='2' color='FF0000'>".@$classname1."</font>";?></td>
		 
        </tr>
        <tr>
          <td width="25%" align="center">班人数</td>
          <td width="75%" align="left"><input type="text" name="num" size="25" value="<?php echo $num;?>"/><?php echo "<font size='2' color='FF0000'>".@$num1."</font>";?></td>
		 
        </tr>        <tr>
          <td colspan="2" align="center" bgcolor="#328EBE">
		  <input  type="submit" name="update" value="修改" id="update" />
		  <input  type="reset" name="back2" value="返回" onclick="location.href='Class.php'"/>	
		  	  
		  </td>
        </tr>
      
    </table></form></td>
  </tr>
</table>

</body>
</html>
