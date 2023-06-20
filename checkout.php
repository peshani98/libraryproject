<?php

include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
}

if(isset($_POST['order_btn'])){

   $name = mysqli_real_escape_string($conn, $_POST['name']);
   $number = $_POST['number'];
   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $method = mysqli_real_escape_string($conn, $_POST['method']);
   $address = mysqli_real_escape_string($conn, 'no '. $_POST['no'].', '. $_POST['street'].', '. $_POST['city'].', '. $_POST['country'].' - '. $_POST['pin_code']);
   $placed_on = date('d-M-Y');

  
   $borrow_total = 0;
   $borrow_books[] = '';

   $borrow_query = mysqli_query($conn, "SELECT * FROM `borrow` WHERE user_id = '$user_id'") or die('query failed');
   if(mysqli_num_rows($borrow_query) > 0){
      while($borrow_item = mysqli_fetch_assoc($borrow_query)){
         $borrow_books[] = $borrow_item['name'].' ('.$borrow_item['quantity'].') ';
         $sub_total = ($borrow_item['quantity']);
         $borrow_total = $sub_total;
      }
   }

   $total_books = implode(', ',$borrow_books);

   $order_query = mysqli_query($conn, "SELECT * FROM `orders` WHERE name = '$name' AND number = '$number' AND email = '$email' AND method = '$method' AND address = '$address'  AND total_books = '$name'  ") or die('query failed');

  









      if(mysqli_num_rows($order_query) > 0){
         $message[] = 'order already placed!'; 
      }else{
         mysqli_query($conn, "INSERT INTO `orders`(user_id, name, number, email, method, address, total_books,  placed_on) VALUES('$user_id', '$name', '$number', '$email', '$method', '$address', '$total_books',  '$placed_on')") or die('query failed');
         $message[] = 'order placed successfully!';
         mysqli_query($conn, "DELETE FROM `borrow` WHERE user_id = '$user_id'") or die('query failed');
      }
   }
   


?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>checkout</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'header.php'; ?>

<div class="heading">
   <h3>checkout</h3>
   <p> <a href="home.php">home</a> / checkout </p>
</div>

<section class="display-order">

   <?php  
     
      $select_borrow = mysqli_query($conn, "SELECT * FROM `borrow` WHERE user_id = '$user_id'") or die('query failed');
      if(mysqli_num_rows($select_borrow) > 0){
         while($fetch_borrow = mysqli_fetch_assoc($select_borrow)){
            $total_books = ($fetch_borrow['quantity']);
           
   ?>
   <p> <?php echo $fetch_borrow['name']; ?> <span>(<?php echo '0'. $fetch_borrow['quantity']; ?>)</span> </p>
   <?php
      }
   }else{
      echo '<p class="empty">your borrow is empty</p>';
   }
   ?>
 

</section>

<section class="checkout">

   <form action="" method="post">
      <h3>borrow your book</h3>
      <div class="flex">
         <div class="inputBox">
            <span>your name :</span>
            <input type="text" name="name" required placeholder="enter your name">
         </div>
         <div class="inputBox">
            <span>your number :</span>
            <input type="number" name="number" required placeholder="enter your number">
         </div>
         <div class="inputBox">
            <span>your email :</span>
            <input type="email" name="email" required placeholder="enter your email">
         </div>
         <div class="inputBox">
            <span>borrow method :</span>
            <select name="method">
               <option value="get a soft copy to email">get a soft copy to email</option>
               <option value="get a boook at home">get a boook at home</option>
              
            </select>
         </div>
         <div class="inputBox">
            <span>address line 01 :</span>
            <input type="text" name="no" required placeholder="e.g.  no.">
         </div>
         <div class="inputBox">
            <span>address line 02 :</span>
            <input type="text" name="street" required placeholder="e.g. street name">
         </div>
         <div class="inputBox">
            <span>home town:</span>
            <input type="text" name="home town" required placeholder="e.g.nugegoda">
         </div>
         <div class="inputBox">
            <span>city :</span>
            <input type="text" name="city" required placeholder="e.g. colombo">
         </div>
         <div class="inputBox">
            <span>country :</span>
            <input type="text" name="country" required placeholder="e.g. sri lanka">
         </div>
         <div class="inputBox">
            <span>pin code :</span>
            <input type="number" min="0" name="pin_code" required placeholder="e.g. 123456">
         </div>
      </div>
      <input type="submit" value="order now" class="btn" name="order_btn">
   </form>

</section>









<?php include 'footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>