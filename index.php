<html>
<head>
	<title>Scannr</title>
    <meta content="width=device-width, initial-scale=1" name="viewport" />
		<link rel="stylesheet" href="../assets/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<style>
	body, a {
		font-family: -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,"Noto Sans",sans-serif,"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol","Noto Color Emoji";
		font-weight: 300;
		color: white;
		text-align: center;
        background-color: #eee;
        text-decoration: none;
	}
	form {
		margin: 10vh;
		font-size: 1em;
	}
	input {
		font-size: 2em;
		border: .1em solid #e33;
		background-color: #111;
		color: white;
	}
	input:focus {
		box-shadow: 2px 2px 2px #e33;
	}
	input:hover {
		color: black;
		background-color: white;
	}
	h1 {
		padding-top: 10vh;
		font-weight: 800;
	}
	#contentBox {
		margin-top: 4vh;
		padding: 2vw;
		padding-bottom: 10vh;
		background-color: rgba(10,10,10,.8);
		margin-left: 10vh;
		margin-right: 10vh;
		box-shadow: 5px 3px 10px 10px #999;
        border-radius: 6px;
	}
	#response {
		background-color: #e33;
		color: white;
        padding-top: 1em;
        padding-bottom: 1em;
		box-shadow: inset .2em .5em 1em black;
		font-size: 2em;
        position: relative;
	}
	b {
		font-weight: bold;
    }
    .vis-network {
        outline: none;
        max-height: 50vh;
    }
    #vulncount {
        font-size: 2em;
        display: inline-block;
        vertical-align: sub;
        background-color: black;
        color: #e33;
        padding-left: .1em;
        padding-right: .1em;
    }
    #controls {
        flex: 1;
        border-top: 8px solid black;
        padding-top: 1em;
    }
    .ctrl {
        border: .1em solid black;
        border-radius: 6px;
		box-shadow: 1px 2px 2px 3px #333;
        padding: .5em;
        display: inline-block;
        margin-right: 1em;
    }
    .ctrl:hover {
        background-color: white;
        color: black;
        cursor: pointer;
    }
    .blink {
        animation: blinker 1s step-start infinite;
        display: inline-block;
    }
    @keyframes blinker {
        50% {
            opacity: 0;
        }
    }
    @media only screen and (max-width: 600px) {
        #contentBox, form, input {
            padding: 0;
            margin: 0;
            word-break: break-all;
        }
        #contentBox {
            width: 100vw;
        }
        input {
            width: 80vw;
        }
        .ctrl {
            padding: .2em;
        }
        #controls {
            font-size: .5em;
        }
    }
	@media only screen and (max-width: 800px) {
		#contentBox {
			margin-left: 0px;
			margin-right: 0px;
		}
	}
	</style>
</head>
<body>
	<div id="contentBox">
        <a href="index.php"><h1>PAI VULNERABILITY SCANNER</h1></a>
        <p>Search a domain for potential attack vectors for hackers and scammers, and identify potential threats.</p>
        <form action="" method="get">
            <!--
            <input type ="text" value="First Name"><br>
            <input type ="text" value="Last Name"><br>
            <input type ="text" value="Email"><br>
            -->
            <input type="url" autofocus="autofocus" name="url"><br>
            <input type="submit" value="Scan">
        </form>
       
        <?php 
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
        if(isset($_GET['url'])) {
            echo "<div id='response'>";
            echo "<b>Results for:</b> ";
            echo $_GET["url"];
            echo "<br>";
            // lookup section
            $url = $_GET["url"];
            // echo "<b>Response time:</b> ";
            // echo shell_exec("ping -c 1 $url");
            echo "<br><b>Vulnerabilities:  <div id='vulncount'><div id='vulnscore' class='blink'>_</div></div></b> ";
            $page = shell_exec("python3 reach.py $url");
            // echo json_decode(json_encode($page));
            echo "<div id='mynetwork'></div><div id='controls'><div class='ctrl' onclick='toggleTwitter()'>generate report</div>toggle: <div class='ctrl' onclick='toggleTwitter()'>pages</div><div class='ctrl' onclick='toggleTwitter()'>email</div><div class='ctrl' onclick='toggleTwitter()'>phone</div><div class='ctrl' onclick='toggleTwitter()'>twitter</div></div></div>";
        }
        ?>
        OUTPUT:
        <div id="json"></div></div>
</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/vis/4.21.0/vis.min.js"></script>
<script>
    var len = undefined;
    var oA =  <?php echo json_decode(json_encode($page)); ?>;
    console.log(oA);
    console.log(oA.numbers);
    
    var nodes = [
        {id: 1, label: oA.site, group: 0},
    ];

    var edges = [
      //  {from: 1, to: 2}
    ];
    
    count = 1;
    for(var i in oA.numbers){
        var item = oA[i];
        var num = oA.numbers;
        count=count+1;
        nodes.push({
            "id" : count,
            "label" : num[count-2],
            "group" : 1
        });
        edges.push({
            "from" : 1,
            "to" : count
        })
     

    }
    
    count2 = -1;
    for(var i in oA.emails){
        var item = oA[i];
        var num = oA.emails;
        count=count+1;
        count2=count2+1;
        nodes.push({
            "id" : count,
            "label" : num[count2],
            "group" : 2
        });
        edges.push({
            "from" : 1,
            "to" : count
        })
     

    }
    count2 = -1;
    for(var i in oA.twitters){
        var item = oA[i];
        var num = oA.twitters;
        count=count+1;
        count2=count2+1;
        nodes.push({
            "id" : count,
            "label" : num[count2],
            "group" : 3
            
        });
        edges.push({
            "from" : 1,
            "to" : count
        })
     

    }
    count2 = -1;
    for(var i in oA.pages){
        var item = oA[i];
        var num = oA.pages;
        count=count+1;
        count2=count2+1;
        nodes.push({
            "id" : count,
            "label" : num[count2],
            "group" : 4
        });
        edges.push({
            "from" : 1,
            "to" : count
        })
     

    }
    
    

    // create a network
    var container = document.getElementById('mynetwork');
    var data = {
        nodes: nodes,
        edges: edges
    };
    var options = {
        nodes: {
            shape: 'dot',
            size: 35,
            shadow: true,
            font: {
                size: 32,
                color: 'white',
                background: 'black'
            },
            borderWidth: 5,
            shadow:true
        },
        groups: {
            0: {color:{background:'white',border:'blue'}}, 
            1: {color:{background:'orange',border:'orange'},shape:'triangle',shape: 'image',image: '/app/scannr/img/phone.png'},   
            2: {color:{background:'red',border:'red'},shape:'diamond',shape: 'image',image: '/app/scannr/img/email.png'},   
            3: {color:{background:'lightblue',border:'lightblue'},shape: 'image',image: '/app/scannr/img/handle.png'},
            4: {color:{background:'rgba(0,0,0,0)',border:'blue'},shape: 'image',image: '/app/scannr/img/page.png'} 
        },
        edges: {
            width: 2,
            shadow:true
        }
    };
    network = new vis.Network(container, data, options);
    
    document.getElementById("json").innerHTML = JSON.stringify(nodes);
    vulnCount = oA.numbers.length + oA.emails.length;
    document.getElementById("vulncount").innerHTML = JSON.stringify(vulnCount) + document.getElementById("vulncount").innerHTML;

    
    console.log(nodes);

</script>
<script>
function toggleTwitter(){
    console.log("yeeted");
    nodes.splice(nodes.findIndex(e => e.group === "3"),10);
    network = new vis.Network(container, data, options);
}
</script>
</html>