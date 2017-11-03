<?php
namespace SCL\Lib;

defined("SCL_SAFETY_CONST") or die;

class Imgconv
{

    public function init($product_id)
    {
        $message = "";

        $file_name = $_FILES["imageinput"]["name"];
        $file_size = $_FILES["imageinput"]["size"];
        $file_tmp  = $_FILES["imageinput"]["tmp_name"];
//        $file_type = $_FILES["imageinput"]["type"];

        $file_ext_name = explode(".", $file_name);
        $raw_file_ext  = end($file_ext_name);
        $file_ext      = strtolower($raw_file_ext);
        if ($file_ext === "jpeg") {
            $file_ext  = "jpg";
        }

        $expensions = array("jpg", "png", "gif");

        if (in_array($file_ext, $expensions) === false){
            $message .= "The " . $file_ext . " extension is not allowed, "
                      . "please choose a JPEG or PNG file.";
        }

        if ($file_size > 5242880){
            $message .= "File size must be less then 5 MB";
        }

        if ($message === "") {

            $temp_dir  = SCL_ROOT_DIR . "Web/pictures/temp/temp_"
                       . $product_id  . "." . $file_ext;

            $image_dir = SCL_ROOT_DIR . "Web/pictures/img_"
                       . $product_id  . ".jpg";

            $thumb_dir = SCL_ROOT_DIR . "Web/pictures/thumbs/thumb_"
                       . $product_id  . ".jpg";

            move_uploaded_file($file_tmp, $temp_dir);

            $img_w = 1600;
            $img_h = 1200;
            $this->img_resize($temp_dir,
                              $image_dir,
                              $img_w,
                              $img_h,
                              $file_ext);

            $thumb_w = 160;
            $thumb_h = 120;
            $this->img_resize($temp_dir,
                              $thumb_dir,
                              $thumb_w,
                              $thumb_h,
                              $file_ext);
        }

        return $message;
    }

    private function img_resize($target,
                                $image_dir,
                                $dest_imagex,
                                $dest_imagey,
                                $file_ext)
    {
        switch ($file_ext) {
            case "gif":
                $source_image = imagecreatefromgif($target);
                break;

            case "png":
                $source_image = imagecreatefrompng($target);
                break;

            case "jpg":
                $source_image = imagecreatefromjpeg($target);
                break;
        }

        $source_imagex = imagesx($source_image);
        $source_imagey = imagesy($source_image);

        $dest_image = imagecreatetruecolor($dest_imagex, $dest_imagey);

        imagecopyresampled($dest_image,
                           $source_image,
                           0, 0, 0, 0,
                           $dest_imagex,
                           $dest_imagey,
                           $source_imagex,
                           $source_imagey);

        imagejpeg($dest_image, $image_dir, 84);
    }
}

//// Access the $_FILES global variable for this specific file being uploaded
//// and create local PHP variables from the $_FILES array of information
//$fileName = $_FILES["imageinput"]["name"]; // The file name
//$fileTmpLoc = $_FILES["imageinput"]["tmp_name"]; // File in the PHP tmp folder
//$fileType = $_FILES["imageinput"]["type"]; // The type of file it is
//$fileSize = $_FILES["imageinput"]["size"]; // File size in bytes
//$fileErrorMsg = $_FILES["imageinput"]["error"]; // 0 for false... and 1 for true
//$fileName = preg_replace("#[^a-z.0-9]#i", "", $fileName); // filter the $filename
//$kaboom = explode(".", $fileName); // Split file name into an array using the dot
//$fileExt = end($kaboom); // Now target the last array element to get the file extension
//if ($fileExt === "jpeg") {
//    $fileExt = "jpg";
//}

