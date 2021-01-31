<?php
/*==========Cateogries Page========== */


ob_start(); // out buffering start
session_start();
if (isset($_SESSION['username'])) {
    $pageTitle = 'cateogries';
    include "init.php";

    $do =  isset($_GET['do']) ?  $_GET['do'] :  'manage';

    if ($do == 'manage') {
        $sort = 'ASC';
        $sort_array = array('ASC', 'DESC');
        if (isset($_GET['sort']) && in_array($_GET['sort'], $sort_array)) {
            $sort = $_GET['sort'];
        }
        $stmt2 = $con->prepare("SELECT * FROM categories where parent = 0 ORDER BY Ordering $sort");
        $stmt2->execute();
        $cats = $stmt2->fetchAll(); ?>
        <h1 class="text-center">Manage Categories</h1>
        <div class="container categories">
            <div class="panel panel-default">
                <div class="card-header">Manage Categories
                    <div class="option float-right">
                        Ordering:[
                        <a class="<?php if ($sort == 'ASC') {
                                        echo 'active';
                                    } ?>" href="?sort=ASC">Asc</a> |
                        <a class="<?php if ($sort == 'DESC') {
                                        echo 'active';
                                    } ?>" href="?sort=DESC">Desc</a>]
                        View :[
                        <span class="active" data-view="full">Full</span> |
                        <span data-view="full">Classic</span>]

                    </div>
                </div>
                <div class="card-body">
                    <?php
                    foreach ($cats as $cat) {
                        echo "<div class='cat'>";
                        echo "<div class='hidden-buttons'>";
                        echo "<a href='cateogries.php?do=Edit&catid=" . $cat['ID'] . "' class='btn btn-xs btn-success'><i class='fa fa-edit'></i> Edit</a>";
                        echo "<a  href='cateogries.php?do=Delete&catid=" . $cat['ID'] . "'class=' confirm btn btn-xs btn-danger'><i class='fa fa-close'></i> Delete</a>";
                        echo "</div>";
                        echo '<h3>' . $cat['Name'] . '</h3>';
                        echo '<div class="full-view">';
                        echo "<p>";
                        if ($cat['Description'] == '') {
                            echo 'This Category description Is Empty';
                        } else {
                            echo  $cat['Description'];
                        }
                        echo "</p>";
                        if ($cat['Visibilty'] == 1) {
                            echo '<span class="Visibilty">Hidden</span>';
                        }
                        if ($cat['Allow_Comment'] == 1) {
                            echo '<span class="commenting">Comment Disabale</span>';
                        }
                        if ($cat['Allow_Ads'] == 1) {
                            echo '<span class="advertis">Ads Disabaled</span>';
                        }
                        echo "</div>";
                        //get child cats
                        $childCats = getAllFrom("*", "categories", "where parent = {$cat['ID']}", "", "ID", "ASC");
                        if (!empty($childCats)) {
                            echo "<h4 class='child-head'>
                                                    Subsections </h4>";
                            echo "<ul class='list-unstyled child-cat'>";
                            foreach ($childCats as $c) {
                                echo "<li class='child-link'>
                                <a href='cateogries.php?do=Edit&catid=" . $c['ID'] . "'>" . $c['Name'] . "</a>
                                <a  href='cateogries.php?do=Delete&catid=" . $c['ID'] . "'class='show-delete confirm'> Delete</a>
                                </li>";
                            }
                            "</ul>";
                        }
                        echo "</div>";
                        echo "<hr>";
                    }
                    ?>
                </div>
            </div>
            <a class="add-category btn btn-primary" href="cateogries.php?do=Add"><i class="fa fa-plus"></i> Add New Category</a>
        </div>
    <?php
    } elseif ($do == 'Add') { ?>
        <h1 class="text-center">Add New Category</h1>
        <div class="container">
            <form class="form-horizontal" action="?do=Insert" method="POST">

                <!--start Name-->
                <div class="form-group ">
                    <label class="col-sm-2 control-label"> Name</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" name="name" class=" form-control form-control-lg" autocomplete="off" required="required" placeholder="Name of The Category ">
                    </div>
                </div>
                <!--end Name-->
                <!--start Description-->
                <div class="form-group">
                    <label class="col-sm-2 control-label">Description</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" name="description" class="form-control form-control-lg" placeholder="descripe The Category">

                    </div>
                </div>
                <!--end Description-->
                <!--start Ordering-->
                <div class="form-group">
                    <label class="col-sm-2 control-label">Ordering</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" name="Ordering" class="form-control form-control-lg" placeholder="Ordering The Category">
                    </div>
                </div>
                <!--end Ordering-->
                <!--start category type-->
                <div class="form-group">
                    <label class="col-sm-2 control-label">Category Type</label>
                    <div class="col-sm-10 col-md-6">
                        <select name="parent">
                            <option value="0">None</option>
                            <?php

                            $allCats = getAllFrom("*", "categories", "where parent = 0", "", "ID", "DESC");
                            foreach ($allCats as $cat) {
                                echo "<option value='" . $cat['ID'] . "'> " . $cat['Name'] . "</option>";
                            }
                            ?>

                        </select>
                    </div>
                </div>
                <!--end category type-->

                <!--start visibilty-->
                <div class="form-group">
                    <label class="col-sm-2 control-label">Visible</label>
                    <div class="col-sm-10 col-md-6">
                        <div>
                            <input id="vis-yes" type="radio" name="Visibilty" value="0" checked />
                            <label for="vis-yes">Yes</label>
                        </div>
                        <div>
                            <input id="vis-no" type="radio" name="Visibilty" value="1" />
                            <label for="vis-no">No</label>
                        </div>
                    </div>
                </div>
                <!--end visibilty-->

                <!--start Commenting-->
                <div class="form-group">
                    <label class="col-sm-2 control-label">Allow Commenting</label>
                    <div class="col-sm-10 col-md-6">
                        <div>
                            <input id="com-yes" type="radio" name="Commenting" value="0" checked />
                            <label for="com-yes">Yes</label>
                        </div>
                        <div>
                            <input id="com-no" type="radio" name="Commenting" value="1" />
                            <label for="com-no">No</label>
                        </div>
                    </div>
                </div>
                <!--end Commenting-->
                <!--start ads-->
                <div class="form-group">
                    <label class="col-sm-2 control-label">Allow ads</label>
                    <div class="col-sm-10 col-md-6">
                        <div>
                            <input id="ads-yes" type="radio" name="ads" value="0" checked />
                            <label for="ads-yes">Yes</label>
                        </div>
                        <div>
                            <input id="ads-no" type="radio" name="ads" value="1" />
                            <label for="ads-no">No</label>
                        </div>
                    </div>
                </div>
                <!--end Commenting-->
                <!--start button-->
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <input type="submit" value="Add Category" class="btn btn-primary btn-lg">
                    </div>
                </div>
                <!--end button-->
            </form>
        </div>
        <?php
    } elseif ($do == 'Insert') {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            echo " <h1 class='text-center'> Insert Category</h1>";
            echo "<div class='container'>";
            // Get Varabiale From Form..
            $name       = $_POST['name'];
            $desc       = $_POST['description'];
            $parent     = $_POST['parent'];
            $order      = $_POST['Ordering'];
            $visible    = $_POST['Visibilty'];
            $comment    = $_POST['Commenting'];
            $ads        = $_POST['ads'];


            // check if user exist in databse
            $check = checkItems("Name", "categories", $name);
            if ($check == 1) {
                $theMsg =  '<div class"alert alert-danger">This Category Is Exist</div>';
                redierctHome($theMsg, 'back');
            } else {
                // Insert categories  In The Database With This Info
                $stmt = $con->prepare("INSERT INTO
                 categories(Name, Description, parent, Ordering, Visibilty, Allow_Comment, Allow_Ads)
                VALUES(:zname, :zdesc, :zparent, :zorder, :zvisible, :zcomment, :zads)");
                $stmt->execute(array(
                    'zname'     => $name,
                    'zdesc'     => $desc,
                    'zparent'   => $parent,
                    'zorder'    => $order,
                    'zvisible'  => $visible,
                    'zcomment'  => $comment,
                    'zads'      => $ads
                ));
                // echo The Sucess Message    
                $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . 'Record Inserted</div>';
                redierctHome($theMsg, 'back');
            }
        } else {
            echo '<div class="container">';
            $theMsg = "<div class='alert alert-danger'> Sorry You Can\'t Browse This Page Directly</div>";
            redierctHome($theMsg, 'back');
            echo '</div>';
        }
        echo "</div>";
    } elseif ($do == 'Edit') {
        $catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0;

        $stmt = $con->prepare("SELECT *  FROM categories WHERE ID = ?");
        $stmt->execute(array($catid));
        $cat = $stmt->fetch(); // اجلب //
        $count = $stmt->rowCount();
        if ($stmt->rowCount() > 0) { ?>

            <h1 class="text-center">Add New Category</h1>
            <div class="container">
                <form class="form-horizontal" action="?do=Update" method="POST">
                    <input type="hidden" name="catid" value="<?php echo $catid ?>" />

                    <!--start Name-->
                    <div class="form-group ">
                        <label class="col-sm-2 control-label"> Name</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" name="name" class=" form-control form-control-lg" required="required" placeholder="Name of The Category" value="<?php echo $cat['Name'] ?>">
                        </div>
                    </div>
                    <!--end Name-->
                    <!--start Description-->
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Description</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" name="description" class="form-control form-control-lg" placeholder="descripe The Category" value="<?php echo $cat['Description'] ?>">

                        </div>
                    </div>
                    <!--end Description-->
                    <!--start Ordering-->
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Ordering</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" name="Ordering" class="form-control form-control-lg" placeholder="Ordering The Category" value="<?php echo $cat['Ordering'] ?>">
                        </div>
                    </div>
                    <!--end Ordering-->
                    <!--start category type-->
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Category Type</label>
                        <div class="col-sm-10 col-md-6">
                            <select name="parent">
                                <option value="0">None</option>
                                <?php

                                $allCats = getAllFrom("*", "categories", "where parent = 0", "", "ID", "DESC");
                                foreach ($allCats as $c) {
                                    echo "<option value='" . $c['ID'] . "'";
                                    if ($cat['parent'] == $c['ID']) {
                                        echo 'selected';
                                    }
                                    echo ">"  . $c['Name'] . "</option>";
                                }
                                ?>

                            </select>
                        </div>
                    </div>
                    <!--end category type-->
                    <!--start visibilty-->
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Visible</label>
                        <div class="col-sm-10 col-md-6">
                            <div>
                                <input id="vis-yes" type="radio" name="Visibilty" value="0" <?php if ($cat['Visibilty'] == 0) {
                                                                                                echo 'checked';
                                                                                            } ?> />
                                <label for="vis-yes">Yes</label>
                            </div>
                            <div>
                                <input id="vis-no" type="radio" name="Visibilty" value="1" <?php if ($cat['Visibilty'] == 1) {
                                                                                                echo 'checked';
                                                                                            } ?> />
                                <label for="vis-no">No</label>
                            </div>
                        </div>
                    </div>
                    <!--end visibilty-->

                    <!--start Commenting-->
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Allow Commenting</label>
                        <div class="col-sm-10 col-md-6">
                            <div>
                                <input id="com-yes" type="radio" name="Commenting" value="0" <?php if ($cat['Allow_Comment'] == 0) {
                                                                                                    echo 'checked';
                                                                                                } ?> />
                                <label for="com-yes">Yes</label>
                            </div>
                            <div>
                                <input id="com-no" type="radio" name="Commenting" value="1" <?php if ($cat['Allow_Comment'] == 1) {
                                                                                                echo 'checked';
                                                                                            } ?> />
                                <label for="com-no">No</label>
                            </div>
                        </div>
                    </div>
                    <!--end Commenting-->
                    <!--start ads-->
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Allow ads</label>
                        <div class="col-sm-10 col-md-6">
                            <div>
                                <input id="ads-yes" type="radio" name="ads" value="0" <?php if ($cat['Allow_Ads'] == 0) {
                                                                                            echo 'checked';
                                                                                        } ?> />
                                <label for="ads-yes">Yes</label>
                            </div>
                            <div>
                                <input id="ads-no" type="radio" name="ads" <?php if ($cat['Allow_Ads'] == 1) {
                                                                                echo 'checked';
                                                                            } ?> />
                                <label for="ads-no">No</label>
                            </div>
                        </div>
                    </div>
                    <!--end Commenting-->
                    <!--start button-->
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <input type="submit" value="Save" class="btn btn-primary btn-lg">
                        </div>
                    </div>
                    <!--end button-->
                </form>
            </div>

<?php
        } else {
            echo '<div class="container">';
            $theMsg = '<div class="alert alert-danger">There\'s no such id</div>';
            redierctHome($theMsg);
            echo '</div>';
        }
    } elseif ($do == 'Update') {
        echo " <h1 class='text-center'>Update Category</h1>";
        echo "<div class='container'>";
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Get Varabiale From Form..
            $id         = $_POST['catid'];
            $name       = $_POST['name'];
            $desc       = $_POST['description'];
            $order      = $_POST['Ordering'];
            $parent     = $_POST['parent'];
            $visible    = $_POST['Visibilty'];
            $comment    = $_POST['Commenting'];
            $ads        = $_POST['ads'];

            // Update The Database With This Info
            $stmt = $con->prepare("UPDATE 
                                        categories 
                                   SET
                                    Name = ?,
                                    Description = ?,
                                        Ordering = ?,
                                        parent = ?,
                                        Visibilty = ?,
                                        Allow_Comment = ?,
                                        Allow_Ads = ?
                                    WHERE 
                                        ID = ?");
            $stmt->execute(array($name, $desc, $order, $parent, $visible, $comment, $ads, $id));
            $theMsg =  "<div class='alert alert-success'>" . $stmt->rowCount() . 'Record Updated</div>';
            redierctHome($theMsg, 'back', 4);
        } else {
            echo '<div class="container">';
            $theMsg = '<div class="alert alert-danger"> you are not allowed here</div>';
            redierctHome($theMsg);
            echo '</div>';
        }
        echo "</div>";
    } elseif ($do == 'Delete') {
        echo " <h1 class='text-center'>Delete Category</h1>";
        echo "<div class='container'>";
        $catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0;

        $stmt = $con->prepare("SELECT *  FROM categories WHERE ID = ? ");
        $stmt->execute(array($catid));

        $count = $stmt->rowCount();
        if ($stmt->rowCount() > 0) {
            $stmt = $con->prepare("DELETE FROM categories WHERE ID = :zid ");
            $stmt->bindParam(':zid', $catid);
            $stmt->execute();
            $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . 'Record Deleted</div>';
            redierctHome($theMsg, 'back');
        } else {
            $theMsg = '<div class="alert alert-danger">The Id Is Not Exist</div>';
            redierctHome($theMsg);
        }
        echo '</div>';
    }

    include $tpl . 'footer.php';
} else {

    header('location: index.php');
    exit();
}
ob_end_flush();
