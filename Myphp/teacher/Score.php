<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>学生成绩管理</title>
<style type="text/css">
<!--
a {
	text-decoration: none;
}
.table1 {
	background-color: #9CC;
}
-->
</style>
<script language="javascript">

function check()
{
	if(document.form1.term.value=="")
	{   alert("请选择任教学期！");
       	document.form1.term.focus();
		return false;
	}
	if(document.form1.class1.value=="")
	{   alert("请选择任教班级！");
       	document.form1.class1.focus();
		return false;
	}
}

function change1()
{  
   //选择任教学期改变任教班级
   var bms=document.form1.term.value;  //bms的值为：班级序号|入学年份+专业名+班名号|...|开课学期
   if (bms=="") {window.alert("任教学期不能为空");document.form1.term.focus();}
   var bm=bms.split("|"); //按"|"将bms分成若干子串，并依次存入数组中,bm的长度为"|"的个数+1；
  
   for(i=0;i<(bm.length-1)/2;i++)
   { with(document.form1.class1) 
     {  length = (bm.length-1)/2+1;
	    options[i+1].value = bm[2*i]; 	//下拉框class1的value值为班级序号
		options[i+1].text =bm[2*i+1];   // class1的text值为"入学年份+专业名+班名"
	 }   
   }
}


</script>

<div style='Display:none'>
<?php
	include "../Fun.php";
	include "../IsLogin.php";
	$userid=$_SESSION["userid"];
?>
</div>
</head>

<body>
<?php

if (isset($_REQUEST["save"]))
{ 
   $kch=$_REQUEST["kch"]; // $kch为变量名
   $xh=$_REQUEST["xh"];  //$xh为数组名
   $score=$_REQUEST["score"]; //$score为数组名
   for($i=0;$i<count($xh);$i++)
   { $sql="update score set score=".$score[$i]." where studentid='".$xh[$i]."' and courseid='$kch'";
     mysql_query($sql);
   }
   echo "<script>alert('保存成功!');</script>";

}

  $xq=@$_REQUEST["term"];   //$xq为：班级序号|入学年份+专业名+班名号|...|开课学期
  $bx=@$_REQUEST["class1"]; //$bx为班级序号
 
if ($xq!="" && $bx!="" ) 
{ 
   //取出选中学期，并存入$xq1中。
   $xqs=explode("|",$xq);//使用"|"，将字符串分为若干个子串，并存入数组中。数组的长度="|"的个数+1
   $xq1=$xqs[count($xqs)-1]; //$xq1为选中的学期，数组元素的最大下标为数组长度-1
  
   //取出班级序号对应的班级名称
   $sql="select * from class where classid='$bx'";
   $rs1=mysql_query($sql);
   $row1=mysql_fetch_assoc($rs1);
   $bm=$row1["enrollyear"].$row1["majorname"].$row1["classname"];
   
   //取出任课教师在指定学期、指定班级任教的课程号、课程名。
   $sql="select course.courseid, coursename from course,offercourse where course.courseid=offercourse.courseid and offerterm='$xq1' and classid='$bx' and teacherid='$userid'";
   $rs2=mysql_query($sql);
   $row2=mysql_fetch_assoc($rs2); //有些教师在一个班级可能同时任教两门课程.
   
   //检查任教班级中的学号、课程号在score表是否有相应记录，无则插入（包括：学号,课程号,开设学期）。
   $kchs="";  //为下拉框kch填充选项
   $kcms="";
   while($row2)
   {  
     $kch=$row2["courseid"];
	 $kcm=$row2["coursename"];
	 $kchs=$kchs.$kch."|";
	 $kcms=$kcms.$kcm."|";
	 
	
     $sql="select * from student where classid='$bx'";
	 $rs5=mysql_query($sql);
	 $row5=mysql_fetch_assoc($rs5);
	 while($row5)
	 {   $xh=$row5["studentid"];
	     $sql="select * from score where studentid='$xh' and courseid='$kch'";
		 $rs6=mysql_query($sql);
		 if (mysql_num_rows($rs6)==0) //若不存在指定学号、课程号的记录，则插入。
	     {
	       $sql="insert into score(studentid,courseid,offerterm) values('$xh','$kch','$xq1')";
		   mysql_query($sql);
	     }
		 $row5=mysql_fetch_assoc($rs5);
	  } 
	  $row2=mysql_fetch_assoc($rs2);//取出教师任教的下一门课	 
   }
  
  
  //显示指定班级、指定课程号的学生成绩。
   $a=explode('|',$kchs); //使用'|'，将字符串分为若干个子串，并存入数组$a中。
   $kch=@$_REQUEST["kch"];  //其中kch为下拉框的名称
   if ($kch=="") $kch=$a[0];
	 
   $sql="select * from student,score,course where student.studentid=score.studentid and score.courseid=course.courseid and classid='$bx' and course.courseid='$kch'";
   $rs7=mysql_query($sql);
   $count=mysql_num_rows($rs7);  //$count为结果集$rs7的总人数
  
   if ($count%2==0) 
   {  $part=$count/2;
      $rs7a=mysql_query($sql." limit 0,$part");//limit m,n：从m+1号记录开始，共检索n条记录
      $rs7b=mysql_query($sql." limit $part,$part");   
   } 
   else
   {  $part=(int)($count/2+1);
      $rs7a=mysql_query($sql." limit 0,$part");
      $rs7b=mysql_query($sql." limit $part,".($part-1));	 
   }
   $row7a=mysql_fetch_assoc($rs7a);	 
   $row7b=mysql_fetch_assoc($rs7b);	    
}

