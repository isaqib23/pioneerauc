<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Files_Model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('string');
        $this->load->library('image_lib');
    }


    public function upload($file = "", $config = array(), $sizes = array())
    {
        if (($config) && (!empty($file))) {

            $config['file_name'] = random_string('alnum', 10);
            $this->load->library('upload', $config);
            if (!$this->upload->do_upload($file)) {
                return array('error' => $this->upload->display_errors());
            } else {
                $user_id = isset($this->session->userdata('logged_in')->id) ? $this->session->userdata('logged_in')->id : '0';
                $uploaded = $this->upload->data();
                $file = array(
                    'name' => $uploaded['file_name'],
                    'orignal_name' => $_FILES[$file]['name'],
                    'type' => $uploaded['file_type'],
                    'size' => $uploaded['file_size'],
                    'path' => $config['upload_path'],
                    'created_by' => $user_id,
                    'created_on' => date('Y-m-d H:i:s')
                );
                $result = $this->db->insert('files', $file);
                $inserted_file_id = $this->db->insert_id();
                if ($result) {

                    if ($sizes) {
                        $source_image = $file['path'] . $file['name'];

                        foreach ($sizes as $key => $size) {
                            $new_image = $file['path'] . $size['width'] . 'x' . $size['height'] . '_' . $file['name'];
                            $resize['image_library'] = 'gd2';
                            $resize['source_image'] = $source_image;
                            $resize['new_image'] = $new_image;
                            $resize['create_thumb'] = TRUE;
                            $resize['maintain_ratio'] = FALSE;
                            $resize['thumb_marker'] = FALSE;
                            $resize['height'] = $size['height'];
                            $resize['width'] = $size['width'];
                            $resize['master_dim'] = 'height';
                            $this->image_lib->initialize($resize);
                            $this->image_lib->resize();
                            $this->image_lib->clear();
                            $this->image_lib = new CI_Image_Lib();
                            unset($this->image_lib);
                        }
                    }

                    return array('id' => $inserted_file_id);
                } else {
                    return array('error' => 'Database error: unable to update file detail in database.');
                }
            }
        } else {
            return array('error' => 'Invalid data pass to upload!');
        }
    }

    public function multiUpload($field = "", $config = array(), $sizes = array())
    {
        if (($config) && (!empty($field))) {


            $filesCount = count($_FILES[$field]['name']);
            for ($i = 0; $i < $filesCount; $i++) {
                $_FILES['file']['name'] = $_FILES[$field]['name'][$i];
                $_FILES['file']['type'] = $_FILES[$field]['type'][$i];
                $_FILES['file']['tmp_name'] = $_FILES[$field]['tmp_name'][$i];
                $_FILES['file']['error'] = $_FILES[$field]['error'][$i];
                $_FILES['file']['size'] = $_FILES[$field]['size'][$i];


                $config['file_name'] = random_string('alnum', 10);
                // $config['file_name'] = $_FILES['file']['name']; // same name as file name 
                // if want to change file name as unique name
                // $config['file_name'] = random_string('alnum', 10); 
                $this->load->library('upload', $config);
                $this->upload->initialize($config);

                if (!$this->upload->do_upload('file')) {
                    return array('error' => $this->upload->display_errors());
                } else {
                    $user_id = isset($this->session->userdata('logged_in')->id) ? $this->session->userdata('logged_in')->id : '0';
                    $uploaded = $this->upload->data();
                    $file = array(
                        'name' => $uploaded['file_name'],
                        'orignal_name' => $_FILES[$field]['name'][$i],
                        'type' => $uploaded['file_type'],
                        'size' => $uploaded['file_size'],
                        'path' => $config['upload_path'],
                        'created_by' => $user_id,
                        'created_on' => date('Y-m-d H:i:s')
                    );

                    $result = $this->db->insert('files', $file);
                    if ($result) {
                        $result_upload[] = $this->db->insert_id();

                        if ($sizes) {
                            $source_image = $file['path'] . $file['name'];
                            foreach ($sizes as $key => $size) {
                                $new_image = $file['path'] . $size['width'] . 'x' . $size['height'] . '_' . $file['name'];
                                $resize['image_library'] = 'gd2';
                                $resize['source_image'] = $source_image;
                                $resize['new_image'] = $new_image;
                                $resize['create_thumb'] = TRUE;
                                $resize['maintain_ratio'] = FALSE;
                                $resize['thumb_marker'] = FALSE;
                                $resize['width'] = $size['width'];
                                $resize['height'] = $size['height'];
                                $resize['master_dim'] = 'height';
                                $this->image_lib->initialize($resize);
                                $this->image_lib->resize();
                                $this->image_lib->clear();
                                $this->image_lib = new CI_Image_Lib();
                                unset($this->image_lib);
                            }

                        }
                    } else {
                        $result_upload['error'] = 'Database error: unable to update file detail in database.';
                    }
                }
            }
            return $result_upload;
        } else {
            return array('error' => 'Invalid data pass to upload!');
        }
    }

    public function multiUpload2($field = "", $config = array(), $sizes = array(), $item_id = "")
    {
        if (($config) && (!empty($field))) {

            $filesCount = count($_FILES[$field]['name']);
            for ($i = 0; $i < $filesCount; $i++) {
                $_FILES['file']['name'] = $_FILES[$field]['name'][$i];
                $_FILES['file']['type'] = $_FILES[$field]['type'][$i];
                $_FILES['file']['tmp_name'] = $_FILES[$field]['tmp_name'][$i];
                $_FILES['file']['error'] = $_FILES[$field]['error'][$i];
                $_FILES['file']['size'] = $_FILES[$field]['size'][$i];

                //$config['file_name'] = random_string('alnum', 10);
                $config['file_name'] = $_FILES['file']['name']; // same name as file name
                // if want to change file name as unique name
                // $config['file_name'] = random_string('alnum', 10);
                $this->load->library('upload', $config);
                $this->upload->initialize($config);

                if (!$this->upload->do_upload('file')) {
                    return array('error' => $this->upload->display_errors());
                } else {
                    $user_id = isset($this->session->userdata('logged_in')->id) ? $this->session->userdata('logged_in')->id : '0';
                    $uploaded = $this->upload->data();
                    $file = array(
                        'name' => $uploaded['file_name'],
                        'orignal_name' => $_FILES[$field]['name'][$i],
                        'type' => $uploaded['file_type'],
                        'size' => $uploaded['file_size'],
                        'path' => $config['upload_path'],
                        'created_by' => $user_id,
                        'created_on' => date('Y-m-d H:i:s')
                    );

                    $result = $this->db->insert('files', $file);
                    if ($result) {
                        $result_upload[] = $this->db->insert_id();

                        if ($sizes) {
                            $source_image = $file['path'] . $file['name'];
                            foreach ($sizes as $key => $size) {
                                $new_image = $file['path'] . $size['width'] . 'x' . $size['height'] . '_' . $file['name'];
                                $resize['image_library'] = 'gd2';
                                $resize['source_image'] = $source_image;
                                $resize['new_image'] = $new_image;
                                $resize['create_thumb'] = TRUE;
                                $resize['maintain_ratio'] = FALSE;
                                $resize['thumb_marker'] = FALSE;
                                $resize['width'] = $size['width'];
                                $resize['height'] = $size['height'];
                                $resize['master_dim'] = 'height';
                                $this->image_lib->initialize($resize);
                                $this->image_lib->resize();
                                $this->image_lib->clear();
                                $this->image_lib = new CI_Image_Lib();
                                unset($this->image_lib);
                            }

                        }
                    } else {
                        $result_upload['error'] = 'Database error: unable to update file detail in database.';
                    }
                }
            }
            return $result_upload;
        } else {
            return array('error' => 'Invalid data pass to upload!');
        }
    }

    public function delete_by_name($fileName = "", $path = "./uploads/icons/", $sizes = array())
    {
        if ($fileName) {
            $old_file_path = $path . $fileName;
            if (file_exists($old_file_path)) {
                unlink($old_file_path);
                $result = $this->db->delete('files', ['name' => $fileName]);

                if ($sizes) {
                    foreach ($sizes as $key => $size) {
                        $fname = $path . $size['width'] . 'x' . $size['height'] . '_' . $fileName;
                        $old_file_path = $path . $fname;
                        if (file_exists($old_file_path)) {
                            unlink($old_file_path);
                        }
                    }
                }

                return ($result) ? true : false;
            } else {
                return false;
            }
        }
    }

    public function get_file_byName($name)
    {
        $this->db->select('*');
        $this->db->from('files');
        $this->db->where('name', $name);
        $result = $this->db->get()->result_array();
        return $result;
    }

    public function get_multiple_files_by_ids($ids_array)
    {
        $this->db->select('*');
        $this->db->from('files');
        $this->db->where_in('id', $ids_array);
        $this->db->order_by('id', 'ASC');
        $result = $this->db->get()->result_array();
        return $result;
    }

    public function get_multiple_files_by_ids_orders($ids_array)
    {
        $this->db->select('*');
        $this->db->from('files');
        $this->db->where_in('id', $ids_array);
        $this->db->order_by('file_order', 'asc');
        $result = $this->db->get()->result_array();
        return $result;
    }


    public function update_file($id, $data)
    {
        $this->db->where('id', $id);
        $query = $this->db->update('files', $data);
        if ($query) {
            return $query;
        } else {
            return false;
        }
    }

    public function delete_by_id($fileId = "", $path = "./uploads/icons/", $sizes = array())
    {
        if ($fileId) {
            $file = $this->db->get_where('files', ['id' => $fileId])->row_array();
            if ($file) {
                $fileName = $file['name'];
                $old_file_path = $path . $fileName;
                if (file_exists($old_file_path)) {
                    unlink($old_file_path);
                    $result = $this->db->delete('files', ['name' => $fileName]);

                    if ($sizes) {
                        foreach ($sizes as $key => $size) {
                            $fname = $path . $size['width'] . 'x' . $size['height'] . '_' . $fileName;
                            $old_file_path = $path . $fname;
                            if (file_exists($old_file_path)) {
                                unlink($old_file_path);
                            }
                        }
                    }

                    return ($result) ? true : false;
                } else {
                    return false;
                }
            }
        }
    }

    public function file_size($size)
    {
        $bytes = (float)$size * 1024;
        $unit = array('b', 'kb', 'mb', 'gb', 'tb', 'pb');
        return round($bytes / pow(1024, ($i = floor(log($bytes, 1024)))), 2) . ' ' . $unit[$i];
    }

    public function get_file_by_id($id)
    {
        $this->db->select('*');
        $this->db->from('files');
        $this->db->where('id', $id);
        $result = $this->db->get()->result_array();
        return $result;
    }

    public function base64_multiupload($images = array(), $path = '', $sizes = array())
    {
        if (!empty($images)) {
            $user_id = isset($this->session->userdata('logged_in')->id) ? $this->session->userdata('logged_in')->id : '0';
            foreach ($images as $key => $image) {
                $array_image64 = explode(',', $image);
                $image64 = base64_decode($array_image64[1]);

                //get type from base 64 string
                $image64_mime = $this->get_string_between($array_image64['0'], ':', ';');
                $image64_ext = $this->mime2ext($image64_mime);

                //get size of image base 64 in bytes
                //formula: x = (n * (3/4)) - y
                $n = strlen($image64);
                $str_end = substr($image64, -2);
                $y = ($str_end == '==') ? 2 : 1;
                $image64_size = ($n * (3 / 4)) - $y;

                $output_path = $_SERVER['DOCUMENT_ROOT'] . $path;
                if (!is_dir($output_path)) {
                    mkdir($output_path, 0777, TRUE);
                }

                $file = array(
                    'name' => random_string('alnum', 10) . '.' . $image64_ext,
                    'type' => $image64_mime,
                    'size' => $image64_size,
                    'path' => $path,
                    'created_by' => $user_id,
                    'created_on' => date('Y-m-d H:i:s')
                );

                file_put_contents($output_path . $file['name'], $image64);

                $result = $this->db->insert('files', $file);
                if ($result) {
                    $result_upload[] = $this->db->insert_id();

                    if ($sizes) {
                        $source_image = $file['path'] . $file['name'];
                        foreach ($sizes as $key => $size) {
                            $new_image = $file['path'] . $size['width'] . 'x' . $size['height'] . '_' . $file['name'];
                            $resize['image_library'] = 'gd2';
                            $resize['source_image'] = $source_image;
                            $resize['new_image'] = $new_image;
                            $resize['create_thumb'] = TRUE;
                            $resize['maintain_ratio'] = FALSE;
                            $resize['thumb_marker'] = FALSE;
                            $resize['width'] = $size['width'];
                            $resize['height'] = $size['height'];
                            $resize['master_dim'] = 'height';
                            $this->image_lib->initialize($resize);
                            $this->image_lib->resize();
                            $this->image_lib->clear();
                            $this->image_lib = new CI_Image_Lib();
                            unset($this->image_lib);
                        }

                    }
                } else {
                    $result_upload['error'] = 'Database error: unable to update file detail in database.';
                }
            }
            return $result_upload;
        } else {
            return array('error' => 'Invalid data pass to upload!');
        }
    }

    public function base64_upload($image = '', $path = '', $sizes = array())
    {
        if (!empty($image)) {
            $user_id = isset($this->session->userdata('logged_in')->id) ? $this->session->userdata('logged_in')->id : '0';
            //foreach ($images as $key => $image) {
            $array_image64 = explode(',', $image);
            $image64 = base64_decode($array_image64[1]);

            //get type from base 64 string
            $image64_mime = $this->get_string_between($array_image64['0'], ':', ';');
            $image64_ext = $this->mime2ext($image64_mime);

            //get size of image base 64 in bytes
            //formula: x = (n * (3/4)) - y
            $n = strlen($image64);
            $str_end = substr($image64, -2);
            $y = ($str_end == '==') ? 2 : 1;
            $image64_size = ($n * (3 / 4)) - $y;

            $output_path = $_SERVER['DOCUMENT_ROOT'] . $path;
            if (!is_dir($output_path)) {
                mkdir($output_path, 0777, TRUE);
            }

            $file = array(
                'name' => random_string('alnum', 10) . '.' . $image64_ext,
                'type' => $image64_mime,
                'size' => $image64_size,
                'path' => $path,
                'created_by' => $user_id,
                'created_on' => date('Y-m-d H:i:s')
            );

            file_put_contents($output_path . $file['name'], $image64);

            $result = $this->db->insert('files', $file);
            if ($result) {
                $inserted_file_id = $this->db->insert_id();

                if ($sizes) {
                    $source_image = $file['path'] . $file['name'];
                    foreach ($sizes as $key => $size) {
                        $new_image = $file['path'] . $size['width'] . 'x' . $size['height'] . '_' . $file['name'];
                        $resize['image_library'] = 'gd2';
                        $resize['source_image'] = $source_image;
                        $resize['new_image'] = $new_image;
                        $resize['create_thumb'] = TRUE;
                        $resize['maintain_ratio'] = FALSE;
                        $resize['thumb_marker'] = FALSE;
                        $resize['width'] = $size['width'];
                        $resize['height'] = $size['height'];
                        $resize['master_dim'] = 'height';
                        $this->image_lib->initialize($resize);
                        $this->image_lib->resize();
                        $this->image_lib->clear();
                        $this->image_lib = new CI_Image_Lib();
                        unset($this->image_lib);
                    }

                }

                return array('id' => $inserted_file_id);
            } else {
                $result_upload['error'] = 'Database error: unable to update file detail in database.';
            }
            //}
            return $result_upload;
        } else {
            return array('error' => 'Invalid data pass to upload!');
        }
    }

    function get_string_between($string, $start, $end)
    {
        $string = ' ' . $string;
        $ini = strpos($string, $start);
        if ($ini == 0) return '';
        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;
        return substr($string, $ini, $len);
    }

    function mime2ext($mime)
    {
        $mime_map = [
            'video/3gpp2' => '3g2',
            'video/3gp' => '3gp',
            'video/3gpp' => '3gp',
            'application/x-compressed' => '7zip',
            'audio/x-acc' => 'aac',
            'audio/ac3' => 'ac3',
            'application/postscript' => 'ai',
            'audio/x-aiff' => 'aif',
            'audio/aiff' => 'aif',
            'audio/x-au' => 'au',
            'video/x-msvideo' => 'avi',
            'video/msvideo' => 'avi',
            'video/avi' => 'avi',
            'application/x-troff-msvideo' => 'avi',
            'application/macbinary' => 'bin',
            'application/mac-binary' => 'bin',
            'application/x-binary' => 'bin',
            'application/x-macbinary' => 'bin',
            'image/bmp' => 'bmp',
            'image/x-bmp' => 'bmp',
            'image/x-bitmap' => 'bmp',
            'image/x-xbitmap' => 'bmp',
            'image/x-win-bitmap' => 'bmp',
            'image/x-windows-bmp' => 'bmp',
            'image/ms-bmp' => 'bmp',
            'image/x-ms-bmp' => 'bmp',
            'application/bmp' => 'bmp',
            'application/x-bmp' => 'bmp',
            'application/x-win-bitmap' => 'bmp',
            'application/cdr' => 'cdr',
            'application/coreldraw' => 'cdr',
            'application/x-cdr' => 'cdr',
            'application/x-coreldraw' => 'cdr',
            'image/cdr' => 'cdr',
            'image/x-cdr' => 'cdr',
            'zz-application/zz-winassoc-cdr' => 'cdr',
            'application/mac-compactpro' => 'cpt',
            'application/pkix-crl' => 'crl',
            'application/pkcs-crl' => 'crl',
            'application/x-x509-ca-cert' => 'crt',
            'application/pkix-cert' => 'crt',
            'text/css' => 'css',
            'text/x-comma-separated-values' => 'csv',
            'text/comma-separated-values' => 'csv',
            'application/vnd.msexcel' => 'csv',
            'application/x-director' => 'dcr',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx',
            'application/x-dvi' => 'dvi',
            'message/rfc822' => 'eml',
            'application/x-msdownload' => 'exe',
            'video/x-f4v' => 'f4v',
            'audio/x-flac' => 'flac',
            'video/x-flv' => 'flv',
            'image/gif' => 'gif',
            'application/gpg-keys' => 'gpg',
            'application/x-gtar' => 'gtar',
            'application/x-gzip' => 'gzip',
            'application/mac-binhex40' => 'hqx',
            'application/mac-binhex' => 'hqx',
            'application/x-binhex40' => 'hqx',
            'application/x-mac-binhex40' => 'hqx',
            'text/html' => 'html',
            'image/x-icon' => 'ico',
            'image/x-ico' => 'ico',
            'image/vnd.microsoft.icon' => 'ico',
            'text/calendar' => 'ics',
            'application/java-archive' => 'jar',
            'application/x-java-application' => 'jar',
            'application/x-jar' => 'jar',
            'image/jp2' => 'jp2',
            'video/mj2' => 'jp2',
            'image/jpx' => 'jp2',
            'image/jpm' => 'jp2',
            'image/jpeg' => 'jpeg',
            'image/pjpeg' => 'jpeg',
            'application/x-javascript' => 'js',
            'application/json' => 'json',
            'text/json' => 'json',
            'application/vnd.google-earth.kml+xml' => 'kml',
            'application/vnd.google-earth.kmz' => 'kmz',
            'text/x-log' => 'log',
            'audio/x-m4a' => 'm4a',
            'audio/mp4' => 'm4a',
            'application/vnd.mpegurl' => 'm4u',
            'audio/midi' => 'mid',
            'application/vnd.mif' => 'mif',
            'video/quicktime' => 'mov',
            'video/x-sgi-movie' => 'movie',
            'audio/mpeg' => 'mp3',
            'audio/mpg' => 'mp3',
            'audio/mpeg3' => 'mp3',
            'audio/mp3' => 'mp3',
            'video/mp4' => 'mp4',
            'video/mpeg' => 'mpeg',
            'application/oda' => 'oda',
            'audio/ogg' => 'ogg',
            'video/ogg' => 'ogg',
            'application/ogg' => 'ogg',
            'font/otf' => 'otf',
            'application/x-pkcs10' => 'p10',
            'application/pkcs10' => 'p10',
            'application/x-pkcs12' => 'p12',
            'application/x-pkcs7-signature' => 'p7a',
            'application/pkcs7-mime' => 'p7c',
            'application/x-pkcs7-mime' => 'p7c',
            'application/x-pkcs7-certreqresp' => 'p7r',
            'application/pkcs7-signature' => 'p7s',
            'application/pdf' => 'pdf',
            'application/octet-stream' => 'pdf',
            'application/x-x509-user-cert' => 'pem',
            'application/x-pem-file' => 'pem',
            'application/pgp' => 'pgp',
            'application/x-httpd-php' => 'php',
            'application/php' => 'php',
            'application/x-php' => 'php',
            'text/php' => 'php',
            'text/x-php' => 'php',
            'application/x-httpd-php-source' => 'php',
            'image/png' => 'png',
            'image/x-png' => 'png',
            'application/powerpoint' => 'ppt',
            'application/vnd.ms-powerpoint' => 'ppt',
            'application/vnd.ms-office' => 'ppt',
            'application/msword' => 'doc',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation' => 'pptx',
            'application/x-photoshop' => 'psd',
            'image/vnd.adobe.photoshop' => 'psd',
            'audio/x-realaudio' => 'ra',
            'audio/x-pn-realaudio' => 'ram',
            'application/x-rar' => 'rar',
            'application/rar' => 'rar',
            'application/x-rar-compressed' => 'rar',
            'audio/x-pn-realaudio-plugin' => 'rpm',
            'application/x-pkcs7' => 'rsa',
            'text/rtf' => 'rtf',
            'text/richtext' => 'rtx',
            'video/vnd.rn-realvideo' => 'rv',
            'application/x-stuffit' => 'sit',
            'application/smil' => 'smil',
            'text/srt' => 'srt',
            'image/svg+xml' => 'svg',
            'application/x-shockwave-flash' => 'swf',
            'application/x-tar' => 'tar',
            'application/x-gzip-compressed' => 'tgz',
            'image/tiff' => 'tiff',
            'font/ttf' => 'ttf',
            'text/plain' => 'txt',
            'text/x-vcard' => 'vcf',
            'application/videolan' => 'vlc',
            'text/vtt' => 'vtt',
            'audio/x-wav' => 'wav',
            'audio/wave' => 'wav',
            'audio/wav' => 'wav',
            'application/wbxml' => 'wbxml',
            'video/webm' => 'webm',
            'image/webp' => 'webp',
            'audio/x-ms-wma' => 'wma',
            'application/wmlc' => 'wmlc',
            'video/x-ms-wmv' => 'wmv',
            'video/x-ms-asf' => 'wmv',
            'font/woff' => 'woff',
            'font/woff2' => 'woff2',
            'application/xhtml+xml' => 'xhtml',
            'application/excel' => 'xl',
            'application/msexcel' => 'xls',
            'application/x-msexcel' => 'xls',
            'application/x-ms-excel' => 'xls',
            'application/x-excel' => 'xls',
            'application/x-dos_ms_excel' => 'xls',
            'application/xls' => 'xls',
            'application/x-xls' => 'xls',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'xlsx',
            'application/vnd.ms-excel' => 'xlsx',
            'application/xml' => 'xml',
            'text/xml' => 'xml',
            'text/xsl' => 'xsl',
            'application/xspf+xml' => 'xspf',
            'application/x-compress' => 'z',
            'application/x-zip' => 'zip',
            'application/zip' => 'zip',
            'application/x-zip-compressed' => 'zip',
            'application/s-compressed' => 'zip',
            'multipart/x-zip' => 'zip',
            'text/x-scriptzsh' => 'zsh',
        ];

        return isset($mime_map[$mime]) ? $mime_map[$mime] : false;
    }
}