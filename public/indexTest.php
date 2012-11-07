<!DOCTYPE html>
<html>
<head>
    <title>Testing jQuery</title>
</head>
<body>
<p id="bar">Hellow World!</p>
<p class="foo">Another paragraph, but thi one has a class.</p>
<p><span class="foo">And this sentence is in a span.</span></p>

<script type="text/javascript" src="http://www.google.com/jsapi"></script>
<script type="text/javascript">google.load("jquery","1.7.1")</script>

<script type="text/javascript">
    $("a").live("click",function(){
        console.log("Link Clicked");
        return false; //evita que o link dispare
    });
</script>
</body>
</html>