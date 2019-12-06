<?php
    session_start();
    include('function.php');
    if($_SERVER["REQUEST_METHOD"]=="POST") {
        insert($_POST["nom"]);
    } else if(!isset($_SESSION["user"])) {
        header('location:index.php');
    }
    $students = getStudents();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Bary Server</title>
</head>
<body>
    <div>
        <h2>Server ID : <?php echo getServerId(); ?></h2>
        <table border=2>
            <thead>
                <tr>
                    <th>nom</th>
                </tr>
            </thead>
            <tbody>
                <?php for($i=0;$i<count($students);$i++) { ?>
                <tr><td><?php echo $students[$i]; ?></td></tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <p><a href="home.php">go back</a></p>
</body>
</html>