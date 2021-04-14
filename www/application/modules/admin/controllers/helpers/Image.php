<?php

class Zend_Controller_Action_Helper_Image extends Zend_Controller_Action_Helper_Abstract {

    /**
     * @var Zend_Loader_PluginLoader
     */
    public $pluginLoader;
    public $folder;

    /**
     * Constructor: initialize plugin loader * 
     * @return void
     */
    public function __construct() {
        $this->pluginLoader = new Zend_Loader_PluginLoader();
        $this->prefix = "quecambio_";
        $this->sizes = array(
            array("name" => "l_", "width" => 1024, 'heigth' => 768, 'label' => 'Muy Grande'),
            array("name" => "b_", "width" => 600, 'heigth' => 600, 'label' => 'Grande'),
            array("name" => "m_", "width" => 300, 'heigth' => 225, 'label' => 'Mediana'),
            array("name" => "s_", "width" => 140, 'heigth' => 105, 'label' => 'Pequeña'),
            array("name" => "t_", "width" => 50, 'heigth' => 38, 'label' => 'Miniatura')
        );
        $this->format = array(
            array('id' => 1, 'width' => 280, 'heigth' => 600, 'radio' => '7:15', 'name' => 'Vertical'),
            array('id' => 2, 'width' => 600, 'heigth' => 150, 'radio' => '4:1', 'name' => 'Horizontal'),
            array('id' => 3, 'width' => 200, 'heigth' => 200, 'radio' => '1:1', 'name' => 'Cuadrado'),
        );
    }

    public function init() {
        //Models
//        $this->model = new Model_DBTable_Galeria();
        $this->folder = PUBLIC_PATH . DS . "images" . DS . "galeria" . DS;
        $view = Zend_Layout::getMvcInstance()->getView();
        $this->folderUrl = $view->baseUrl() . "/images/galeria/";
    }

    public function cutImage($currentImage, $newImage = null, $x1, $y1, $x2, $y2, $w, $h) {
//        exit($this->folder);
        $currentImage = $currentImage;
        $ext = @strtolower(end(explode(".", $currentImage)));
        list($current_width, $current_height) = getimagesize($currentImage);
        $crop_width = $x2 - $x1;
        $crop_height = $y2 - $y1;
        $new = imagecreatetruecolor($crop_width, $crop_height);
        $newImage = ($newImage == null) ? $currentImage : $newImage;

        switch ($ext) {
            case "jpg":
                $currentImageObejct = imagecreatefromjpeg($currentImage);
                imagecopyresampled($new, $currentImageObejct, 0, 0, $x1, $y1, $crop_width, $crop_height, $w, $h);
                imagejpeg($new, $newImage, 100);
                return $newImage;
                break;
            case "gif":
                $currentImageObejct = imagecreatefromgif($currentImage);
                imagecopyresampled($new, $currentImageObejct, 0, 0, $x1, $y1, $crop_width, $crop_height, $w, $h);
                imagegif($new, $newImage);
                return $newImage;
                break;
            case "png":
                $currentImageObejct = imagecreatefrompng($currentImage);
                imagecopyresampled($new, $currentImageObejct, 0, 0, $x1, $y1, $crop_width, $crop_height, $w, $h);
                imagepng($new, $newImage, 0);
                return $newImage;
                break;
            default :
                return false;
        }
    }

    public function create($string = null, $fileName = null, $width = 900, $heigth = 30, $x = 10, $y = 8, $pl = 1, $size = 3, $resize = false) {
        if ($string && $fileName) {
            if ($resize) {
                $width = ImageFontWidth($size) * strlen($string) + $x + $pl;
            }
            $im = imagecreatetruecolor($width, $heigth);
            imagefilledrectangle($im, $pl, 0, $width - 0, $heigth - 0, 0xFFFFFF);
            $text_color = imagecolorallocate($im, 68, 122, 221);

            imagestring($im, $size, $x, $y, utf8_decode($string), $text_color);
            imagejpeg($im, $this->folder . $fileName, 100);
            imagedestroy($im);
        }
    }

    public function unzip($source, $destination = null) {

        @mkdir($destination, 0777, true);

        foreach ((array) glob($source . "/*.zip") as $key => $value) {
            $zip = new ZipArchive;
            if ($zip->open(str_replace("//", "/", $value)) === true) {
                $zip->extractTo($destination);
                $zip->close();
            }
        }
    }

