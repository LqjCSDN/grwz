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
	include "../Fun.php";      //选择数据库
	include "../IsLogin.php";  //判断用户是否登录
	$id=$_SESSION["userid"];	//登陆成功则把用户类别及用户名写入SESSION					
	$result=mysql_query("select * from teacher where teacherid='$id'");
    $row=mysql_fetch_array($result);  //数组的键名可以是整数和字段名。
?>
</div>

    <table border="1" cellpadding="4" cellspacing="0" width="500px" id="table2" bordercolor="#328EBE">
        <tr>
          <td colspan="2" align="center" bgcolor="#328EBE" style="font-family:'华文新魏'; font-size:20px"> 个人信息查询</td>
        </tr>
        <tr>
          <td width="35%" align="right">工 号：</td>
          <td><?php echo @$row['teacherid'];?></td>
        </tr>
        <tr>
          <td width="35%" align="right">姓 名：</td> 
          <td><?php echo @$row['teachername'];?></td>
        </tr>
        <tr>
          <td width="35%" align="right">职 称：</td>
          <td><?php echo @$row['level'];?></td>
        </tr>        
        <tr>
          <td width="35%" align="right">手 机：</td>
          <td><?php echo @$row['tel'];?></td>
        </tr>		           
		<tr>
          <td colspan="2" align="center" bgcolor="#328EBE">&nbsp;</td>
        </tr>
    </table>
 
</body>
</html>
