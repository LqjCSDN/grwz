<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>开课表录入</title>
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
<script type="text/javascript">
//选择班级序号改变课程
function change()
{
    var kcs=document.form1.classid.value;
	if (kcs=="") {window.alert("班级序号不能为空");document.form1.classid.focus();}
    var kc=kcs.split("|"); //按"|"将kcs分成若干子串，并依次存入数组中,kc长度为"|"的个数+1；
    for(i=0;i<(kc.length-1)/2;i++)    //courseid的value值为课程号，text值为课程名;
    { with(document.form1.courseid) 
         {  length = (kc.length-1)/2+1; //原有一个选项。
	        options[i+1].value = kc[2*i]; 	
		    options[i+1].text =kc[2*i+1]; 
	      }   
      }
   
}  
</script>
</head>

<body>
<?php 
if (isset($_REQUEST["add"]))
{
  $test=1;    //只要$test=0，则表单信息就无法提交
  $id=explode("|",$_REQUEST["classid"]);//使用"|"，将字符串分为若干个子串，并存入数组中。
  foreach($id as $x) $classid=$x;
  $courseid=$_REQUEST["courseid"];
  $weekhour=$_REQUEST["weekhour"];
  $weeknum=$_REQUEST["weeknum"];
  $offerterm=$_REQUEST["offerterm"];
  $teacherid=$_REQUEST["teacherid"];
  //若正则表达式含^、$，只有正则表达式与字符串完全匹配，该函数才返回1。
 
  if ($classid=="") {$classid1="必须选择班级序号!";$test=0;}
  if ($courseid=="") {$courseid1="必须选择课程名称!";$test=0;}
  if ($test==1)
  {  $sql="select * from offercourse where classid='$classid' and courseid='$courseid'";
	 $result=mysql_query($sql);
     if (mysql_num_rows($result)>=1) {$courseid1="选择的课程名称已经存在,请重选!";$test=0;}
  }
  if ($weekhour=="") {$weekhour1="必须输入周课时!";$test=0;}
  elseif(preg_match('/^\d{1,2}$/',$weekhour)==0)  {$weekhour1="周课时必须为整数！";$test=0;}
  
  if ($weeknum=="") {$weeknum1="必须输入周数!";$test=0;}
  elseif(preg_match('/^\d+(\.\d)?$/',$weeknum)==0) {$weeknum1="周数只能整数或保留一位小数！";$test=0;}
  if ($offerterm=="") {$offerterm1="必须选择开设学期!";$test=0;}
 
  if ($test==1)  
  { $sql="insert into offercourse values('$classid','$courseid',$weekhour,$weeknum,'$offerterm','$teacherid')";
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
          <td colspan="2" align="center" bgcolor="#328EBE"> 录入开课表</td>
        </tr>
		
		<tr>
          <td width="25%" align="center" >班级序号</td>
          <td width="75%" align="left"><select name="classid" id="classid" onChange="change()">
            <option value="">请选择班级序号</option>
<?php
	//在php脚本中嵌入javascript语句
	$sqlx="select * from class order by  classid";
	$rs1=mysql_query($sqlx);
    $row1=mysql_fetch_assoc($rs1); 
	//每取出一个班级序号,就查看其专业,并输出其全部课程。
    while($row1)
    {  $zy=$row1["majorname"];
       $sqlx="select distinct courseid,coursename from course where majorname='$zy' or majorname='公共课'";
	   $rs2=mysql_query($sqlx);
	   $row2=mysql_fetch_assoc($rs2); 
	   $course="";
	   while($row2)
	   {  
	       $course.=$row2["courseid"]."|".$row2["coursename"]."|";
	       $row2=mysql_fetch_assoc($rs2); 
	    }
       echo "<option value='".$course.$row1["classid"]."'>".$row1["classid"]."</option>";
	   $row1=mysql_fetch_assoc($rs1);
    }
?>
          </select> 
         
            <font color="#FF0000">*</font><?php echo "<font size='2' color='FF0000'>".@$classid1."</font>";?>
            </td>
         
        </tr>
		<tr>
        
          <td width="25%" align="center">课程名称</td>
          <td width="75%" align="left"><select name="courseid" id="courseid">
            <option value="">请选择课程</option>
          </select> 
           <font color="#FF0000">*</font><?php echo "<font size='2' color='FF0000'>".@ $courseid1."</font>";?>
          </td>
        
        </tr>
        <tr>
          <td width="25%" align="center" >周课时</td>
          <td width="75%" align="left"><input type="text" name="weekhour" size="20" value="" id="weekhour"/> 
           <font color="#FF0000">*</font><?php echo "<font size='2' color='FF0000'>".@$weekhour1."</font>";?> </td>
		 
        </tr>
		<tr>
          <td width="25%" align="center">周数</td>
          <td width="75%" align="left"><input name="weeknum" type="text" id="weeknum" size="20" /> 
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
		{
			echo  "<option>".($year-$i-1)."-".($year-$i)."(2)</option>";
			echo  "<option>".($year-$i-1)."-".($year-$i)."(1)</option>";
	    }
		else  for($i=0;$i<3;$i++)
		{
			echo  "<option>".($year-$i)."-".($year-$i+1)."(1)</option>";
			echo  "<option>".($year-$i-1)."-".($year-$i)."(2)</option>";
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
			   {  echo "<option value='".$row1["teacherid"]."'>". $row1["teacherid"]." ".$row1["teachername"]."</option>";
                 $row1=mysql_fetch_assoc($rs1); 
			   }
			 ?>
          </select></td>
		 
        </tr>        <tr>
          <td colspan="2" align="center" bgcolor="#328EBE">
		  <input  type="submit" name="add" value="添加" />
		  <input  type="reset" name="back2" value="返回" onclick="location.href='Offercourse.php'"/>	
		  	  
		  </td>
        </tr>
      
    </table></form></td>
  </tr>
</table>

</body>
</html>
