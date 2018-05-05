<?php
$act="hh";
$studentID=5;
$password='null';
$act=$_REQUEST['act'];
$studentID=$_REQUEST['studentID'];
$password=$_REQUEST['password'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <script src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
    <script>
        $(document).ready(function(){
            tt('<?php echo $studentID;?>');
        });
        function tt(msg) {
        $("#info").val(msg);
        }
    </script>
    <meta charset="utf-8">
    <title>东北大学教务处镜像</title>
</head>
<body>
<form action="index.php" method="post">
    <input type="text" name="studentID"/>
    <input type="text" name="password" />
    <input type="submit" name="login" value="登录">
</form>
<button onclick="tt()"></button>
<textarea id="info">

</textarea>
</body>
</html>