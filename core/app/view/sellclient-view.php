
<style>
  
#v{
    width:320px;
    height:240px;
}
#qr-canvas{
    display:none;
}
#qrfile{
    width:320px;
    height:240px;
}
#mp1{
    text-align:center;
    font-size:35px;
}
#imghelp{
    position:relative;
    left:0px;
    top:-160px;
    z-index:100;
    font:18px arial,sans-serif;
    background:#f0f0f0;
  margin-left:35px;
  margin-right:35px;
  padding-top:10px;
  padding-bottom:10px;
  border-radius:20px;
}

</style>
<section class="content">



<?php



?>


<div class="row">
	<div class="col-md-12">
	<h1>VENTA A CLIENTE</h1>
	<p><b>Buscar Cliente por nombre o por DNI:</b></p>
		<form id="searchp">
		<div class="row">
			<div class="col-md-3">
				<input type="hidden" name="view" value="sell">
				<input type="text" id="client_name" name="client_name" class="form-control" placeholder="Nombre del Cliente">
			</div>

			<div class="col-md-3">
				<input type="hidden" name="view" value="sell">
				<input type="text" id="client_code" name="client_code" class="form-control" placeholder="Nro de DNI">
			</div>

      

			<div class="col-md-1">
			<button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-search"></i> Buscar</button>
			</div>
      <div class="col-md-1">
      </div>

		</div>
		</form>


<div style="display:none;" id="qrreader">
<div id="mainbody">
<a class="selector" id="webcamimg" onclick="setwebcam()" align="left">Camara</a>
<a class="selector" id="qrimg" src="cam.png" onclick="setimg()" align="right">Imagen</a>
<div id="outdiv">
</div>
<div id="result">-- Scaning --</div>
<canvas id="qr-canvas" width="800" height="600"></canvas>


<button onclick="captureToCanvas()">Capture</button><br>
</div>
</div>

<script>
  $(document).ready(function(){
      $("#readqr").click(function(){
        qrreader = document.getElementById("qrreader");
        if(qrreader.style.display=="none"){
          qrreader.style.display="block";
          load();
        }else if(qrreader.style.display=="block"){
          qrreader.style.display="none";
          var MediaStream = window.MediaStream;

          if (typeof MediaStream === 'undefined' && typeof webkitMediaStream !== 'undefined') {
              MediaStream = webkitMediaStream;
          }

          /*global MediaStream:true */
          if (typeof MediaStream !== 'undefined' && !('stop' in MediaStream.prototype)) {
              MediaStream.prototype.stop = function() {
                  this.getAudioTracks().forEach(function(track) {
                      track.stop();
                  });

                  this.getVideoTracks().forEach(function(track) {
                      track.stop();
                  });
              };
          }

        }

      });
  });
</script>

<div id="show_search_results"></div>

<script>
//jQuery.noConflict();

$(document).ready(function(){
	$("#searchp").on("submit",function(e){
		e.preventDefault();

    code = $("#client_code").val();
    name = $("#client_name").val();
		if(name!=""){
		$.get("./?action=searchclient",$("#searchp").serialize()+"&go=name",function(data){
			$("#show_search_results").html(data);
		});
		$("#client_name").val("");
    $("#category_id").val("");
    }
    else if(code!=""){
    $.get("./?action=searchclient",$("#searchp").serialize()+"&go=code",function(data){
      $("#show_search_results").html(data);
    });
    $("#client_code").val("");
    $("#category_id").val("");
    }else {
      $("#show_search_results").html("");
    }

	});
	});

$(document).ready(function(){
    $("#client_code").keydown(function(e){
        if(e.which==17 || e.which==74){
            e.preventDefault();
        }else{
            console.log(e.which);
        }
    })
});
</script>

<div id="cartofsell"></div>



</div>
</section>
<script>
$(document).ready(function(){
$.get("./?action=cartofsell",null,function(data){
$("#cartofsell").html(data);
});
});
</script>