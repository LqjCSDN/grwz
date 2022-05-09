<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>教师修改</title>
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
$teacherid=$_REQUEST["id"]; //教工号是主键,不能修改。
if (isset($_REQUEST["update"]))
{
  $test=1;    //只要$test=0，则表单信息就无法提交
  
  $teachername=$_REQUEST["teachername"];
  $pwd=$_REQUEST["pwd"];
  $level=$_REQUEST["level"];
  $tel=$_REQUEST["tel"];
  //若正则表达式含^、$，只有正则表达式与字符串完全匹配，该函数才返回1。
 
  if ($teachername=="") {$teachername1="必须输入姓名!";$test=0;}
  if ($pwd=="") { $pwd1="必须输入密码!";$test=0;}
  if ($level=="") { $level1="必须选择职称!";$test=0;}
 
  if ($test==1)  
  {  $sql="update teacher set teachername='$teachername',pwd='$pwd',level='$level',tel='$tel' where teacherid='$teacherid'";
    mysql_query($sql);
    echo "<script language='javascript'> alert('修改成功!');</script>";
  }
}
$sql="select * from teacher where teacherid='$teacherid'";
$result=mysql_query($sql);
$row=mysql_fetch_assoc($result);
$teachername=$row["teachername"];
$pwd=$row["pwd"];
$level=$row["level"];
$tel=$row["tel"];
?>
<table border="0" cellpadding="0" cellspacing="0" width="100%" id="table1">
  <tr>
    <td> <form action="" method="post" name="form1" >
    <table border="1" cellpadding="4" cellspacing="0" width="500px" id="table2" bordercolor="#328EBE">
     
        <tr>
          <td colspan="2" align="center" bgcolor="#328EBE"> 修改教师信息</td>
        </tr>
		<tr>
          <td width="25%" align="center" >教工号</td>
          <td width="75%" align="left"><input name="id" type="text" id="id" readonly="readonly" value="<?php echo $teacherid;?>"/></td>
         
        </tr>
		<tr>
          <td width="25%" align="center">姓名</td>
          <td width="75%" align="left"><input type="text" name="teachername" id="teachername" value="<?php echo $teachername;?>"/>            
            <font color="#FF0000">*</font><?php echo "<font size='2' color='FF0000'>".@$teachername1."</font>";?>
          </td>
        
        </tr>
        <tr>
          <td width="25%" align="center">密码</td>
          <td width="75%" align="left"><input type="password" name="pwd" id="pwd" value="<?php echo $pwd;?>"/> 
            <font color="#FF0000">*</font><?php echo "<font size='2' color='FF0000'>".@$pwd1."</font>";?> </td>
		 
        </tr>
		<tr>
          <td width="25%" align="center">职称</td>
          <td width="75%" align="left"><select name="level" id="level">
            <option value="">请选择职称</option>
            <option  <?php if ($level=="助教") echo "selected";?>>助教</option>
            <option  <?php if ($level=="讲师") echo "selected";?>>讲师</option>
            <option  <?php if ($level=="副教授") echo "selected";?>>副教授</option>
            <option <?php if ($level=="教授") echo "selected";?>>教授</option>
          </select> 
            <font color="#FF0000">*</font><?php echo "<font size='2' color='FF0000'>".@$level1."</font>";?></td>
		 
        </tr>
        <tr>
          <td width="25%" align="center">手机</td>
          <td width="75%" align="left"><input type="text" name="tel" size="25"  id="tel" value="<?php echo $tel;?>"/></td>
		 
        </tr>        <tr>
          <td colspan="2" align="center" bgcolor="#328EBE">
		  <input  type="submit" name="update" value="修改" id="update" />
		  <input  type="reset" name="back2" value="返回" onclick="location.href='teacher.php'"/>	
		  	  
		  </td>
        </tr>
      
    </table></form></td>
  </tr>
</table>

</body>
</html>
