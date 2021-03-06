<?php
$names = array();
$file = file_get_contents("people.json");
$people = json_decode($file);
$count = 0;
foreach($people as $key => $value) {
	$names[$count] = $key;
	$count++;
}

$messages = array();
$messages_text = fopen("messages.txt", "r");
for($i = 0; !feof($messages_text); $i++){
	$messages[$i] = fgets($messages_text);
}

if($_SERVER["REQUEST_METHOD"] == "POST") {
	$en_name = $_POST['person'];
	foreach($people as $key => $value) {
		if ($key == $en_name) {
			$fa_name = $value;
			break;
		}
	}
	$question=$_POST['question'];
	if ((str_starts_with($question, 'آیا')) and ((str_ends_with($question, '?')) or (str_ends_with($question, '؟')))) {
		$hash = hash('crc32', $question . " " . $en_name);
		$hash = hexdec($hash);
		$random_number = ($hash % 16);
		$msg = $messages[$random_number];
	}else{
		$msg = "سوال درستی پرسیده نشده";
	}
}else{
	$msg = "سوال خود را بپرس!";
	$question = '';
	$random_key = array_rand($names);
	$en_name = $names[$random_key];
	foreach($people as $key => $value){
		if($key == $en_name){
			$fa_name = $value;
			break;
		}
	}
}

if(empty($question)){
	$question_box = '';
	$msg = "سوال خود را بپرس!";
	$random_key = array_rand($names);
	$en_name = $names[$random_key];
}else{
	$question_box = 'پرسش:';
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
        <span id="label"><?php echo $question_box ?></span>
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
            <select name="person">
                <?php
				$file = file_get_contents("people.json");
				$list_of_people = json_decode($file);
				foreach($list_of_people as $key => $value){
					if($key == $en_name){
						echo "<option value=$key selected> $value </option>";
					}else{
						echo "<option value=$key> $value </option>";
					}
				}
				foreach($list_of_people as $key => $value){
					if($key == $en_name){
						echo "<option value=$key selected> $value </option>";
					}else{
						echo "<option value=$key> $value </option>";
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