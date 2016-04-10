<?php

include_once("database/db_conection.php");

include_once("header.php");
?>
  <body>

    <div class="container">
    <div class="row">
        <div class="table-scrol"> 
        <div class="row">
        <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                <h1 align="center" class="addtitle">Add the Categories</h1> 
				<h1 align="center" class="edittitle">Edit Category</h1> 
        </div>  
        </div>
        <p></p>
        <div id="errormsg"></div>
        <!--add category start-->
        <div class="container">
                <div class="row">
                <h2 class="addsubtitle">Add Categories</h2>
                <h2 class="editsubtitle">Edit Category</h2>
            
              <form id="uploadForm" action="" method="post" autocomplete="off">
              
              <div class="form-group">
                <label for="addcategoryname11">Category Name</label>
                <input type="text" class="form-control" name="addcategoryname" value="" id="addcategoryname" placeholder="Category name" maxlength="100" required  autofocus>
				
              </div>
              
             <div class="form-group">
			<label for="exampleInputcategory">Select Category:</label>
        
			<?php
				function fetchCategoryTree($parent = 0, $spacing = '', $user_tree_array = '') {
				  global $db_conn;
				  if (!is_array($user_tree_array))
					$user_tree_array = array();
				  $select_categorydropdown_query="Select * from category where 1 AND parent_id ='".$parent."' ORDER BY id ASC";
				 
				  $query = mysqli_query($db_conn,$select_categorydropdown_query);
				  if (mysqli_num_rows($query) > 0) {
					while ($row = mysqli_fetch_object($query)) {
					$user_tree_array[] = array("id" => $row->id, "name" => $spacing . $row->cat_name);
					$user_tree_array = fetchCategoryTree($row->id, $spacing . '&nbsp;&nbsp;', $user_tree_array);
					}
				  }
				  return $user_tree_array;
				}
				
			?>
			<?php 
				$categoryList = fetchCategoryTree(); 
			?>
			<select class="form-control" id="selectcatoption" name='selectcategory' required>            
					<option value=''>None</option>
					<option value='0'>Parent Category</option>
					<?php foreach($categoryList as $cl) { ?>
						<option value="<?php echo $cl["id"]; ?>"><?php echo $cl["name"]; ?></option>
					<?php } ?>

			</select>
			</div>
            
              <input class="btn btn btn-success" type="submit" value="Add Category" name="addcategorybtn" id="addcategorybtn" >
              <input type="hidden" name="updatecatid" id="updatecatid" value="">
			  <input class="btn btn btn-success" type="submit" value="Save" name="editcategorybtn" id="editcategorybtn" >
			  <input class="btn btn btn-success" type="button" value="Cancel" name="cancelcategorybtn" id="cancelcategorybtn" >
            </div>
            </form>
        </div>
        <!--add category Ends-->
        <p></p>
        <div class="row">
        <div class="table-responsive"><!--this is used for responsive display in mobile and other devices-->  
      
      
        <table id="example" class="table table-bordered table-hover table-striped" style="table-layout: fixed">  
            <thead>
            <tr>  
                <th class="col-md-1 col-sm-1">Category ID</th>
                <th class="col-md-3 col-sm-3">Category Name</th>             
                <th class="col-md-2 col-sm-2">Parent ID</th>   
                <th class="col-md-4 col-sm-4">Category Path</th>
                <th class="col-md-2 col-sm-2">Action</th>              
            </tr>  
            </thead> 
            <tfoot>
            <tr>  
               
                 <th class="col-md-1 col-sm-1">Category ID</th>
                <th class="col-md-3 col-sm-3">Category Name</th>               
                <th class="col-md-2 col-sm-2">Parent ID</th>   
                <th class="col-md-4 col-sm-4">Category Path</th>
                <th class="col-md-2 col-sm-2">Action</th>                              
            </tr>  
            </tfoot>  
            <tbody>
            <tr id="category-list-box">
                <img src="LoaderIcon.gif" id="loaderIcon" style="display:none;" />
            </tr> 
            <?php  
            
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
				
			
            $view_category_query="select * from category";//select query for viewing users. 
            $run=mysqli_query($db_conn,$view_category_query);//here run the sql query.  
            if(mysqli_num_rows($run)>0){
            while($row=mysqli_fetch_array($run))//while look to fetch the result and store in a array $row.  
            {  
                $category_id=$row[0]; 
                $category_name=$row[1];   
                $cat_parent_id=$row[2];   
                 
		       $view_parentid_query="select cat_name,parent_id from category where id='$category_id'";
		       $parenrun=mysqli_query($db_conn,$view_parentid_query);
		       $row=mysqli_fetch_row($parenrun);
			   if($row['1'] == 0){
			   		$cat_name = $row['0'];
			   } else {
			   	$cat_name = createTreename($row['0'],$row['1']);
			   }
			   // Format Category name
			   $strtoarray = explode(">>",$cat_name);
			   $reversed = array_reverse($strtoarray);
			   $return_cat = implode(" >> ",$reversed);
				
            ?>  
            
            <tr class="tredit" id="ajax_<?php echo $category_id;?>">  
                <!--here showing results in the table -->  
                
                <td class="col-md-1 col-sm-1">
                   <span id="first_<?php echo $category_id; ?>" class="text"><?php echo $category_id; ?></span>                   
                </td>
                <td class="col-md-3 col-sm-3">
                    <span id="second_<?php echo $category_id; ?>" class="text"><?php echo $category_name; ?></span>                    
                </td> 
                <td class="col-md-2 col-sm-2">
                    <span id="third_<?php echo $category_id; ?>" class="text"><?php echo $cat_parent_id; ?></span>                    
                </td>
                <td class="col-md-4 col-sm-4">
                	<span id="fourth_<?php echo $category_id; ?>" class="text"><?php echo $return_cat; ?></span>   
                </td>  
                <td class="col-md-2 col-sm-2">                   	
                    <input type="submit" class="btn btn-success ajaxeditcategory" id="<?php echo $cat_parent_id."_".$category_id; ?>" name="editcategory" value="Edit">
                    <input type="submit" class="btn btn-danger delete" id="sixth_ajax_<?php echo $category_id; ?>" name="deletecategory" value="Delete" onClick="deleteAction(<?php echo $category_id; ?>)">
                </td> <!--btn btn-danger is a bootstrap button to show danger-->                 
                
                
            </tr>  
            
            <?php }
            } /*else {
                echo "<tr><td colspan='6'><h3 class='text-center'>No Categories Found</h3></tr></td>";
            }*/
            
            ?>   
            
            </tbody>
        </table> 
        
        </div>  
        </div>
        
            
        
    </div>
    </div>  
    </div> 

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="lib/js/bootstrap.min.js"></script>
    <script>
    $(document).ready(function (e) {       
		$( "#addcategorybtn" ).click(function() {
			$( "#uploadForm" ).submit(function(e) {
				e.preventDefault();
            $("#loaderIcon").show();            
            var updatevalue = $("#updatecatid").val();
            if (updatevalue == ''){
            	$("#updatecatid").val('');
            }
            var formData = new FormData(this);
            //formData.append("sku", $("#addproductsku").val());
            //formData.append("name", $("#addproductname").val());
            //formData.append("description", $("#add_admin_productdesc").val()); 
      
            $.ajax({
            url: "add_action.php",            
            type: "POST",
            data: formData,
            async : false,
            contentType: false,       // The content type used when sending data to the server.
            cache: false,             // To unable request pages to be cached
            processData:false, 
            success:function(data){
               
              if (data == "categorynamerror"){                   
                   $("#errormsg").html("<div role='alert' class='alert alert-danger alert-dismissible fade in'><button aria-label='Close' data-dismiss='alert' class='close' type='button'><span aria-hidden='true'>×</span></button>Category Name is already exist in our database, Please try another one!</div>");     
               } else if (data == "Can't Add") {
               	
               } else {
                   $("#category-list-box").after(data); 
               }
                if (updatevalue == ''){          
                $("#addcategoryname").val('');
                $("#selectcatoption").find('option:selected').removeAttr("selected"); 
                }
                $("#loaderIcon").hide();
            },
            complete: function (data) {
      				//alert("successCount");
     		},
            error:function (){
            },
            timeout: 3000 // sets timeout to 3 seconds
			});
		});
    });
	$( "#editcategorybtn" ).click(function() {
		$( "#uploadForm" ).submit(function(e) {
			e.preventDefault();
            $("#loaderIcon").show();
            var ID = $(this).parents().find('input[type="hidden"]').val();
            var formData = new FormData(this);
            //formData.append("sku", $("#addproductsku").val());
            //formData.append("name", $("#addproductname").val());
            //formData.append("description", $("#add_admin_productdesc").val()); 
            
            $.ajax({
            url: "edit_action.php",            
            type: "POST",
            data: formData,
            async : false,
            contentType: false,       // The content type used when sending data to the server.
            cache: false,             // To unable request pages to be cached
            processData:false, 
            success:function(data){
               
              if (data == "categorynamerror"){                   
                   $("#errormsg").html("<div role='alert' class='alert alert-warning alert-dismissible fade in'>Category Name is already exist in our database, Please try another one!</div>");     
               } else {
                   //$("#category-list-box").after(data); 
                   $("#ajax_"+ID).html(data); 
                   
               }
                               
                $("#addcategoryname").val('');
				$("#selectcatoption").find('option:selected').removeAttr("selected"); 
                $("#loaderIcon").hide();
            },
            complete: function (data) {
      				$(".edittitle").hide();
					$(".addtitle").show();
					$(".editsubtitle").hide();
					$(".addsubtitle").show();
					$("#cancelcategorybtn" ).hide();
					$("#editcategorybtn" ).hide();
					$("#addcategorybtn" ).show();
					$("#updatecatid").val('');	
     		},
            error:function (){
            },
            timeout: 3000 // sets timeout to 3 seconds
			});
		});
	});
	$( "#cancelcategorybtn" ).click(function() {
		$("#cancelcategorybtn" ).hide();
		$("#editcategorybtn" ).hide();
		$("#addcategorybtn" ).show();
		$("#addcategoryname").val('');
		$("#selectcatoption").find('option:selected').removeAttr("selected"); 
		$(".edittitle").hide();
		$(".editsubtitle").hide();
		$(".addtitle").show();
		$(".addsubtitle").show();
		$("#updatecatid").val('');
	});
    });
	
    function deleteAction(id) {
        
        var info = 'id=' + id;
		
        if(confirm("Are you sure you want to delete this?"))
        {
         $.ajax({
           type: "POST",
           url: "delete_category.php",
           data: info,
           success: function(){
            
            }
        });
          //$(this).parents("tr").animate({backgroundColor: "#003" }, "slow").animate({opacity: "hide"}, "slow").remove();
         // $(this).parents("tr").remove(); 
            $( ".tredit#ajax_"+id ).hide( 1200, function() {
            $( ".tredit#ajax_"+id ).remove();
            });
         }
        return false;
    }
    </script>
    <script> 
    
        function ajaxeditinline() {
        $(".ajaxeditcategory").click(function(){
                     
			var str=$(this).attr('id');
			var arrayid = str.split("_");
			var PID = arrayid[0];
			var CID = arrayid[1];
			
			
			$(".edittitle").show();
			$(".addtitle").hide();
			$(".editsubtitle").show();
			$(".addsubtitle").hide();
			$("#addcategorybtn").hide();
			$("#editcategorybtn").show();
			$("#updatecatid").val(CID);
			$("#cancelcategorybtn").show();
            var catname = $("#second_"+CID).text();
            $("#addcategoryname").val(catname);
			
			$("#selectcatoption option").each(function()
			{
				if ($(this).val() == PID)
				$(this).prop('selected', true);
			});

        });
        }
    
    $(document).ajaxComplete(function(){
        	ajaxeditinline ();
    });
    
    $(document).ready(function(){
        ajaxeditinline ();
        $("#errormsg").val('');
		$(".edittitle").hide();
		$(".editsubtitle").hide();
		$("#editcategorybtn").hide();
		$("#cancelcategorybtn").hide();
		
    });
    </script>
    
  </body>
</html>