    public function insertImage($src, $dst, $x, $y, $w = 0, $h = 0) {
        $stamp = imagecreatefrompng($src);

        $ext = @strtolower(end(explode(".", $_POST["image"])));
        switch ($ext) {
            case "jpg":
                $im = imagecreatefromjpeg($dst);
                $marge_right = 10;
                $marge_bottom = 10;
                $sx = imagesx($stamp);
                $sy = imagesy($stamp);
                if ($w == 0 && $h == 0) {
                    $w = imagesx($stamp);
                    $h = imagesy($stamp);
                }
                imagecopy($im, $stamp, $x, $y, 0, 0, $w, $h);
                imagejpeg($im, $this->folder . DS . $_POST["image"], 100);
                break;
            case "gif":
                $im = imagecreatefromgif($dst);
                $marge_right = 10;
                $marge_bottom = 10;
                $sx = imagesx($stamp);
                $sy = imagesy($stamp);
                if ($w == 0 && $h == 0) {
                    $w = imagesx($stamp);
                    $h = imagesy($stamp);
                }
                imagecopy($im, $stamp, $x, $y, 0, 0, $w, $h);
                imagegif($im, $this->folder . DS . $_POST["image"]);
                break;
            case "png":
                $im = imagecreatefrompng($dst);
                $marge_right = 10;
                $marge_bottom = 10;
                $sx = imagesx($stamp);
                $sy = imagesy($stamp);
                if ($w == 0 && $h == 0) {
                    $w = imagesx($stamp);
                    $h = imagesy($stamp);
                }
                imagecopy($im, $stamp, $x, $y, 0, 0, $w, $h);
                imagepng($im, $this->folder . DS . $_POST["image"], 0);
                break;
            default :
                return false;
        }
    }

    public function insertLayout($dst, $idType) {

        foreach ($this->format as $format) {
            if ($format["id"] == $idType) {
                $src = PUBLIC_PATH . DS . "images" . DS . "layer" . DS . $format["width"] . "x" . $format["heigth"] . ".png";
                $stamp = imagecreatefrompng($src);
                $ext = @strtolower(end(explode(".", $_POST["image"])));
                switch ($ext) {
                    case "jpg":
                        $im = imagecreatefromjpeg($dst);
                        imagecopy($im, $stamp, 0, 0, 0, 0, $format["width"], $format["heigth"]);
                        imagejpeg($im, $this->folder . DS . $_POST["image"], 100);
                        break;
                    case "gif":
                        $im = imagecreatefromgif($dst);
                        imagecopy($im, $stamp, 0, 0, 0, 0, $format["width"], $format["heigth"]);
                        imagegif($im, $this->folder . DS . $_POST["image"]);
                        break;
                    case "png":
                        $im = imagecreatefrompng($dst);
                        imagecopy($im, $stamp, 0, 0, 0, 0, $format["width"], $format["heigth"]);
                        imagepng($im, $this->folder . DS . $_POST["image"], 0);
                        break;
                    default :
                        return false;
                }
            }
        }
        //exit();
    }

    public function countImageFolder($folder = null, $types = array('jpg', 'png', 'gif', 'jpeg')) {
        $string = '';
        foreach ($types as $type):
            $string.="*." . $type . ",";
        endforeach;

        $string = "{" . substr($string, 0, -1) . "}";

        if ($folder == null) {
            $folder = $this->folder . DS;
        }
        $total = count(glob($folder . $string, GLOB_BRACE));
        return $total;
    }

    public function getImageFolder($folder = null, $types = array('jpg', 'png', 'gif', 'jpeg')) {
        $string = '';
        foreach ($types as $type):
            $string.="*." . $type . ",";
        endforeach;

        $string = "{" . substr($string, 0, -1) . "}";

        if ($folder == null) {
            $folder = $this->folder . DS;
        }
        $files = glob($folder . $string, GLOB_BRACE);
        foreach ($files as $key => $file) {
            $files[$key] = str_replace($folder, "", $file);
        }
        return $files;
    }

    public function getIdBySrc($src) {
        $name = @end(explode("/", $src));
        $identity = @end(explode($this->prefix, $name));
        $id = $this->model->getIdByname($this->prefix . $identity);
        return $id;
    }

    public function getFormat($formatAvailable = array()) {

        if (count($formatAvailable)):
            $formats = array();
            foreach ($this->format as $format) {
                if (in_array($format["id"], $formatAvailable)) {
                    $formats[] = $format;
                }
            }
        else:
            $formats = $this->format;
        endif;
        return $formats;
    }

