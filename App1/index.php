<?php
    $HOST = "localhost";
    $USER = "root";
    $PASS = "";
    $DB = "App1";
    $ERROR1 = "Loi mysql";
    $ERROR2 = "Loi DB";
    $con = mysqli_connect($HOST, $USER, $PASS) or die($ERROR1);
    @mysqli_select_db($con, $DB) or die($ERROR2);
    mysqli_set_charset($con, 'UTF8');

    date_default_timezone_set('Asia/Ho_Chi_Minh');
?>
<!DOCTYPE html>
<html>
<head>
<title>App1 Form</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
</head>
<body>

<h1 class="alert alert-warning">App1</h1>

<div class="row">
	<div class="col-md-6 form-group">
		<form action="index.php" method="post" enctype="multipart/form-data">
			<label>Tên user</label>
			<input type="text" name="txtTen" class="form-control" value="" required>
			<label>Số điện thoại</label>
			<input type="tel" name="txtSdt" class="form-control" placeholder="0123456789" pattern="[0]{1}[0-9]{9}" value="" required>
		    <label>Chọn file để upload:</label>
		    <input type="file" name="fileupload" class="form-control"><br>
		    <button type="submit" name="ok" class="btn btn-success">Xác nhận</button><br><br><br>
		</form>
	</div>
  <div class="col-md-6">
    <div class="card">
  <div class="card-header">
    Tra cứu
  </div>
  <div class="card-body">
    <form action="index.php" method="POST">
    <h5 class="card-title">Tìm kiếm thông tin</h5>
    <input type="tel" name="txtTim" class="form-control" placeholder="0123456789" pattern="[0]{1}[0-9]{9}" value="" required>
    <button type="submit" name="btnTim" class="btn btn-warning">Tìm</button>
  </form>
  </div>
  <div class="card-body">
    <form action="index.php" method="POST">
    <h5 class="card-title">Tra cứu thưởng</h5>
    <label>Số điện thoại</label>
    <input type="tel" name="txtTsdt" class="form-control" placeholder="0123456789" pattern="[0]{1}[0-9]{9}" value="" required>
    <label>Mã dự thưởng</label>
    <input type="number" name="txtTmathuong" class="form-control" min="0" value="" required>
    <button type="submit" name="btnTra" class="btn btn-primary">Tra cứu</button>
  </form>
  </div>
</div>
  </div>
  <hr>
  <div class="col-md-12">
    <h3 class="alert alert-info">BẢNG THÔNG TIN</h3>
    <table class="table table-dark">
      <thead>
        <tr>
          <th>STT</th>
          <th>Tên</th>
          <th>SĐT</th>
          <th>Ảnh đại diện</th>
          <th>Mã dự thưởng</th>
        </tr>
      </thead>
      <tbody>
        <?php
          $i=0;
          if(isset($_POST['btnTim'])&&isset($_POST['txtTim'])){
            $sdt = strip_tags($_POST['txtTim']);
            $sql_xem = "SELECT * FROM user WHERE sdt = '".$sdt."'";
          }else{
            $sql_xem = "SELECT * FROM user";
          }
          $query_xem = mysqli_query($con,$sql_xem);
          while($row_xem = mysqli_fetch_array($query_xem)){
            $i++;
            ?>
            <tr>
              <td><?=$i?></td>
              <td><?=$row_xem['ten']?></td>
              <td><?=$row_xem['sdt']?></td>
              <td><img src="<?=$row_xem['file']?>" title='<?=$row_xem['ten']?>'style="width: 100px;height: 100px"></td>
              <td><?=$row_xem['mathuong']?></td>
            </tr>
            <?php
          }
        ?>
      </tbody>
    </table>
  </div>