//// START PHP Image Upload Error Handling --------------------------------
//if (!$fileTmpLoc) { // if file not chosen
//    // echo "ERROR: Please browse for a file before clicking the upload button.";
//    exit();
//} else if($fileSize > 5242880) { // if file size is larger than 5 Megabytes
//    // echo "ERROR: Your file was larger than 5 Megabytes in size.";
//    unlink($fileTmpLoc); // Remove the uploaded file from the PHP temp folder
//    exit();
//} else if (!preg_match("/.(gif|jpg|jpeg|png)$/i", $fileName) ) {
//     // This condition is only if you wish to allow uploading of specific file types
//     // echo "ERROR: Your image was not .gif, .jpg, or .png.";
//     unlink($fileTmpLoc); // Remove the uploaded file from the PHP temp folder
//     exit();
//} else if ($fileErrorMsg == 1) { // if file upload error key is equal to 1
//    // echo "ERROR: An error occured while processing the file. Try again.";
//    exit();
//}
//// END PHP Image Upload Error Handling ----------------------------------
//// Place it into your "uploads" folder mow using the move_uploaded_file() function
//$moveResult = move_uploaded_file($fileTmpLoc, SCL_ROOT_DIR . "Web/pictures/temp/" . $product_id);
//// Check to make sure the move result is true before continuing
//if ($moveResult != true) {
//    // echo "ERROR: File not uploaded. Try again.";
//    exit();
//}
//
//if (strtolower($fileExt) == "jpg" || strtolower($fileExt) == "jpeg") {
//// ---------- Start Universal Image Resizing Function --------
//    $target_file = SCL_ROOT_DIR . "Web/pictures/temp/" . $product_id;
//
//    $resized_file = SCL_ROOT_DIR . "Web/pictures/img_$product_id".".jpg";
//    $wmax = 1000;
//    $hmax = 1000;
//    ak_img_resize($target_file, $resized_file, $wmax, $hmax, "jpg");
//
//    $thumb_file = SCL_ROOT_DIR . "Web/pictures/thumbs/thumb_$product_id".".jpg";
//    $wmax = 160;
//    $hmax = 160;
//    ak_img_resize($resized_file, $thumb_file, $wmax, $hmax, "jpg");
//// ----------- End Universal Image Resizing Function ----------
//    unlink($target_file);
//} else {
//    $target_file = SCL_ROOT_DIR . "Web/pictures/temp/" . $product_id;
//
//    $new_jpg = SCL_ROOT_DIR . "Web/pictures/img_".$product_id.".jpg";
//    ak_img_convert_to_jpg($target_file, $new_jpg, $fileExt);
//
//    $resized_file = SCL_ROOT_DIR . "Web/pictures/img_$product_id".".jpg";
//    $wmax = 1000;
//    $hmax = 1000;
//    ak_img_resize($target_file, $resized_file, $wmax, $hmax, $fileExt);
//
//    $thumb_file = SCL_ROOT_DIR . "Web/pictures/thumbs/thumb_$product_id".".jpg";
//    $wmax = 160;
//    $hmax = 160;
//    ak_img_resize($resized_file, $thumb_file, $wmax, $hmax, $fileExt);
//
//    unlink($target_file);
//}
//
//
//// ----------- End Convert to JPG Function -----------
//// Display things to the page so you can see what is happening for testing purposes
//// echo "The file named <strong>$fileName</strong> uploaded successfuly.<br /><br />";
//// echo "It is <strong>$fileSize</strong> bytes in size.<br /><br />";
//// echo "It is an <strong>$fileType</strong> type of file.<br /><br />";
//// echo "The file extension is <strong>$fileExt</strong><br /><br />";
//// echo "The Error Message output for this upload is: $fileErrorMsg";
//
//
//
//
//
//// ----------------------- RESIZE FUNCTION -----------------------
//// Function for resizing any jpg, gif, or png image files
//function ak_img_resize($target, $newcopy, $w, $h, $ext) {
//    list($w_orig, $h_orig) = getimagesize($target);
//    $scale_ratio = $w_orig / $h_orig;
//    if (($w / $h) > $scale_ratio) {
//        $w = $h * $scale_ratio;
//    } else {
//        $h = $w / $scale_ratio;
//    }
//    $img = "";
//    $ext = strtolower($ext);
//    if ($ext == "gif"){
//        $img = imagecreatefromgif($target);
//    } else if($ext =="png"){
//        $img = imagecreatefrompng($target);
//    } else {
//        $img = imagecreatefromjpeg($target);
//    }
//    $tci = imagecreatetruecolor($w, $h);
//    // imagecopyresampled(dst_img, src_img, dst_x, dst_y, src_x, src_y, dst_w, dst_h, src_w, src_h)
//    imagecopyresampled($tci, $img, 0, 0, 0, 0, $w, $h, $w_orig, $h_orig);
//    if ($ext == "gif"){
//        imagegif($tci, $newcopy);
//    } else if($ext =="png"){
//        imagepng($tci, $newcopy);
//    } else {
//        imagejpeg($tci, $newcopy, 84);
//    }
//}
//// ---------------- THUMBNAIL (CROP) FUNCTION ------------------
//// Function for creating a true thumbnail cropping from any jpg, gif, or png image files
//function ak_img_thumb($target, $newcopy, $w, $h, $ext) {
//    list($w_orig, $h_orig) = getimagesize($target);
//    $src_x = ($w_orig / 2) - ($w / 2);
//    $src_y = ($h_orig / 2) - ($h / 2);
//    $ext = strtolower($ext);
//    $img = "";
//    if ($ext == "gif"){
//        $img = imagecreatefromgif($target);
//    } else if($ext =="png"){
//        $img = imagecreatefrompng($target);
//    } else {
//        $img = imagecreatefromjpeg($target);
//    }
//    $tci = imagecreatetruecolor($w, $h);
//    imagecopyresampled($tci, $img, 0, 0, $src_x, $src_y, $w, $h, $w, $h);
//    if ($ext == "gif"){
//        imagegif($tci, $newcopy);
//    } else if($ext =="png"){
//        imagepng($tci, $newcopy);
//    } else {
//        imagejpeg($tci, $newcopy, 84);
//    }
//}
//// ------------------ IMAGE CONVERT FUNCTION -------------------
//// Function for converting GIFs and PNGs to JPG upon upload
//function ak_img_convert_to_jpg($target, $newcopy, $ext) {
//    list($w_orig, $h_orig) = getimagesize($target);
//    $ext = strtolower($ext);
//    $img = "";
//    if ($ext == "gif"){
//        $img = imagecreatefromgif($target);
//    } else if($ext =="png"){
//        $img = imagecreatefrompng($target);
//    }
//    $tci = imagecreatetruecolor($w_orig, $h_orig);
//    imagecopyresampled($tci, $img, 0, 0, 0, 0, $w_orig, $h_orig, $w_orig, $h_orig);
//    imagejpeg($tci, $newcopy, 84);
//}
