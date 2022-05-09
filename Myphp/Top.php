<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>无标题文档</title>
<link href="styles/Index.css" rel="stylesheet" />
</head>

<body>
    <div class="header">
        <label class="logo-title">学生学籍成绩管理系统</label>
    </div>
    <div class="nav">
	 <?php 
	      session_start();
		  $role="管理员";
		  if($_SESSION["role"]=="teacher") $role="教师";
		  if($_SESSION["role"]=="student") $role="学生";
		  echo "当前用户类别：".$role."，用户名：".$_SESSION["userid"];
	?>
    </div>
</body>
</html>
