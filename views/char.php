<?php		
if (isset($_POST['action'])){
    if($_POST['action']=='move'){
        $sql = "UPDATE `inventory` SET `inventory`.`slot` =" . intval($_POST['target']) . " WHERE `inventory`.`id`=" . mysql_real_escape_string($_POST['dragID']) . " AND `inventory`.`owner`= " . mysql_real_escape_string($_SESSION['char_id']) . ";";
        $res = $mysql->query($sql);
    }elseif($_POST['action']=='add'){
        $sql = "INSERT INTO `objects`(`template`) VALUES (" . intval($_POST['value']) . ");";
        $res = $mysql->query($sql);
        $sql = "INSERT INTO `inventory`(`owner`, `object`, `slot`, `qnt`, `linked`) VALUES (" . mysql_real_escape_string($_SESSION['char_id']) . ", (SELECT LAST_INSERT_ID())," . intval($_POST['slot']) . ", 1, 0);";
        $res = $mysql->query($sql);
    }elseif($_POST['action']=='delete'){
        $sql = "DELETE FROM `inventory` WHERE `inventory`.`id` = " . mysql_real_escape_string($_POST['id']) . " AND `inventory`.`owner`= " . mysql_real_escape_string($_SESSION['char_id']) . ";";
        $res = $mysql->query($sql);
    }
}
?>

<div class="box pane" id="characterInfoBox">
<?php
	if (isset($_SESSION['char_id'])){
		$sql = "SELECT `name`, `objectID`, `unapprovedName`, `lastZoneId`, `mapInstance`, `mapClone`, `x`, `y`, `z` FROM `characters` WHERE `objectID` = '" . $_SESSION['char_id'] . "'";
		$res = $mysql->query($sql);
		if ($res->num_rows > 0){
			$obj = $res->fetch_object();
			$objid = $obj->objectID;
			$uname = $obj->unapprovedName;
			$name = $obj->name;
?>
		<h1 style="margin: 0;"><?php echo $name . " "; if ($uname != "") echo "(" . $uname . ") "?></h1>
		<h3>[ObjectID: <?php echo $objid; ?>]</h3>
		<br/>
    <script>
        var playerPosition = [<?php echo $obj->x . "," . $obj->y . "," . $obj->z; ?>]
    </script>
		<span>Zone: <?php echo $obj->lastZoneId; ?>, Instance: <?php echo $obj->mapInstance; ?>, Clone: <?php echo $obj->mapClone; ?>, Position: (<?php echo $obj->x . "|" . $obj->y . "|" . $obj->z; ?>)</span><br/>
		<span style="font-size: 9pt; color: #B00;">This position is only updated on world change at the moment</span>
<?php
		}
	}else{
?>
			<div class="alert">
				To use this page, please select a character from the menu on the left
			</div>
<?php
	}
?>
		</div>

<div id="positionMap" class="box pane" style=""> 
        Map with approximate position:
        <canvas id="worldPosition" width="100%" height="100%" style="width:100%; height:100%;">
        </canvas> 
    </div>
    <div style="background-image: url(img/equippedgear/bg.png); height:256px; width:256px;">
        <div>Equipped Gear</div>
        <?php
        for ($x = 1; $x <= 6; $x++) {
            echo "
            <div style='background-image: url(img/equippedgear/0.png); width:60px;'>
                <img src='img/equippedgear/". $x .".png' draggable='true' class='draggable-item' id='17' height='60' width='60'>
                ";
        }
            ?>
            </div>
    </div>

<div id="options" style="width:370px; position:absolute; right:0px; bottom:0px;">
    <?php
        for ($x = 1; $x <= 6; $x++) {
            echo "<img src='img/dock/". $x .".png' onclick='dockOptions(". $x .")' style='width:15%; max-width:100px;'>";
        } 
    ?>
    <!--img src="img/Backpack.png" onclick="hideShowBackpack()"><img src="img/controls-comingsoon.png" onclick="javascript:window.alert('coming soon.')"></div-->


