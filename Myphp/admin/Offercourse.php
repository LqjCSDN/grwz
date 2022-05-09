<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../styles/com.css" rel="stylesheet" />
<script src="../scripts/Com.js"></script>
<style type="text/css">
table {
	width: 90%;
	margin: 0 auto;
}
</style>
<title>开课信息</title>
</head>
<body>
<div style="Display:none">
<?php
	include "../Fun.php";
	include "../IsLogin.php";
?>
</div>
<script type="text/javascript">
 function check()
{
	if (document.form1.major.value=="")
	{
	   alert("请选择专业！");
       document.form1.major.focus();
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
<font style="font-family:'华文新魏'; font-size:20px"  >开课表管理</font><br>

  <select name="major" id="major">
    <option value="">请选择专业</option>
    <?php 
        $sqlx="select distinct majorname from class";
        $rs1=mysql_query($sqlx);
        $row1=mysql_fetch_assoc($rs1);
		while($row1)
		{
			echo "<option value='".$row1["majorname"]."'>".$row1["majorname"]."</option>";
			$row1=mysql_fetch_assoc($rs1);
		}
	?>
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
</select>&nbsp;
  <input name="search" type="submit" value="查询" onclick="return check()"/>
  <br /> <?php //只有按查询按钮或地址栏page有值，才能显示记录。
        if(isset($_REQUEST["search"])|| isset($_REQUEST["page"]))
        {  if(isset($_REQUEST["search"]))
		   {  $_SESSION["major"]=$_REQUEST["major"]; 
			  $_SESSION["offerterm"]=$_REQUEST["term"];  
		   }
		   echo $_SESSION["offerterm"]."学期&nbsp;&nbsp;".$_SESSION["major"]."专业各班开课表"; 
    	}
    ?>

<table>
<thead>
		<tr>
			<th width="25%" align="center">班级名称</th>
			<th width="15%" align="center">课程号</th>	
			<th width="20%" align="center">课程名</th>		
			<th width="10%" align="center">周课时</th>
			<th width="10%" align="center">周数</th>
			<th width="10%" align="center">授课教师</th>
			
			<th width="10%" align="center">删除<input type='checkbox' id='CBox' onClick='checkall(this.form)'/></th>
		</tr>
</thead>
<?php 
if(isset($_REQUEST["del"]))//点击删除按钮,删除所选数据并重新加载数据
{
	$id=@$_REQUEST["T_id"];   //$id为数组名，每个元素为：'$classid-$courseid'。
	if(!$id) echo "<script>alert('请至少选择一条记录！');</script>";			
	else{
		  foreach($id as $x)   //$x的格式为'$classid-$courseid'
          {  $ch=explode("-",$x);       //使用"-"，将$x分为若干个子串，并存入数组中。
             $delsql="delete from offercourse where classid='".$ch[0]."' and courseid='".$ch[1]."'";
             mysql_query($delsql);      
           }  
		  echo "<script>alert('删除成功！');</script>";
		} 
	$sql="SELECT distinct class.classid,enrollyear,class.majorname,classname, course.courseid,coursename,weekhour,weeknum,teachername FROM offercourse,course,class,teacher WHERE offercourse.courseid=course.courseid and offercourse.classid=class.classid and teacher.teacherid=offercourse.teacherid and offerterm='".$_SESSION["offerterm"]."' and class.majorname='".$_SESSION["major"]."' order by class.classid";
	
    if (!isset($_REQUEST["page"])) loadinfo($sql); 
}


if(isset($_REQUEST["search"])|| isset($_REQUEST["page"]))//只有按查询按钮或地址栏page有值，才能显示记录。
 {  
    $sql="SELECT distinct class.classid,enrollyear,class.majorname,classname, course.courseid,coursename,weekhour,weeknum,teachername FROM offercourse,course,class,teacher WHERE offercourse.courseid=course.courseid and offercourse.classid=class.classid and teacher.teacherid=offercourse.teacherid and offerterm='".$_SESSION["offerterm"]."'  and class.majorname='".$_SESSION["major"]."' order by class.classid";
	loadinfo($sql); 
 }
function loadinfo($sqlstr)   
{                            
	$result=mysql_query($sqlstr);
	$total=mysql_num_rows($result);
	if (isset($_REQUEST["search"])) $page=1;     //每次按查询按钮,则从第1页开始显示.
	else $page=isset($_REQUEST['page'])?intval($_REQUEST['page']):1;	//获取地址栏中page的值，不存在则设为1
	
	$num=10;                                     //每页显示15条记录
	$url='Offercourse.php';							 //本页URL
	//页码计算
	$pagenum=ceil($total/$num);				      //获得总页数，ceil()返回不小于 x 的最小整数。
						
	$prepg=$page-1;								  //上一页
	$nextpg=($page==$pagenum? 0: $page+1);		  //下一页
	//limit m,n：从m+1号记录开始，共检索n条
	$new_sql=$sqlstr." limit ".($page-1)*$num.",".$num;	//按每页记录数生成查询语句
	$new_result=mysql_query($new_sql);
	if($new_row=@mysql_fetch_array($new_result))  //数组$new_row的键名可为整数或字段名。
	{   
		//若有查询结果，则以表格形式输出		
		do
		{
			list($classid,$enrollyear,$majorname,$classname,$courseid,$coursename,$weekhour,$weeknum,$teachername)=$new_row;	//数组的键名为从0开始的连续整数。
			echo "<tr>";
			echo "<td width='25%'>".$enrollyear.$majorname.$classname."</td>";			
			echo "<td width='15%'><a href='Offercourse_update.php?classid=$classid&courseid=$courseid'>$courseid</a></td>";
			echo "<td width='20%'>$coursename</td>"; 		
			echo "<td width='10%' >$weekhour</td>";		
			echo "<td width='10%' >$weeknum</td>";		
			echo "<td width='10%'>$teachername</td>";				
			echo "<td width='10%'><input type='checkbox' name='T_id[]' value='$classid-$courseid' /></td>";
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
		echo "<tr> <td colspan='7' align='center'>".$pagenav."</td></tr>";	 
	}
	else echo "<tr> <td colspan='7' align='center'>暂无记录</td></tr>";		
}

if(isset($_REQUEST["add"]))//点击添加按钮
{
	header("Location:Offercourse_add.php");
}

?>    
  <tr> 
			<td colspan='7' align="center"><input type='submit' name='add'  value='添加' />&nbsp;&nbsp;&nbsp;&nbsp;<input type='submit' name='del'  value='删除' onClick="delcfm()"  />	</td>
		</tr>	 
</table>
</div>
</form>
</body>
</html>