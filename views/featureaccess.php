<?php
if(isset($_GET['change']) && $_GET['change']==1)
{
    $menuitems = array( '<li><a class=\'button img-button dashboard-button". isPage($page, \'dashboard\') ."\' href=\'?page=dashboard\'>Dashboard</a></li>";',
				        '<li><a class=\'button img-button forums-button". isPage($page, \'forums\') ."\' href=\'?page=forums\'>Forums</a></li>";',
				        '<li><a class=\'button img-button account-button". isPage($page, \'account\') ."\' href=\'?page=account\'>Account</a></li>";',
				        '<li><a class=\'button img-button logout-button\' href=\'?logout\'>Logout</a></li>";',
				        '<li><a class=\'button img-button character-button". isPage($page, \'char\') ."\' href=\'?page=char\'>Character</a></li>";',
				        '<li><a class=\'button img-button mail-button". isPage($page, \'mail\') ."\' href=\'?page=mail\'>Mail</a></li>";',
				        '<li><span class=\'button img-button admin-button title\'>Administration</span></li>";',
				        '<li><a class=\'button img-button accounts-button". isPage($page, \'accounts\') ."\' href=\'?page=accounts\'>Accounts</a></li>";',
				        '<li><a class=\'button img-button characters-button". isPage($page, \'characters\') ."\' href=\'?page=characters\'>Characters</a></li>";',
				        '<li><a class=\'button img-button instances-button". isPage($page, \'instances\') ."\' href=\'?page=instances\'>Instances</a></li>";',
				        '<li><a class=\'button img-button sessions-button". isPage($page, \'sessions\') ."\' href=\'?page=sessions\'>Sessions</a></li>";',
                        '<li><a class=\'button img-button featureaccess-button". isPage($page, \'featureaccess\') ."\' href=\'?page=featureaccess\'>Feature Access</a></li>";',
                        '<li><a class=\'button img-button help-button". isPage($page, \'help\') ."\' href=\'?page=help\'>Help</a></li>";');
    
    $menu = '<?php
    define("dashboardRank", '. $_POST['Dashboard'] .');
    define("forumsRank", '. $_POST['Forums'] . ');
    define("accountRank", '. $_POST['Account'] . ');
    define("logoutRank", '. $_POST['Logout'] . ');
    define("characterRank", '. $_POST['Character'] . ');
    define("mailRank", '. $_POST['Mail'] . ');
    define("accountsRank", '. $_POST['Accounts'] . ');
    define("charactersRank", '. $_POST['Characters'] . ');
    define("instancesRank", '. $_POST['Instances'] . ');
    define("sessionsRank", '. $_POST['Sessions'] . ');
    define("featureaccessRank", '. $_POST['FeatureAccess'] . ');
    define("helpRank", '. $_POST['Help'] . ');
    define("characterselectionRank", '. $_POST['CharSelect'] . ');
    ?>
    <div class="menu<?php if ($style != ""){ echo " " . $style;} if($minimizedmenu){echo " minimizedmenu";} ?>">
			<div class="logo pane"></div>
			<ul class="nav">
                <?php if (isset($_SESSION["rank"]) && $_SESSION["rank"] >= dashboardRank){ echo "' .$menuitems[0]. '};
                if (isset($_SESSION["rank"]) && $_SESSION["rank"] >= forumsRank){ echo "'.$menuitems[1].'};
                if (isset($_SESSION["rank"]) && $_SESSION["rank"] >= accountRank){ echo "'.$menuitems[2].'};
                if (isset($_SESSION["rank"]) && $_SESSION["rank"] >= logoutRank){ echo "'.$menuitems[3].'}; ?>
            </ul>
            <ul class="nav">
                <?php if (isset($_SESSION["rank"]) && $_SESSION["rank"] >= characterRank){ echo "'.$menuitems[4].'};
                if (isset($_SESSION["rank"]) && $_SESSION["rank"] >= mailRank){ echo "'.$menuitems[5].'}; ?>
            </ul>
			<ul class="nav">
                <?php
                if (isset($_SESSION["rank"]) && $_SESSION["rank"] >= 2){ echo "'.$menuitems[6].'};
                if (isset($_SESSION["rank"]) && $_SESSION["rank"] >= accountsRank){ echo "'.$menuitems[7].'};
                if (isset($_SESSION["rank"]) && $_SESSION["rank"] >= charactersRank){ echo "'.$menuitems[8].'};
                if (isset($_SESSION["rank"]) && $_SESSION["rank"] >= instancesRank){ echo "'.$menuitems[9].'};
                if (isset($_SESSION["rank"]) && $_SESSION["rank"] >= sessionsRank){ echo "'.$menuitems[10].'};
                if (isset($_SESSION["rank"]) && $_SESSION["rank"] >= featureaccessRank){ echo "'.$menuitems[11].'};?>
            </ul>
            <ul class="nav">
                <?php if (isset($_SESSION["rank"]) && $_SESSION["rank"] >= helpRank){ echo "'.$menuitems[12].'}; ?>
            </ul>
            
            <ul class="nav">
<?php if (isset($_SESSION["rank"]) && $_SESSION["rank"] >= characterselectionRank){
			if (!$mysql->connect_errno) {
		$sql = "SELECT `characters`.`name` as `charname`, `characters`.`objectID` FROM `characters`, `accounts` WHERE `accounts`.`id` = `characters`.`accountID` AND `accounts`.`name` = \'" . $_SESSION[\'user_name\'] . "\'";
		$result = $mysql->query($sql);
		$chars = [];
		if ($result->num_rows > 0){
			for ($i = 0; $i < $result->num_rows; $i++){
				$resobj = $result->fetch_object();
				$chars[] = array( \'name\' => $resobj->charname, \'id\' => $resobj->objectID );
				if (isset($_GET[\'char_id\'])){
					if ($_GET[\'char_id\'] == $resobj->objectID){
						$_SESSION[\'char_id\'] = $resobj->objectID;
					}
				}
			}
		}
		for ($i = 0; $i < count($chars); $i++){
			$char = $chars[$i];
			$f = true;
			if (isset($_SESSION[\'char_id\'])){
				if ($_SESSION[\'char_id\'] == $char[\'id\']){
					$f = false; ?>
				<li><span class="button char-button char-button-<?php echo $i+1; ?>" style="color: #000;" title="<?php echo $char[\'name\']; ?>"><?php echo $char[\'name\']; ?></span></li>
<?php 			}
			}
			if ($f){
?>				<li><a class="button char-button char-button-<?php echo $i+1; ?>" href="?page=<?php echo $page; ?>&char_id=<?php echo $char[\'id\']; ?>" title="<?php echo $char[\'name\']; ?>"><?php echo $char[\'name\']; ?></a></li>
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
		</div>';
        
    $myFile = "views/menu.php";
$fh = fopen($myFile, 'w') or die("can't open file");
fwrite($fh, $menu);
fclose($fh);
    echo "Changes are saved and will be visible soon.";
}
?>
<div class="box pane">
    <form method="POST" action="?page=featureaccess&change=1">
        <?php $options= array(
               "<option value='0'>available for all Users</option>
                <option value='1'>only for Moderators or higher</option>
                <option value='2'>only for Admin</option>
                <option value='3'>make unavailable<!--still available from rank 3(Hacker)--></option>",
               "<option value='1'>only for Moderators or higher</option>
                <option value='0'>available for all Users</option>
                <option value='2'>only for Admin</option>
                <option value='3'>make unavailable<!--still available from rank 3(Hacker)--></option>",
               "<option value='2'>only for Admin</option>
                <option value='0'>available for all Users</option>
                <option value='1'>only for Moderators or higher</option>
                <option value='3'>make unavailable<!--still available from rank 3(Hacker)--></option>",
               "<option value='3'>make unavailable<!--still available from rank 3(Hacker)--></option>
                <option value='0'>available for all Users</option>
                <option value='1'>only for Moderators or higher</option>
                <option value='2'>only for Admin</option>");
        ?>
        
        <fieldset>
            <legend>General</legend> 
                Dashboard: <select name="Dashboard"><?php echo $options[dashboardRank]; ?></select> <br/>
                Forums: <select name="Forums"><?php echo $options[forumsRank]; ?></select> <br/>
                Account: <select name="Account"><?php echo $options[accountRank]; ?></select> <br/>
                Logout: <select name="Logout"><?php echo $options[logoutRank]; ?></select> <br/>
        </fieldset>
        <fieldset>
            <legend>Character</legend>
                Character: <select name="Character"><?php echo $options[characterRank]; ?></select> <br/>
                Mail: <select name="Mail"><?php echo $options[mailRank]; ?></select> <br/>
        </fieldset>
        <fieldset>
            <legend>Administration</legend>
                Accounts: <select name="Accounts"><?php echo $options[accountsRank]; ?></select> <br/>
                Characters: <select name="Characters"><?php echo $options[charactersRank]; ?></select> <br/>
                Instances: <select name="Instances"><?php echo $options[instancesRank]; ?></select> <br/>
                Sessions: <select name="Sessions"><?php echo $options[sessionsRank]; ?></select> <br/>
                Feature Access(Attention): <select name="FeatureAccess"><?php echo $options[featureaccessRank]; ?></select> <br/>
        </fieldset>
        <fieldset>
            <legend>Help</legend>
                Help: <select name="Help"><?php echo $options[helpRank]; ?></select> <br/>
        </fieldset>
        <fieldset>
            <legend>Minifigures</legend>
                Show users minifigures: <select name="CharSelect"><?php echo $options[characterselectionRank]; ?></select> <br/>
        </fieldset>
        <input type="submit" value="Save">
    </form> 
</div>