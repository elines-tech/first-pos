<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Restaurant</title> 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<style>
body { min-height: 100vh;background-image: linear-gradient(-20deg, #d558c8 0%, #24d292 100%); }
.container { margin: 150px auto; max-width: 600px; }
h1 { color: #fff;  }
</style>
 <script>
       var base_path = "<?= base_url() ?>";
	</script>
</head>

<body>
  <div class="container">
  <input type="hidden" id="message" name="message" value="<?=$message?>">
  <input type="hidden" id="subject" name="subject" value="<?=$subject?>">
  <?php 
   $i=0;
     if($email!=""){
     foreach($email as $e) { ?>
   <input type="hidden" id="email" name="email[]" value="<?= $e ?>">
  <?php 
  $i++;
	 }}
  ?>
<div class="panel panel-default">
    <div class="panel-heading">
     <h3 class="panel-title">Send Email...........</h3>
    </div>
      <div class="panel-body">
       <span id="success_message"></span>
       <div class="form-group" id="process" style="display:block;">
        <div class="progress">
       <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="">
       </div>
      </div>
       </div>
      </div>
     </div>
<script>
  $(document).ready(function() {
	   var message=$("#message").val();
	   var subject=$("#subject").val();
		$('#process').css('display', 'block');
	   var email = $("input[name='email[]']")
              .map(function(){return $(this).val();}).get();
              
        var size = 10;
	    var arrayOfArrays = [];
        for (var i=0; i<email.length; i+=size) {
             arrayOfArrays.push(email.slice(i,i+size));	
        }
        var loopTotalCount=arrayOfArrays.length;
        
        for(var i=0; i<=arrayOfArrays.length; i++){ 
        	sendData(arrayOfArrays[i]);
        	var percentage = 0;
        	percentage = (((i+1)/loopTotalCount)*100);
			progress_bar_process(percentage);
			if(percentage==100)
			{
			     setTimeout(function(){
						 progress_bar_process(101);
						}, 3000);
			}
        }
  
	function sendData(email)
	{
		 $.ajax({
			url: base_path+"sendemail/sendEmailNotification",  
			method:"POST",
			data:{subject,message,email},
			datatype:"text",
			async: false,
			success: function(data)
			{
				console.log(data);
			}
		});
	 }
	 
	 
	  function progress_bar_process(percentage)
	  {
	   $('.progress-bar').css('width', percentage + '%');
	   if(percentage > 100)
	   {	
		$('#process').css('display', 'none');
		$('.progress-bar').css('width', '0%');
		$('#success_message').html("<div class='alert alert-success'>All Email Sent Successfully</div>");
		setTimeout(function(){
			window.location.href=base_path+"sendemail/notifiy";
		}, 5000);
	   }
	  }
  
 });
</script>
</body>
</html>