<div id="BackPackWrapper" style="width:370px; position: absolute; right: 40px; bottom: 60px; height: 525px; visibility:<?php 
$backpackVisibility="hidden"; 
if (isset($_POST['backpackVisibility'])){ 
    if ($_POST['backpackVisibility']=="visible"){
        $backpackVisibility="visible";    
    }
}
echo $backpackVisibility;?>
;">    
<div id="backpackOptions" style="float:left; width:60px; height:500px; border-radius:20px;"><div id="backpackOptionsPlaceholder" style="width:100%; height:363px; float: left;"><img src="img/back.png" id="backpackBackButton" onclick="hideShowBackpack()" style="visibility:hidden;"></div><img src="img/trash.png" width="60px" height="60px" style="visibility:hidden; border-radius:10px; margin-top:1px; margin-bottom:1px; margin-right:2px; margin-left:2px; float:right;" id="trashicon" class="droptarget-item"></img></div>
<div id="itemwindow" style="background-color:black; width:260px; border-radius: 20px; padding: 20px; float:right;"><div><div style="width:100%; text-align:center; color:white; ">Items</div>
    
<?php
for ($slot = 0; $slot <= 23; $slot++) {
        $sql = "SELECT DISTINCT `objects`.`template`, `inventory`.`id` FROM `objects`, `inventory`, `characters`, `accounts` WHERE `objects`.`objectid` = `inventory`.`object` AND `inventory`.`owner` = `characters`.`objectID` AND `characters`.`objectID` = '" . mysql_real_escape_string($_SESSION['char_id']) . "' AND `inventory`.`slot` = " . intval($slot) . "";
		$res = $mysql->query($sql);
    echo "<img src=img/backpack/";
if (mysqli_num_rows($res) > 0) { 
    // output data of each row
    while($row = mysqli_fetch_assoc($res)) {
        if(file_exists("img/backpack/" . $row["template"] . ".jpg")){
            echo $row["template"] . ".jpg";}else{
            echo "1.jpg";}
        echo " draggable=true class=draggable-item id='" . $row["id"] . "'";
    }
} else {
    echo "0.jpg class=droptarget-item draggable=false id='". $slot . "'";
}
    echo " height=60 width=60 style='margin-left:2px; margin-right:2px; margin-top:1px; margin-bottom:1px; border-radius:10px;'>";
}
?>
    
    </div></div>
<div style="visibility:hidden; background-color:black; width:260px; border-bottom-left-radius:20px; border-bottom-right-radius:20px; padding:20px; float:right; border-top:1px solid grey;" id="AddItemBox">
    <form>
        <label for="putInID" style="color:#fff;">ID:</label> 
        <input type="text" id="putInID" maxlength="6" style="width: 100px; box-sizing: border-box;" oninput="autoLoadBgPic()" list="itemlist">
        <datalist id="itemlist">
            <?php include 'libraries/itemlist.php'; ?>
        </datalist>
        <input type="button" value="Add Item" onclick="AddItem()">
        <input type="button" value="x" class="closebutton" onclick="CloseAddItemBox()" style="width: 28px; float:right;">
    </form>
