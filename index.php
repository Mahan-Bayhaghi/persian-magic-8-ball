<?php

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
	$question = '' ;
	$people_json = file_get_contents("people.json");
	$names_list = json_decode($people_json);
	
	$messages_file = fopen("messages.txt" , "r");
	$messages_list = array() ;
	$t=0 ;		// counter for lines in messages_list array
	while ( !feof($messages_file)){
		$messages_list[$t] = fgets($messages_file);
		$t++;
	}
	
	$question = $_POST["question"] ;	// POSTed from html tag
	$en_name = $_POST["person"] ;		// POSTed from html tag
	// creating unique code by hashing question and en_name
	$prime_code = hexdec(hash("crc32" , $question.$en_name));		// hexadecimal pass converted to decimal pass
	$unipass = $prime_code % 16 ;		// mod in terms of all choices (16)
	$msg = $messages_list[$unipass] ;
	
	foreach ($names_list as $name_en => $name_fa){
		if ( $name_en == $en_name ){
			$fa_name = $name_fa ;
		}
	}
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="styles/default.css">
    <title>مشاوره بزرگان</title>
</head>
<body>
<p id="copyright">تهیه شده برای درس کارگاه کامپیوتر،دانشکده کامییوتر، دانشگاه صنعتی شریف</p>
<div id="wrapper">
    <div id="title">
        <span id="label">پرسش:</span>
        <span id="question"><?php echo $question ?></span>
    </div>
    <div id="container">
        <div id="message">
            <p><?php echo $msg ?></p>
        </div>
        <div id="person">
            <div id="person">
                <img src="images/people/<?php echo "$en_name.jpg" ?>"/>
                <p id="person-name"><?php echo $fa_name ?></p>
            </div>
        </div>
    </div>
    <div id="new-q">
        <form method="post">
            سوال
            <input type="text" name="question" value="<?php echo $question ?>" maxlength="150" placeholder="..."/>
            را از
            <select name="person" >
                <?php
					$people_json = file_get_contents("people.json") ;
					$names_list = json_decode($people_json);
					foreach ($names_list as $eng_esm => $far_esm){
						if ($en_name == $eng_esm){
							echo "<option value=$eng_esm selected> $far_esm </option>";
						}
						else{
							echo "<option value=$eng_esm> $far_esm </option>";
						}
					}
                ?>
            </select>
            <input type="submit" value="بپرس"/>
        </form>
    </div>
</div>
</body>
</html>