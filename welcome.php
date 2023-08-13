<?php
session_start();

if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin']!=true){
    header("location: login.php");
    exit;
}
?>

<?php  




$insert = false;
$update = false;
$delete = false;

include 'partials/_dbconnect.php';
if(isset($_GET['delete'])){
  $Id = $_GET['delete'];
  $delete = true;
  $sql = "DELETE FROM `company` WHERE `company`.`Id` = $Id";
  $result = mysqli_query($conn, $sql);
}
if ($_SERVER['REQUEST_METHOD'] == 'POST'){
if (isset( $_POST['IdEdit'])){
  // Update the record
    $Id = $_POST["IdEdit"];
    $Name = $_POST["NameEdit"];
    $Address = $_POST["AddressEdit"];

  // Sql query to be executed
  $sql = "UPDATE `company` SET `Name` = '$Name' , `Address` = '$Address' WHERE `company`.`Id` = $Id";
  $result = mysqli_query($conn, $sql);
  if($result){
    $update = true;
}
else{
    echo "We could not update the record successfully";
}
}
else{
    $Name = $_POST["Name"];
    $Address = $_POST["Address"];

  // Sql query to be executed
  $sql = "INSERT INTO `company` (`Name`, `Address`) VALUES ('$Name', '$Address')";
  $result = mysqli_query($conn, $sql);

   
  if($result){ 
      $insert = true;
  }
  else{
      echo "The record was not inserted successfully because of this error ---> ". mysqli_error($conn);
  } 
}
}
?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
    integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
    
    <title>Company Info</title>
  </head>
  <body>
    <?php require 'partials/_nav.php' ?>
    <h2 class="text-center" >Welcome - <?php echo $_SESSION['username']?></h2>
    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
      aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="editModalLabel">Edit this Company Info</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <form id="companyEditForm" action="/webassignment/welcome.php" method="POST">
            <div class="modal-body">
              <input type="hidden" name="IdEdit" id="IdEdit">
              <div class="form-group">
                <label for="Name">Name</label>
                <input type="text" class="form-control" id="NameEdit" name="NameEdit" aria-describedby="emailHelp">
                <div id="nameEditError" style="color: red;"></div>
              </div>

              <div class="form-group">
                <label for="Address">Address</label>
                <input class="form-control" id="AddressEdit" name="AddressEdit" rows="3">
                <div id="addressEditError" style="color: red;"></div>
              </div> 
            </div>
            <div class="modal-footer d-block mr-auto">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary" onclick="validateForm('Edit')">Save changes</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    

    <?php
    if($insert){
      echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
      <strong>Success!</strong> Your company info has been inserted successfully
      <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
        <span aria-hidden='true'>×</span>
      </button>
    </div>";
    }
    ?>
    <?php
    if($delete){
      echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
      <strong>Success!</strong> Your company has been deleted successfully
      <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
        <span aria-hidden='true'>×</span>
      </button>
    </div>";
    }
    ?>
    <?php
    if($update){
      echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
      <strong>Success!</strong> Your company info has been updated successfully
      <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
        <span aria-hidden='true'>×</span>
      </button>
    </div>";
    }
    ?>
    <div class="container my-4">
      <h2>Add Company Info</h2>
      <form id="companyForm" action="/webassignment/welcome.php" method="POST">
        <div class="form-group">
          <label for="Name">Company Name</label>
          <input type="text" class="form-control col-md-4" id="Name" name="Name" aria-describedby="emailHelp">
          <div id="nameError" style="color: red;"></div>
        </div>

        <div class="form-group">
          <label for="Address">Company Address</label>
          <input type="text" class="form-control col-md-4" id="Address" name="Address" rows="3">
          <div id="addressError" style="color: red;"></div>
        </div>
        <button type="button" class="btn btn-primary" onclick="validateForm('')">Add Info</button>
      </form>
    </div>

    <div class="container my-4">


      <table class="table" id="myTable">
        <thead>
          <tr>
            <th scope="col">S.No</th>
            <th scope="col">Name</th>
            <th scope="col">Address</th>
            <th scope="col">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php 
            $sql = "SELECT * FROM `company`";
            $result = mysqli_query($conn, $sql);
            $sno = 0;
            while($row = mysqli_fetch_assoc($result)){
              $sno = $sno + 1;
              echo "<tr>
              <th scope='row'>". $sno . "</th>
              <td>". $row['Name'] . "</td>
              <td>". $row['Address'] . "</td>
              <td> 
                <button class='edit btn btn-sm btn-primary' id=".$row['Id'].">Edit</button> 
                <button class='delete btn btn-sm btn-primary' id=".$row['Id'].">Delete</button> 
                <button class='employeeInfo btn btn-sm btn-primary' id=".$row['Name'].">EmployeeInfo</button>
               </td>
            </tr>";
            } 
          ?>


        </tbody>
      </table>
    </div>
    <hr>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
      integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n"
      crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
      integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
      crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
      integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6"
      crossorigin="anonymous"></script>
    <script src="//cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script>
      $(document).ready(function () {
        $('#myTable').DataTable();

      });
    </script>
    <script>
      edits = document.getElementsByClassName('edit');
      Array.from(edits).forEach((element) => {
        element.addEventListener("click", (e) => {
          console.log("edit ");
          tr = e.target.parentNode.parentNode;
          Name = tr.getElementsByTagName("td")[0].innerText;
          Address = tr.getElementsByTagName("td")[1].innerText;
          console.log(Name, Address);
          NameEdit.value = Name;
          AddressEdit.value = Address;
          IdEdit.value = e.target.id;
          console.log(e.target.id)
          $('#editModal').modal('toggle');
        })
      })

      deletes = document.getElementsByClassName('delete');
      Array.from(deletes).forEach((element) => {
        element.addEventListener("click", (e) => {
          console.log(e.target.id);
          Id = e.target.id;

          if (confirm("Are you sure you want to delete this info!")) {
            console.log("yes");
            window.location = `/webassignment/welcome.php?delete=${Id}`;
            // TODO: Create a form and use post request to submit a form
          }
          else {
            console.log("no");
          }
        })
      })
      updates = document.getElementsByClassName('employeeInfo');
      Array.from(updates).forEach((element) => {
        element.addEventListener("click", (e) => {
          console.log(e.target.id);
          Name = e.target.id;  
          window.location = `/webassignment/employee.php?companyName=${Name}`;
        })
      })

      //Validation code which checks for empty field 
      function validateForm (str){
        const name = document.getElementById("Name"+str).value;
        const address = document.getElementById("Address"+str).value;

        // Reset error messages
        document.getElementById("name"+str+"Error").textContent = "";
        document.getElementById("address"+str+"Error").textContent = "";

        let isValid = true;

        // Check if name is empty or null
        if (name === "") {
            document.getElementById("name"+str+"Error").textContent = "Name is required.";
            isValid = false;
        }
        if (address==="") {
            document.getElementById("address"+str+"Error").textContent = "Address is required.";
            isValid = false;
        }

          // If all validations passed, submit the form
        if (isValid) {
          if(str===""){
            document.getElementById("companyForm").submit();
          } else if(str==="Edit"){
            document.getElementById("companyEditForm").submit();
          }
        }
      }
    </script>
  </body>
</html>
