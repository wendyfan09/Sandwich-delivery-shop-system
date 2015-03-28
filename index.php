<!DOCTYPE html>
<h1>Welcome to order sandwich!<br><br>
<?php
$mysqli = new mysqli('localhost', 'root', '', 'sandwich'); /*check connection*/
if (mysqli_connect_errno()){
printf("Connect failed:s%\n", mysqli_connect_errno());
exit(); 

}

if (isset($_POST["phonenumber"])) {

    if (!preg_match("/\b\d{10}\b/", $_POST["phonenumber"], $match)) {

        echo "<b>Please enter 10 digit phone number. </b></br>";

        echo "Please return <a href = \"index.php\"><b>here</b></a> to try again. </br>";

    }

    else {

        $pn = htmlspecialchars($_POST["phonenumber"]);

        if (isset($_POST["keyword"])) {

            $kw = htmlspecialchars($_POST["keyword"]);

        }

        else { $kw = "*"; }

        if ($stmt = $mysqli->prepare("SELECT phone FROM customer WHERE phone = ?")) {

            $stmt->bind_param("s", $pn);

            $stmt->execute();

            $stmt->bind_result($phone);

            if (!$stmt->fetch()) {

                $stmt->close();

                echo "<b>This phone number is not found.</b></br>";

                echo "<b>Please register first!</b><br>";

                echo "Please click <a href = \"register.php\"><b>here</b></a> to register.</br>";

                echo "Or click <a href = \"index.php\"><b>here</b></a> to try again. </br>";

            }

            else {

                $stmt->close();

                session_start();

                if(isset($SESSION["REMOTE_ADDR"]) && $SESSION["REMOTE_ADDR"] != $SERVER["REMOTE_ADDR"]) {

                    session_destroy();

                    session_start();

                }

                $_SESSION["phonenumber"] = $pn;

                $_SESSION["keyword"] = $kw;

                $_SESSION["REMOTE_ADDR"] = $_SERVER["REMOTE_ADDR"];

                header("location: orderpage.php");

            }

        }

    }

}

else {

    echo "Please Enter Your Phone number to order: </br></br>";

    echo "<form action = \"index.php\" method = \"POST\">";

    echo "<b>Phone Number: </b></br>";

    echo "<input type = \"text\" name = \"phonenumber\">";

    echo "</br>";

    echo "<b>Keywords:</b></br>";

    echo "<input type = \"text\" name = \"keyword\">";

    echo "</br>";

    echo "<input type = \"submit\" value = \"Shoping\"><br><br>";

    echo "Check the whole menu? Please leave the keyword blank!<br><br> ";

    echo "</form>";

}

$mysqli->close();

?>

</html>