    public function getFormatbyId($id) {
        foreach ($this->format as $format) {
            if ($format['id'] == $id)
                return $format;
        }
    }

    public function getId($url) {
        $id = @end(explode("/", $url));
        return $id;
    }

    public function is_valid_video($url) {
        if (filter_var($url, FILTER_VALIDATE_URL) === false) {
            throw new Zend_Controller_Action_Exception('La URL del video no es válida', 599);
        }
        return true;
    }

    public function is_valid_image($imageName) {
        $extension = @strtolower(end(explode(".", $imageName)));

        switch (strtoupper($extension)) {
            case "JPG":
                return 'jpg';
                break;
            case "PNG":
                return 'png';
                break;
            case "GIF":
                return 'gif';
                break;
            case "JPEG":
                return 'jpg';
                break;
            default:
                throw new Zend_Controller_Action_Exception('Formato de imagen no válido', 599);
                break;
        }
    }

    public function upload($src, $dstFolder, $dstName, $sizes = null) {
        try {
            if (!$sizes) {
                $sizes = $this->sizes;
            }
            move_uploaded_file($src, $dstFolder . $dstName);
            $info = getimagesize($dstFolder . $dstName);
            list($width_old, $height_old) = $info;
            if ($sizes) {
                foreach ($sizes as $key => $size) {
                    if ($width_old >= $size['width']) {
                        $this->smart_resize_image($dstFolder . $dstName, $size["width"], 0, true, $dstFolder . $size["name"] . $dstName, false, false);
                    }
                }
            }
            $this->delete($dstFolder, array($dstName));
        } catch (Zend_Exception $exc) {
            throw new Zend_Controller_Action_Exception('Error al subir la imagen', 599);
        }
    }

    public function uploadFree($src, $dstFolder, $dstName, $width = 800) {
        try {

            move_uploaded_file($src, $dstFolder . $dstName);
            $info = getimagesize($dstFolder . $dstName);
            list($width_old, $height_old) = $info;
            if ($width_old >= $width) {
                $this->smart_resize_image($dstFolder . $dstName, $width, 0, true, $dstFolder . $dstName, false, false);
            }
            return true;
        } catch (Zend_Exception $exc) {
            return false;
            throw new Zend_Controller_Action_Exception('Error al subir la imagen', 599);
        }
    }

    public function copy($dstName) {
        try {
            $dstName = str_replace("l_", "", $dstName);
            $info = getimagesize($this->folder . "l_" . $dstName);
            list($width_old, $height_old) = $info;
            if ($this->sizes) {
                foreach ($this->sizes as $key => $size) {
                    if ($key != 0) {
                        if ($width_old >= $size['width']) {
                            $this->smart_resize_image($this->folder . "l_" . $dstName, $size["width"], 0, true, $this->folder . $size["name"] . $dstName, false, false);
                        }
                    }
                }
            }
            $this->delete($this->folder, array("l_" . $dstName));
        } catch (Zend_Exception $exc) {
            throw new Zend_Controller_Action_Exception('Error al copiar la imagen', 599);
        }
    }

    public function redimFormat($dstName, $format) {
        try {

            $dstName = str_replace("l_", "", $dstName);
            $this->smart_resize_image($this->folder . "l_" . $dstName, $this->format[$format - 1]["width"], 0, true, $this->folder . "l_" . $dstName, false, false);

            // $this->delete($this->folder, array("l_" . $dstName));
        } catch (Zend_Exception $exc) {
            throw new Zend_Controller_Action_Exception('Error al copiar la imagen', 599);
        }
    }

