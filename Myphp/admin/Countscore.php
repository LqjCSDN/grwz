<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<script src="../scripts/Com.js"></script>
<title>学生成绩统计</title>
<style type="text/css">
table {
	width: 90%;
	margin: 0 auto;
}

table,td {
	
	border-top-color: #0CF;
	border-right-color: #0CF;
	border-bottom-color: #0CF;
	border-left-color: #0CF;
}
</style>
<div style="Display:none">
<?php
	include "../Fun.php";
	include "../IsLogin.php";
?> 
</div>
</head>
<body>

<script type="text/javascript">
//选择专业改变班级
function change()
{
    var bms=document.form1.major.value; //bms的值为:班级序号|入学年份-班名|班级序号|入学年份-班名|
	if (bms=="") {window.alert("专业名不能为空");document.form1.major.focus();}
    var bm=bms.split("|"); //按"|"将bms分成若干子串，并依次存入数组中,bm的长度为"|"的个数+1；
    for(i=0;i<(bm.length-1)/2;i++)    //class1的value值为班级序号，text值为"入学年份-班名";
    { with(document.form1.class1) 
         {  length = (bm.length-1)/2+1;
	        options[i+1].value = bm[2*i]; 	
		    options[i+1].text =bm[2*i+1]; 
	      }   
      }
   
}  
function check()
{
	if (document.form1.class1.value=="")
	{
	   alert("请选择专业班！");
      document.form1.class1.focus();
      return false;
    }
	if (document.form1.term.value=="")
	{
	   alert("请选择学期！");
      document.form1.term.focus();
      return false;
    }
}
</script>
<form method="post" name="form1">
<div align="center">
<font style="font-family:'华文新魏'; font-size:20px"  >学生成绩统计</font>
    <br>
      <select name="major" id="major" onChange="change()" >
        <option value="" >请选择专业</option>
        <?php 
$sqlx="select distinct majorname from class";
$rs1=mysql_query($sqlx);
$row1=mysql_fetch_assoc($rs1);
//每取出一个专业,就输出其全部班级。
while($row1)
{   $zy=$row1["majorname"];
    $sqlx="select distinct classid,enrollyear,classname from class where majorname='$zy'";
	$rs2=mysql_query($sqlx);
	$row2=mysql_fetch_assoc($rs2); 
	$class="";
	while($row2)
	{  $class.=$row2["classid"]."|".$row2["enrollyear"]."-".$row2["classname"]."|";
	   $row2=mysql_fetch_assoc($rs2); 
	}
?>
        <option value="<?php echo $class;?>"><?php echo $row1["majorname"];?></option>
        <?php	
    $row1=mysql_fetch_assoc($rs1);
}
?>
      </select>
      <select name="class1" id="class1" >
        <option value="" selected>请选择班级</option>
      </select>
      <select name="term">
        <option value="">请选择学期</option>
        <?php
	    $array=getdate();
        $year=$array["year"];
		$month=$array["mon"];
		if ($month<=7) for($i=0;$i<3;$i++)
		{
			echo  "<option value='".($year-$i-1)."-".($year-$i)."(2)'>".($year-$i-1)."-".($year-$i)."(2)</option>";
			echo  "<option value='".($year-$i-1)."-".($year-$i)."(1)'>".($year-$i-1)."-".($year-$i)."(1)</option>";
	    }
		else  for($i=0;$i<3;$i++)
		{
			echo  "<option value='".($year-$i)."-".($year-$i+1)."(1)'>".($year-$i)."-".($year-$i+1)."(1)</option>";
			echo  "<option value='".($year-$i-1)."-".($year-$i)."(2)'>".($year-$i-1)."-".($year-$i)."(2)</option>";
		}			
	?>
      </select>
      <input name="search" type="submit" value="查询" onclick="return check()"/>
      <br />
  <?php
