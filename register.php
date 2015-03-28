<!DOCTYPE html>

<html>
<h1>


<?php

if (isset($_POST["phone"]) && isset($_POST["bn"]) && isset($_POST["street"]) && isset($_POST["apt"])) {

	if (!preg_match("/\b\d{10}\b/", $_POST["phone"], $match)) {

		unset($_POST["phone"]);

		unset($_POST["bn"]);

		unset($_POST["street"]);

		unset($_POST["apt"]);

		echo "Please enter 10 digit phone number!</br>";

		echo "<a href = \"register.php\"><input type = \"button\" value = \"Try Again\"></a>";

		echo "<a href = \"index.php\"><input type = \"button\" value = \"Go Back\"></a>";

	}

	else {

		if (!preg_match("/\b\d+\b/", $_POST["bn"], $match2)) {

			unset($_POST["phone"]);

			unset($_POST["bn"]);

			unset($_POST["street"]);

			unset($_POST["apt"]);

			echo "Please enter valid building number!</br>";

			echo "<a href = \"register.php\"><input type = \"button\" value = \"Try Again\"></a>";

			echo "<a href = \"index.php\"><input type = \"button\" value = \"Go Back\"></a>";

		}

		else {

			$mysqli = new mysqli('localhost', 'root', '', 'sandwich');

			if ($stmt = $mysqli->prepare("INSERT INTO customer VALUES (?, ?, ?, ?)")) {

				$ph = $_POST["phone"];

				$bnum = (int)$_POST["bn"];

				$st = $_POST["street"];

				$aptnum = $_POST["apt"];

				$stmt->bind_param("siss", $ph, $bnum, $st, $aptnum);

				$stmt->execute();

				$stmt->close();

				session_start();

				$_SESSION["phonenumber"] = $_POST["phone"];

				echo "New account created!</br>";

				echo "Click <a href = \"orderpage.php\"><b>here</b></a> to start making orders.</br>";

				$mysqli->close();

			}

			else {

				$mysqli->close();

				echo "<b>Unexpected Error Occured!</b></br>";

				echo "<a href = \"register.php\"><input type = \"button\" value = \"Try Again\"></a>";

				echo "<a href = \"index.php\"><input type = \"button\" value = \"Go Back\"></a>";

			}

		}

	}



}

else {

	echo "<form action = \"register.php\" method = \"POST\">";

	echo "Please enter your information to complete the registeration!<br><br>";

	echo "Phone: <input type = \"text\" name = \"phone\"></br>";

	echo "Building Number: <input type = \"text\" name = \"bn\"></br>";

	echo "Street: <input type = \"text\" name = \"street\"></br>";

	echo "Apartment: <input type = \"text\" name = \"apt\"></br>";

	echo "<input type = \"submit\" value = \"Register\">";

	echo "<a href = \"index.php\"><input type = \"button\" value = \"Back\"></a>";

	echo "</form>";

}

?>

</html>