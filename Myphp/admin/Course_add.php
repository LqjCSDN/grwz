<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>课程添加</title>
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
  $courseid=$_REQUEST["courseid"];
  $coursename=$_REQUEST["coursename"];
  $period=$_REQUEST["period"];
  $credit=$_REQUEST["credit"];	
  $majorname=$_REQUEST["majorname"];
 
  //若正则表达式含^、$，只有正则表达式与字符串完全匹配，该函数才返回1。
   if($courseid=="") {$courseid1="必须输入课程号！";$test=0;}
   elseif(preg_match('/^\d{8}$/',$courseid)==0)  {$courseid1="课程号必须为8位数字！";$test=0;}
      else { $sql="select * from course where courseid='$courseid'";
	         $result=mysql_query($sql);
			 if (mysql_num_rows($result)>=1) {$courseid1="输入的课程号已经存在,请重输!";$test=0;}
	       }
  if ($coursename=="") {$coursename1="必须输入课程名!";$test=0;}
  if ($period=="") {$period1="必须输入学时!";$test=0;}
  elseif (preg_match('/^\d{1,3}$/',$period)==0){$period1="学时必须为1-3位数字！";$test=0;}
  
  if ($credit=="") {$credit1="必须选择学分！";$test=0;}
  if ($majorname=="") { $majorname1="必须选择专业名称或公共课！";$test=0;}
    
  if ($test==1)  
  { $sql="insert into course values('$courseid','$coursename',$period,$credit,'$majorname')";
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
          <td colspan="2" align="center" bgcolor="#328EBE"> 添加课程信息</td>
        </tr>
		<tr>
          <td width="25%" align="center" >课程号</td>
          <td width="75%" align="left"><input type="text" name="courseid" id="courseid" /> 
           <font color="#FF0000">*</font><?php echo "<font size='2' color='FF0000'>".@$courseid1."</font>";?></td>
         
        </tr>
		<tr>
          <td width="25%" align="center">课程名</td>
          <td width="75%" align="left"><input type="text" name="coursename" id="coursename" /> 
            <font color="#FF0000">*</font><?php echo "<font size='2' color='FF0000'>".@$coursename1."</font>";?>
          </td>
        
        </tr>
        <tr>
          <td width="25%" align="center">总课时</td>
          <td width="75%" align="left"><input type="password" name="period" size="24" value="" id="period"/>            
            <font color="#FF0000">*</font><?php echo "<font size='2' color='FF0000'>".@$period1."</font>";?> </td>
		 
        </tr>
		<tr>
          <td width="25%" align="center">学分</td>
          <td width="75%" align="left"><select name="credit" id="credit">
            <option value="">请选择学分</option>
            <option>1</option>
            <option>2</option>
            <option>3</option>
            <option>4</option>
            <option>5</option>
            <option>6</option>
            <option>7</option>
            <option>8</option>
          </select>            &nbsp;<font color="#FF0000">*</font> <?php echo "<font size='2' color='FF0000'>".@$credit1."</font>";?></td>
		 
        </tr>
        <tr>
          <td align="center">专业</td>
          <td align="left"><select name="majorname" id="majorname">
            <option value="">请选择专业</option>
      <?php
      $sqlx="select distinct majorname from class";
      $rs1=mysql_query($sqlx);
      $row1=mysql_fetch_assoc($rs1);
      while($row1)
      {  echo "<option>".$row1["majorname"]."</option>";
         $row1=mysql_fetch_assoc($rs1);
       }
      ?>
            <option>公共课</option>
          </select>            <font color="#FF0000">*</font><?php echo "<font size='2' color='FF0000'>".@$majorname1."</font>";?></td>
        </tr>        <tr>
          <td colspan="2" align="center" bgcolor="#328EBE">
            <input  type="submit" name="add" value="添加" />
            <input  type="reset" name="back2" value="返回" onclick="location.href='course.php'"/>	
            
            </td>
        </tr>
      
    </table></form></td>
  </tr>
</table>

</body>
</html>
