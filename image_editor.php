<?php
/* rozhranie editora obrazkov */

include 'functions.php';
include 'config.php';
bootstrap_header('Editor');
?>


<script type="text/javascript" src="scripts/pixastic-lib/pixastic.custom.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js" type="text/javascript"></script>


<script type="text/javascript">

function setBrightness(value){
    var img = document.getElementById("editedImage"); 
    Pixastic.process(img,"brightness",{"brightness" : value});
}


function setContrast(value){
    var img = document.getElementById("editedImage"); 
    Pixastic.process(img,"brightness",{"contrast" : value});
}

function setNoise(amount_val, strength_val){
    var img = document.getElementById("editedImage"); 
    Pixastic.process(img, "noise", {"mono":true,"amount": amount_val,"strength":strength_val});
}

function setSepia(){
    var img = document.getElementById("editedImage"); 
    Pixastic.process(img, "sepia");
}

function desaturate(){
    var img = document.getElementById("editedImage"); 
    Pixastic.process(img, "desaturate", {average : false});
}


/* refresh obrazku po kazdej zmene potrebny - inak by sa tazko reverzovali efekty */
function refresh(){
    var img = document.getElementById("editedImage");  
    Pixastic.revert(img);
    setBrightness($("#range_brightness").val());
    setContrast($("#range_contrast").val());
    setNoise($("#range_noise_amount").val(), $("#range_noise_strength").val());     
    if ($("#sepia_check").is(":checked")) {
        setSepia();
    }
    if ($("#desaturate_check").is(":checked")) {
        desaturate();
    }
}

function save(){
    var canvas = document.getElementById('editedImage');
    
    $.ajax({
        type: "POST",
        url: "upload_edited_image.php",
        data: { 
            imgBase64: canvas.toDataURL('image/jpg')
        }
    }).done(function(o) {
    console.log('saved'); 
    });
}

</script>
</head>
<body>
<img src="./get_image.php?image_id=<?php echo $_GET['image_id']; ?>.jpg" id="editedImage">
<input type="range" min="-150" max="150" step="1" value="0" style="width:300px;" onchange="refresh();" id = "range_brightness"> brightness
<br/>
<input type="range" min="-1" max="10" step="0.01" value="0" style="width:300px;" onchange="refresh();" id = "range_contrast"> contrast
<br/>
<input type="range" min="0" max="1" step="0.01" value="0" style="width:300px;" onchange="refresh();" id = "range_noise_strength"> noise strength
<br/>
<input type="range" min="0" max="1" step="0.01" value="0" style="width:300px;" onchange="refresh();" id = "range_noise_amount"> noise amount
<br/>
<input type="checkbox" id="sepia_check" onclick="refresh();"> sepia tone
<br/>
<input type="checkbox" id="desaturate_check" onclick="refresh();"> desaturate
<input type="button" onclick="save();" value="save">
<a href="" id="download_image" onclick="this.href = document.getElementById('editedImage').toDataURL('image/jpeg');" download="picture.jpg">download</a>
<?php bootstrap_scripts ?>
</body>
</html>