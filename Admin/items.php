<?php
/*==========items Page========== */

ob_start(); // out buffering start
session_start();
if (isset($_SESSION['username'])) {
    $pageTitle = 'Items';
    include "init.php";

    $do =  isset($_GET['do']) ?  $_GET['do'] :  'manage';

    if ($do == 'manage') {
        $query = '';

        // select all database excipt admin
        $stmt = $con->prepare("SELECT items.*,categories.Name AS category_name, users.Username FROM items
        INNER JOIN categories ON categories.ID = items.Cat_ID 
        INNER JOIN users ON users.UserId = items.Member_ID ORDER BY item_iD ASC");
        // execute to variable
        $stmt->execute();
        // assign to variable
        $items = $stmt->fetchAll();
        if (!empty($items)) {


?>
            <h1 class="text-center">Manage Items</h1>
            <div class="container">
                <div class="table-responsive">
                    <table class="main-table manage-members text-center table table-bordered">
                        <tr>
                            <td>#ID</td>
                            <td>Image</td>
                            <td>Name</td>
                            <td>Description</td>
                            <td>Price</td>
                            <td>Adding Date</td>
                            <td>Category</td>
                            <td>Username</td>
                            <td>Control</td>
                        </tr>
                        <?php

                        foreach ($items as $item) {

                            echo "<tr>";
                            echo "<td>" . $item['item_iD'] . "</td>";
                            echo "<td><img src='products/phones/" . $item['img'] . "' alt=''/></td>";
                            echo "<td>" . $item['Name'] . "</td>";
                            echo "<td>" . $item['Description'] . "</td>";
                            echo "<td>" . $item['Price'] . "</td>";
                            echo "<td>" . $item['Add_Date'] . "</td>";
                            echo "<td>" . $item['category_name'] . "</td>";
                            echo "<td>" . $item['Username'] . "</td>";
                            echo "<td>
                          <a href='items.php?do=Edit&itemid=" . $item['item_iD'] . "' class='btn btn-success'>Edit</a>
                          <a href= 'items.php?do=Delete&itemid=" . $item['item_iD'] . "'  class='btn btn-danger confirm'>Delete</a>";

                            if ($item['Approve'] == 0) {
                                echo "<a
                             href= 'items.php?do=Approve&itemid=" . $item['item_iD'] . "'  
                             class='btn btn-info activet'>Approve</a>";
                            }
                            echo "</td>";

                            echo "</tr>";
                        }

                        ?>


                    </table>
                </div>
                <a href="items.php?do=Add" class="btn btn-sm btn-primary"> New Items</a>
            </div>
        <?php } else {
            echo '<div class="container">';
            echo '<div class"nice-message">There\'s No Items To Show</div>';
            echo '<a href="items.php?do=Add" class="btn btn-sm btn-primary"> New Items</a>';
            echo '</div>';
        } ?>
    <?php  } elseif ($do == 'Add') { ?>
        <h1 class="text-center">Add New Item</h1>
        <div class="container">
            <form class="form-horizontal" action="?do=Insert" method="POST" enctype="multipart/form-data">

                <!--start Name-->
                <div class="form-group ">
                    <label class="col-sm-2 control-label"> Name</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" name="name" class=" form-control form-control-lg" autocomplete="off" required="required" placeholder="Name of The Items ">
                    </div>
                </div>
                <!--end Name-->
                <!--start Description-->
                <div class="form-group ">
                    <label class="col-sm-2 control-label"> Description</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" name="description" class=" form-control form-control-lg" autocomplete="off" required="required" placeholder="Description of The Items ">
                    </div>
                </div>
                <!--end Description-->
                <!--start Price-->
                <div class="form-group ">
                    <label class="col-sm-2 control-label"> Price</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" name="price" class=" form-control form-control-lg" autocomplete="off" required="required" placeholder="Price of The Items ">
                    </div>
                </div>
                <!--end Price-->
                <!--start Country-->
                <div class="form-group ">
                    <label class="col-sm-2 control-label"> Country</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" name="country" class=" form-control form-control-lg" autocomplete="off" required="required" placeholder="Country Made ">
                    </div>
                </div>
                <!--end Country-->
                <!--start Status-->
                <div class="form-group ">
                    <label class="col-sm-2 control-label">Status</label>
                    <div class="col-sm-10 col-md-6">
                        <select name="status">
                            <option value="0">Choose US</option>
                            <option value="1">New</option>
                            <option value="2">Like New</option>
                            <option value="3">Used</option>
                            <option value="4">Old</option>
                        </select>
                    </div>
                </div>
                <!--end Status-->
                <!--start member field-->
                <div class="form-group ">
                    <label class="col-sm-2 control-label">Member</label>
                    <div class="col-sm-10 col-md-6">
                        <select name="member">
                            <option value="0">Choose US</option>
                            <?php
                            $allMembers = getAllFrom("*", "users", "", "", "UserId");
                            foreach ($allMembers as $user) {
                                echo "<option value='" . $user['UserId'] . "'>" . $user['Username'] . "</option>";
                            }

                            ?>

                        </select>
                    </div>
                </div>
                <!--end member field-->
                <!--start category field-->
                <div class="form-group ">
                    <label class="col-sm-2 control-label">Category</label>
                    <div class="col-sm-10 col-md-6">
                        <select name="category">
                            <option value="0">Choose US</option>
                            <?php
                            $allCats = getAllFrom("*", "categories", "where parent = 0", "", "ID");
                            foreach ($allCats as $cat) {
                                echo "<option value='" . $cat['ID'] . "'>" . $cat['Name'] . "</option>";
                                $childCats = getAllFrom("*", "categories", "where parent = {$cat['ID']}", "", "ID");
                                foreach ($childCats as $child) {
                                    echo "<option value='" . $child['ID'] . "'>---" . $child['Name'] . "</option>";
                                }
                            }

                            ?>

                        </select>
                    </div>
                </div>
                <!--end category field-->
                <!--start tags-->
                <div class="form-group ">
                    <label class="col-sm-2 control-label"> Tags </label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" name="tags" class=" form-control form-control-lg" placeholder="Sperate It With Comma(,)">
                    </div>
                </div>
                <!--end tags-->
                <!--start User Image-->
                <div class="form-group">
                    <label class="col-sm-2 control-label">Item Image</label>
                    <div class="col-sm-10 col-md-6">
                        <div class="custom-file">
                            <input type="file" name="img" class="custom-file-input" id="inputGroupFile01" aria-describedby="inputGroupFileAddon01" required="required">
                            <label class="custom-file-label" for="inputGroupFile01">Choose file</label>
                        </div>
                    </div>
                </div>
                <!--end User Image-->
                <!--start button-->
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <input type="submit" value="Add Item" class="btn btn-primary btn-sm">
                    </div>
                </div>
                <!--end button-->
            </form>
        </div>
        <?php
    } elseif ($do == 'Insert') {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            echo " <h1 class='text-center'>Item Inserted</h1>";
            echo "<div class='container'>";

            // Upload varabiales
            $avatarName     = $_FILES['img']['name'];
            $avatarSize     = $_FILES['img']['size'];
            $avatarTmp      = $_FILES['img']['tmp_name'];
            $avatarType     = $_FILES['img']['type'];

            //list of allowed type file to upload

            $avatarAllowedExtension = array("jpeg", "jpg", "png", "gif");

            //get avatar Extension
            $avatarExtension = explode('.', $avatarName);
            $dump = strtolower(end($avatarExtension));


            // Get Varabiale From Form..
            $name       = $_POST['name'];
            $desc       = $_POST['description'];
            $price      = $_POST['price'];
            $country    = $_POST['country'];
            $status     = $_POST['status'];
            $cat        = $_POST['category'];
            $member     = $_POST['member'];
            $tags       = $_POST['tags'];


            // validate the form
            $formErrors = array();
            if (empty($name)) {
                $formErrors[] = 'Name can\'t be empty <strong>Empty</strong>';
            }
            if (empty($desc)) {
                $formErrors[] = 'Description can\'t be empty <strong>Empty</strong>';
            }
            if (empty($price)) {
                $formErrors[] = 'Price can\'t be empty <strong>Empty</strong>';
            }
            if (empty($country)) {
                $formErrors[] = 'Country can\'t be empty <strong>Empty</strong>';
            }
            if (($status) == 0) {
                $formErrors[] = 'You Must Choose The  <strong>Status</strong>';
            }
            if (($member) == 0) {
                $formErrors[] = 'You Must Choose The  <strong>Member</strong>';
            }
            if (($cat) == 0) {
                $formErrors[] = 'You Must Choose The  <strong>Category</strong>';
            }
            if (!empty($avatarName) && !in_array($dump, $avatarAllowedExtension)) {
                $formErrors[] = 'This Extension Not Allowed';
            }
            if (empty($avatarName)) {
                $formErrors[] = 'Image Is required';
            }
            if ($avatarSize  > 4194304) {
                $formErrors[] = 'Image Cant\' be larger Than 4MB';
            }

            foreach ($formErrors as $error) {
                echo '<div class="alert alert-danger">' . $error . '</div>';
            }

            // check if there is no erorr proceed the update operation 
            if (empty($formErrors)) {
                $img = rand(0, 100000000) . '_' . $avatarName;
                move_uploaded_file($avatarTmp, "products\phones\\" . $img);

                // Insert The Database With This Info
                $stmt = $con->prepare("INSERT INTO items(Name, Description, Price, Country_Made, Status, Add_Date, Cat_ID, Member_ID, tags, img)
                                        VALUES(:zname, :zdesc, :zprice, :zcountry, :zstatus, now(), :zcat, :zmember, :ztags, :zimg)");
                $stmt->execute(array(
                    'zname'     => $name,
                    'zdesc'     => $desc,
                    'zprice'    => $price,
                    'zcountry'  => $country,
                    'zstatus'   => $status,
                    'zcat'      => $cat,
                    'zmember'   => $member,
                    'ztags'     => $tags,
                    'zimg'      => $img
                ));
                // echo The Sucess Message    
                $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . 'Record Inserted</div>';
                redierctHome($theMsg, 'back');
            }
        } else {
            echo '<div class="container">';
            $theMsg = "<div class='alert alert-danger'> Sorry You Can\'t Browse This Page Directly</div>";
            redierctHome($theMsg);
            echo '</div>';
        }
        echo "</div>";
    } elseif ($do == 'Edit') {
        $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;

        $stmt = $con->prepare("SELECT *  FROM items WHERE item_ID = ?");
        $stmt->execute(array($itemid));
        $item = $stmt->fetch(); // اجلب //
        $count = $stmt->rowCount();
        if ($stmt->rowCount() > 0) { ?>
            <h1 class="text-center">Edit Item</h1>
            <div class="container">
                <form class="form-horizontal" action="?do=Update" method="POST">
                    <input type="hidden" name="itemid" value="<?php echo $itemid ?>" />
                    <!--start Name-->
                    <div class="form-group ">
                        <label class="col-sm-2 control-label"> Name</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" name="name" class=" form-control form-control-lg" autocomplete="off" required="required" placeholder="Name of The Items " value="<?php echo $item['Name'] ?>">
                        </div>
                    </div>
                    <!--end Name-->
                    <!--start Description-->
                    <div class="form-group ">
                        <label class="col-sm-2 control-label"> Description</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" name="description" class=" form-control form-control-lg" autocomplete="off" required="required" placeholder="Description of The Items " value="<?php echo $item['Description'] ?>">
                        </div>
                    </div>
                    <!--end Description-->
                    <!--start Price-->
                    <div class="form-group ">
                        <label class="col-sm-2 control-label"> Price</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" name="price" class=" form-control form-control-lg" autocomplete="off" required="required" placeholder="Price of The Items " value="<?php echo $item['Price'] ?>">
                        </div>
                    </div>
                    <!--end Price-->
                    <!--start Country-->
                    <div class="form-group ">
                        <label class="col-sm-2 control-label"> Country</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" name="country" class=" form-control form-control-lg" autocomplete="off" required="required" placeholder="Country Made " value="<?php echo $item['Country_Made'] ?>">
                        </div>
                    </div>
                    <!--end Country-->
                    <!--start Status-->
                    <div class="form-group ">
                        <label class="col-sm-2 control-label">Status</label>
                        <div class="col-sm-10 col-md-6">
                            <select name="status">
                                <option value="1" <?php if ($item['Status'] == 1) {
                                                        echo 'selected';
                                                    } ?>>New</option>
                                <option value="2" <?php if ($item['Status'] == 2) {
                                                        echo 'selected';
                                                    } ?>>Like New</option>
                                <option value="3" <?php if ($item['Status'] == 3) {
                                                        echo 'selected';
                                                    } ?>>Used</option>
                                <option value="4" <?php if ($item['Status'] == 4) {
                                                        echo 'selected';
                                                    } ?>>Old</option>
                            </select>
                        </div>
                    </div>
                    <!--end Status-->
                    <!--start member field-->
                    <div class="form-group ">
                        <label class="col-sm-2 control-label">Member</label>
                        <div class="col-sm-10 col-md-6">
                            <select name="member">

                                <?php
                                $stmt = $con->prepare("SELECT * FROM users");
                                $stmt->execute();
                                $users = $stmt->fetchAll();
                                foreach ($users as $user) {
                                    echo "<option value='" . $user['UserId'] . "'";
                                    if ($item['Member_ID'] == $user['UserId']) {
                                        echo 'selected';
                                    }
                                    echo ">" . $user['Username'] . "</option>";
                                }

                                ?>

                            </select>
                        </div>
                    </div>
                    <!--end member field-->
                    <!--start category field-->
                    <div class="form-group ">
                        <label class="col-sm-2 control-label">Category</label>
                        <div class="col-sm-10 col-md-6">
                            <select name="category">

                                <?php
                                $stmt2 = $con->prepare("SELECT * FROM  categories");
                                $stmt2->execute();
                                $cats = $stmt2->fetchAll();
                                foreach ($cats as $cat) {
                                    echo "<option value='" . $cat['ID'] . "'";
                                    if ($item['Cat_ID'] == $cat['ID']) {
                                        echo 'selected';
                                    }
                                    echo ">" . $cat['Name'] . "</option>";
                                }

                                ?>

                            </select>
                        </div>
                    </div>
                    <!--end category field-->
                    <!--start tags-->
                    <div class="form-group ">
                        <label class="col-sm-2 control-label"> Tags </label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" name="tags" class=" form-control form-control-lg" placeholder="Sperate It With Comma(,)" value="<?php echo $item['tags'] ?>">
                        </div>
                    </div>
                    <!--end tags-->
                    <!--start button-->
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <input type="submit" value="Save Item" class="btn btn-primary btn-sm">
                        </div>
                    </div>
                    <!--end button-->
                </form>
                <?php

                $stmt = $con->prepare(
                    "SELECT
                            comment.*,  users.Username AS Member
                        FROM
                            comment
                        INNER JOIN
                            users
                        ON
                            users.Userid = comment.user_id
                        WHERE item_id = ?"

                );
                // execute to variable
                $stmt->execute(array($itemid));
                // assign to variable
                $rows = $stmt->fetchAll();
                if (!empty($rows)) {
                ?>
                    <h1 class="text-center">Manage[<?php echo $item['Name']; ?>] Comments</h1>
                    <div class="table-responsive">
                        <table class="main-table text-center table table-bordered">
                            <tr>
                                <td>Comment</td>
                                <td>User Name</td>
                                <td>Added Date</td>
                                <td>Control</td>
                            </tr>
                            <?php

                            foreach ($rows as $row) {

                                echo "<tr>";
                                echo "<td>" . $row['Comment'] . "</td>";
                                echo "<td>" . $row['Member'] . "</td>";
                                echo "<td>" . $row['Comment_date'] . "</td>";
                                echo "<td>
                          <a href='comment.php?do=Edit&comid=" . $row['C_id'] . "' class='btn btn-success'>Edit</a>
                          <a href= 'comment.php?do=Delete&comid=" . $row['C_id'] . "'  class='btn btn-danger confirm'>Delete</a>";

                                if ($row['Status'] == 0) {
                                    echo "<a href= 'comment.php?do=Approve&comid=" . $row['C_id'] . "'  class='btn btn-info activet'>Approve</a>";
                                }
                                echo "</td>";

                                echo "</tr>";
                            }

                            ?>
                        </table>
                    </div>
                <?php } ?>
            </div>

<?php } else {
            echo '<div class="container">';
            $theMsg = '<div class="alert alert-danger">There\'s no such id</div>';
            redierctHome($theMsg);
        }
        echo '</div>';
    } elseif ($do == 'Update') {
        echo " <h1 class='text-center'>Update Item</h1>";
        echo "<div class='container'>";
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Get Varabiale From Form..
            $id         = $_POST['itemid'];
            $name       = $_POST['name'];
            $desc       = $_POST['description'];
            $price      = $_POST['price'];
            $country    = $_POST['country'];
            $status     = $_POST['status'];
            $cat        = $_POST['category'];
            $member     = $_POST['member'];
            $tags     = $_POST['tags'];
            // validate the form
            $formErrors = array();
            if (empty($name)) {
                $formErrors[] = 'Name can\'t be empty <strong>Empty</strong>';
            }
            if (empty($desc)) {
                $formErrors[] = 'Description can\'t be empty <strong>Empty</strong>';
            }
            if (empty($price)) {
                $formErrors[] = 'Price can\'t be empty <strong>Empty</strong>';
            }
            if (empty($country)) {
                $formErrors[] = 'Country can\'t be empty <strong>Empty</strong>';
            }
            if (($status) == 0) {
                $formErrors[] = 'You Must Choose The  <strong>Status</strong>';
            }
            if (($member) == 0) {
                $formErrors[] = 'You Must Choose The  <strong>Member</strong>';
            }
            if (($cat) == 0) {
                $formErrors[] = 'You Must Choose The  <strong>Category</strong>';
            }
            foreach ($formErrors as $error) {
                echo '<div class="alert alert-danger">' . $error . '</div>';
            }

            // check if there is no erorr proceed the update operation 
            if (empty($formErrors)) {

                // Update The Database With This Info
                $stmt = $con->prepare("UPDATE
                                            items 
                                        SET
                                            Name = ?,
                                            Description = ?,
                                            Price = ?,
                                            Country_Made = ?,
                                            Status = ?,
                                            Cat_ID = ?,
                                            Member_ID = ?, 
                                            tags = ?   
                                        WHERE
                                             item_ID = ?");
                $stmt->execute(array($name, $desc, $price, $country, $status, $cat, $member, $tags, $id));
                $theMsg =  "<div class='alert alert-success'>" . $stmt->rowCount() . 'Record Updated</div>';
                redierctHome($theMsg, 'back', 4);
            }
        } else {
            echo '<div class="container">';
            $theMsg = '<div class="alert alert-danger"> you are not allowed here</div>';
            redierctHome($theMsg);
            echo '</div>';
        }
        echo "</div>";
    } elseif ($do == 'Delete') {
        echo " <h1 class='text-center'>Delete item</h1>";
        echo "<div class='container'>";
        $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;

        $stmt = $con->prepare("SELECT *  FROM items WHERE item_ID = ?");
        $stmt->execute(array($itemid));

        $count = $stmt->rowCount();
        if ($stmt->rowCount() > 0) {
            $stmt = $con->prepare("DELETE FROM items WHERE item_ID = :zid ");
            $stmt->bindParam(':zid', $itemid);
            $stmt->execute();
            $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . 'Record Deleted</div>';
            redierctHome($theMsg, 'back');
        } else {
            $theMsg = '<div class="alert alert-danger">The Id Is Not Exist</div>';
            redierctHome($theMsg);
        }
        echo '</div>';
    } elseif ($do == 'Approve') {
        echo " <h1 class='text-center'>Approve Item</h1>";
        echo "<div class='container'>";
        $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;

        $stmt = $con->prepare("SELECT *  FROM items WHERE item_ID = ? ");
        $stmt->execute(array($itemid));

        $count = $stmt->rowCount();
        if ($stmt->rowCount() > 0) {
            $stmt = $con->prepare("UPDATE items SET Approve = 1 WHERE item_ID = ?");
            $stmt->execute(array($itemid));
            $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . 'Record Approved</div>';
            redierctHome($theMsg, 'back');
        } else {
            $theMsg = '<div class="alert alert-danger">The Id Is Not Exist</div>';
            redierctHome($theMsg);
        }
    }

    include $tpl . 'footer.php';
} else {

    header('location: index.php');
}
exit();

ob_end_flush();
