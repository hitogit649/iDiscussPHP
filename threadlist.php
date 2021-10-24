
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous" />
    <style>
    #ques {
        min-height: 433px;
    }
    </style>
    <title>Welcome to iDiscuss - coding Forums</title>
</head>

<body>
    <?php include 'partials/_dbconnect.php'; ?>
    <?php include 'partials/_header.php'; ?>
    <?php
    $id = $_GET['catid'];
    $sql = "SELECT * FROM `categories` WHERE category_id=$id"; 
    $result = mysqli_query($conn, $sql);
    while($row = mysqli_fetch_assoc($result)){
        $catname = $row['category_name'];
        $catdesc = $row['category_description'];

    }
    
    ?>
    <?php
    $showAlert = false;
    $method = $_SERVER['REQUEST_METHOD'];
    if($method=='POST'){
        // insert into thread db
        $th_title = $_POST['title'];
        $th_desc = $_POST['desc'];
        $sno = $_POST['sno'];

        $th_title = str_replace("<", "&lt", $th_title);
        $th_title = str_replace(">", "&gt", $th_title);

        $th_desc = str_replace("<", "&lt", $th_desc);
        $th_desc = str_replace(">", "&gt", $th_desc);

        $sql = "INSERT INTO `threads` (`thread_title`, `thread_desc`, `thread_cat_id`, `thread_user_id`, `timestamp`) VALUES ('$th_title', '$th_desc', '$id', ' $sno', current_timestamp())"; 
        $result = mysqli_query($conn, $sql);
        $showAlert = true;
        if($showAlert){
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Success!</strong> Your thread has been added! Please wait for community to respond
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                     </div>
            ';
        }


    }
    ?>

    <div class="container my-4">
        <div class="jumbotron">
            <h1 class="display-4">Welcome to <?php echo $catname; ?> Forums</h1>
            <p class="lead"><?php echo $catdesc; ?></p>
            <hr class="my-4">
            <p>This is a peer to peer Forums. No Spam / Advertising / Self-promote in the forums is not allowed
                Do not post copyright-infringing material. Do not post “offensive” posts, links or images. Do not cross
                post questions. Remain respectful of other members at all times.</p>
            <a class="btn btn-success btn-lg" href="#" role="button">Learn more</a>
        </div>

    </div>

    <?php
    if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true){
    echo '<div class="container">
        <h1 class="py-3">Satrt a Discussion</h1>
        <form action="' . $_SERVER["REQUEST_URI"] . '" method="post">
           <div class="form-group">
                <label for="exampleInputEmail1">Problem Title</label>
                <input type="text" class="form-control" id="title" name="title" aria-describedby="emailHelp">
                <small id="emailHelp" class="form-text text-muted">Keep your title as short and crisp as
                    possible</small>
            </div>
            <div class="form-group">
                <label for="exampleFormControlTextarea1">Ellaborate Your concern</label>
                <textarea class="form-control" id="desc" name="desc" rows="3"></textarea>
                <input type="hidden" name="sno" value="'. $_SESSION['sno'].'">
            </div>
            <button type="submit" class="btn btn-success">Submit</button>
        </form>
    </div>';
    }
    else{
        echo '
        <div class="container">
        <h2 class="py-3">Satrt a Discussion</h2>
            <p class="lead">You are not logged in. Please login to be able to start a Discussion </p>
        </div>
        ';
    }

?>
    
    <div class="container" id="ques">
        <h1 class="py-3">Browse Questions</h1>
    <?php
    $id = $_GET['catid'];
    $noresult = true;
    $sql = "SELECT * FROM `threads` WHERE thread_cat_id=$id"; 
    $result = mysqli_query($conn, $sql);
    while($row = mysqli_fetch_assoc($result)){
        $noresult = false;
        $id = $row['thread_id'];
        $title = $row['thread_title'];
        $desc = $row['thread_desc'];

        $thread_time = $row['timestamp'];
        $thread_user_id = $row['thread_user_id']; 
        $sql2 = "SELECT user_email FROM `users` WHERE sno='$thread_user_id'";
        $result2 = mysqli_query($conn, $sql2);
        $row2 = mysqli_fetch_assoc($result2);
        
              echo '<div class="media my-4">
                    <img src="img/userdefult.png" width="55px" class="mr-3" alt="...">
                    <div class="media-body">'.
                    
                        '<h5 class="mt-0"><a class="text-dark" href="thread.php?threadid='. $id.'">' . $title . '</a></h5>
                        <p>' . $desc . '</p>
                    </div>'.'<p class="font-weight-bold my-0"> '. $row2['user_email'].' at ('.$thread_time.')</p>'.
                '</div>';

    }
    // echo var_dump($noresult);
    if($noresult){
        echo '<div class="jumbotron jumbotron-fluid">
                <div class="container">
                <p class="display-4">No Threads Found</p>
                <p class="lead">This is a modified jumbotron that occupies the entire horizontal space of its parent.</p>
                </div>
            </div>';
    }
    
    ?>


    </div>

    <?php include 'partials/_footer.php'; ?>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
    </script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
    </script>
</body>

</html>