<?php
	require_once('libraries/pages.php');
	$page = 'dashboard';
	if (isset($_GET['page'])){
		$page = strtolower($_GET['page']);
	}
    $minimizedmenu = 0;
    if(isset($_GET["minimizedmenu"])){
        $minimizedmenu = $_GET["minimizedmenu"];
    }
	$style = '';
	$mysql = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	$mysql->set_charset("utf8");
	
?>		<div class="menu<?php if ($style != ''){ echo " " . $style;} if($minimizedmenu){echo " minimizedmenu";} ?>">
			<div class="logo pane"></div>
			<ul class="nav">
				<li><a class="button dashboard-button<?php echo isPage($page, 'dashboard'); ?>" href="?page=dashboard">Dashboard</a></li>
				<li><a class="button forums-button<?php echo isPage($page, 'forums'); ?>" href="?page=forums">Forums</a></li>
				<li><a class="button account-button<?php echo isPage($page, 'account'); ?>" href="?page=account">Account</a></li>
				<li><a class="button logout-button" href="?logout">Logout</a></li>
			</ul>
			<ul class="nav">
				<li><a class="button character-button<?php echo isPage($page, 'char'); ?>" href="?page=char">Character</a></li>
				<li><a class="button mail-button<?php echo isPage($page, 'mail'); ?>" href="?page=mail">Mail</a></li>
			</ul>
			<?php if (isset($_SESSION['rank']) && $_SESSION['rank'] > 1){ ?><ul class="nav">
				<li><span class="button title">Administration</span></li>
				<li><a class="button<?php echo isPage($page, 'accounts'); ?>" href="?page=accounts">Accounts</a></li>
				<li><a class="button<?php echo isPage($page, 'characters'); ?>" href="?page=characters">Characters</a></li>
				<li><a class="button<?php echo isPage($page, 'instances'); ?>" href="?page=instances">Instances</a></li>
				<li><a class="button<?php echo isPage($page, 'sessions'); ?>" href="?page=sessions">Sessions</a></li>
			</ul>
			<?php } ?><ul class="nav">
				<li><a class="button help-button<?php echo isPage($page, 'help'); ?>" href="?page=help">Help</a></li>
			</ul>
			<ul class="nav">
<?php
			if (!$mysql->connect_errno) {
		$sql = "SELECT `characters`.`name` as `charname`, `characters`.`objectID` FROM `characters`, `accounts` WHERE `accounts`.`id` = `characters`.`accountID` AND `accounts`.`name` = '" . $_SESSION['user_name'] . "'";
		$result = $mysql->query($sql);
		$chars = [];
		if ($result->num_rows > 0){
			for ($i = 0; $i < $result->num_rows; $i++){
				$resobj = $result->fetch_object();
				$chars[] = array( 'name' => $resobj->charname, 'id' => $resobj->objectID );
				if (isset($_GET['char_id'])){
					if ($_GET['char_id'] == $resobj->objectID){
						$_SESSION['char_id'] = $resobj->objectID;
					}
				}
			}
		}
		for ($i = 0; $i < count($chars); $i++){
			$char = $chars[$i];
			$f = true;
			if (isset($_SESSION['char_id'])){
				if ($_SESSION['char_id'] == $char['id']){
					$f = false; ?>
				<li><span class="button" style="color: #000;"><?php echo $char['name']; ?></span></li>
<?php 			}
			}
			if ($f){
?>				<li><a class="button" href="?page=<?php echo $page; ?>&char_id=<?php echo $char['id']; ?>"><?php echo $char['name']; ?></a></li>
<?php 		}
		}
	}
?>			</ul>
            <ul class="nav">
                <li><a class="button" id="menusizer" href="?page=<?php echo $page; ?>&minimizedmenu=<?php if($minimizedmenu){echo "0";}else{echo"1";} ?>"><?php if($minimizedmenu){echo ">";}else{echo"<";} ?></a></li>
            </ul>
		</div>
		<div class="content-pane" style="padding-left: 180px; overflow: auto;">
			<div style="padding: 40px;">
<?php
	switch($page){
		case 'dashboard':
			include('views/dashboard.php');
			break;
		case 'forums':
			include('views/forums.php');
			break;
		case 'account':
			include('views/account.php');
			break;
		case 'char':
			include('views/char.php');
			break;
		case 'mail':
			include('views/mail.php');
			break;
		case 'sessions':
			include('views/sessions.php');
			break;
		case 'accounts':
			include('views/accounts.php');
			break;
		case 'characters':
			include('views/characters.php');
			break;
		case 'instances':
			include ('views/instances.php');
			break;
		default:
			include('views/404.php');
	}
?>
			</div>
		</div>
		
