<?php
session_start();
$pageTitle = 'Create New Item';
include "init.php";
if (isset($_SESSION['user'])) {

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $formErrors = array();

        $name       = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
        $desc       = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
        $price      = filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_INT);
        $country    = filter_var($_POST['country'], FILTER_SANITIZE_STRING);
        $status     = filter_var($_POST['status'], FILTER_SANITIZE_NUMBER_INT);
        $cate       = filter_var($_POST['category'], FILTER_SANITIZE_NUMBER_INT);
        $tags       = filter_var($_POST['tags'], FILTER_SANITIZE_STRING);
        if (strlen($name) < 4) {
            $formErrors[] = 'Item Name  Must Be At Least 4 Character';
        }
        if (strlen($desc) < 10) {
            $formErrors[] = 'Item Description Must Be At Least 10 Character';
        }
        if (empty($price)) {
            $formErrors[] = 'Item Price Must Be Not Empty';
        }
        if (strlen($country) < 1) {
            $formErrors[] = 'Item Country Must Be At Least 1 Character';
        }
        if (empty($status)) {
            $formErrors[] = 'Item Status Must Be Not Empty';
        }
        if (empty($cate)) {
            $formErrors[] = 'Item Category Must Be Not Empty';
        }


        // check if there is no erorr proceed the update operation 
        if (empty($formErrors)) {
            // Insert The Database With This Info
            $stmt = $con->prepare("INSERT INTO items(Name, Description, Price, Country_Made, Status, Add_Date, Cat_ID, Member_ID, tags)
                                        VALUES(:zname, :zdesc, :zprice, :zcountry, :zstatus, now(), :zcat, :zmember, :ztags)");
            $stmt->execute(array(
                'zname'     => $name,
                'zdesc'     => $desc,
                'zprice'    => $price,
                'zcountry'  => $country,
                'zstatus'   => $status,
                'zcat'      => $cate,
                'zmember'   => $_SESSION['uid'],
                'ztags'      => $tags,
            ));
            // echo The Sucess Message   
            if ($stmt) {


                $successMsg =  'Item Has Been Added ';
            }
        }
    }


?>

    <h1 class="text-center"><?php echo $pageTitle ?> </h1>
    <div class="create-ad display">
        <div class="container">
            <div class="panel panel-primary">
                <div class="card-header">
                    <?php echo $pageTitle ?>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">

                            <form class="form-horizontal main-form" action="<?php echo $_SERVER["PHP_SELF"] ?>" method="POST">

                                <!--start Name-->
                                <div class="form-group ">
                                    <label class="col-sm-3 control-label"> Name</label>
                                    <div class="col-sm-10 col-md-9">
                                        <input type="text" name="name" class=" form-control form-control-lg live" autocomplete="off" required="required" placeholder="Name of The Items " data-class=".live-title" pattern=".{4,}" title="This Field required At Least 4 chars">
                                    </div>
                                </div>
                                <!--end Name-->
                                <!--start Description-->
                                <div class="form-group ">
                                    <label class="col-sm-3 control-label"> Description</label>
                                    <div class="col-sm-10 col-md-9">
                                        <input type="text" name="description" class=" form-control form-control-lg live" autocomplete="off" required="required" placeholder="Description of The Items" data-class=".live-desc" pattern=".{10,}" title="This Field required At Least 10 chars">
                                    </div>
                                </div>
                                <!--end Description-->
                                <!--start Price-->
                                <div class="form-group ">
                                    <label class="col-sm-3 control-label"> Price</label>
                                    <div class="col-sm-10 col-md-9">
                                        <input type="text" name="price" class=" form-control form-control-lg live" autocomplete="off" required="required" placeholder="Price of The Items" data-class=".live-price">
                                    </div>
                                </div>
                                <!--end Price-->
                                <!--start Country-->
                                <div class="form-group ">
                                    <label class="col-sm-3 control-label"> Country</label>
                                    <div class="col-sm-10 col-md-9">
                                        <input type="text" name="country" class=" form-control form-control-lg" autocomplete="off" required="required" placeholder="Country Made ">
                                    </div>
                                </div>
                                <!--end Country-->
                                <!--start Status-->
                                <div class="form-group ">
                                    <label class="col-sm-3 control-label">Status</label>
                                    <div class="col-sm-10 col-md-9">
                                        <select name="status">
                                            <option value="">Choose US</option>
                                            <option value="1">New</option>
                                            <option value="2">Like New</option>
                                            <option value="3">Used</option>
                                            <option value="4">Old</option>
                                        </select>
                                    </div>
                                </div>
                                <!--end Status-->
                                <!--start category field-->
                                <div class="form-group ">
                                    <label class="col-sm-3 control-label">Category</label>
                                    <div class="col-sm-10 col-md-9">
                                        <select name="category">
                                            <option value="">Choose US</option>
                                            <?php
                                            $cats = getAllFrom('*', 'categories', '', '', 'ID');
                                            foreach ($cats as $cat) {
                                                echo "<option value='" . $cat['ID'] . "'>" . $cat['Name'] . "</option>";
                                            }

                                            ?>

                                        </select>
                                    </div>
                                </div>
                                <!--end category field-->
                                <!--start tags-->
                                <div class="form-group ">
                                    <label class="col-sm-3 control-label"> Tags </label>
                                    <div class="col-sm-10 col-md-9">
                                        <input type="text" name="tags" class=" form-control form-control-lg" placeholder="Sperate It With Comma(,)">
                                    </div>
                                </div>
                                <!--end tags-->
                                <!--start button-->
                                <div class="form-group">
                                    <div class="col-sm-offset-3 col-sm-10">
                                        <input type="submit" value="Add Item" class="btn btn-primary btn-sm">
                                    </div>
                                </div>
                                <!--end button-->
                            </form>

                        </div>
                        <div class="col-md-4">

                            <div class="card-deck item-box live-preview">
                                <div class="card caption">
                                    <span class="price-tag">$<span class="live-price">0</span></span>
                                    <img src="layout/photos/product03.png" class="card-img-top" alt="...">
                                    <div class="card-body">
                                        <h5 class="live-title"> title</h5>
                                        <p class="live-desc"> description</p>
                                        <a href="items.php" class="btn btn-primary">The Item</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <!--start looping form errors-->
                    <?php
                    if (!empty($formErrors)) {
                        foreach ($formErrors as $errors) {
                            echo '<div class="alert alert-danger">' . $errors . '</div>';
                        }
                    }
                    if (isset($successMsg)) {
                        echo '<div class="alert alert-success">' . $successMsg . '</div>';
                    }

                    ?>

                    <!--End looping form errors-->
                </div>
            </div>
        </div>
    </div>

<?php
} else {
    header('Location: login.php');
    exit();
}

include $tpl . 'footer.php';
