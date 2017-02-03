<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Exenstion File Uploading Class
 */
class MY_Upload extends CI_Upload
{

    public function do_multi_upload($field = 'userfile', $return_info = TRUE)
    {

        // Is $_FILES[$field] set? If not, no reason to continue.
        if (!isset($_FILES[$field])) {

            $this->set_error('upload_no_file_selected');
            return FALSE;

        }

        //If not every file filled was used, clear the empties
        /*foreach ($_FILES[$field] as $k => $n) {

            if ($n === '') {

                foreach ($_FILES[$field] as $kk => $f) {

                    unset($_FILES[$field][$kk][$k]);

                }

            }

        }*/

        // Is the upload path valid?
        if (!$this->validate_upload_path($field)) {

            // errors will already be set by validate_upload_path() so just return FALSE
            return FALSE;
        }

        //Multiple file upload
        if (is_array($_FILES[$field])) {

            $return_value = [];

            //Rebuild the file array so that its easier to update any core Codeigniter code
            $newFileArray = [];
            foreach ($_FILES[$field] as $index => $values) {
                foreach ($values as $key => $val) {
                    $newFileArray[$key][$index] = $val;
                }
            }

            foreach ($newFileArray as $k => $_file) {

                /*
                 * CORE Codeigniter library code:
                 * Path: framework/system/libraries/Upload.php
                 * Line: 412
                 */

                // Was the file able to be uploaded? If not, determine the reason why.
                if (!is_uploaded_file($_file['tmp_name'])) {
                    $error = isset($_file['error']) ? $_file['error'] : 4;

                    switch ($error) {
                        case UPLOAD_ERR_INI_SIZE:
                            $this->set_error('upload_file_exceeds_limit', 'info');
                            break;
                        case UPLOAD_ERR_FORM_SIZE:
                            $this->set_error('upload_file_exceeds_form_limit', 'info');
                            break;
                        case UPLOAD_ERR_PARTIAL:
                            $this->set_error('upload_file_partial', 'debug');
                            break;
                        case UPLOAD_ERR_NO_FILE:
                            $this->set_error('upload_no_file_selected', 'debug');
                            break;
                        case UPLOAD_ERR_NO_TMP_DIR:
                            $this->set_error('upload_no_temp_directory', 'error');
                            break;
                        case UPLOAD_ERR_CANT_WRITE:
                            $this->set_error('upload_unable_to_write_file', 'error');
                            break;
                        case UPLOAD_ERR_EXTENSION:
                            $this->set_error('upload_stopped_by_extension', 'debug');
                            break;
                        default:
                            $this->set_error('upload_no_file_selected', 'debug');
                            break;
                    }

                    return FALSE;
                }

                // Set the uploaded data as class variables
                $this->file_temp = $_file['tmp_name'];
                $this->file_size = $_file['size'];

                // Skip MIME type detection?
                if ($this->detect_mime !== FALSE) {
                    $this->_file_mime_type($_file);
                }

                $this->file_type = preg_replace('/^(.+?);.*$/', '\\1', $this->file_type);
                $this->file_type = strtolower(trim(stripslashes($this->file_type), '"'));
                $this->file_name = $this->_prep_filename($_file['name']);
                $this->file_ext = $this->get_extension($this->file_name);
                $this->client_name = $this->file_name;

                // Is the file type allowed to be uploaded?
                if (!$this->is_allowed_filetype()) {
                    $this->set_error('upload_invalid_filetype', 'debug');
                    return FALSE;
                }

                // if we're overriding, let's now make sure the new name and type is allowed
                if ($this->_file_name_override !== '') {
                    $this->file_name = $this->_prep_filename($this->_file_name_override);

                    // If no extension was provided in the file_name config item, use the uploaded one
                    if (strpos($this->_file_name_override, '.') === FALSE) {
                        $this->file_name .= $this->file_ext;
                    } else {
                        // An extension was provided, let's have it!
                        $this->file_ext = $this->get_extension($this->_file_name_override);
                    }

                    if (!$this->is_allowed_filetype(TRUE)) {
                        $this->set_error('upload_invalid_filetype', 'debug');
                        return FALSE;
                    }
                }

                // Convert the file size to kilobytes
                if ($this->file_size > 0) {
                    $this->file_size = round($this->file_size / 1024, 2);
                }

                // Is the file size within the allowed maximum?
                if (!$this->is_allowed_filesize()) {
                    $this->set_error('upload_invalid_filesize', 'info');
                    return FALSE;
                }

                // Are the image dimensions within the allowed size?
                // Note: This can fail if the server has an open_basedir restriction.
                if (!$this->is_allowed_dimensions()) {
                    $this->set_error('upload_invalid_dimensions', 'info');
                    return FALSE;
                }

                // Sanitize the file name for security
                $this->file_name = $this->_CI->security->sanitize_filename($this->file_name);

                // Truncate the file name if it's too long
                if ($this->max_filename > 0) {
                    $this->file_name = $this->limit_filename_length($this->file_name, $this->max_filename);
                }

                // Remove white spaces in the name
                if ($this->remove_spaces === TRUE) {
                    $this->file_name = preg_replace('/\s+/', '_', $this->file_name);
                }

                if ($this->file_ext_tolower && ($ext_length = strlen($this->file_ext))) {
                    // file_ext was previously lower-cased by a get_extension() call
                    $this->file_name = substr($this->file_name, 0, -$ext_length) . $this->file_ext;
                }

                /*
                 * Validate the file name
                 * This function appends an number onto the end of
                 * the file if one with the same name already exists.
                 * If it returns false there was a problem.
                 */
                $this->orig_name = $this->file_name;
                if (FALSE === ($this->file_name = $this->set_filename($this->upload_path, $this->file_name))) {
                    return FALSE;
                }

                /*
                 * Run the file through the XSS hacking filter
                 * This helps prevent malicious code from being
                 * embedded within a file. Scripts can easily
                 * be disguised as images or other file types.
                 */
                if ($this->xss_clean && $this->do_xss_clean() === FALSE) {
                    $this->set_error('upload_unable_to_write_file', 'error');
                    return FALSE;
                }

                /*
                 * Move the file to the final destination
                 * To deal with different server configurations
                 * we'll attempt to use copy() first. If that fails
                 * we'll use move_uploaded_file(). One of the two should
                 * reliably work in most environments
                 */
                if (!@copy($this->file_temp, $this->upload_path . $this->file_name)) {
                    if (!@move_uploaded_file($this->file_temp, $this->upload_path . $this->file_name)) {
                        $this->set_error('upload_destination_error', 'error');
                        return FALSE;
                    }
                }

                /*
                 * Set the finalized image dimensions
                 * This sets the image width/height (assuming the
                 * file was an image). We use this information
                 * in the "data" function.
                 */
                $this->set_image_properties($this->upload_path . $this->file_name);

                if ($return_info === TRUE) {

                    $return_value[$k] = $this->data();

                } else {

                    $return_value = TRUE;

                }


            }

            return $return_value;

        } else //Single file upload, rely on native CI upload class
        {
            return self::do_upload();
        }


    }

}
