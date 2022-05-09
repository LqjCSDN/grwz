<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../styles/com.css" rel="stylesheet" />
<style type="text/css">
table {
	width: 70%;
	margin: 0 auto;
}

.table1 {
	border-top-color: #069;
	border-right-color: #069;
	border-bottom-color: #069;
	border-left-color: #069;
}
</style>
<script src="../scripts/Com.js"></script>
<title>成绩查询</title>
<div style="Display:none">
	<?php 
		include "../Fun.php";
		include "../IsLogin.php";
	?>
 </div> 
 
<script language="javascript">
function check()
{
	if(document.form1.term.value=="")
	{   alert("请选择学期！");
       	document.form1.term.focus();
		return false;
	}	
}
</script>
</head>
<body>
<form method="post" name="form1">
<div align="center"><font style="font-family:'华文新魏'; font-size:20px"  >成绩查询</font><br>
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
  &nbsp;
  <input type="submit" name="search"  value="查询" onclick="return check()"/>
  
<?php
if(isset($_POST["search"]))//点击查询专业信息
{	
	$userid=$_SESSION["userid"];
	$sql="select * from student where studentid='$userid'";
	$rs=mysql_query($sql);
	$row0=mysql_fetch_array($rs); 
	
	$term=$_POST["term"];
	$sql="select * from score,course where score.courseid=course.courseid and score.studentid='$userid' and offerterm='$term'";
	$result=mysql_query($sql);
	
	echo "<br />学号：".$userid."，姓名：".$row0["studentname"]."&nbsp;&nbsp;".$term."学期各课程成绩";

?>

<table class="table1">
        <thead>
		<tr>
			<th width="30%">课程号</th>
			<th width="50%">课程名称</th>
			<th width="20%">分数</th>
		</tr>
        </thead>
  <?php
  $count=0;
  $sum=0;
  $row=mysql_fetch_array($result);  //数组的键名可以是整数和字段名。
  while($row)
  {
  ?>
		<tr>
		  <td width="30%"><?php echo $row["courseid"];?></td>
		  <td width="50%"><?php echo $row["coursename"];?></td>
		  <td width="20%"><?php echo $row["score"];?></td>
	     </tr>   
  <?php
   $count+=1;
   $sum+=$row["score"];
   $row=mysql_fetch_array($result);  
   } 
   if ($count>0)
   {
   ?>
		<tr>
		  <td colspan="2">总分</td>
		  <td width="20%"><?php echo $sum; ?></td>
	  </tr>
        <tr>
		  <td colspan="2">平均分</td>
		  <td width="20%"><?php echo round($sum/$count,1); ?></td>
	  </tr>
   <?php
   }else{
   ?>     
   <tr> <td colspan="3">暂无记录</td> </tr>
  <?php } ?>
</table>
<?php
}
?>
</div>
</form>
</body>
</html>