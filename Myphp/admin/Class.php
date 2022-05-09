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
</style>
<script src="../scripts/Com.js"></script>
<title>班级信息</title>
</head>
<body>
<form method="post" name="form1">
<div align="center">
<font style="font-family:'华文新魏'; font-size:20px"  >班级管理</font><br>

请输入专业名称：
    <input name="major" type="text" id="major" size="20" />
    <input name="search" type="submit" value="查询"/>
 
<table>
<thead>
  <tr>
    <th width="20%">班级序号</th>
    <th width="10%">入学年份</th>
    <th width="25%">专业名称</th>
    <th width="15%">班名</th>
    <th width="10%">班人数</th>
    <th width="20%">删除<input type='checkbox' id='CBox' onClick='checkall(this.form)'/></th>
  </tr>
 </thead>
 
<?php 
include "../Fun.php";  //连接数据库
include "../IsLogin.php";
function loadinfo($sqlstr)
{
	$result=mysql_query($sqlstr);
	$total=mysql_num_rows($result);   //记录总条数$total。
	if (isset($_REQUEST["search"])) $page=1;  //单击查询按钮，总是从第1页开始显示
	else $page=isset($_REQUEST['page'])?intval($_REQUEST['page']):1; //获取地址栏中page的值，若不存在则设为1。
	$num=10;                                     	  //每页显示10条记录
	$url='Class.php';								  //本页URL
	//页码计算
	$pagenum=ceil($total/$num);			              //获得总页数，ceil()返回不小于 x 的最小整数。
	//$page=min($pagenum,$page);						  //获当前页，min()取得较小数。
	$prepg=$page-1;									  //上一页
	$nextpg=($page==$pagenum? 0: $page+1);		 	  //下一页
	//limit m,n：从m+1号记录开始，共检索n条记录
	$new_sql=$sqlstr." limit ".($page-1)*$num.",".$num;	//按每页记录数生成查询语句
	$new_result=mysql_query($new_sql);
	
	if($new_row=@mysql_fetch_array($new_result))
	{   
		//若有查询结果，则以表格形式输出		
		do
		{
			list($classid,$enrollyear,$major,$classname,$num)=$new_row;	//数组的键名从0开始
			echo "<tr>";
			echo "<td width='20%'><a href='class_update.php?id=$classid' >$classid</a></td>";				
			echo "<td width='10%'>$enrollyear</td>";
			echo "<td width='25%'>$major</td>";
			echo "<td width='15%'>$classname</td>";
			echo "<td width='10%'>$num</td>";					
			echo "<td width='20%'><input type='checkbox' name='T_id[]' value='$classid' /></td>";
			echo "</tr>";  
		}while($new_row=mysql_fetch_array($new_result));
			//开始分页导航条代码
		 $pagenav="";
		if($prepg) //如果当前显示第一页，则不会出现 ”上一页“。
			$pagenav.="<a href='$url?page=$prepg'>上一页</a> "; 
		for($i=1;$i<=$pagenum;$i++)  //$pagenum为总页数
		{
			if($page==$i)$pagenav.="<b><font color='#FF0000'>$i</font></b>&nbsp;";
			else $pagenav.=" <a href='$url?page=$i'>$i"."&nbsp;</a>"; 
		}
		if($nextpg) //如果当前显示最后一页，则不会出现 ”下一页“。
			$pagenav.=" <a href='$url?page=$nextpg'>下一页</a>"; 
		$pagenav.="&nbsp;&nbsp;共".$pagenum."页";
		//输出分页导航
		echo "<tr> <td colspan='6' align='center'>".$pagenav."</td></tr>";	 
	}
	else
		echo "<tr> <td colspan='6' align='center'>暂无记录</td></tr>";		
}

if(isset($_POST["del"]))//点击删除按钮,删除所选数据并重新加载数据
{
	$id=@$_POST["T_id"];   //$id为数组名
	if(!$id) echo "<script>alert('请至少选择一条记录！');</script>";			
	else{
		   $num=count($id);							 //使用count函数取得数组中值的个数
		   for($i=0;$i<$num;$i++)						 //使用for循环删除所选数据
		   {  
			//若要删除班级序号为A的记录，除非[学生表]、[开课表]中尚没有班级序号为A的记录。
			   $sql="select * from student where classid='$id[$i]'";
			   $rs0=mysql_query($sql);
			   $sql="select * from offercourse where classid='$id[$i]'";
			   $rs1=mysql_query($sql);
			   if (mysql_num_rows($rs0)==0 &&  mysql_num_rows($rs1)==0)
			   {  $delsql="delete from class where classid='$id[$i]'";
			       mysql_query($delsql);   
			   }
		   }
		   echo "<script>alert('操作完成！');</script>";
		} 
}

$major=@$_REQUEST["major"];
if ($major=="") $sql="select * from class order by classid";
else $sql="select * from class where  majorname like '%".$major."%' order by classid";
loadinfo($sql); 


if(isset($_POST["add"]))//点击添加按钮转向班级增加页面
{
	echo "<script>location.href='class_add.php';</script>";
}
?>
    <tr>
    <td colspan="6" align="center"><input type="submit" name="add"  value="添加"/>&nbsp;&nbsp;&nbsp;&nbsp;<input type='submit' name="del"  value="删除" onClick="delcfm()"  />	</td>
    </tr>
</table>
</div>
</form>
</body>
</html>