<?php
session_start();

if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin']!=true){
    header("location: login.php");
    exit;
}
?>

<?php  
$companyName="";
if(isset($_GET['companyName'])) {
    $GLOBALS['companyName']=$_GET['companyName'];
} else if(isset($_POST['Company'])) {
    $GLOBALS['companyName']=$_POST['Company'];
} else if(isset($_POST['CompanyEdit'])) {
    $GLOBALS['companyName']=$_POST['CompanyEdit'];
} else if(isset($_GET['delete'])) {
    $GLOBALS['companyName']=$_POST['CompanyEdit'];
}


// INSERT INTO `notes` (`sno`, `title`, `description`, `tstamp`) VALUES (NULL, 'But Books', 'Please buy books from Store', current_timestamp());
$insert = false;
$update = false;
$delete = false;

include 'partials/_dbconnect.php';
if(isset($_GET['delete'])){
  $Id = $_GET['delete'];
  $delete = true;
  $sql = "DELETE FROM `employee` WHERE `employee`.`Id` = $Id";
  $result = mysqli_query($conn, $sql);
}
if ($_SERVER['REQUEST_METHOD'] == 'POST'){
if (isset( $_POST['IdEdit'])){
  // Update the record
    $Id = $_POST["IdEdit"];
    $Name = $_POST["NameEdit"];
    $Salary = $_POST["SalaryEdit"];
    $Date = $_POST["DateEdit"];

  // Sql query to be executed
  $sql = "UPDATE `employee` SET `Name` = '$Name' , `Salary` = '$Salary', `DOB` = '$Date' WHERE `employee`.`Id` = $Id";
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
    $Salary = $_POST["Salary"];
    $DOB = $_POST["Date"];
    $Company = $_POST["Company"];

  // Sql query to be executed
  $sql = "INSERT INTO `employee` (`Name`, `Salary`, `DOB`, `Company`) VALUES ('$Name', '$Salary', '$DOB', '$Company')";
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
    
    <title>Employee Info</title>
  </head>
  <body>
    <?php require 'partials/_nav.php' ?>
    <h2 class="text-center" >Welcome to <?php echo $companyName?></h2>
    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
      aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="editModalLabel">Edit this Employee Info</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <form id="employeeEditForm" action="/webassignment/employee.php" method="POST">
            <div class="modal-body">
              <input type="hidden" name="IdEdit" id="IdEdit">
              <div class="form-group">
                <label for="Name">Name</label>
                <input type="text" class="form-control" id="NameEdit" name="NameEdit" aria-describedby="emailHelp">
                <div id="nameEditError" style="color: red;"></div>
              </div>

              <div class="form-group">
                <label for="Salary">Salary</label>
                <input type="number" class="form-control" id="SalaryEdit" name="SalaryEdit" rows="3" min="10000" max="50000">
                <div id="salaryEditError" style="color: red;"></div>
              </div> 
              <div class="form-group">
                <label for="Date">Date of Birth</label>
                <input type="date" class="form-control" id="DateEdit" name="DateEdit" rows="3">
                <div id="dobEditError" style="color: red;"></div>
              </div> 
              <div class="form-group">
                <label for="Company">Company</label>
                <textarea readonly class="form-control" id="CompanyEdit" name="CompanyEdit" rows="3"><?php echo $companyName; ?></textarea>
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
      <strong>Success!</strong> Your employee info has been inserted successfully
      <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
        <span aria-hidden='true'>×</span>
      </button>
    </div>";
    }
    ?>
    <?php
    if($delete){
      echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
      <strong>Success!</strong> Your employee has been deleted successfully
      <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
        <span aria-hidden='true'>×</span>
      </button>
    </div>";
    }
    ?>
    <?php
    if($update){
      echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
      <strong>Success!</strong> Your employee info has been updated successfully
      <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
        <span aria-hidden='true'>×</span>
      </button>
    </div>";
    }
    ?>
    <div class="container mx-auto px-5">
      <h2>Add Employee Info</h2>
      <form id="employeeForm" action="/webassignment/employee.php" method="POST">
        <div class="form-group">
          <label for="Name">Name</label>
          <input type="text" class="form-control col-md-4" id="Name" name="Name" aria-describedby="emailHelp">
          <div id="nameError" style="color: red;"></div>
        </div>

        <div class="form-group">
          <label for="Salary">Salary</label>
          <input type="number" class="form-control col-md-4" id="Salary" name="Salary" rows="3" min="10000" max="50000">
          <div id="salaryError" style="color: red;"></div>
        </div>
        <div class="form-group">
          <label for="Date">Date of Birth</label>
          <input type="date" class="form-control col-md-4" id="Date" name="Date" rows="3">
          <div id="dobError" style="color: red;"></div>
        </div>
        <div class="form-group">
          <label for="Company">Company</label>
          <input readonly class="form-control col-md-4" id="Company" name="Company" rows="3" value="<?php echo $companyName; ?>">
          
        </div>
        <button type="button" class="btn btn-primary" onclick="validateForm('')">Add Info</button>
      </form>
    </div>

    <!-- //Represents value from database to the Web UI in the table -->
    <div class="container my-4">
      <table class="table" id="myTable">
        <thead>
          <tr>
            <th scope="col">S.No</th>
            <th scope="col">Name</th>
            <th scope="col">Salary</th>
            <th scope="col">Date of Birth</th>
            <th scope="col">Company</th>
            <th scope="col">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php 
            $sql = "SELECT * FROM `employee` WHERE `employee`.`Company` = '$companyName'";
            $result = mysqli_query($conn, $sql);
            $sno = 0;
            while($row = mysqli_fetch_assoc($result)){
              $sno = $sno + 1;
              echo "<tr>
              <th scope='row'>". $sno . "</th>
              <td>". $row['Name'] . "</td>
              <td>". $row['Salary'] . "</td>
              <td>". $row['DOB'] . "</td>
              <td>". $row['Company'] . "</td>
              <td> 
                <button class='edit btn btn-sm btn-primary' id=".$row['Id'].">Edit</button> 
                <button class='delete btn btn-sm btn-primary' id=".$row['Id'].">Delete</button> 
               </td>
                </tr>";
            } 
          ?>
        </tbody>
      </table>
        <a class="nav-link p-0 mt-4" href="/WebAssignment/welcome.php"><button type="button" class="btn btn-secondary">Return to Company Info</button><span class="sr-only">(current)</span></a>
        
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
        //Sends value to edit in modal 
      edits = document.getElementsByClassName('edit');
      Array.from(edits).forEach((element) => {
        element.addEventListener("click", (e) => {
          console.log("edit ");
          tr = e.target.parentNode.parentNode;
          Name = tr.getElementsByTagName("td")[0].innerText;
          Salary = tr.getElementsByTagName("td")[1].innerText;
          DOB = tr.getElementsByTagName("td")[2].innerText;

          console.log(Name, Salary);
          NameEdit.value = Name;
          SalaryEdit.value = Salary;
          DateEdit.value = DOB;
          IdEdit.value = e.target.id;
          console.log(e.target.id)
          $('#editModal').modal('toggle');
        })
      })

      //Provides id to delete the information of
      deletes = document.getElementsByClassName('delete');
      Array.from(deletes).forEach((element) => {
        element.addEventListener("click", (e) => {
          console.log(e.target.id);
          tr = e.target.parentNode.parentNode;
          Id = e.target.id;
          Company = tr.getElementsByTagName("td")[3].innerText;

          if (confirm("Are you sure you want to delete this info!")) {
            console.log("yes");
            window.location = `/webassignment/employee.php?delete=${Id}&companyName=${Company}`;
            // TODO: Create a form and use post request to submit a form
          }
          else {
            console.log("no");
          }
        })
      })

      
      //Validation code if there is any empty field or conditions not satisfied
      function validateForm (str) {
            console.log(str);
            const name = document.getElementById("Name"+str).value;
            const dob = document.getElementById("Date"+str).value;
            const salary = parseFloat(document.getElementById("Salary"+str).value);

            // Reset error messages
            document.getElementById("name"+str+"Error").textContent = "";
            document.getElementById("salary"+str+"Error").textContent = "";
            document.getElementById("dob"+str+"Error").textContent = "";

            let isValid = true;

            // Check if name is empty or null
            if (name === "") {
                document.getElementById("name"+str+"Error").textContent = "Name is required.";
                isValid = false;
            }
            if (dob===null || dob==='') {
                document.getElementById("dob"+str+"Error").textContent = "Date of Birth is required.";
                isValid = false;
            }

            // Check salary range
            if (salary < 10000 || salary > 50000) {
                document.getElementById("salary"+str+"Error").textContent = "Salary should be between 10,000 and 50,000.";
                isValid = false;
            }

             // If all validations passed, submit the form
             if (isValid) {
                if(str===""){
                    document.getElementById("employeeForm").submit();
                }
                else if (str==='Edit') {
                    document.getElementById("employeeEditForm").submit();
                }
            }
      }
      
    </script>
  </body>
</html>