    //VALIDO SOLO PARA IMAGENES CON FORMATOS ESPECIFICOS
    public function getFormatAvailable($width, $height) {
        $formatAvailable = array();
        foreach ($this->format as $key => $format):
            if ($width >= $format["width"] && $height >= $format["heigth"]):
                $formatAvailable[] = $format["id"];
            endif;
        endforeach;
        return $formatAvailable;
    }

//    public function uploadTmp($src, $dstName) {
//
//        move_uploaded_file($src, $this->folder . $dstName);
//        $info = getimagesize($this->folder . $dstName);
//        list($width_old, $height_old) = $info;
//        if ($width_old >= $this->sizes[1]['width'] && $height_old >= $this->sizes[1]['heigth']) {
//            if ($width_old <= $this->sizes[0]['width'] && $height_old <= $this->sizes[0]['heigth']) {
//                rename($this->folder . $dstName, $this->folder . $this->sizes[0]["name"] . $dstName);
//            } else {
//                $this->smart_resize_image($this->folder . $dstName, $this->sizes[0]['width'], 0, true, $this->folder . $this->sizes[0]["name"] . $dstName, false, false);
//                $this->delete($this->folder, array($dstName));
//            }
//            return $this->folderUrl . $this->sizes[0]["name"] . $dstName;
//        } else {
//            $this->delete($this->folder, array($dstName));
//            throw new Zend_Controller_Action_Exception('La imagen es muy pequeña (' . $width_old . ' x ' . $height_old . ')px, debe tener mínimo ' . $this->sizes[1]['width'] . 'px de Ancho y ' . $this->sizes[1]['heigth'] . 'px de Alto', 599);
//        }
//    }

    public function uploadAfterCut($src, $dst, $width) {
        try {
            move_uploaded_file($src, $dst);
            $info = getimagesize($dst);
            list($width_old, $height_old) = $info;
            if ($width_old > $width) {
                $this->smart_resize_image($dst, $width, 0, true, $dst, false, false);
            }
        } catch (Zend_Exception $e) {
            throw new Zend_Controller_Action_Exception('No se pudo subir la imagen', 599);
        }
    }

    public function uploadTmp($src, $dstName) {

        move_uploaded_file($src, $this->folder . $dstName);
        $info = getimagesize($this->folder . $dstName);
        list($width_old, $height_old) = $info;
        $formatList = $this->getFormatAvailable($width_old, $height_old);
        if (count($formatList) > 0) {
            rename($this->folder . $dstName, $this->folder . $this->sizes[0]["name"] . $dstName);
            return array('url' => $this->folderUrl . $this->sizes[0]["name"] . $dstName, 'formatList' => $formatList);
        } else {
            $this->delete($this->folder, array($dstName));
            throw new Zend_Controller_Action_Exception('La imagen es muy pequeña (' . $width_old . ' x ' . $height_old . ')px.', 599);
        }
    }

    public function delete($folder, $files = array()) {
        try {
            if ($files) {
                foreach ($files as $filename) {
                    if (file_exists($folder . $filename)) {
                        unlink($folder . $filename);
                    }
                }
            }
        } catch (Zend_Exception $exc) {
            throw new Zend_Controller_Action_Exception('Error al eliminar la imagen', 6558);
        }
    }

    public function show($folder = null, $folderUrl = null, $fileName, $prefix, $class = null, $other = null) {
        try {
            if ($folder == null) {
                $folder = $this->folder;
            }
            if ($folderUrl == null) {
                $folderUrl = $this->folderUrl;
            }
            if (file_exists($folder . $prefix . $fileName)) {
                $string = "<img src='" . $folderUrl . $prefix . $fileName . "' class='" . $class . "' " . $other . " />";
            } else {
                if ($this->sizes) {
                    foreach ($this->sizes as $size) {
                        if (file_exists($folder . $size["name"] . $fileName)) {
                            $string = "<img  src='" . $folderUrl . $size["name"] . $fileName . "' class='" . $class . "' " . $other . "/>";
                            break;
                        }
                    }
                }
            }
            return $string;
        } catch (Zend_Exception $exc) {
            throw new Zend_Controller_Action_Exception('Error al eliminar la imagen', 6558);
        }
    }

    public function getSizes($fileName) {

        if ($this->sizes) {
            foreach ($this->sizes as $size) {
                if (file_exists($this->folder . $size["name"] . $fileName)) {
                    $size["name"] = str_replace("_", "", $size["name"]);
                    $sizeAvailable[] = $size;
                }
            }
        }

        return $sizeAvailable;
    }

