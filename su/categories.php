<?php
$page = "Categories";
session_start();
if (empty($_SESSION['email'])) {
  header("Location: https://carefordcorp.com/su");
  session_unset();
  session_destroy();
   exit();
}

?>
<?php require_once('header.php'); ?>
<div class="container">

  <!-- Page Heading/Breadcrumbs -->
  <h1 class="mt-4 mb-3"><?php echo $page; ?>
    <small>(Super User => Careford Corp)</small>
  </h1>

  <ol class="breadcrumb">
    <li class="breadcrumb-item">
      <a href="https://carefordcorp/su">Super User</a>
    </li>
    <li class="breadcrumb-item active"><?php echo $page; ?></li>
  </ol>
<br/>
  <div class="text-center">

    <?php
      if (!empty($_GET['msg'])) {    $msg = $_GET['msg'];     ?>
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php echo test_input($msg); ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    <?php  } ?>





  <!--view-->

    <table class="table">
      <thead>
        <tr>
          <th colspan="3"><a href="add_category.php">Add New Category</a></th>
        </tr>
        <tr>
          <th colspan="3">View Categories</th>
        </tr>
        <tr>
          <th>#</th>
          <th>Category Name</th>
          <th>Action</th>
        </tr>

      </thead>
      <tbody>
        <?php
          try {
            $ftch = $conn->prepare("SELECT * FROM categories");
            $ftch->setFetchMode(PDO::FETCH_OBJ);
            $ftch->execute();
            for ($i=1; $rfch = $ftch->fetch() ; $i++) { ?>

              <tr>
                <th scope="row"><?php echo $i; ?></th>
                <td><?php echo $rfch->title; ?></td>
                <td><a href="edit_category.php?edt=<?php echo $rfch->id; ?>">Edit</a> |
                  <a href="delete_category.php?dlt=<?php echo $rfch->id; ?>" onclick="return confirm('Do you really want to delete <?php echo $rfch->title; ?> from Categories?')">Delete</a>
                </td>
              </tr>

        <?php      }
          } catch (PDOException $e) {
            echo "Oops : " . $e->getMessage();
          }
        ?>
      </tbody>
    </table>
    <br/>
  </div>
</div>
<?php require_once('footer.php'); ?>
