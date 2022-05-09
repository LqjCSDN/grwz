<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../styles/com.css" rel="stylesheet" />
<script src="../scripts/Com.js"></script>
<style type="text/css">
table {
	width: 70%;
	margin: 0 auto;
}
</style>
<title>学生学籍查询</title>
</head>
<body>
<div style='Display:none'>
<?php
  include "../Fun.php";
  include "../IsLogin.php";
  $userid=$_SESSION["userid"];
?>
</div>
<script type="text/javascript">
function change()
{  
   //选择任教学期改变任教班级
   var bms=document.form1.term.value;
   if (bms=="") {window.alert("任教学期不能为空");document.form1.term.focus();}
   var bm=bms.split("|"); //按"|"将bms分成若干子串，并依次存入数组中,bm的长度为"|"的个数+1；
  
   for(i=0;i<(bm.length-1)/2;i++)  //class1的value值为班级序号，text值为"入学年份+专业名+班名";
   { with(document.form1.class1) 
     {  length = (bm.length-1)/2+1;
	    options[i+1].value = bm[2*i]; 	
		options[i+1].text =bm[2*i+1]; 
	 }   
   }
}
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

</script>

<form method="post" name="form1">
<div align="center">
<font style="font-family:'华文新魏'; font-size:20px"  >学生学籍查询</font><br>
<select name="term" id="term" onChange="change()">
    <option value="">请选择学期</option>
      <?php
			$sql="select distinct offerterm from offercourse where teacherid='$userid' order by offerterm desc";
			$rs1=mysql_query($sql);
			$row1=mysql_fetch_assoc($rs1); 
			//每读取一个学期，就能检索出自己在本学期任教的班级
			while($row1)
			{  $xq=$row1["offerterm"]; //开课学期
			   $sql="select distinct class.classid,enrollyear,majorname,classname  from class,offercourse where class.classid=offercourse.classid and teacherid='$userid' and offerterm='$xq'";
			   $rs2=mysql_query($sql);
			   $row2=mysql_fetch_assoc($rs2); 
			   $bxs="";  //班级序号
			   while($row2)
			   { $bxs.=$row2["classid"]."|".$row2["enrollyear"].$row2["majorname"].$row2["classname"]."|";
				 $row2=mysql_fetch_assoc($rs2); 
				}
	  ?>  
	<option value="<?php echo $bxs;?>"><?php echo $xq;?></option>
      <?php 
			   $row1=mysql_fetch_assoc($rs1); 
			    }
	  ?> 
</select>&nbsp;
<select name="class1" id="class1">
  <option value="">请选择任教班级</option>
</select>
<input name="search" type="submit" value="查询" onclick="return check()"/>
  <br />   
	<?php //只有按查询按钮或地址栏page有值，才能显示记录。
        if(isset($_REQUEST["search"])|| isset($_REQUEST["page"]))
        {  if(isset($_REQUEST["search"])) $_SESSION["classid"]=$_REQUEST["class1"];   
		   $sqlx="select * from class where classid='".$_SESSION["classid"]."'";
		   $rs3=mysql_query($sqlx);
	       $row3=mysql_fetch_assoc($rs3);
		   echo $row3["enrollyear"].$row3["majorname"].$row3["classname"]."学生名单";    
    	}
    ?>
<table>
<thead>
		<tr>
			<th width="20%">学号</th>
			<th width="20%">姓名</th>
			<th width="20%">性别</th>
			<th width="20%">出生日期</th>
			<th width="20%">总学分</th>
	  </tr>
</thead>
<?php 
if(isset($_REQUEST["search"])|| isset($_REQUEST["page"]))//只有按查询按钮或地址栏page有值，才能显示记录。
 {  
    $sql="select * from student where classid='".$_SESSION["classid"]."'";
    loadinfo($sql); 
 }

function loadinfo($sqlstr)
{
	$result=mysql_query($sqlstr);
	$total=mysql_num_rows($result);
	if (isset($_REQUEST["search"])) $page=1;     //每次按查询按钮,则从第1页开始显示.
	$page=isset($_REQUEST['page'])?intval($_REQUEST['page']):1;	//获取地址栏中page的值，不存在则设为1
	$num=15;                                     		//每页显示15条记录
	$url='Student.php';								    //本页URL
	//页码计算
	$pagenum=ceil($total/$num);							//获得总页数，ceil()返回不小于 x 的最小整数。
	$prepg=$page-1;										//上一页
	$nextpg=($page==$pagenum? 0: $page+1);		 		//下一页
	//limit m,n：从m+1号记录开始，共检索n条
	$new_sql=$sqlstr." limit ".($page-1)*$num.",".$num;	//按每页记录数生成查询语句
	$new_result=mysql_query($new_sql);
	if($new_row=@mysql_fetch_array($new_result))
	{   
		//若有查询结果，则以表格形式输出		
		do
		{
			list($id,$name,$pwd,$sex,$birthday,$classid,$credit)=$new_row;	//数组的键名为从0开始的连续整数。
			echo "<tr>";
			echo "<td width='20%'>$id</td>";			
			echo "<td width='20%'>$name</a></td>";
			echo "<td width='20%'>$sex</td>";	
			echo "<td width='20%'>$birthday</td>";			
			echo "<td width='20%'>$credit</td>";				
			echo "</tr>";  
		}while($new_row=mysql_fetch_array($new_result));
			//开始分页导航条代码
		 $pagenav="";
		if($prepg) //如果当前显示第一页，则不会出现 ”上一页“。
			$pagenav.="<a href='$url?page=$prepg'>上一页</a> "; 
		for($i=1;$i<=$pagenum;$i++)//$pagenum为总页数
		{
			if($page==$i)$pagenav.="<b><font color='#FF0000'>$i</font></b>&nbsp;";
			else $pagenav.=" <a href='$url?page=$i'>$i"."&nbsp;</a>"; 
		}
		if($nextpg)//如果当前显示最后一页，则不会出现 ”下一页“。
			$pagenav.=" <a href='$url?page=$nextpg'>下一页</a>"; 
		$pagenav.="&nbsp;&nbsp;共".$pagenum."页";
		//输出分页导航
		echo "<tr> <td colspan='5'>".$pagenav."</td></tr>";	 
	}
	else
		echo "<tr> <td colspan='5'>暂无记录</td></tr>";		
}

?>	
</table>
</div>
</form>
</body>
</html>