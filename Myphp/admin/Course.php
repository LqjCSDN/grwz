<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../styles/com.css" rel="stylesheet" />
<style type="text/css">
table {
	width: 70%;
	margin: 0 auto;
}
</style>
<div style='Display:none'>
<?php
	include "../Fun.php"; //选择数据库
	include "../IsLogin.php";
?>
</div>
<script src="../scripts/Com.js"></script>
<script language="javascript">
function checkmajor()
{
	if (document.form1.major.value=="")
	{
	   alert("请选择专业！");
      document.form1.major.focus();
      return false;
    }
}
</script>

</head>
<body>
<form method="post" name="form1">
<div align="center">

<font style="font-family:'华文新魏'; font-size:20px"  >课程设置管理</font><br />

  <select name="major" id="major">
    <option value="">请选择专业</option>
    <?php
$sqlx="select distinct majorname from class";
$rs0=mysql_query($sqlx);
$row1=mysql_fetch_assoc($rs0);
while($row1)
{  echo "<option value='".$row1["majorname"]."'>".$row1["majorname"]."</option>";
   $row1=mysql_fetch_assoc($rs0);
}
?>
  </select>&nbsp;<input name="search" type="submit" value="查询" onclick="return checkmajor()"/>
<br />   
<?php
//只有按查询按钮或地址栏page有值，才能显示记录。
 if(isset($_REQUEST["search"])|| isset($_REQUEST["page"]))
 {
	 if(isset($_REQUEST["search"])) $_SESSION["major"]=$_REQUEST["major"];   
	 echo $_SESSION["major"]."专业课程设置";  
 }
?>
<table>
<thead>
 <tr>
    <th width="25%">课程号</th>
    <th width="30%">课程名</th>
    <th width="15%">总课时</th>
    <th width="15%">学分</th>
    <th width="15%">删除<input type='checkbox' id='CBox' onClick='checkall(this.form)'/></th>
  </tr>
</thead>
<?php 
if(isset($_REQUEST["del"]))//点击删除按钮,删除所选数据并重新加载数据
{
	$id=@$_REQUEST["T_id"];   //$id为数组名
	if(!$id) echo "<script>alert('请至少选择一条记录！');</script>";			
	else{
			$num=count($id);							 //使用count函数取得数组中值的个数
			for($i=0;$i<$num;$i++)						 //使用for循环删除所选数据
			{  //若要删除课程号为A的记录，除非 [开课表]、[成绩表]中尚没有课程号为A的记录。
			   $sql="select * from offercourse where  courseid='$id[$i]'";
			   $rs1=mysql_query($sql);
			   $sql="select * from score where  courseid='$id[$i]'";
			   $rs2=mysql_query($sql);
			   if (mysql_num_rows($rs1)==0 && mysql_num_rows($rs2)==0) 
			   {
			     $delsql="delete from course where courseid='$id[$i]'";
			     mysql_query($delsql);    
			   }
			}
			echo "<script>alert('操作完成！');</script>";
		} 
	 $sql="select * from course where majorname='公共课' or majorname='".$_SESSION["major"]."' order by courseid";
    if (!isset($_REQUEST["page"])) loadinfo($sql); 
}

if(isset($_REQUEST["search"])|| isset($_REQUEST["page"]))//只有按查询按钮或地址栏page有值，才能显示记录。
 {  
     $sql="select * from course where majorname='公共课' or majorname='".$_SESSION["major"]."' order by courseid";
    loadinfo($sql); 
 }
function loadinfo($sqlstr)
{
	$result=mysql_query($sqlstr);
	$total=mysql_num_rows($result);
	if (isset($_REQUEST["search"])) $page=1;     //每次按查询按钮,则从第1页开始显示.
	else $page=isset($_REQUEST['page'])?intval($_REQUEST['page']):1;	//获取地址栏中page的值，不存在则设为1
	$num=15;                                     		//每页显示15条记录
	$url='Course.php';								    //本页URL
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
			list($id,$cname,$period,$credit)=$new_row;	//数组的键名为从0开始的连续整数。
			echo "<tr>";
			echo "<td width='25%'><a href='Course_update.php?id=$id'>$id</a></td>";
			echo "<td width='30%'>$cname</td>";
			echo "<td width='15%'>$period</td>";
			echo "<td width='15%'>$credit</td>";					
			echo "<td width='15%'><input type='checkbox' name='T_id[]' value='$id' /></td>";
			echo "</tr>";  
		}while($new_row=mysql_fetch_array($new_result));
			//开始分页导航条代码
		$pagenav="";
		if($prepg)  //如果当前显示第一页，则不会出现 ”上一页“。
			$pagenav.="<a href='$url?page=$prepg'>上一页</a> "; 
			
		for($i=1;$i<=$pagenum;$i++)//$pagenum为总页数
		{
			if($page==$i)$pagenav.="<b><font color='#FF0000'>$i</font></b>&nbsp;";
			else $pagenav.=" <a href='$url?page=$i'>$i"."&nbsp;</a>"; 
		}
		
		if($nextpg) //如果当前显示最后一页，则不会出现 ”下一页“。
			$pagenav.=" <a href='$url?page=$nextpg'>下一页</a>"; 
		$pagenav.="&nbsp;&nbsp;共".$pagenum."页";
		//输出分页导航
		echo "<tr> <td colspan='5' align='center'>".$pagenav."</td></tr>";	 
	}
	else
		echo "<tr> <td colspan='5' align='center'>暂无记录</td></tr>";		
}
 
 
if(isset($_REQUEST["add"]))//点击添加按钮转向班级增加页面
{
	echo "<script>location.href='Course_add.php';</script>";
}

?>
        <tr> 
			<td colspan="5" align="center"><input type='submit' name='add'  value='添加' />&nbsp;&nbsp;&nbsp;&nbsp;<input type='submit' name='del'  value='删除' onClick="delcfm()"  />	</td>
		</tr>	
</table>
</div>
</form>
</body>
</html>