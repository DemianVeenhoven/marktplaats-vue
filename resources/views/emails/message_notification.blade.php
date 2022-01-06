<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Notification for new message</title>
<head>

<body>
	<div id="wrapper">
	    <div id="page" class="container">
		    <div id="content">
			    <div class="title">
				    <h2>New message from {{$messageSenderName}}</h2>
                </div>
                
			    <div>
					<p>Hello {{$messageReceiverName}},</p>
					<p>You have received a new message on the marketplace.</p>
					<a href="{{ url($messageChainUrl) }}">Click me to see the new message!</a>
			    </div>
		    </div>
	    </div>
    </div>
</body>
</html>