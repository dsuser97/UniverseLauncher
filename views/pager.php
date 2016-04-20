<?php
	require_once('libraries/pages.php');
	$page = 'dashboard';
	if (isset($_GET['page'])){
		$page = strtolower($_GET['page']);
	}
	
	$minimizedmenu = 0;
	if (isset($_SESSION["minimizedmenu"])){
		$minimizedmenu = $_SESSION["minimizedmenu"];
	}
    if(isset($_POST["minimizedmenu"])){
        $minimizedmenu = $_POST["minimizedmenu"];
		$_SESSION["minimizedmenu"] = $minimizedmenu;
    }
	$style = '';
	$mysql = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	$mysql->set_charset("utf8");
	

include "menu.php";
?>		
		<div class="content-pane">
			<div style="padding: 40px;">
<?php
	switch($page){
		case 'dashboard':
            if (isset($_SESSION["rank"]) && $_SESSION["rank"] >= dashboardRank) include('views/dashboard.php');
            else include('views/404.php');
			break;
		case 'forums':
			if (isset($_SESSION["rank"]) && $_SESSION["rank"] >= forumsRank) include('views/forums.php');
            else include('views/404.php');
			break;
		case 'account':
			if (isset($_SESSION["rank"]) && $_SESSION["rank"] >= accountRank) include('views/account.php');
            else include('views/404.php');
			break;
		case 'char':
			if (isset($_SESSION["rank"]) && $_SESSION["rank"] >= characterRank) include('views/char.php');
            else include('views/404.php');
			break;
		case 'mail':
			if (isset($_SESSION["rank"]) && $_SESSION["rank"] >= mailRank) include('views/mail.php');
            else include('views/404.php');
			break;
		case 'sessions':
			if (isset($_SESSION["rank"]) && $_SESSION["rank"] >= sessionsRank) include('views/sessions.php');
            else include('views/404.php');
			break;
		case 'accounts':
			if (isset($_SESSION["rank"]) && $_SESSION["rank"] >= accountRank) include('views/accounts.php');
            else include('views/404.php');
			break;
		case 'characters':
			if (isset($_SESSION["rank"]) && $_SESSION["rank"] >= charactersRank) include('views/characters.php');
            else include('views/404.php');
			break;
		case 'instances':
			if (isset($_SESSION["rank"]) && $_SESSION["rank"] >= instancesRank) include ('views/instances.php');
            else include('views/404.php');
			break;
        case 'featureaccess':
            if (isset($_SESSION["rank"]) && $_SESSION["rank"] >= featureaccessRank) include ('views/featureaccess.php');
            else include('views/404.php');
            break;
		default:
			include('views/404.php');
	}
?>
			</div>
		</div>		
	</div>		