if(isset($_POST["search"]))
{
	$classid=$_POST["class1"];//获取班级序号
	$term=$_POST["term"];     //获取开课学期
	$rs0=mysql_query("select * from class where classid='$classid'");
	$row0=mysql_fetch_array($rs0);
	echo $row0["enrollyear"].$row0["majorname"].$row0["classname"]."&nbsp;&nbsp;".$term."学期学生成绩统计表";
	//取出指定班级的学生的学号、姓名
	$sql="select * from student where classid='$classid'";
	$rs1=mysql_query($sql);
	$xss=mysql_num_rows($rs1);  //算出班级学生数$xss
	$row1=mysql_fetch_array($rs1); //数组的键名可以是整数和字段名。
	$i=1;
	while($row1)
	{   //自动建立数组$no、$name
		$no[$i]=$row1["studentid"];
		$name[$i]=$row1["studentname"];
		$row1=mysql_fetch_array($rs1); 
		$i++;
	}
	
	// 取出指定学期、指定班级所开设的课程号、课程名。
	$sql="select distinct course.courseid,coursename from course,offercourse where course.courseid=offercourse.courseid and classid='$classid' and offerterm='$term'";
	$rs2=mysql_query($sql);
	$kcs=mysql_num_rows($rs2);  //算出课程门数$kcs
	if ($kcs==0) echo "<br><center>暂无记录</center>";
	else
	{
	   $row2=mysql_fetch_array($rs2);
	   //每选择一门课程，就取出指定学期、指定班级的学生该门课程的成绩
	  $i=1;   //$i表示课程序号
	  while($row2)
	  {  //自动建立数组$kcm
	     $kcm[$i]=$row2["coursename"];  //$kcm存放课程名。
	     $courseid=$row2["courseid"];
	     $sql="select * from score,student  where score.studentid=student.studentid  and classid='$classid' and offerterm='$term' and courseid='$courseid'";
	     $rs3=mysql_query($sql);
	     $row3=mysql_fetch_array($rs3);
	     $j=1;  //$j表示学生序号
	     while($row3)
	     {  
	       //自动建立数组$course
		   $course[$j][$i]=$row3["score"];  //第$j位学生第$i号课的成绩
		   $row3=mysql_fetch_array($rs3);
		   $j++;
		 }
		 $row2=mysql_fetch_array($rs2);
		 $i++;
	  }
	  //算出每位学生的总分、平均分
	  for($i=1;$i<=$xss;$i++)
	  {  //自动建立数组  
	     $sum[$i]=0;
		 $sum_sort[$i]=0;
	     for ($j=1;$j<=$kcs;$j++)
	     { $sum[$i]+=@$course[$i][$j]; //第$i位学生第$j号课的成绩
		   $sum_sort[$i]+=@$course[$i][$j];
		 }
	     $avg[$i]=round($sum[$i]/$kcs,1); //四舍五入到一位小数。
	  }
       rsort($sum_sort);  //对数组$sum_sort的值降序排序，数组的键名修改为从0开始的整数。
	   echo "<table width='100%' border='1' cellspacing='0'>";
       echo "<tr>";
       echo "<td align='center'>学号</td>";
       echo "<td align='center'>姓名</td>";
       for ($j=1;$j<=$kcs;$j++)   
          echo  "<td align='center' width='7%'>".$kcm[$j]."</td>";
       echo "<td align='center'>总分</td>";
       echo  "<td align='center'>平均分</td>";
       echo "<td align='center'>名次</td>";
       echo "</tr>";
	 
      for($i=1;$i<=$xss;$i++)
      {
        echo "<tr>";
        echo "<td align='center'>".$no[$i]."</td>";
        echo "<td align='center'>".$name[$i]."</td>";
        for ($j=1;$j<=$kcs;$j++)   
          echo  "<td align='center' width='7%'>". @$course[$i][$j]."</td>";
        echo "<td align='center'>".$sum[$i]."</td>";
        echo  "<td align='center'>". $avg[$i]."</td>";
        for ($j=0;$j<$xss;$j++)
		  if ($sum[$i]==$sum_sort[$j]) {
			  echo "<td align='center'>".($j+1)."</td>";
			  break;
		  }
        echo "</tr>";
	  }
      echo "</table>";

	}
}
?>    
</div>
</form>
</body>
</html>