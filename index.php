<?php include 'inc/header.php'; ?>
<?php include 'lib/Database.php'; ?>

<?php $db = new Database(); ?>
	<div class="myform">

<!--Image Insert and Validation began-->
		<?php 
			if($_SERVER['REQUEST_METHOD'] == 'POST'){
			$permitedformat   = array('jpg', 'jpeg', 'png', 'gif');
			$file_name 		  = $_FILES['image']['name'];
			$file_size 		  = $_FILES['image']['size'];
			$file_tmplocation = $_FILES['image']['tmp_name'];

			$div 			= explode('.', $file_name);
			$file_ext		= strtolower(end($div));
			$unique_name	= substr(md5(time()), 0, 10).'.'.$file_ext;
			$uploaded_image = 'uploads/'.$unique_name;

			if(empty($file_name)){
				echo "<span class='error'>please select an image</span>";
			}elseif ($file_size > 1048567) {
				echo "<span class='error'>file size should not be greater than 1MB </span>";
			}elseif(in_array($file_ext, $permitedformat)===false){
				echo "<span class='error'>Image formate should be in:". implode(', ', $permitedformat)."</span>";
			}else{
				move_uploaded_file($file_tmplocation, $uploaded_image);
				$sql = "INSERT INTO tbl_image(image) VALUES('$uploaded_image') ";
				$insertquery = $db->insert($sql);
				if($insertquery){
					echo "<span class='success'>file inserted successfully</span>";
			}else{
					echo "<span class='error'>file inserted successfully</span>";
			}
			}
			}
			?>
<!--Image Insert and Validation end-->



		<form action="" method="POST" enctype="multipart/form-data">
			<table>
				<tr>
					<td>Select Image</td>
					<td><input type="file" name="image"></td>
				</tr>
				<tr>
					<td></td>
					<td><input type="submit" name="submit" value="Upload"></td>
				</tr>
			</table>
			
		</form>
		
		<table>
			<tr>
				<th>SI NO.</th>
				<th>Image</th>
				<th>Action</th>
			</tr>
<!--Image Delete began-->

		<?php 
			if(isset($_GET['delimg'])){

				$imgid = $_GET['delimg'];

				$retrivefordelete = "SELECT * FROM tbl_image WHERE imgid = $imgid";
				$execute = $db->select($retrivefordelete);
				if($execute){
					while($unlinkresult = $execute->fetch_assoc()){
					$delimg = $unlinkresult['image'];
					unlink($delimg);
				}
				}
				


				$sql = "DELETE FROM tbl_image WHERE imgid = $imgid";
				$delrow = $db->delete($sql);
				if($delrow){
					echo "<span class='success'>image deleted successfully</span>";
				}else{
					echo "<span class='error'>image not deleted</span>";
				}
			}
		?>
	
<!--Image Delete end-->
	
<!--Image Retrive began-->
		<?php 
			$sql = "SELECT * FROM tbl_image";
			$getimg = $db->select($sql);
			if($getimg){
			$i=0;
			while($result = $getimg->fetch_assoc()){
			$i++;
		?>
			<tr>
				<td><?php echo $i; ?></td>
				<td><img src=" <?php echo $result['image']; ?> " height="60px" width="120px" /></td>
				<td><a href="?delimg=<?php echo $result['imgid'] ?>" onclick="return confirm('Are you sure to delete')" >Delete</a></td>
			</tr>
		<?php } } ?>
	
<!--Image Retrive end-->
		</table>

	</div>
<?php include 'inc/footer.php'; ?>