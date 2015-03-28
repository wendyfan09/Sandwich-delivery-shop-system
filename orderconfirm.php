<!DOCTYPE html>

<html>
<h1>
<?php

session_start();

$order = [];

foreach ($_POST as $k => $v) {

	$qt = $k."+q";

	if ($_POST[$k] == 'on' && $_POST[$qt]<>'0') {

		if (!preg_match("/\b\d+\b/", $_POST[$qt], $match)) {

			echo "<b>Please check the quantity number!</b></br>";

			echo "Please click <a href =\"orderpage.php\"><b>here</b></a> to go back to the order page.</br>";

			exit();

		}

		else {

			$arr = explode("+", str_replace("_", " ", $k));

			$order[$arr[0]][$arr[1]] = (int)$_POST[$qt];

		}

	}

}

if (count($order) == 0) {

	echo "<b>Please make a order to continue!</b></br>";

	echo "Click <a href =\"orderpage.php\"><b>here</b></a> to go back to the order page.</br>";

	exit();

}

else {

	$mysqli = new mysqli('localhost', 'root', '', 'sandwich');

	$phone = $_SESSION["phonenumber"];

	foreach ($order as $sname => $t)

		foreach ($t as $size => $qt) {

			if ($stmt = $mysqli->prepare("SELECT phone FROM orders WHERE phone = ? AND sname = ? AND size = ? AND status = 'pending'")) {

				$stmt->bind_param("sss", $phone, $sname, $size);

				$stmt->execute();

				$stmt->bind_result($ph);

				if ($stmt->fetch()) {

					$stmt->close();

					$stmt2 = $mysqli->prepare("UPDATE orders SET quantity = quantity + ? WHERE phone = ? AND sname = ? AND size = ? AND status = 'pending'");

					$stmt3 = $mysqli->prepare("UPDATE orders SET o_time = now() WHERE phone = ? AND sname = ? AND size = ? and status = 'pending'");

					$stmt2->bind_param("isss", $qt, $phone, $sname, $size);

					$stmt3->bind_param("sss", $phone, $sname, $size);

					$stmt2->execute();

					$stmt3->execute();

					$stmt2->close();

					$stmt3->close();

				}

				else {

					$stmt->close();

					$stmt2 = $mysqli->prepare("INSERT INTO orders VALUES (?, ?, ?, now(), ?, 'pending')");

					$stmt2->bind_param("sssi", $phone, $sname, $size, $qt);

					$stmt2->execute();

					$stmt2->close();

				}

			}

		}

	echo "<b>Ordered!</b></br>";

	echo "Your orders are:</br>";

	echo "<table border = '1' cellspacing = '0'>";

	echo "<tr><td>Phone</td><td>Sname</td><td>Size</td><td>Order Time</td><td>Quantity</td><td>Status</td></tr></br>";

	foreach ($order as $sname => $t)

		foreach ($t as $size => $qt) {

			$stmt = $mysqli->prepare("SELECT * FROM orders WHERE phone = ? AND status = 'pending'");

			$stmt->bind_param("s", $phone);

			$stmt->execute();

			$stmt->bind_result($pn, $sn, $si, $ot, $q, $st);

			while ($stmt->fetch()) {

				echo "<td>$pn</td><td>$sn</td><td>$si</td><td>$ot</td><td>$q</td><td>$st</td>";

				echo "</tr></br>";

			}

			$stmt->close();

		}

	echo "</table></br>";

	//echo "Click <a href = \"orderpage.php\"><b>here</b></a> to make another order.</br>";

	echo "Click <a href = \"index.php\"><b>here</b></a> go back.</br>";

	//echo "Or close webpage to quit.</br>";

	$mysqli->close();

}	

?>

</html>