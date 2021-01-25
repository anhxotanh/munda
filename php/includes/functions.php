<?php
  // Validate uploaded files
  // Function source: http://php.net/features.file-upload
  function file_upload_validate($file, $mime_types, $max_size, $dir) {
    try {
      if(!isset($file['error']) || is_array($file['error'])) {
        throw new RuntimeException('Invalid parameters.');
      }

      // Check $file['error'] value
      switch ($file['error']) {
        case UPLOAD_ERR_OK:
          break;
        case UPLOAD_ERR_NO_FILE:
          throw new RuntimeException('No file was uploaded.');
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
          throw new RuntimeException('Exceeded file size limit.');
        default:
          throw new RuntimeException('Unknown errors.');
      }

      // You should also check filesize here.
      if($file['size'] > $max_size) {
        throw new RuntimeException('Exceeded file size limit.');
      }

      // Do not trust $file['mime'] value!
      // Check MIME Type by yourself.
      $finfo = new finfo(FILEINFO_MIME_TYPE);

      if(false === $ext = array_search($finfo->file($file['tmp_name']), $mime_types, true)) {
        throw new RuntimeException('Invalid file format.');
      }

      // You should name it uniquely
      // Do not use $files['name'] without any validation!
      // Obtain safe unique name from its binary data.
      // if(!move_uploaded_file($file['tmp_name'], sprintf('' . $dir . '/%s.%s', sha1_file($file['tmp_name']), $ext))) {
      //   throw new RuntimeException('Failed to move uploaded file.');
      // }
      return 'success';

    } catch(RuntimeException $e) {
      return $e->getMessage();
    }
  }
?>