</div>
</div>
<script>
    function dockOptions(nr){
        switch(nr) {
    case 0:
        /*todo*/
        break;
    case 1:
        hideShowBackpack();
        break;
    default:
        window.alert('coming soon.')
}
    }
    
    var backpackState = "<?php echo $backpackVisibility;?>";
    if(backpackState=="visible"){
        if(screen.width <= 1080){
                document.getElementById('characterInfoBox').style.position="absolute";
                document.getElementById('characterInfoBox').style.visibility="hidden";
                document.getElementById('options').style.height="0px";
                document.getElementById('options').style.visibility="hidden";
                document.getElementById('positionMap').style.visibility="hidden";
        }
    }


    function hideShowBackpack() {
        if(backpackState == "visible"){
            document.getElementById('BackPackWrapper').style.visibility='hidden';
            backpackState = "hidden";
            document.getElementById('characterInfoBox').style.position="relative";
            document.getElementById('characterInfoBox').style.visibility="visible";
            document.getElementById('options').style.height="80px";
            document.getElementById('options').style.visibility="visible";
            document.getElementById('backpackBackButton').style.visibility="hidden";
            document.getElementById('positionMap').style.visibility="visible";
            if(screen.width <= 1080){document.getElementById('positionMap').style.position="relative";}else{
                document.getElementById('positionMap').style.position="absolute";
            }
            
        }else{
            if(screen.width <= 1080){
                document.getElementById('characterInfoBox').style.position="absolute";
                document.getElementById('characterInfoBox').style.visibility="hidden";
                document.getElementById('options').style.height="0px";
                document.getElementById('options').style.visibility="hidden";
                document.getElementById('backpackBackButton').style.visibility="visible";
                document.getElementById('positionMap').style.visibility="hidden";
                document.getElementById('positionMap').style.position="absolute";
            }
            document.getElementById('BackPackWrapper').style.visibility='visible';
            backpackState = "visible";
        }
    }
            var lastdrag;
    
            var draggables = document.querySelectorAll('.draggable-item');
            [].forEach.call(draggables, function(draggable) {
                draggable.addEventListener('dragstart', dragStart, false);
                draggable.addEventListener('dragend',   dragEnd,   false);
            });
    
            var droptargets = document.querySelectorAll(".droptarget-item");
            [].forEach.call(droptargets, function(droptarget) {
                droptarget.addEventListener('dragenter', dragEnter  , false);
                droptarget.addEventListener('dragover' , dragOver   , false);
                droptarget.addEventListener('dragleave', dragLeave  , false);
                droptarget.addEventListener('drop'     , drop       , false);
                droptarget.addEventListener('click'    , click      , false);
            }); 
    
    function trashclick(event) {}

            /* Draggable event handlers */
            function dragStart(event) {
                lastdrag = event.target.id;
                document.getElementById('trashicon').style.visibility='visible';
            }

            function dragEnd(event) {
                document.getElementById('trashicon').style.visibility='hidden';
            }

            /* Drop target event handlers */
            function dragEnter(event) {
                event.target.style.border = "1px dashed #ffffff";
                event.target.style.marginRight = "1px";
                event.target.style.marginLeft =  "1px";
                event.target.style.marginTop =  "0px";
                event.target.style.marginBottom =  "0px";
            }
        
            function dragOver(event) {
                event.preventDefault();
                return false;
            }
        
            function dragLeave(event) {
                event.target.style.border = "none";
                event.target.style.marginRight = "2px";
                event.target.style.marginLeft =  "2px";
                event.target.style.marginTop =  "1px";
                event.target.style.marginBottom =  "1px";
            }
        
            function drop(event) {
                var data = event.dataTransfer.getData('text/html');
                event.preventDefault();
                
                if(event.target.id=="trashicon") {
                    event.target.style.border = "1px dashed #B00"; //no need for this right now, maybe in use in the future.
                    location.href = "index.php?page=char&action=delete&id=" + lastdrag + "&backpackVisibility=" + backpackState;
                    post('#', {action: 'delete', id: lastdrag, backpackVisibility: backpackState});
                }else{
                    event.target.style.border = "1px dashed #00cc33"; 
                    //location.href = "index.php?page=char&action=move&target=" + event.target.id + "&dragID=" + lastdrag + "&backpackVisibility=" + backpackState;
                    post('#', {action: 'move', target: event.target.id, dragID: lastdrag, backpackVisibility: backpackState});
                }
                return false;
            }
    
            function click(event){
                if(lastdrag != null){
                    if(lastdrag.target != undefined){
                    lastdrag.target.style.marginRight = "2px";
                    lastdrag.target.style.marginLeft =  "2px";
                    lastdrag.target.style.marginTop =  "1px";
                    lastdrag.target.style.marginBottom =  "1px";
                    lastdrag.target.style.border = "none";
                    lastdrag.target.style.opacity = "1";
                    }
                }
                
                lastdrag = event;
                lastdrag.target.style.border = "1px solid #dd9000";
                lastdrag.target.style.marginRight = "1px";
                lastdrag.target.style.marginLeft =  "1px";
                lastdrag.target.style.marginTop =  "0px";
                lastdrag.target.style.marginBottom =  "0px";
                lastdrag.target.style.opacity = "0.5";
                
                
                document.getElementById('AddItemBox').style.visibility='visible';
                document.getElementById('itemwindow').style.paddingBottom="10px";
                document.getElementById('itemwindow').style.borderBottomLeftRadius="0px";
                document.getElementById('itemwindow').style.borderBottomRightRadius="0px";
                
                autoLoadBgPic();
            }
    
            function autoLoadBgPic(){
                if(document.getElementById('putInID').value != ""){
                    lastdrag.target.src = "img/backpack/" + document.getElementById('putInID').value + ".jpg";
                }else{
                    lastdrag.target.src = "img/backpack/0.jpg";
                }
            }

          function AddItem(){
              if(document.getElementById('putInID').value == ""){
                   window.alert("This ID isn't valid! Put In a valid ID.");
              } else {
                  post('#', {action: 'add', slot: lastdrag.target.id, value: document.getElementById('putInID').value, backpackVisibility: backpackState});
              }
          }

          function CloseAddItemBox(){
              if(lastdrag.target != undefined){
               lastdrag.target.style.border = "none";
               lastdrag.target.style.marginRight = "2px";
               lastdrag.target.style.marginLeft =  "2px";
               lastdrag.target.style.marginTop =  "1px";
               lastdrag.target.style.marginBottom =  "1px";
               lastdrag.target.style.opacity = "1";
               lastdrag.target.src = "img/backpack/0.jpg"
              
               document.getElementById('AddItemBox').style.visibility='hidden';
               document.getElementById('itemwindow').style.paddingBottom="20px";
               document.getElementById('itemwindow').style.borderBottomLeftRadius="20px";
               document.getElementById('itemwindow').style.borderBottomRightRadius="20px";
              }
          }

        function post(path, params, method) { //http://stackoverflow.com/questions/133925/javascript-post-request-like-a-form-submit
            method = method || "post"; // Set method to post by default if not specified.

                // The rest of this code assumes you are not using a library.
                // It can be made less wordy if you use one.
                var form = document.createElement("form");
                form.setAttribute("method", method);
                form.setAttribute("action", path);

                for(var key in params) {
                    if(params.hasOwnProperty(key)) {
                        var hiddenField = document.createElement("input");
                        hiddenField.setAttribute("type", "hidden");
                        hiddenField.setAttribute("name", key);
                        hiddenField.setAttribute("value", params[key]);

                        form.appendChild(hiddenField);
                    }
                }

                document.body.appendChild(form);
                form.submit();
                }
    
    //painting worldPosition
    var wP = document.getElementById("worldPosition");
    
    var worldImage=new Image();
    worldImage.src="img/worlds/1100.png";
    wP.width=worldImage.width;//1792;
    wP.height=worldImage.height;//1280;
    var wPcontent = wP.getContext("2d");
    
    wPcontent.drawImage(worldImage,0,0);
    
    var pointerImage=new Image();
    pointerImage.src="img/positionPointer.png";
    wPcontent.drawImage(pointerImage,(0.5*wP.width-(playerPosition[0]))-(pointerImage.width/2),(0.5*wP.height-(playerPosition[2]))-(pointerImage.width/2));
    //wPcontent.drawImage(pointerImage,0.5*wP.width,0.5*wP.height); //==middle
</script>