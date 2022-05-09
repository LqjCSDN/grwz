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
<title>教师信息</title>
</head>
<body>
<form method="post">
<div align="center">
<font style="font-family:'华文新魏'; font-size:20px"  >教师管理</font><br>

  请输入姓名：
  <input type="text" name="name" />
  <input name="search" type="submit" value="查询"/>  
<table>
  <thead>
		<tr>
			<th width="20%">工号</th>
			<th width="15%">姓名</th>
			<th width="15%">密码</th>
			<th width="15%">职称</th>
		  <th width="20%">联系电话</th>
		  <th width="15%">删除<input type='checkbox' id='CBox' onClick='checkall(this.form)'/></th>
	    </tr>
</thead>	
<?php 
include "../Fun.php";
include "../IsLogin.php";
function loadinfo($sqlstr)
{
	$result=mysql_query($sqlstr);						//查询数据库
	$total=mysql_num_rows($result);						//获取所查询记录的总数
	if (isset($_REQUEST["search"])) $page=1;
	else $page=isset($_GET['page'])?intval($_GET['page']):1;	//获取地址栏中page的值，不存在则设为1
	$num=10;                                     		//每页显示10条记录
	$url='Teacher.php';								    //本页URL
	//页码计算
	$pagenum=ceil($total/$num);						//获得总页数，ceil()返回不小于 x 的最小整数页
    $prepg=$page-1;										//上一页
	$nextpg=($page==$pagenum? 0: $page+1);		 		//下一页
	$new_sql=$sqlstr." limit ".($page-1)*$num.",".$num;	//按每页记录数生成查询语句
	$new_result=mysql_query($new_sql);
	if($new_row=@mysql_fetch_array($new_result))
	{   
		//若有查询结果，则以表格形式输出		
		do
		{
			list($id,$name,$pwd,$level,$tel)=$new_row;	//数组的键名从0开始
			echo "<tr>";
			echo "<td width='20%'><a href='teacher_update.php?id=$id'>$id</a></td>";			
			echo "<td width='15%'>$name</td>";
			echo "<td width='15%'>$pwd</td>";
			echo "<td width='15%'>$level</td>";
			echo "<td width='20%'>$tel</td>";					
			echo "<td width='15%'><input type='checkbox' name='T_id[]' value='$id' /></td>";
			echo "</tr>";  
		}while($new_row=mysql_fetch_array($new_result));
		//开始分页导航条代码
		 $pagenav="";
		if($prepg) 
			$pagenav.="<a href='$url?page=$prepg'>上一页</a> "; 
		for($i=1;$i<=$pagenum;$i++)
		{
			if($page==$i)$pagenav.="<B><font color='#FF0000'>$i</font></B>&nbsp;";
			else $pagenav.=" <a href='$url?page=$i'>$i"."&nbsp;</a>"; 
		}
		if($nextpg)
			$pagenav.=" <a href='$url?page=$nextpg'>下一页</a>"; 
		$pagenav.="&nbsp;&nbsp;共".$pagenum."页";
		//输出分页导航
		echo "<tr> <td colspan='6' align='center' >".$pagenav."</td></tr>";	 
	}
	else
		echo "<tr> <td colspan='6' align='center'>暂无记录</td></tr>";		
}

if(isset($_POST["del"]))//点击删除按钮,删除所选数据并重新加载数据
{
	 $id=@$_POST["T_id"];  //$id为数组名
	 if(!$id) echo "<script>alert('请至少选择一条记录！');</script>";			
	 else{
			$num=count($id);							 //使用count函数取得数组中值的个数
			for($i=0;$i<$num;$i++)						 //使用for循环删除所选数据
			{  //若要删除教工号为A的教师，除非[开课表]中没有教工号为A的任教信息。
			   $sql="select * from offercourse where teacherid='$id[$i]'";
			   $rs0=mysql_query($sql);
			   if (mysql_num_rows($rs0)==0)
			   {
			      $delsql="delete from teacher where teacherid='$id[$i]'";
			      mysql_query($delsql);   
			    }
			}
			echo "<script>alert('操作完成！');</script>";
		} 
}
	
$sql="select * from teacher";
$sql=$sql." where teachername like '%".@$_POST["name"]."%' order by teacherid";
loadinfo($sql);	//加载显示数据

if(isset($_POST["add"]))//点击添加按钮转教师添加、修改页面。
{
		echo "<script>location.href='teacher_add.php';</script>";
}
	
?>
        <tr> 
			<td colspan='6' align="center">
			<input type='submit' name='add'  value='添加' />&nbsp;&nbsp;&nbsp;&nbsp;
			<input type='submit' name='del'  value='删除' onClick="delcfm()"  />	
			</td>
		</tr>	
</table>
</div>
</form>
</body>
</html>