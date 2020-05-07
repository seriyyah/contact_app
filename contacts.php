<?php
require_once './config.php';
include './header.php';
try {
   $sql = "SELECT * FROM tbl_contacts WHERE 1 AND contact_id = :cid";
   $stmt = $DB->prepare($sql);
   $stmt->bindValue(":cid", intval($_GET["cid"]));
   
   $stmt->execute();
   $results = $stmt->fetchAll();
} catch (Exception $ex) {
  echo $ex->getMessage();
}
?>

    <div class="panel-heading">
        <!-- <h3><?php // echo ($_GET["m"] == "update") ? "Edit" : "Add"; ?> Контакт</li></h3> -->
      </div>
    <div>
    <form class="form-horizontal" name="contact_form" id="contact_form" enctype="multipart/form-data" method="post" action="process_form.php">
          <input type="hidden" name="mode" value="<?php echo ($_GET["m"] == "update") ? "update_old" : "add_new"; ?>" >
          <input type="hidden" name="old_pic" value="<?php echo $results[0]["profile_pic"] ?>" >
          <input type="hidden" name="cid" value="<?php echo intval($results[0]["contact_id"]); ?>" >
          <input type="hidden" name="pagenum" value="<?php echo $_GET["pagenum"]; ?>" >
          
          
          <fieldset>
          <div class='edit'>


        <div class="edit-picture"><?php $pic = ($results[0]["profile_pic"] <> "" ) ? $results[0]["profile_pic"] : "no_avatar.png" ?>
                <a href="profile_pics/<?php echo $pic ?>" target="_blank"><img src="profile_pics/<?php echo $pic ?>" alt="" width="100" height="100" class="thumbnail" ></a></div>
        
        <div class="edit-form">  
         <div class="edit-initials">
            <div class="form-group">
              <label class=" control-label" for="first_name"><span class="required">*</span>Имя:</label>
              <div class=" ">
                <input type="text" value="<?php echo $results[0]["first_name"] ?>" placeholder="First Name" id="first_name" class="form-control" name="first_name" required><span id="first_name_err" class="error"></span>
              </div>
            </div>            
            <div class="form-group">
              <label class="  control-label" for="last_name"><span class="required">*</span>Фамилия</label>
              <div class=" ">
                <input type="text" value="<?php echo $results[0]["last_name"] ?>" placeholder="Last Name" id="last_name" class="form-control" name="last_name" required><span id="last_name_err" class="error"></span>
              </div>
            </div>
        </div>        
            <div class="edit-number form-group ">
              <label class=" control-label" for="contact_no"><span class="required">*</span>Номер</label>
              <div class=" ">
                <input type="text" value="<?php echo $results[0]["contact_no"] ?>" placeholder="Contact Number" id="contact_no" class="form-control" name="contact_no" required><span id="contact_no_err" class="error"></span>
                <span class="help-block">мин 10 цыфр</span>
              </div>
            </div>
        

        <div class="edit-image form-group">
              <label class=" control-label" for="profile_pic">Фото</label>
              <div class="edit-file">
                <input type="file"  id="profile_pic" class="form-control file" name="profile_pic"><span id="profile_pic_err" class="error"></span>
                <span class="help-block">jpg, jpeg, png, gif.</span>
              </div>
            </div>

            <?php if ($_GET["m"] == "update") { ?>
            <div class="edit-button form-group">
              <div class=" ">
                <?php $pic = ($results[0]["profile_pic"] <> "" ) ? $results[0]["profile_pic"] : "no_avatar.png" ?>
                <a href="profile_pics/<?php echo $pic ?>" target="_blank"><img src="profile_pics/<?php echo $pic ?>" alt="" width="100"  class="thumbnail" ></a>
              </div>
            </div>
            <?php 
            }
            ?>
            <div class="edit-button form-group ">
              <div class=" ">
                <button class="" type="submit">Принять</button> 
              </div>
            </div>
          </div>
          </div>
</div>

        </div>

        </fieldset>
        </form>
    </div>   

    <script type="text/javascript">
$(document).ready(function() {
	
	// the fade out effect on hover
	$('.error').hover(function() {
		$(this).fadeOut(200);  
	});
	
	
	$("#contact_form").submit(function() {
		$('.error').fadeOut(200);  
		if(!validateForm()) {
            $(window).scrollTop($("#contact_form").offset().top);
			return false;
		}     
		return true;
    });

});

function validateForm() {
	 var errCnt = 0;
	 
	 var first_name = $.trim( $("#first_name").val());
     var last_name = $.trim( $("#last_name").val());
	 var contact_no = $.trim( $("#contact_no").val());
	 var profile_pic =  $.trim( $("#profile_pic").val());

	if (first_name == "" ) {
		$("#first_name_err").html("Enter your first name.");
		$('#first_name_err').fadeIn("fast"); 
		errCnt++;
	}  else if (first_name.length <= 2 ) {
		$("#first_name_err").html("Enter atleast 3 letter.");
		$('#first_name_err').fadeIn("fast"); 
		errCnt++;
	}
    
    if (last_name == "" ) {
		$("#last_name_err").html("Enter your last name.");
		$('#last_name_err').fadeIn("fast"); 
		errCnt++;
	}  else if (last_name.length <= 2 ) {
		$("#last_name_err").html("Enter atleast 3 letter.");
		$('#last_name_err').fadeIn("fast"); 
		errCnt++;
	}
    
   
    
    if (contact_no == "" ) {
		$("#contact_no_err").html("Enter first contact number.");
		$('#contact_no_err').fadeIn("fast"); 
		errCnt++;
	}  else if (contact_no.length <= 9 || contact_no.length > 10 ) {
		$("#contact_no_err").html("Enter 10 digits only.");
		$('#contact_no_err').fadeIn("fast"); 
		errCnt++;
	} else if ( !$.isNumeric(contact_no) ) {
		$("#contact_no_err").html("Must be digits only.");
		$('#contact_no_err').fadeIn("fast"); 
		errCnt++;
	}
 
    
    
    if (profile_pic.length > 0) {
        var exts = ['jpg','jpeg','png','gif', 'bmp'];
		var get_ext = profile_pic.split('.');
		get_ext = get_ext.reverse();
        
       
        if ($.inArray ( get_ext[0].toLowerCase(), exts ) <= -1 ){
          $("#profile_pic_err").html("Must me jpg, jpeg, png, gif, bmp image only..");
          $('#profile_pic_err').fadeIn("fast"); 
        }
       
    }
    
	if(errCnt > 0) return false; else return true;
}

</script>
 
</body>
</html>