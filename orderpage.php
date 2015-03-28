<!DOCTYPE html>

<html>
<h1>Please Order here!

<?php

session_start();

if(isset($SESSION["REMOTE_ADDR"]) && $SESSION["REMOTE_ADDR"] != $SERVER["REMOTE_ADDR"]) {

  session_destroy();

  session_start();

}

$mysqli = new mysqli('localhost', 'root', '', 'sandwich');

function orderSelect($sname) {

	$mysqli2 = new mysqli('localhost', 'root', '', 'sandwich');

	if ($stmt2 = $mysqli2->prepare("SELECT size, price FROM menu WHERE sname = ?")) {

		$stmt2->bind_param("s", $sname);

		$stmt2->execute();

		$stmt2->bind_result($size, $price);

		echo "<tr><td width = 60><b>Size:</b></td><td width = 60><b>Price:</b></td><td width = 60><b>Quantity:</b></td></tr>";

		while ($stmt2->fetch()) {

			echo "<tr>";

			$cbname = "$sname"."+"."$size";

			$tbname = "$sname"."+"."$size"."+q";

			echo "<td width = 100><input type = \"checkbox\" name = \"$cbname\">$size</td>";

			echo "<td width = 100>\$$price</td>";

			echo "<td width = 150><input type =\"text\" name = \"$tbname\" style = \"width:40px\" value = '0'></td>";

			echo "</tr></br>";

		}

		$stmt2->close();

	}

	$mysqli2->close();

}

$pn = $_SESSION["phonenumber"];

$kw = $_SESSION["keyword"];

echo "<form action = \"orderconfirm.php\" method = \"POST\">";

if ($stmt = $mysqli->prepare("SELECT * FROM sandwich WHERE description like ?")) {

	if ($kw <> "*") {

		$kw = "%".$kw."%";

	}

	$stmt->bind_param("s", $kw);

	$stmt->execute();

	$stmt->bind_result($sname, $sdesc);

	if (!$stmt->fetch()) {

		$stmt->close();

		echo "<b>No Result!</b></br>";

		echo "Please click <a href = \"index.php\"><b>here</b></a> to try again!</br>";

	}

	else {

	    echo "<table border = '1' cellspacing = '0'>";

	    echo "<tr><td width = 300>Sandwich name</td><td width = 300>Description</td><td>Order</td></tr></br>";

	    echo "<tr>";

		echo "<td width = 100>$sname</td><td width = 300>$sdesc</td>";

		echo "<td><table border = '0'>";

		orderSelect($sname);

		echo "</table></td>";

		echo "</tr></br>";

	    while ($stmt->fetch()) {

		    echo "<tr>";

		    echo "<td width = 100>$sname</td><td width = 300>$sdesc</td>";

		    echo "<td><table border = '0'>";

		    orderSelect($sname);

		    echo "</table></td>";

		    echo "</tr></br>";

	    }

	    echo "</table></br>";

	    $stmt->close();

    }

}

echo "</br>";

echo "<input type = \"submit\" value = \"Submit Order\">";

echo "<a href = \"index.php\"><input type = \"button\" value = \"Go Back\"></a>";

echo "</form>";

$mysqli->close();

?>

</html>

