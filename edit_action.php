<?php
include_once ("database/db_conection.php");

if ($_POST['addcategoryname']) {

	$error_flag = false;

	$add_categoryname = mysqli_real_escape_string($db_conn, $_POST['addcategoryname']);
	$cat_pid = mysqli_real_escape_string($db_conn, $_POST['selectcategory']);
	$update_cat_pid = mysqli_real_escape_string($db_conn, $_POST['updatecatid']);

	/*$check_category_query = "select * from category WHERE cat_name='$add_categoryname'";
	$result = mysqli_query($db_conn, $check_category_query);

	if (mysqli_num_rows($result) > 0) {
		$error_flag = true;
		echo "categorynamerror";
	}*/
	
	if (($error_flag == false)) {

		$update_category = "UPDATE category SET cat_name='$add_categoryname',parent_id='$cat_pid' where id='$update_cat_pid'";

		$run_updatecategoryquery = mysqli_query($db_conn, $update_category);
		if ($run_updatecategoryquery) {
			//$insert_id = mysqli_insert_id($db_conn);
			$insert_id = $update_cat_pid;
			
			//fetch category record start
			
			function createTreename($category_name,$parent_id) {
			  global $db_conn;
					$cat_name = $category_name;
				
					$get_parentid_query="select cat_name,parent_id from category where id='$parent_id'";
		       		$getparenrun=mysqli_query($db_conn,$get_parentid_query);
		       		$getrow=mysqli_fetch_row($getparenrun);
					
					if($getrow['1'] == 0){
			   			$cat_name .= ">>".$getrow['0'];
			   		} else {
			   			$cat_name .= ">>".createTreename($getrow['0'],$getrow['1']);
			   		}
		
			  return $cat_name;
			}
			
			$view_category_query="select cat_name,parent_id from category Where id='$insert_id'";//select query for viewing categories. 
            $run=mysqli_query($db_conn,$view_category_query);//here run the sql query. 
			$row=mysqli_fetch_row($run);
			if($row['1'] == 0){
			   		$cat_name = $row['0'];
			   } else {
			   	$cat_name = createTreename($row['0'],$row['1']);
			   }
			   
			   // Format Category name
			   $strtoarray = explode(">>",$cat_name);
			   $reversed = array_reverse($strtoarray);
			   $return_cat = implode(" >> ",$reversed);
			   
			//fetch category record ends
			echo '<td class="col-md-1 col-sm-1">
                            <span id="first_' . $insert_id . '" class="text">' . $insert_id. '</span>                            
                        </td>
                        <td class="col-md-3 col-sm-3">
                            <span id="second_' . $insert_id . '" class="text">' . $_POST["addcategoryname"] . '</span>                            
                        </td>
                        <td class="col-md-2 col-sm-2">
                            <span id="third_' . $insert_id . '" class="text">' . $row[1] . '</span>
                        </td>
                        <td class="col-md-4 col-sm-4">                            
                            <span class="text" id="fourth_' . $insert_id . '">'.$return_cat.'</span> 
                        </td>
                        <td class="col-md-2 col-sm-2">
							<input type="submit" class="btn btn-success ajaxeditcategory" name="editcategory" id="'.$cat_pid.'_' . $insert_id . '" value="Edit	">
                            <input type="submit" onClick="deleteAction(' . $insert_id . ')" class="btn btn-danger delete" name="deletecategory" id="fifth_ajax_' . $insert_id . '" value="Delete">
                        </td>';
		}
	}
}
?>