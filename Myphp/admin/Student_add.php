<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>学生添加</title>
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
  $studentid=$_REQUEST["studentid"];
  $studentname=$_REQUEST["studentname"];
  $pwd=$_REQUEST["pwd"];
  $sex=@$_REQUEST["sex"];	//若未选中任何选项，则sex就不存在
  $birthday=$_REQUEST["birthday"];
  $classid=$_REQUEST["classid"]; //取得班级序号
  $credit=$_REQUEST["credit"];
  
  //若正则表达式含^、$，只有正则表达式与字符串完全匹配，该函数才返回1。
  $checkbirthday=preg_match('/^\d{4}-(0?\d|1?[012])-(0?\d|[12]\d|3[01])$/',$birthday);

  if($studentid=="") {$studentid1="必须输入学号！";$test=0;}
  elseif(preg_match('/^\d{10}$/',$studentid)==0)  {$studentid1="学号必须为10位数字！";$test=0;}
      else { $sql="select * from student where studentid='$studentid'";
	         $result=mysql_query($sql);
			 if (mysql_num_rows($result)>=1) {$studentid1="输入的学号已经存在,请重输!";$test=0;}
	       }
  if ($studentname=="") {$studentname1="必须输入姓名!";$test=0;}
  if ($pwd=="") {$pwd1="必须输入密码!";$test=0;}
  if ($sex=="") {$sex1="必须选择性别！";$test=0;}
  if ($birthday=="") { $birthday1="必须输入日期！";$test=0;}
  elseif ($checkbirthday==0) {$birthday1="日期必须为yyyy-mm-dd！";$test=0;}
  if ($classid=="") {$classid1="必须输入班级序号!";$test=0;}

  
  if ($test==1)  
  { if ($credit=="") $sql="insert into student(studentid,studentname,pwd,sex,birthday,classid) values('$studentid','$studentname','$pwd','$sex','$birthday','$classid')";
    else  $sql="insert into student values('$studentid','$studentname','$pwd','$sex','$birthday','$classid',$credit)";
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
          <td colspan="2" align="center" bgcolor="#328EBE"> 添加学生信息</td>
        </tr>
		<tr>
          <td width="25%" align="center" >学号</td>
          <td width="75%" align="left"><input type="text" name="studentid" id="studentid" /> 
           <font color="#FF0000">*</font><?php echo "<font size='2' color='FF0000'>".@$studentid1."</font>";?></td>
         
        </tr>
		<tr>
          <td width="25%" align="center">姓名</td>
          <td width="75%" align="left"><input type="text" name="studentname" id="studentname" /> 
            <font color="#FF0000">*</font><?php echo "<font size='2' color='FF0000'>".@$studentname1."</font>";?>
          </td>
        
        </tr>
        <tr>
          <td width="25%" align="center">密码</td>
          <td width="75%" align="left"><input type="password" name="pwd" size="24" value="" id="pwd"/>            
            <font color="#FF0000">*</font><?php echo "<font size='2' color='FF0000'>".@$pwd1."</font>";?> </td>
		 
        </tr>
		<tr>
          <td width="25%" align="center">性别</td>
          <td width="75%" align="left"><input type="radio" name="sex" id="radio" value="男" /> 
            男 &nbsp;&nbsp; <input type="radio" name="sex" id="radio2" value="女" />
            女&nbsp;<font color="#FF0000">*</font> <?php echo "<font size='2' color='FF0000'>".@$sex1."</font>";?></td>
		 
        </tr>
        <tr>
          <td align="center">出生日期</td>
          <td align="left"><input type="text" name="birthday" id="birthday" /> 
             <font color="#FF0000">*</font><?php echo "<font size='2' color='FF0000'>".@$birthday1."</font>";?></td>
        </tr>
        <tr>
          <td align="center">班级序号</td>
          <td align="left"><input name="classid" type="text" id="classid" value="<?php if (isset($_SESSION["classid"])) echo  $_SESSION["classid"]; else echo @$classid;?>" /> <font color="#FF0000">*</font><?php echo "<font size='2' color='FF0000'>".@$classid1."</font>";?></td>
        </tr>
        <tr>
          <td width="25%" align="center">总学分</td>
          <td width="75%" align="left"><input type="text" name="credit" size="25" value="" id="credit"/></td>
		 
        </tr>        <tr>
          <td colspan="2" align="center" bgcolor="#328EBE">
		  <input  type="submit" name="add" value="添加" />
		  <input  type="reset" name="back2" value="返回" onclick="location.href='student.php'"/>	
		  	  
		  </td>
        </tr>
      
    </table></form></td>
  </tr>
</table>

</body>
</html>