    public function getOne($id, $size) {
        $result = $this->model->get($id);
        if ($result) {
            $ex = $this->is_valid_image($result["Gnombre"]);
            switch (strtoupper($ex)) {
                case "JPG":
                    $header = 'Content-Type: image/jpeg';
                    break;
                case "PNG":
                    $header = 'Content-Type: image/png';
                    break;
                case "GIF":
                    $header = 'Content-Type: image/gif';
                    break;
                case "JPEG":
                    $header = 'Content-Type: image/jpeg';
                    break;
                default:
                    $header = null;
                    break;
            }
            if (file_exists($this->folder . $size . "_" . $result["Gnombre"])) {
                $readfile = $this->folder . $size . "_" . $result["Gnombre"];
            } else {
                if ($this->sizes) {
                    foreach ($this->sizes as $size) {
                        if (file_exists($this->folder . $size["name"] . $result["Gnombre"])) {
                            $readfile = $this->folder . $size["name"] . $result["Gnombre"];
                            break;
                        }
                    }
                }
            }
            $return = array(
                'readfile' => $readfile,
                'header' => $header,
            );
            return $return;
        }
        return false;
    }

    public function smart_resize_image($file, $width = 0, $height = 0, $proportional = false, $output = 'file', $delete_original = true, $use_linux_commands = false) {
        //exit($file);
        if ($height <= 0 && $width <= 0) {
            return false;
        }

        $info = getimagesize($file);
        $image = '';

        $final_width = 0;
        $final_height = 0;
        list($width_old, $height_old) = $info;

        if ($proportional) {
            if ($width == 0)
                $factor = $height / $height_old;
            elseif ($height == 0)
                $factor = $width / $width_old;
            else
                $factor = min($width / $width_old, $height / $height_old);

            $final_width = round($width_old * $factor);
            $final_height = round($height_old * $factor);
        }
        else {
            $final_width = ( $width <= 0 ) ? $width_old : $width;
            $final_height = ( $height <= 0 ) ? $height_old : $height;
        }

        switch ($info[2]) {
            case IMAGETYPE_GIF:
                $image = imagecreatefromgif($file);
                break;
            case IMAGETYPE_JPEG:
                $image = imagecreatefromjpeg($file);
                break;
            case IMAGETYPE_PNG:
                $image = imagecreatefrompng($file);
                break;
            default:
                return false;
        }

        $image_resized = imagecreatetruecolor($final_width, $final_height);

        if (($info[2] == IMAGETYPE_GIF) || ($info[2] == IMAGETYPE_PNG)) {
            $trnprt_indx = imagecolortransparent($image);

            // If we have a specific transparent color
            if ($trnprt_indx >= 0) {

                // Get the original image's transparent color's RGB values
                $trnprt_color = imagecolorsforindex($image, $trnprt_indx);

                // Allocate the same color in the new image resource
                $trnprt_indx = imagecolorallocate($image_resized, $trnprt_color['red'], $trnprt_color['green'], $trnprt_color['blue']);

                // Completely fill the background of the new image with allocated color.
                imagefill($image_resized, 0, 0, $trnprt_indx);

                // Set the background color for new image to transparent
                imagecolortransparent($image_resized, $trnprt_indx);
            }
            // Always make a transparent background color for PNGs that don't have one allocated already
            elseif ($info[2] == IMAGETYPE_PNG) {

                // Turn off transparency blending (temporarily)
                imagealphablending($image_resized, false);

                // Create a new transparent color for image
                $color = imagecolorallocatealpha($image_resized, 0, 0, 0, 127);

                // Completely fill the background of the new image with allocated color.
                imagefill($image_resized, 0, 0, $color);

                // Restore transparency blending
                imagesavealpha($image_resized, true);
            }
        }

        imagecopyresampled($image_resized, $image, 0, 0, 0, 0, $final_width, $final_height, $width_old, $height_old);

        if ($delete_original) {
            if ($use_linux_commands)
                exec('rm ' . $file);
            else
                @unlink($file);
        }

        switch (strtolower($output)) {
            case 'browser':
                $mime = image_type_to_mime_type($info[2]);
                header("Content-type: $mime");
                $output = NULL;
                break;
            case 'file':
                $output = $file;
                break;
            case 'return':
                return $image_resized;
                break;
            default:
                break;
        }

        switch ($info[2]) {
            case IMAGETYPE_GIF:
                imagegif($image_resized, $output);
                break;
            case IMAGETYPE_JPEG:
                imagejpeg($image_resized, $output);
                break;
            case IMAGETYPE_PNG:
                imagepng($image_resized, $output);
                break;
            default:
                return false;
        }

        return true;
    }

    /**
     * Strategy pattern: call helper as broker method
     * 
     * @param  int $month 
     * @param  int $year 
     * @return int
     */
    public function direct($mensaje) {
        return $this->verificar($mensaje);
    }

}

?>
