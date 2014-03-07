<?php session_start();?>

<?php
if (! $_SESSION['logged_in']) {
	header("location:login.php");
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset='UTF-8' />
<style type="text/css">
<!--
.chat_wrapper {
	width: 500px;
	margin-right: auto;
	margin-left: auto;
	background: #CCCCCC;
	border: 1px solid #999999;
	padding: 10px;
	font: 12px 'lucida grande',tahoma,verdana,arial,sans-serif;
}
.chat_wrapper .message_box {
	background: #FFFFFF;
	height: 150px;
	overflow: auto;
	padding: 10px;
	border: 1px solid #999999;
}
.chat_wrapper .panel input{
	padding: 2px 2px 2px 5px;
}
.system_msg{color: #BDBDBD;font-style: italic;}
.user_name{font-weight:bold;}
.user_message{color: #88B6E0;}

button.clean-gray {
  background-color: #eeeeee;
  background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #eeeeee), color-stop(100%, #cccccc));
  background-image: -webkit-linear-gradient(top, #eeeeee, #cccccc);
  background-image: -moz-linear-gradient(top, #eeeeee, #cccccc);
  background-image: -ms-linear-gradient(top, #eeeeee, #cccccc);
  background-image: -o-linear-gradient(top, #eeeeee, #cccccc);
  background-image: linear-gradient(top, #eeeeee, #cccccc);
  border: 1px solid #ccc;
  border-bottom: 1px solid #bbb;
  border-radius: 3px;
  color: #333;
  font: bold 11px/1 "Lucida Grande", "Lucida Sans Unicode", "Lucida Sans", Geneva, Verdana, sans-serif;
  padding: 8px 0;
  text-align: center;
  text-shadow: 0 1px 0 #eee;
  width: 150px; }
  button.clean-gray:hover {
    background-color: #dddddd;
    background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #dddddd), color-stop(100%, #bbbbbb));
    background-image: -webkit-linear-gradient(top, #dddddd, #bbbbbb);
    background-image: -moz-linear-gradient(top, #dddddd, #bbbbbb);
    background-image: -ms-linear-gradient(top, #dddddd, #bbbbbb);
    background-image: -o-linear-gradient(top, #dddddd, #bbbbbb);
    background-image: linear-gradient(top, #dddddd, #bbbbbb);
    border: 1px solid #bbb;
    border-bottom: 1px solid #999;
    cursor: pointer;
    text-shadow: 0 1px 0 #ddd; }
  button.clean-gray:active {
    border: 1px solid #aaa;
    border-bottom: 1px solid #888;
    -webkit-box-shadow: inset 0 0 5px 2px #aaaaaa, 0 1px 0 0 #eeeeee;
    box-shadow: inset 0 0 5px 2px #aaaaaa, 0 1px 0 0 #eeeeee; }

-->
</style>
</head>
<body>


<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>

<script language="javascript" type="text/javascript">
$(document).ready(function(){
	//create a new WebSocket object.
	var wsUri = "ws://192.168.1.107:1089/chat/server.php";
	websocket = new WebSocket(wsUri);

	$('#send-btn').click(function(){ //use clicks message send button
		send();
	});
	$(document).keyup(function(e){
	if (e.keyCode == 13) {
	    send();
	}
	});


	//#### Message received from server?
	websocket.onmessage = function(ev) {
		var msg = JSON.parse(ev.data); //PHP sends Json data
		var type = msg.type; //message type
		var umsg = msg.message; //message text
		var uname = msg.name; //user name

		if(type == 'usermsg')
		{
			$('#message_box').append("<div><span class=\"user_name\">"+uname+"</span> : <span class=\"user_message\">"+umsg+"</span></div>");
		}
		if(type == 'system')
		{
			$('#message_box').append("<div class=\"system_msg\">"+umsg+"</div>");
		}

		$('#message').val(''); //reset text
	};
	function send()
	{
				var mymessage = $.trim($('#message').val()); //get message text
		var myname = $('#name').val(); //get user name

		if(mymessage == ""){ //emtpy message?
			alert("მომეჩვენა თუ არაფერი შეგიყვანია?");
			return;
		}

		//prepare json data
		var msg = {
		message: mymessage,
		name:"<?=$_SESSION['username']?>"
		};
		//convert and send data to server
		websocket.send(JSON.stringify(msg));
	}
	websocket.onerror	= function(ev){$('#message_box').append("<div class=\"system_error\">შეცდომა - "+ev.data+"</div>");};
	websocket.onclose 	= function(ev){$('#message_box').append("<div class=\"system_msg\">კავშირი დაიხურა</div>");};
});
</script>
<div class="chat_wrapper">
<div class="message_box" id="message_box"></div>
<div class="panel">
<input type="text" name="message" id="message" placeholder="შეტყობინება" style="width:90%" />
<button id="send-btn" class="clean-gray">გაგზავნა</button>
</div>
</div>

</body>
</html>