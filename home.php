<?php

include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
}

if(isset($_POST['add_to_borrow'])){

   $book_name = $_POST['book_name'];
   $book_category = $_POST['book_category'];
   $book_image = $_POST['book_image'];
   $book_quantity = $_POST['book_quantity'];

   $check_borrow_numbers = mysqli_query($conn, "SELECT * FROM `borrow` WHERE name = '$book_name' AND user_id = '$user_id'") or die('query failed');

   if(mysqli_num_rows($check_borrow_numbers) > 0){
      $message[] = 'already added to borrow!';
   }else{
      mysqli_query($conn, "INSERT INTO `borrow`(user_id, name, category, quantity, image) VALUES('$user_id', '$book_name', '$book_category', '$book_quantity', '$book_image')") or die('query failed');
      $message[] = 'book added to borrow!';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>home</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'header.php'; ?>

<section class="home">

   <div class="content">
      <h3>Explore the world of books.</h3>
      <p>Our online library provide the students with the convenience of learning at their own comfort.</p>
      <a href="about.php" class="white-btn">discover more</a>
   </div>

</section>

<section class="books">

   <h1 class="title">books</h1>

   <div class="box-container">

      <?php  
         $select_books = mysqli_query($conn, "SELECT * FROM `books` LIMIT 6") or die('query failed');
         if(mysqli_num_rows($select_books) > 0){
            while($fetch_books = mysqli_fetch_assoc($select_books)){
      ?>
     <form action="" method="post" class="box">
      <img class="image" src="uploaded_img/<?php echo $fetch_books['image']; ?>" alt="">
      <div class="name"><?php echo $fetch_books['name']; ?></div>
      <div class="category"><?php echo $fetch_books['category']; ?></div>
      <input type="number" min="1" name="book_quantity" value="1" class="qty">
      <input type="hidden" name="book_name" value="<?php echo $fetch_books['name']; ?>">
      <input type="hidden" name="book_category" value="<?php echo $fetch_books['category']; ?>">
      <input type="hidden" name="book_image" value="<?php echo $fetch_books['image']; ?>">
      <input type="submit" value="add to borrow" name="add_to_borrow" class="btn">
     </form>
      <?php
         }
      }else{
         echo '<p class="empty">no books added yet!</p>';
      }
      ?>
   </div>

   <div class="load-more" style="margin-top: 2rem; text-align:center">
      <a href="library.php" class="option-btn">load more</a>
   </div>

</section>

<section class="about">

   <div class="flex">

      <div class="image">
         <img src="images/about-img.jpg" alt="">
      </div>

      <div class="content">
         <h3>about us</h3>
         <p>Our online library provide the students with the convenience of learning at their own comfort.</p>
         <a href="about.php" class="btn">read more</a>
      </div>

   </div>

</section>

<section class="home-contact">

   <div class="content">
      <h3>have any questions?</h3>
      <p>Feel free to contact us if you need any further information. Please let us know if you have any questions. </p>
      <a href="contact.php" class="white-btn">contact us</a>
   </div>

</section>





<?php include 'footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>