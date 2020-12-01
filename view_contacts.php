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

<body>
    <div class="post">
        <div class="post-picture"><?php $pic = ($results[0]["profile_pic"] <> "" ) ? $results[0]["profile_pic"] : "no_avatar.png" ?>
                <a href="profile_pics/<?php echo $pic ?>" target="_blank"><img src="profile_pics/<?php echo $pic ?>" alt="" width="100" height="100" class="thumbnail" ></a></div>
        <div class="post-initials">
            <p class="post-initials_name"><?php echo $results[0]["first_name"] ?></p>
            <p class="post-initials_surname"><?php echo $results[0]["last_name"] ?></p>
        </div>        
        <div class="post-number"><?php echo $results[0]["contact_no"] ?></div>
        <?php foreach ($results as $res) { ?>
            <div class="post-edit"><a href="index.php?m=update&cid=<?php echo $res["contact_id"]; ?>&pagenum=<?php echo $_GET["pagenum"]; ?>" ><button>Редактировать</button></a></div>
        <?php } ?>
    </div>    
</body>
</html>