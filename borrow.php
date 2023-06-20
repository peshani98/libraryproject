<?php

include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
}

if(isset($_POST['update_borrow'])){
   $borrow_id = $_POST['borrow_id'];
   $borrow_quantity = $_POST['borrow_quantity'];
   mysqli_query($conn, "UPDATE `borrow` SET quantity = '$borrow_quantity' WHERE id = '$borrow_id'") or die('query failed');
   $message[] = 'borrow quantity updated!';
}

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   mysqli_query($conn, "DELETE FROM `borrow` WHERE id = '$delete_id'") or die('query failed');
   header('location:borrow.php');
}

if(isset($_GET['delete_all'])){
   mysqli_query($conn, "DELETE FROM `borrow` WHERE user_id = '$user_id'") or die('query failed');
   header('location:borrow.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>cart</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'header.php'; ?>

<div class="heading">
   <h3>book borrow</h3>
   <p> <a href="home.php">home</a> / borrow </p>
</div>

<section class="book-borrow">

   <h1 class="title">books added</h1>

   <div class="box-container">
      <?php
         $grand_total = 0;
         $select_borrow= mysqli_query($conn, "SELECT * FROM `borrow` WHERE user_id = '$user_id'") or die('query failed');
         if(mysqli_num_rows($select_borrow) > 0){
            while($fetch_borrow = mysqli_fetch_assoc($select_borrow)){   
      ?>
      <div class="box">
         <a href="borrow.php?delete=<?php echo $fetch_borrow['id']; ?>" class="fas fa-times" onclick="return confirm('delete this from borrow?');"></a>
         <img src="uploaded_img/<?php echo $fetch_borrow['image']; ?>" alt="">
         <div class="name"><?php echo $fetch_borrow['name']; ?></div>
         <div class="category"><?php echo $fetch_borrow['category']; ?></div>
         <form action="" method="post">
            <input type="hidden" name="borrow_id" value="<?php echo $fetch_borrow['id']; ?>">
            <input type="number" min="1" name="borrow_quantity" value="<?php echo $fetch_borrow['quantity']; ?>">
            <input type="submit" name="update_borrow" value="update" class="option-btn">
         </form>
         <div class="sub-total"> sub total : <span><?php echo $sub_total = ($fetch_borrow['quantity'] ); ?></span> </div>
      </div>
      <?php
      $grand_total += $sub_total;
         }
      }else{
         echo '<p class="empty">your borrow is empty</p>';
      }
      ?>
   </div>

   <div style="margin-top: 2rem; text-align:center;">
      <a href="borrow.php?delete_all" class="delete-btn <?php echo ($grand_total > 1)?'':'disabled'; ?>" onclick="return confirm('delete all from cart?');">delete all</a>
   </div>

   <div class="borrow-total">
      <p>grand total : <span><?php echo $grand_total; ?></span></p>
      <div class="flex">
         <a href="library.php" class="option-btn">continue borrowing</a>
         <a href="checkout.php" class="btn <?php echo ($grand_total >= 1)?'':'disabled'; ?>">proceed to checkout</a>
      </div>
   </div>

</section>








<?php include 'footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>