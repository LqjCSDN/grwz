<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>开课表修改</title>
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
$classid=$_REQUEST["classid"];  //班级序号、课程号是主键,不能修改。
$courseid=$_REQUEST["courseid"];
if (isset($_REQUEST["update"]))
{
  $test=1;    //只要$test=0，则表单信息就无法提交
  $weekhour=$_REQUEST["weekhour"];
  $weeknum=$_REQUEST["weeknum"];
  $offerterm=$_REQUEST["offerterm"];
  $teacherid=$_REQUEST["teacherid"];
  //若正则表达式含^、$，只有正则表达式与字符串完全匹配，该函数才返回1。

  if ($weekhour=="") {$weekhour1="必须输入周课时!";$test=0;}
  elseif(preg_match('/^\d{1,2}$/',$weekhour)==0)  {$weekhour1="周课时必须为整数！";$test=0;}
  
  if ($weeknum=="") {$weeknum1="必须输入周数!";$test=0;}
  elseif(preg_match('/^\d+(\.\d)?$/',$weeknum)==0) {$weeknum1="周数只能整数或保留一位小数！";$test=0;}
  if ($offerterm=="") {$offerterm1="必须选择开设学期!";$test=0;}
 
  if ($test==1)  
  {  $sql="update Offercourse set weekhour=$weekhour,weeknum=$weeknum,offerterm='$offerterm',teacherid='$teacherid' where  
  classid='$classid' and courseid='$courseid'";
     mysql_query($sql);
     echo "<script language='javascript'> alert('删除成功!');</script>";
  }
}

$sql="select * from offercourse where classid='$classid' and courseid='$courseid'";
$result=mysql_query($sql);
$row=mysql_fetch_assoc($result);
$weekhour=$row["weekhour"];
$weeknum=$row["weeknum"];
$offerterm=$row["offerterm"];
$teacherid=$row["teacherid"];
?>
<table border="0" cellpadding="0" cellspacing="0" width="100%" id="table1">
  <tr>
    <td> <form action="" method="post" name="form1" >
    <table border="1" cellpadding="4" cellspacing="0" width="500px" id="table2" bordercolor="#328EBE">
     
        <tr>
          <td colspan="2" align="center" bgcolor="#328EBE"> 修改开课表</td>
        </tr>
		
		<tr>
          <td width="25%" align="center" >班级序号</td>
          <td width="75%" align="left"><input name="classid" type="text" id="classid" value="<?php  echo $classid;?>" readonly="readonly" /></td>
         
        </tr>
		<tr>
        
          <td width="25%" align="center">课程名称</td>
          <td width="75%" align="left"><input name="courseid" type="text" id="courseid" value="<?php echo $courseid;?>" readonly="readonly" /></td>
        
        </tr>
        <tr>
          <td width="25%" align="center" >周课时</td>
          <td width="75%" align="left"><input type="text" name="weekhour" size="20" value="<?php echo $weekhour;?>" /> 
           <font color="#FF0000">*</font><?php echo "<font size='2' color='FF0000'>".@$weekhour1."</font>";?> </td>
		 
        </tr>
		<tr>
          <td width="25%" align="center">周数</td>
          <td width="75%" align="left"><input name="weeknum" type="text" id="weeknum" value="<?php echo $weeknum;?>" size="20" /> 
           <font color="#FF0000">*</font><?php echo "<font size='2' color='FF0000'>".@$weeknum1."</font>";?></td>
		 
        </tr>
        <tr>
		  <td align="center" >开课学期</td>
		  <td align="left"><select name="offerterm" id="offerterm">
          <option value="">请选择学期</option>
    <?php
	    $array=getdate();
        $year=$array["year"];
		$month=$array["mon"];
		if ($month<=7) for($i=0;$i<3;$i++)
		{   if ($offerterm==($year-$i-1)."-".($year-$i)."(2)")
			     echo  "<option  selected>".($year-$i-1)."-".($year-$i)."(2)</option>";
			else echo  "<option>".($year-$i-1)."-".($year-$i)."(2)</option>";
			if ($offerterm==($year-$i-1)."-".($year-$i)."(1)")
			     echo  "<option selected>".($year-$i-1)."-".($year-$i)."(1)</option>";
			else  echo  "<option>".($year-$i-1)."-".($year-$i)."(1)</option>";
	    }
		else  for($i=0;$i<3;$i++)
		{
			if ($offerterm==($year-$i)."-".($year-$i+1)."(1)")
			     echo  "<option selected>".($year-$i)."-".($year-$i+1)."(1)</option>";
			else echo  "<option>".($year-$i)."-".($year-$i+1)."(1)</option>";
			if ($offerterm==($year-$i-1)."-".($year-$i)."(2)")
			     echo  "<option selected>".($year-$i-1)."-".($year-$i)."(2)</option>";
			else echo  "<option>".($year-$i-1)."-".($year-$i)."(2)</option>";
		}			
	?>
		    </select> 
		    <font color="#FF0000">*</font><?php echo "<font size='2' color='FF0000'>".@$offerterm1."</font>";?></td>
		  </tr>
        <tr>
          <td width="25%" align="center">任课教师</td>
          <td width="75%" align="left"><select name="teacherid" id="teacherid">
            <option value="">请选择教师</option>
             <?php
			   $sql="select * from teacher order by  teacherid,teachername";
			   $rs1=mysql_query($sql);
               $row1=mysql_fetch_assoc($rs1); 
			   while($row1)
			   {  if (strcmp($teacherid,$row1["teacherid"])==0)
			         echo "<option value='".$row1["teacherid"]."' selected>". $row1["teacherid"]." ".$row1["teachername"]."</option>";
			     else  echo "<option value='".$row1["teacherid"]."'>". $row1["teacherid"]." ".$row1["teachername"]."</option>";
                 $row1=mysql_fetch_assoc($rs1); 
			   }
			 ?>
          </select></td>
		 
        </tr>        <tr>
          <td colspan="2" align="center" bgcolor="#328EBE">
		  <input  type="submit" name="update" value="修改" id="update" />
		  <input  type="reset" name="back2" value="返回" onclick="location.href='Offercourse.php'"/>	
		  	  
		  </td>
        </tr>
      
    </table></form></td>
  </tr>
</table>

</body>
</html>