</div>
</body>
</html>
<?php
//Tra mã thưởng
if(isset($_POST['btnTra'])&&isset($_POST['txtTsdt'])&&isset($_POST['txtTmathuong'])){
  $sdt = strip_tags($_POST['txtTsdt']);
  $mathuong = strip_tags($_POST['txtTmathuong']);
  $sql_tra = "SELECT * FROM user WHERE sdt = '".$sdt."' AND mathuong = '".$mathuong."'";
  $query_tra = mysqli_query($con,$sql_tra);
  $num_tra = mysqli_num_rows($query_tra);
  if($num_tra != 0){
    echo "<script>alert('Chúc mừng bạn có quà!!!')</script>";
  }else{
    echo "<script>alert('Bạn không có quà!!!')</script>";
  }
}
//Thêm dữ liệu
	if(isset($_POST['ok'])){
		$ten = strip_tags($_POST['txtTen']);
		$sdt = strip_tags($_POST['txtSdt']);
		$mathuong = time();
    $sql_ktra = "SELECT * FROM user WHERE sdt = '".$sdt."'";
    $query_ktra = mysqli_query($con,$sql_ktra);
    $num_ktra = mysqli_num_rows($query_ktra);
    if($num_ktra!=0){
      echo "<script>alert('SĐT đã được sử dụng!!!')</script>";
      exit();
    }
  if ($_SERVER['REQUEST_METHOD'] !== 'POST')
  {
      // Dữ liệu gửi lên server không bằng phương thức post
      echo "Phải Post dữ liệu";
      die;
  }

  // Kiểm tra có dữ liệu fileupload trong $_FILES không
  // Nếu không có thì dừng
  if (!isset($_FILES["fileupload"]))
  {
      echo "Dữ liệu không đúng cấu trúc";
      die;
  }

  // Kiểm tra dữ liệu có bị lỗi không
  if ($_FILES["fileupload"]['error'] != 0)
  {
    echo "Dữ liệu upload bị lỗi";
    die;
  }

  // Đã có dữ liệu upload, thực hiện xử lý file upload

  //Thư mục bạn sẽ lưu file upload
  $target_dir    = "uploads/";
  //Vị trí file lưu tạm trong server (file sẽ lưu trong uploads, với tên giống tên ban đầu)
  $target_file   = $target_dir . basename($_FILES["fileupload"]["name"]);

  $allowUpload   = true;

  //Lấy phần mở rộng của file (jpg, png, ...)
  $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

  // Cỡ lớn nhất được upload (bytes)
  $maxfilesize   = 800000;

  ////Những loại file được phép upload
  $allowtypes    = array('jpg', 'png', 'jpeg', 'gif');


  if(isset($_POST["submit"])) {
      //Kiểm tra xem có phải là ảnh bằng hàm getimagesize
      $check = getimagesize($_FILES["fileupload"]["tmp_name"]);
      if($check !== false)
      {
          echo "Đây là file ảnh - " . $check["mime"] . ".";
          $allowUpload = true;
      }
      else
      {
          echo "Không phải file ảnh.";
          $allowUpload = false;
      }
  }

  // Kiểm tra nếu file đã tồn tại thì không cho phép ghi đè
  // Bạn có thể phát triển code để lưu thành một tên file khác
  if (file_exists($target_file))
  {
      echo "Tên file đã tồn tại trên server, không được ghi đè";
      $allowUpload = false;
  }
  // Kiểm tra kích thước file upload cho vượt quá giới hạn cho phép
  if ($_FILES["fileupload"]["size"] > $maxfilesize)
  {
      echo "Không được upload ảnh lớn hơn $maxfilesize (bytes).";
      $allowUpload = false;
  }


  // Kiểm tra kiểu file
  if (!in_array($imageFileType,$allowtypes ))
  {
      echo "Chỉ được upload các định dạng JPG, PNG, JPEG, GIF";
      $allowUpload = false;
  }


  if ($allowUpload)
  {
      // Xử lý di chuyển file tạm ra thư mục cần lưu trữ, dùng hàm move_uploaded_file
      if (move_uploaded_file($_FILES["fileupload"]["tmp_name"], $target_file))
      {
          echo "File ". basename( $_FILES["fileupload"]["name"]).
          " Đã upload thành công.";
          $sql = "INSERT INTO user(ten,sdt,mathuong,file) VALUES('".$ten."','".$sdt."','".$mathuong."','".$target_file."')";
  $query = mysqli_query($con,$sql);
  if($query){
    echo "<script>alert('Thành công!!!')</script>";
    echo "<script>location.href='index.php';</script>";
  }else{
    echo "<script>alert('Lỗi!!!')</script>";
  }

      }
      else
      {
          echo "Có lỗi xảy ra khi upload file.";
      }
  }
  else
  {
      echo "Không upload được file, có thể do file lớn, kiểu file không đúng ...";
  }
  
	}
?>