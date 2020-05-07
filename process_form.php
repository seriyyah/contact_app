<?php

require './config.php';
$mode = $_REQUEST["mode"];
if ($mode == "add_new" ) {
  $first_name = trim($_POST['first_name']);
  $last_name = trim($_POST['last_name']);
  $contact_no = trim($_POST['contact_no']);
  $filename = "";
  $error = FALSE;

  if (is_uploaded_file($_FILES["profile_pic"]["tmp_name"])) {
    $filename = time() . '_' . $_FILES["profile_pic"]["name"];
    $filepath = 'profile_pics/' . $filename;
    if (!move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $filepath)) {
      $error = TRUE;
    }
  }

  if (!$error) {
    $sql = "INSERT INTO `tbl_contacts` (`first_name`, `last_name`, `contact_no`, `profile_pic`) VALUES "
            . "( :fname, :lname, :contact, :pic)";

    try {
      $stmt = $DB->prepare($sql);

      // bind the values
      $stmt->bindValue(":fname", $first_name);
      $stmt->bindValue(":lname", $last_name);
      $stmt->bindValue(":contact", $contact_no);
      $stmt->bindValue(":pic", $filename);

      // execute Query
      $stmt->execute();
      $result = $stmt->rowCount();
      if ($result > 0) {
        $_SESSION["errorType"] = "success";
        $_SESSION["errorMsg"] = "Контакт успешно добавлен!.";
      } else {
        $_SESSION["errorType"] = "danger";
        $_SESSION["errorMsg"] = "Что то пошло не так...контакт не добавлен!!.";
      }
    } catch (Exception $ex) {

      $_SESSION["errorType"] = "danger";
      $_SESSION["errorMsg"] = $ex->getMessage();
    }
  } else {
    $_SESSION["errorType"] = "danger";
    $_SESSION["errorMsg"] = "Что то пошло не так...фото не смогло загрузится!!!";
  }
  header("location:app.php");
} elseif ( $mode == "update_old" ) {
  
  $first_name = trim($_POST['first_name']);
  $last_name = trim($_POST['last_name']);
  $contact_no = trim($_POST['contact_no']);
  $cid = trim($_POST['cid']);
  $filename = "";
  $error = FALSE;

  if (is_uploaded_file($_FILES["profile_pic"]["tmp_name"])) {
    $filename = time() . '_' . $_FILES["profile_pic"]["name"];
    $filepath = 'profile_pics/' . $filename;
    if (!move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $filepath)) {
      $error = TRUE;
    }
  } else {
     $filename = $_POST['old_pic'];
  }

  if (!$error) {
    $sql = "UPDATE `tbl_contacts` SET `first_name` = :fname, `last_name` = :lname, `contact_no` = :contact, `profile_pic` = :pic  "
            . "WHERE contact_id = :cid ";

    try {
      $stmt = $DB->prepare($sql);

      // bind the values
      $stmt->bindValue(":fname", $first_name);
      $stmt->bindValue(":lname", $last_name);
      $stmt->bindValue(":contact", $contact_no);
      $stmt->bindValue(":pic", $filename);
      $stmt->bindValue(":cid", $cid);

      // execute Query
      $stmt->execute();
      $result = $stmt->rowCount();
      if ($result > 0) {
        $_SESSION["errorType"] = "success";
        $_SESSION["errorMsg"] = "Контакт успешно обновлён!.";
      } else {
        $_SESSION["errorType"] = "info";
        $_SESSION["errorMsg"] = "Вы не внесли не каких изминений контакту!";
      }
    } catch (Exception $ex) {

      $_SESSION["errorType"] = "danger";
      $_SESSION["errorMsg"] = $ex->getMessage();
    }
  } else {
    $_SESSION["errorType"] = "danger";
    $_SESSION["errorMsg"] = "Не удалось загрузить фото!!!";
  }
  header("location:app.php?pagenum=".$_POST['pagenum']);
} elseif ( $mode == "delete" ) {
   $cid = intval($_GET['cid']);
   
   $sql = "DELETE FROM `tbl_contacts` WHERE contact_id = :cid";
   try {
     
      $stmt = $DB->prepare($sql);
      $stmt->bindValue(":cid", $cid);
        
       $stmt->execute();  
       $res = $stmt->rowCount();
       if ($res > 0) {
        $_SESSION["errorType"] = "success";
        $_SESSION["errorMsg"] = "Контакт успешно удалён.";
      } else {
        $_SESSION["errorType"] = "info";
        $_SESSION["errorMsg"] = "Не удалось удалить контакт!";
      }
     
   } catch (Exception $ex) {
      $_SESSION["errorType"] = "danger";
      $_SESSION["errorMsg"] = $ex->getMessage();
   }
   
   header("location:app.php?pagenum=".$_GET['pagenum']);
}
?>