?>
<form id="form1" name="form1" method="post" action="score.php">
<center><font style="font-family:'华文新魏'; font-size:20px"  >学生成绩管理</font></center>
<table width="80%" border="0" align="center" cellpadding="0" cellspacing="0" >
      
      <tr>
        <td height="30">
          <table width="70%" border="1" align="center" cellpadding="0" cellspacing="0">
            <tr>
              <td height="25" align="center"><img src="../images/checkarticle.gif" width="15" height="15" /> &nbsp;
                <select name="term" onChange="change1()">
    <option value="">请选择学期</option>
      <?php
			$sql="select distinct offerterm from offercourse where teacherid='$userid' order by offerterm desc";
			$rs1=mysql_query($sql);
			$row1=mysql_fetch_assoc($rs1); 
			//每读取一个学期，就能检索出自己在本学期任教的班级
			while($row1)
			{  $xq2=$row1["offerterm"]; //$xq2为下拉框term的选项
			   $sql="select distinct class.classid,enrollyear,majorname,classname  from class,offercourse where class.classid=offercourse.classid and teacherid='$userid' and offerterm='$xq2'";
			   $rs2=mysql_query($sql);
			   $row2=mysql_fetch_assoc($rs2); 
			   $bxs="";  //班级序号
			   while($row2)
			   { $bxs.=$row2["classid"]."|".$row2["enrollyear"].$row2["majorname"].$row2["classname"]."|";
				 $row2=mysql_fetch_assoc($rs2); 
				}
	  ?>  
	<option value="<?php echo $bxs.$xq2;?>"><?php echo $xq2;?></option>
      <?php 
			   $row1=mysql_fetch_assoc($rs1); 
			    }
	  ?> 
</select>                &nbsp;&nbsp;
                              
                <select name="class1" id="class1">
                  <option value="">选择任教班级</option>
                  
                </select>
                &nbsp;
<input type="submit" name="search" id="search" value="查询" onclick="return check()"/></td>
            </tr>
          </table>
        </td>
      </tr>
      <?php 
      if ($xq!="" && $bx!="" ) 
	  {
	  ?>
      <tr>
        <td height="35" align="center"><font size="+2">汕头职业技术学院学生成绩登记表</font></td>
      </tr>
     
     
      <tr>
        <td height="255" valign="top">
          <table width="95%" border="0" align="center" cellpadding="2" cellspacing="0">
            <tr>
              <td width="38%">班级：<?php echo $bm;?></td>
              <td width="38%">科目：
                <select name="kch" id="kch" onchange="change2()">
                 
                <?php  
				$a=explode('|',$kchs);  //$a：存放课程号
				$b=explode('|',$kcms);  //$b：存放课程名
                for($i=0;$i<count($a)-1;$i++)
				{?>
                <option value="<?php echo $a[$i];?>" <?php if (strcmp($a[$i],$kch)==0) echo "selected";?> ><?php echo $b[$i];?></option>

                <?php
				}?>
                </select></td>
              <td width="24%">学期：<?php echo $xq1;?></td>
            </tr>
          </table>
          <table width="95%" border="1" align="center" cellpadding="0" cellspacing="0" class="table1">
            <tr>
              <td width="12%" align="center">序号</td>
              <td width="12%" height="20" align="center">学号</td>
              <td width="12%" height="20" align="center">姓名</td>
              <td width="14%" height="20" align="center">成绩</td>
              <td width="12%" align="center">序号</td>
              <td width="12%" height="20" align="center">学号</td>
              <td width="12%" height="20" align="center">姓名</td>
              <td width="14%" height="20" align="center">成绩</td>
            </tr>
            <?php
			$i=1;
			while($i<=$part)
			{ 
			?>
			
            <tr>
              <td width="12%" align="center"><?php echo $i;?></td>
              <td width="12%" height="20" align="center"><input name="xh[]" type="text" id="xh[]" value="<?php echo $row7a["studentid"];?>" size="10" readonly="true"  onKeyDown="return false;"/></td>
              <td width="12%" height="20" align="center"><?php echo $row7a["studentname"];?></td>
              <td width="14%" height="20" align="center"><input name="score[]" type="text" id="score[]" size="10" onKeyDown="if(event.keyCode==13) return false;" value="<?php echo $row7a["score"]; ?>"/></td>
              <td width="12%" align="center"><?php echo $i+$part;?></td>
              <td width="12%" height="20" align="center"><input name="xh[]" type="text" id="xh[]" value="<?php echo @$row7b["studentid"];?>" size="10" readonly="true" onKeyDown="return false;"/></td>
              <td width="12%" height="20" align="center"><?php echo @$row7b["studentname"];?></td>
              <td width="14%" height="20" align="center"><input name="score[]" type="text" id="score[]" size="10" onKeyDown="if(event.keyCode==13) return false;" value="<?php echo @$row7b["score"]; ?>" <?php if ($i>$count) echo "readonly";  ?>/></td>
            </tr>
            <?php
			$row7a=mysql_fetch_assoc($rs7a);	 
            $row7b=mysql_fetch_assoc($rs7b);	
			$i++;
			}
			?>
            <tr>
              <td height="20" colspan="8" align="center"><input type="submit" name="save" id="save" value="保存" /></td>
              </tr>
          </table>
       </td>
      </tr>
       <?php }?>
    </table>
 </form>
<script language="javascript">
 
function change2()
{  var x=document.form1.kch.value;
   window.location.replace("score.php?term=<?php echo $xq;?>&class1=<?php echo $bx;?>&kch="+x);
}
</script>
</body>
</html>