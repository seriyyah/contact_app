<?php

require_once './config.php';
include './header.php';
// 
if (!(isset($_GET['pagenum']))) {
  $pagenum = 1;
} else {
  $pagenum = $_GET['pagenum'];
}
$page_limit = ($_GET["show"] <> "" && is_numeric($_GET["show"]) ) ? $_GET["show"] : 8;


try {
  $keyword = trim($_GET["keyword"]);
  if ($keyword <> "" ) {
    $sql = "SELECT * FROM tbl_contacts WHERE 1 AND "
            . " (first_name LIKE :keyword) ORDER BY first_name ";
    $stmt = $DB->prepare($sql);
    
    $stmt->bindValue(":keyword", $keyword."%");
    
  } else {
    $sql = "SELECT * FROM tbl_contacts WHERE 1 ORDER BY first_name ";
    $stmt = $DB->prepare($sql);
  }
  
  $stmt->execute();
  $total_count = count($stmt->fetchAll());

  $last = ceil($total_count / $page_limit);

  if ($pagenum < 1) {
    $pagenum = 1;
  } elseif ($pagenum > $last) {
    $pagenum = $last;
  }

  $lower_limit = ($pagenum - 1) * $page_limit;
  $lower_limit = ($lower_limit < 0) ? 0 : $lower_limit;


  $sql2 = $sql . " limit " . ($lower_limit) . " ,  " . ($page_limit) . " ";
  
  $stmt = $DB->prepare($sql2);
  
  if ($keyword <> "" ) {
    $stmt->bindValue(":keyword", $keyword."%");
   }
   
  $stmt->execute();
  $results = $stmt->fetchAll();
} catch (Exception $ex) {
  echo $ex->getMessage();
}
// пегинация конец 

// 
// 
?>
<div class="row">

<!--  -->
<body>

<div class="notebook">    
  <header>
      Контакты
      <img src="image/1.png" alt="">
  </header>
  <?php if ($ERROR_MSG <> "") { ?>
    <div class="alert alert-dismissable alert-<?php echo $ERROR_TYPE ?>">
      <button data-dismiss="alert" class="close" type="button">×</button>
      <p><?php echo $ERROR_MSG; ?></p>
    </div>
<?php } 
?>
        
  <section class="container">
  
<?php if (count($results) > 0) { ?>
       <div class="all-contact">       
              <div class="contact-title row">
                  <p class='col-2'>Фото</p>
                  <p class='col-2'> Имя</p>
                  <p class='col-2'>Фамилия</p>
                  <p class='col-2'>Телефон</p>
                  <p class='col-4'>Управление контактом</p>
              </div>                                     
            <?php foreach ($results as $res) { ?>
                <div class="contact row">
                        <div class="contact-picture  col-2">
                          <?php $pic = ($res["profile_pic"] <> "" ) ? $res["profile_pic"] : "no_avatar.png" ?>
                          <a href="profile_pics/<?php echo $pic ?>" target="_blank"><div><img src="profile_pics/<?php echo $pic ?>" alt="" ></div></a>
                        </div>                    
                      
                        <div class="contact-name col-2"><?php echo $res["first_name"]; ?> </div> 
                        <div class="contact-surname col-2"><?php echo $res["last_name"]; ?>  </div>  
                        <div class="contact-number col-2"><?php echo $res["contact_no"]; ?>  </div>                       
                      
                        <div class="contact-management col-4">
                          <a href="view_contacts.php?cid=<?php echo $res["contact_id"]; ?>" title='Посмотреть'><img src="image/icon-view2.png" alt="" ></a>
                          <a href="index.php?m=update&cid=<?php echo $res["contact_id"]; ?>&pagenum=<?php echo $_GET["pagenum"]; ?>" title='Редактировать'><img src="image/icons8-edit3.png" alt=""></a>
                          <a href="process_form.php?mode=delete&cid=<?php echo $res["contact_id"]; ?>&keyword=<?php echo $_GET["keyword"]; ?>&pagenum=<?php echo $_GET["pagenum"]; ?>" onclick="return confirm('Are you sure?')" title='Удалить'><img src="image/icons8-del4.png" alt=""></a>                                   
                        </div>                                                
                  </div>                                                 
            <?php } ?>

           
        </div>     
        <div class="col-lg-12 center">
          <ul class="pagination pagination-sm">

  <?php
  

//   
// 
if (count($results) > 8) { 

  for ($i = 1; $i <= $last; $i++) {
    if ($i == $pagenum) {
      ?>
                <li class="active"><a href="javascript:void(0);" ><?php echo $i ?></a></li>
                <?php
              } else {
                ?>
                <li><a href="app.php?pagenum=<?php echo $i; ?>&keyword=<?php echo $_GET["keyword"]; ?>" class="links"  onclick="displayRecords('<?php echo $page_limit; ?>', '<?php echo $i; ?>');" ><?php echo $i ?></a></li>
                <?php
              }
            }
          }
            ?>
          </ul>
        </div>

          <?php } else { ?>
        <div class="well well-lg">Нет контактов</div>
<?php } ?>
    </div>
  </div>
</div>

<div class="add-contact">
            <a href=""  data-toggle="modal" data-target="#modalContactForm"><button>Добавить контакт</button></a>
          </div> 


        </section>
    
         
    </div>



    <form class="form-horizontal" name="contact_form" id="contact_form" enctype="multipart/form-data" method="post" action="process_form.php">
    <input type="hidden" name="mode" value="<?php echo ($_GET["m"] == "update") ? "update_old" : "add_new"; ?>" >
          <input type="hidden" name="old_pic" value="<?php echo $results[0]["profile_pic"] ?>" >
          <input type="hidden" name="cid" value="<?php echo intval($results[0]["contact_id"]); ?>" >
          <input type="hidden" name="pagenum" value="<?php echo $_GET["pagenum"]; ?>" >
          <fieldset>
    <div class="modal fade" id="modalContactForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header text-center">
        <h4 class="modal-title w-100 font-weight-bold">Новый контакт</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-add modal-body mx-3">
      <div class="form-group">
              <label class=" " for="first_name"><span class="required">*</span>Имя:</label>
              <div class=" ">
                <input type="text"  placeholder="First Name" id="first_name" class="form-control" name="first_name" required><span id="first_name_err" class="error"></span>
              </div>
            </div>   

            <div class="form-group">
              <label class=" " for="last_name"><span class="required">*</span>Фамилия</label>
              <div class=" ">
                <input type="text"  placeholder="Last Name" id="last_name" class="form-control" name="last_name" required><span id="last_name_err" class="error"></span>
              </div>
            </div>

            <div class="form-group">
              <label class=" " for="contact_no"><span class="required">*</span>Номер</label>
              <div class=" ">
                <input type="text" placeholder="Contact Number" id="contact_no" class="form-control" name="contact_no" required><span id="contact_no_err" class="error"></span>
                <span class="help-block">мин 10 цыфр</span>
              </div>
            </div>

            <div class="form-group">
              <label class=" control-label" for="profile_pic">Фото</label>
              <div class=" ">
                <input type="file"  id="profile_pic" class="new-picture form-control file" name="profile_pic" style='border:none'><span id="profile_pic_err" class="error"></span>
                <span class="help-block">jpg, jpeg, png, gif.</span>
              </div>
            </div>

      </div>
      <div class="modal-footer d-flex justify-content-center">
      <div class="form-group">
              <div class=" ">
                <button class="new-contact" type="submit">Принять</button> 
              </div>
            </div>
      </div>
    </div>
  </div>
</div>
</form>

    <script src="js/app.js"></script>

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