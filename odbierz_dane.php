<?php
    
    header("Content-type: text/xml");
	header("Cache-Control: no-cache");

	$link = mysqli_connect("chatbaza.mysql.database.azure.com", "chatadmin@chatbaza", "Passw0rd1234", "chatbaza");

	mysqli_query($link, "SET NAMES 'utf8'");

    $time = (int)$_POST['time'];
    $nick = mysqli_real_escape_string($link, $_POST['nick']);
	$text = mysqli_real_escape_string($link, $_POST['text']);
    $action = mysqli_real_escape_string($link, $_POST['action']);

    if($action == "post")
    {
        mysqli_query($link, "INSERT INTO wiadomosci (nick, tresc, czas) VALUES ('$nick', '$text',".time().")");
        $tmp = mysqli_insert_id($link) - 10;
        mysqli_query($link, "DELETE FROM wiadomosci WHERE id < ".$tmp);
    }


	$results = mysqli_query($link, "SELECT nick, tresc, czas FROM wiadomosci WHERE czas > ".$time." ORDER BY id");
    

    if(mysqli_num_rows($results) == 0)
        $status = 0;
    else
        $status = 1;

	echo "<?xml version='1.0' ?>";
	echo "<wiadomosci>";
    echo "<timestamp>".time()."</timestamp>";
    echo "<status>".$status."</status>";
	while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC))
	{
		echo "<wiadomosc>";
		echo "<nick>".$row['nick']."</nick>";
		echo "<tresc>".$row['tresc']."</tresc>";
		echo "<czas>".date('d-m-y H:i:s',$row['czas'])."</czas>";
		echo "</wiadomosc>";
	}
	
	echo "</wiadomosci>";
	@mysqli_close($link);
?>
