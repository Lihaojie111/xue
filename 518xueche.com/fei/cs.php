<html>
<head lang="en">
<meta charset="UTF-8">
<title></title>
  <meta name="viewport" content="width=device-width
initial-scale=1.0

maximum-scale=1.0

minimum-scale=1.0

user-scalable=no" />
<script src="jquery.js"></script>
<script src="pintuer.js"></script>
<script src="clipboard.min.js"></script>
</head>
<body>
<textarea id="target">411325197606109477</textarea></br>
<a onclick="fuzhi(1)" id="copy1" data-text="411325197606109477">复制</a>





<script>
function fuzhi(mid){
    var clipboard = new Clipboard('#copy'+mid,{ 
 
       text: function(trigger) { 
 
          alert("<?echo rand(100,999); ?>复制成功！");
 
          return trigger.getAttribute('data-text'); 
 
       }
 
    });
}
</script>
</body>
</html>