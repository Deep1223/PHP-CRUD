<?php
    require("connection.php");

    if(isset($_POST['submit'])){
    $id=$_POST['id'];
    $name=$_POST['name'];
    $email=$_POST['email'];
    $date=date('Y-m-d');

        $fatchname="SELECT name FROM crud WHERE name = '$name' and id<>$id";
        $fatchsql=mysqli_query($conn, $fatchname);
        if(mysqli_num_rows($fatchsql)==0){
            if($id){
                $updatequery="UPDATE crud SET name='$name', email='$email', date='$date' WHERE id=$id"; 
                $update=mysqli_query($conn, $updatequery);
                
            }
            else{
            $insertQury = "INSERT INTO crud (name, email, date) VALUES ('$name', '$email', '$date')";
            $insert = mysqli_query($conn, $insertQury);
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="style.css">
    <title>Document</title>
</head>

<body>
    <div class="container mt-4 mb-4">
        <div class="d-flex justify-content-between">
            <div>
                <button class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">Add New</button>
            </div>
            <div>
                <input type="text" placeholder="Search..." id="search" />
            </div>
        </div>
        <div class="mt-4 mb-4">
            <table class="table table-striped table-hover" id="crudTable">
                <thead class="thead-dark">
                    <tr>
                        <th>No</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Date</th>
                        <th>Delete</th>
                        <th>Update</th>
                        <th>View</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $fatchQuery="SELECT id, name, email, date FROM crud ORDER BY date";
                        $fetch=mysqli_query($conn, $fatchQuery);
                        
                        $i=1;
                        while($row = mysqli_fetch_assoc($fetch)) {
                        echo '<tr>';
                        echo '<td>'. $i .'</td>';
                        echo '<td>'. $row['name'] .'</td>';
                        echo '<td>'. $row['email'] .'</td>';
                        $formattedDate = date('d/m/Y', strtotime($row['date']));
                        echo '<td>'. $formattedDate .'</td>';
                        echo '<td><button class="btn btn-secondary deletebtn" data-id="' . $row['id'] . '">Delete</button></td>';
                        echo '<td><button class="btn btn-secondary updatebtn" data-toggle="modal" data-target="#exampleModal" data-id="' . $row['id'] . '" data-name="' . $row['name'] . '" data-email="' . $row['email'] . '">Update</button></td>';
                        echo '<td><button class="btn btn-secondary viewbtn" data-toggle="modal" data-target="#exampleviewModal" data-id="' . $row['id'] . '" data-name="' . $row['name'] . '" data-email="' . $row['email'] . '">View</button></td>';
                        echo '</tr>';

                        $i++;
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9INacjzI4xY4L1N5AT8VQvzcbIgh7KqDFGAoF" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
    </script>
    <script>
    $(document).ready(function() {
        $('.updatebtn').on('click', function() {
            var id = $(this).data('id');
            var name = $(this).data('name');
            var email = $(this).data('email');

            $('#id').val(id);
            $('#name').val(name);
            $('#email').val(email);
        })

        $(document).ready(function() {
            $('.deletebtn').on('click', function() {
                var id = $(this).data('id');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: 'delete.php',
                            type: 'POST',
                            data: {
                                id: id
                            },
                            success: function(response) {
                                Swal.fire(
                                    'Deleted!',
                                    'Your record has been deleted.',
                                    'success'
                                ).then(() => {
                                    location
                                .reload(); // Reload after successful deletion
                                });
                            },
                            error: function() {
                                Swal.fire(
                                    'Error!',
                                    'There was an issue deleting the record.',
                                    'error'
                                );
                            }
                        });
                    }
                });
            });
        });

        $('#search').on('keyup', function() {
            var value = $(this).val().toLowerCase();
            $("#crudTable tbody tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });

        $('.viewbtn').on('click', function() {
            var id=$(this).data('id');
            var name=$(this).data('name');
            var email=$(this).data('email');

            $('#viewname').text(name);
            $('#viewemail').text(email);
        })
    });
    </script>
</body>

</html>


<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST">
                    <input type="hidden" name="id" id="id" value="0">
                    <div class="form-group">
                        <label>Name:</label>
                        <input type="text" class="form-control" name="name" id="name" />
                    </div>
                    <div class="form-group">
                        <label>Email:</label>
                        <input type="email" class="form-control" name="email" id="email" />
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary" name="submit">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleviewModal" tabindex="-1" role="dialog" aria-labelledby="exampleviewModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                    <div class="form-group">
                        <span>Name: </span><span id="viewname"></span>
                    </div>
                    <div class="form-group">
                        <span>Email: </span><span id="viewemail"></span>
                    </div>
            </div>
        </div>
    </div>
</div>