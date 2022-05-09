<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>个人信息查询</title>
<link href="../styles/com.css" rel="stylesheet" />
<style type="text/css">
#table2 {
	width: 50%;
	margin: 0 auto;
}
</style>
<script src="../scripts/Com.js"></script>
</head>
<body>
<div style="Display:none">
<?php 
	include "../Fun.php";
	include "../IsLogin.php";
	$userid=$_SESSION["userid"];
	$sql="select * from student,class where student.classid=class.classid and studentid='$userid'";							
	$result=mysql_query($sql);
    $row=mysql_fetch_array($result);
?>
</div>
<table border="1" cellpadding="4" cellspacing="0" id="table2" bordercolor="#328EBE">
   
        <tr>
          <td height="25" colspan="2" align="center" bgcolor="#328EBE" style="font-family:'华文新魏'; font-size:20px">个人信息查询</td>
        </tr>
        <tr>
          <td width="35%" height="25" align="center">专业</td>
          <td height="25" align="center"><?php echo $row["majorname"];?></td>
        </tr>
        <tr> 
          <td width="35%" height="25" align="center">班级名称</td>
          <td height="25" align="center"><?php echo  $row["enrollyear"].$row["majorname"].$row["classname"];?></td>
        </tr>
		
        <tr>
          <td width="35%" height="25" align="center">学 号</td>
          <td height="25" align="center"><?php echo $row["studentid"];?></td>
        </tr>
        <tr>
          <td width="35%" height="25" align="center">姓 名</td>
          <td height="25" align="center"><?php echo $row["studentname"];?></td>
        </tr>        
        <tr>
          <td width="35%" height="25" align="center">性 别</td>
		  <td height="25" align="center"><?php echo $row["sex"];?></td>
        </tr>
		<tr>
          <td width="35%" height="25" align="center">出生日期</td>
          <td height="25" align="center"><?php echo $row["birthday"];?></td>
        </tr>
        <tr>
          <td width="35%" height="25" align="center">总学分</td>
          <td height="25" align="center"><?php echo $row["credit"];?></td>
        </tr>              
		<tr>
          <td height="25" colspan="2" align="center" bgcolor="#328EBE">&nbsp;</td>
        </tr>

    </table>
</body>
</html>
