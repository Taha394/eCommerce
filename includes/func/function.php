<?php


/*
**  Function v 1.0
** Function To Get All records From any  Database  table
**
*/


function getAllFrom($field, $table, $where = NULL, $and = NULL, $orderfield, $ordering = "DESC")
{
    global $con;
    $getAll = $con->prepare("SELECT $field FROM $table $where $and ORDER BY $orderfield $ordering");
    $getAll->execute();
    $all = $getAll->fetchAll();
    return $all;
}

// Check If The User is activited
function checkUserStatus($user)
{

    global $con;
    $stmtx = $con->prepare("SELECT
                                Username, RegStatus
                           FROM
                                users
                            WHERE
                                Username = ?
                            AND 
                                RegStatus = 0
                          
                           ");
    $stmtx->execute(array($user));
    $status = $stmtx->rowCount();
    return $status;
}

/*
    **  Title Functions That Echo The Page Title In Case The Page 
    **  Has The Varabiale $PageTitle and echo default title for other Pages 
    */

function getTitle()
{
    global $pageTitle;
    if (isset($pageTitle)) {
        echo $pageTitle;
    } else {
        echo "Default";
    }
}

/*
 ** function v 2.0
 ** function redirct [this function accepts parameters]
 ** $theMsg = echo the errorMsg [sucess | message | warning]
 ** $seconds = echo the seconds before directing
*/
function redierctHome($theMsg, $url = null, $seconds = 3)
{
    if ($url === null) {
        $url = 'index.php';
        $link = 'homepage';
    } else {
        if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== '') {
            $url = $_SERVER['HTTP_REFERER'];
            $link = 'previous page';
        } else {
            $url = 'index.php';
            $link = 'homepage';
        }
    }

    echo $theMsg;
    echo "<div class='alert alert-info'>You Will Redirct To the $link After $seconds Seconds </div>";
    header("refresh:$seconds;url=$url");
    exit();
}


/*
** check items function v 1.0
** functon check items in databse [function accepts parameters]
** $select the item to select [example : user , items, cteogry]
** $from = the table to select form[users: ,items , cateogies ]
** value = the value of select [example: taha , box , electronics]
*/

function checkItems($select, $from, $value)
{
    global $con;
    $statment = $con->prepare("SELECT $select FROM $from WHERE $select = ?");
    $statment->execute(array($value));
    $count = $statment->rowCount();
    echo $count;
};


/**
 **count numbers of items function v 1.0
 **function to count number of items rows  
 **$item = the items you need to count
 **$table = the table to choose from
 */

function countItems($item, $table)
{
    global $con;

    $stmt2 = $con->prepare("SELECT COUNT($item) FROM $table");
    $stmt2->execute();
    return $stmt2->fetchColumn();
}

/*
** Get Latest Function v 1.0
** Function To Get Latest Items From Database [users , items , comments]
** $select = Field To Select 
** $order = The Descinding order
** $table = To Choose From 
** $limit = Numbers Of Records To Get 
*/


function getLatest($select, $table, $order, $limit = 5)
{
    global $con;
    $getStmt = $con->prepare("SELECT $select FROM $table ORDER BY $order DESC LIMIT $limit");
    $getStmt->execute();
    $rows = $getStmt->fetchAll();
    return $rows;
}
