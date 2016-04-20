<?php
    define("dashboardRank", 0);
    define("forumsRank", 0);
    define("accountRank", 0);
    define("logoutRank", 0);
    define("characterRank", 0);
    define("mailRank", 0);
    define("accountsRank", 2);
    define("charactersRank", 2);
    define("instancesRank", 2);
    define("sessionsRank", 2);
    define("featureaccessRank", 2);
    define("helpRank", 0);
    define("characterselectionRank", 0);
    ?>
    <div class="menu<?php if ($style != ""){ echo " " . $style;} if($minimizedmenu){echo " minimizedmenu";} ?>">
			<div class="logo pane"></div>
			<ul class="nav">
                <?php if (isset($_SESSION["rank"]) && $_SESSION["rank"] >= dashboardRank){ echo "<li><a class='button img-button dashboard-button". isPage($page, 'dashboard') ."' href='?page=dashboard'>Dashboard</a></li>";};
                if (isset($_SESSION["rank"]) && $_SESSION["rank"] >= forumsRank){ echo "<li><a class='button img-button forums-button". isPage($page, 'forums') ."' href='?page=forums'>Forums</a></li>";};
                if (isset($_SESSION["rank"]) && $_SESSION["rank"] >= accountRank){ echo "<li><a class='button img-button account-button". isPage($page, 'account') ."' href='?page=account'>Account</a></li>";};
                if (isset($_SESSION["rank"]) && $_SESSION["rank"] >= logoutRank){ echo "<li><a class='button img-button logout-button' href='?logout'>Logout</a></li>";}; ?>
            </ul>
            <ul class="nav">
                <?php if (isset($_SESSION["rank"]) && $_SESSION["rank"] >= characterRank){ echo "<li><a class='button img-button character-button". isPage($page, 'char') ."' href='?page=char'>Character</a></li>";};
                if (isset($_SESSION["rank"]) && $_SESSION["rank"] >= mailRank){ echo "<li><a class='button img-button mail-button". isPage($page, 'mail') ."' href='?page=mail'>Mail</a></li>";}; ?>
            </ul>
			<ul class="nav">
                <?php
                if (isset($_SESSION["rank"]) && $_SESSION["rank"] >= 2){ echo "<li><span class='button img-button admin-button title'>Administration</span></li>";};
                if (isset($_SESSION["rank"]) && $_SESSION["rank"] >= accountsRank){ echo "<li><a class='button img-button accounts-button". isPage($page, 'accounts') ."' href='?page=accounts'>Accounts</a></li>";};
                if (isset($_SESSION["rank"]) && $_SESSION["rank"] >= charactersRank){ echo "<li><a class='button img-button characters-button". isPage($page, 'characters') ."' href='?page=characters'>Characters</a></li>";};
                if (isset($_SESSION["rank"]) && $_SESSION["rank"] >= instancesRank){ echo "<li><a class='button img-button instances-button". isPage($page, 'instances') ."' href='?page=instances'>Instances</a></li>";};
                if (isset($_SESSION["rank"]) && $_SESSION["rank"] >= sessionsRank){ echo "<li><a class='button img-button sessions-button". isPage($page, 'sessions') ."' href='?page=sessions'>Sessions</a></li>";};
                if (isset($_SESSION["rank"]) && $_SESSION["rank"] >= featureaccessRank){ echo "<li><a class='button img-button featureaccess-button". isPage($page, 'featureaccess') ."' href='?page=featureaccess'>Feature Access</a></li>";};?>
            </ul>
            <ul class="nav">
                <?php if (isset($_SESSION["rank"]) && $_SESSION["rank"] >= helpRank){ echo "<li><a class='button img-button help-button". isPage($page, 'help') ."' href='?page=help'>Help</a></li>";}; ?>
            </ul>
            
            <ul class="nav">
<?php if (isset($_SESSION["rank"]) && $_SESSION["rank"] >= characterselectionRank){
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
				<li><span class="button char-button char-button-<?php echo $i+1; ?>" style="color: #000;" title="<?php echo $char['name']; ?>"><?php echo $char['name']; ?></span></li>
<?php 			}
			}
			if ($f){
?>				<li><a class="button char-button char-button-<?php echo $i+1; ?>" href="?page=<?php echo $page; ?>&char_id=<?php echo $char['id']; ?>" title="<?php echo $char['name']; ?>"><?php echo $char['name']; ?></a></li>
<?php 		}
		}
	}
}?>			</ul>
<ul class="nav">
                <li>
					<form method="post">
						<button type="submit" name="minimizedmenu" class="button" id="menusizer" value="<?php if($minimizedmenu){echo "0";}else{echo"1";} ?>"><span><?php if($minimizedmenu){echo "&rsaquo;";}else{echo"&lsaquo;";} ?></span></button>
					</form>
				</li>
            </ul>
		</div>