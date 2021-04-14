<?php

function strtofilename($String) {
    $String = str_replace(array('á', 'à', 'â', 'ã', 'ª', 'ä'), "a", $String);
    $String = str_replace(array('Á', 'À', 'Â', 'Ã', 'Ä'), "a", $String);
    $String = str_replace(array('Í', 'Ì', 'Î', 'Ï'), "i", $String);
    $String = str_replace(array('í', 'ì', 'î', 'ï'), "i", $String);
    $String = str_replace(array('é', 'è', 'ê', 'ë'), "e", $String);
    $String = str_replace(array('É', 'È', 'Ê', 'Ë'), "e", $String);
    $String = str_replace(array('ó', 'ò', 'ô', 'õ', 'ö', 'º'), "o", $String);
    $String = str_replace(array('Ó', 'Ò', 'Ô', 'Õ', 'Ö'), "o", $String);
    $String = str_replace(array('ú', 'ù', 'û', 'ü'), "u", $String);
    $String = str_replace(array('Ú', 'Ù', 'Û', 'Ü'), "u", $String);
    $String = str_replace(array('[', '^', '´', '`', '¨', '~', ']'), "", $String);
    $String = str_replace("ç", "c", $String);
    $String = str_replace("Ç", "C", $String);
    $String = str_replace("ñ", "n", $String);
    $String = str_replace("Ñ", "N", $String);
    $String = str_replace("Ý", "Y", $String);
    $String = str_replace("ý", "y", $String);
    $String = str_replace(" ", "_", $String);
    $String = str_replace("&aacute;", "a", $String);
    $String = str_replace("&Aacute;", "a", $String);
    $String = str_replace("&eacute;", "e", $String);
    $String = str_replace("&Eacute;", "e", $String);
    $String = str_replace("&iacute;", "i", $String);
    $String = str_replace("&Iacute;", "i", $String);
    $String = str_replace("&oacute;", "o", $String);
    $String = str_replace("&Oacute;", "o", $String);
    $String = str_replace("&uacute;", "u", $String);
    $String = str_replace("&Uacute;", "u", $String);
    $String = str_replace("/", "__", $String);
    return strtolower($String);
}

function invertDate($date,$separator='-') {
    list($day, $mon, $year) = explode($separator, $date);
    return $year . "/" . $mon . "/" . $day;
}

function sec_to_time($seconds) {
    $hours = floor($seconds / 3600);
    $minutes = floor($seconds % 3600 / 60);
    $seconds = $seconds % 60;

    return sprintf("%d:%02d:%02d", $hours, $minutes, $seconds);
}

function emptyFolder($path) {
    $c = 0;
    $files = glob($path . '*'); // get all file names
    foreach ($files as $file) { // iterate files
        if (is_file($file))
            $c++;
        unlink($file); // delete file
    }
    return $c;
}

function boolean_to_string($boolean) {
    if ($boolean == 1):
        return '<span class="glyphicon glyphicon-ok text-success"></span>&nbsp;';
    elseif ($boolean == -1):
        return '<span class="glyphicon glyphicon-minus text-muted"></span>&nbsp;';
    else:
        return '<span class="glyphicon glyphicon-remove text-danger"></span>&nbsp;';
    endif;
}

function boolean_to_string_ie($boolean) {
    if ($boolean == 1):
        return '<span class="text-success"><b>SI</b></span>&nbsp;';
    elseif ($boolean == -1):
        return '<span class="text-muted"><b>N/A</b></span>&nbsp;';
    else:
        return '<span class="text-danger"><b>NO</b></span>&nbsp;';
    endif;
}

function showTime($time, $hours = true) {
    $now = time();
    $diff = $now - $time;

    if ($hours == true):
        $hoursLabel = " a las " . date("H:i", $time);
    else:
        $hoursLabel = "";
    endif;
//    return $diff;
    if ($diff <= 60):
        return "Hace " . $diff . " segundos";
    elseif ($diff < 3600):
        return "Hace " . (int) ($diff / 60) . " minutos";
    elseif ($diff < 86400):
        return "Hace " . (int) ($diff / 60 / 60) . " horas";
    elseif ($diff < 172800):
        return "Ayer" . $hoursLabel;
    else:
        return "El " . date("d-m-Y", $time) . $hoursLabel;
    endif;
}

set_time_limit(0);

function MakePropertyValue($name, $value, $osm) {
    $oStruct = $osm->Bridge_GetStruct("com.sun.star.beans.PropertyValue");
    $oStruct->Name = $name;
    $oStruct->Value = $value;
    return $oStruct;
}

//HEREDATED

function UUID($prefix = "") {
    $chars = md5(uniqid(rand()));
    $uuid = substr($chars, 0, 8) . '-';
    $uuid .= substr($chars, 8, 4) . '-';
    $uuid .= substr($chars, 12, 4) . '-';
    $uuid .= substr($chars, 16, 4) . '-';
    $uuid .= substr($chars, 20, 12);
    return $prefix . $uuid;
}

function word2pdf($doc_url, $output_url) {
    // Invoke the OpenOffice.org service manager 

    $osm = new COM("com.sun.star.ServiceManager") or die("Please be sure that OpenOffice.org is installed.\n");

    // Set the application to remain hidden to avoid flashing the document onscreen 
    $args = array(MakePropertyValue("Hidden", true, $osm));
    // Launch the desktop 
    $top = $osm->createInstance("com.sun.star.frame.Desktop");
    // Load the .doc file, and pass in the "Hidden" property from above 
    $oWriterDoc = $top->loadComponentFromURL($doc_url, "_blank", 0, $args);
    // Set up the arguments for the PDF output 
    $export_args = array(MakePropertyValue("FilterName", "writer_pdf_Export", $osm));
    // Write out the PDF 
    $oWriterDoc->storeToURL($output_url, $export_args);

    $oWriterDoc->close(true);
}

//----------------------------------------------//
//FUNCION QUE ARMA QUERY PARA BUSCAR RECIBE
//UN STRIGN QUE LO PARSEA POR ESPACIO Y ARRAY DE CAMPOS A BUSCAR
//
//----------------------------------------------//
function create_where($words, $fields) {
    $str_query = "";
    $words = explode(' ', $words);
    foreach ($words as $word) {
        foreach ($fields as $field) {
            if ($field['search'] == true) {
                $str_query.=$field["field"] . " LIKE '%" . $word . "%' or ";
            }
        }
    }
    $str_query = substr($str_query, 0, -3);
    $str_query = "(" . $str_query . ")";
    return $str_query;
}

//----------------------------------------------//
//FUNCION REDIMENSIONAR Y CAMBIAR DE LUGAR UNA IMAGEN
//----------------------------------------------//

function redim_imagen($original, $nueva, $max_ancho, $max_alto, $marca) {
    list($img_anchorig, $img_altorig, $tipo) = getimagesize($original);

    switch ($tipo) {
        case 1:
            $img_orig = imagecreatefromgif($original);
            break;
        case 2:
            $img_orig = imagecreatefromjpeg($original);
            break;
        case 3:
            $img_orig = imagecreatefrompng($original);
            break;
        case 15:
            $img_orig = imagecreatefromwbmp($original);
            break;
        default:
            die("Formato de imagen no soportado");
            ?><script>alert("Formato de imagen no soportado");
                            history.go(-1);</script><?php
    }

    $black = @imagecolorallocate($img_orig, 0, 0, 0);
    $white = @imagecolorallocate($img_orig, 255, 255, 255);
    $font = 4;

    $img_ancho = ($img_anchorig / $img_altorig) * $max_alto;
    $img_alto = $max_alto;
    if ($img_ancho > $max_ancho) {
        $img_ancho = $max_ancho;
        $img_alto = ($img_altorig / $img_anchorig) * $max_ancho;
    }
    $img_nueva = imagecreatetruecolor($img_ancho, $img_alto);

    imagecopyresampled($img_nueva, $img_orig, 0, 0, 0, 0, $img_ancho, $img_alto, $img_anchorig, $img_altorig);

    $originx = imagesx($img_nueva) - 245;
    $originy = imagesy($img_nueva) - 17;

    @imagestring($img_nueva, $font, $originx + 10, $originy, $marca, $black);
    @imagestring($img_nueva, $font, $originx + 11, $originy - 1, $marca, $white);



    //unlink($nueva);
    imagejpeg($img_nueva, $nueva, 90);

    imagedestroy($img_nueva);
}

function formatear($string) {
    $new_string = $string;
    // $new_string=nl2br($string);
    $new_string = str_replace('\\', '', $new_string);
    return $new_string;
}

function smart_resize_image($file, $width = 0, $height = 0, $proportional = false, $output = 'file', $delete_original = true, $use_linux_commands = false) {
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

//----------------------------------------------//
//FUNCION ROTAR IMAGEN
//----------------------------------------------//
function rotar_imagen($fuente, $grados, $destino) {
    $png = imagecreatefrompng($fuente);
    $png = imagerotate($png, $grados, 0, 0);
    imagealphablending($png, false);
    imagesavealpha($png, true);
    imagepng($png, $destino);
}

//----------------------------------------------//
//FUNCION TAMAÑO IMAGEN
//----------------------------------------------//
function tamanio($imagen, $direccion) {
    $tamanoImagen = getimagesize($imagen);
    //DIRECCION =0 - ANCHO / =1 ALTO
    return $tamanoImagen[$direccion];
}

//----------------------------------------------//
//FUNCION CORTAR TEXTOS LARGOS Y AGREAGR PUNTOS
//----------------------------------------------//
function CutText($text_to_cut, $words_to_display, $link_more = null) {

    //Checking main argouments phase 1 
    if (trim($text_to_cut) == "") {
        $text_cutted = "";
        return false;
    }

    //Checking main argouments phase 2 
    if ($text_to_cut == "" && (!is_numeric($words_to_display))) {
        $text_cutted = "";
        return false;
    } else {
        $words_to_display = (integer) $words_to_display;
    }

    //Cutting text 
    $vectors = explode(" ", $text_to_cut);
    if ($words_to_display >= (count($vectors) + 1)) {

        return $text_to_cut;
    } else {
        $LimUP = ($words_to_display - 1);
        $exitLoop = false;
        do {
            $c = substr($vectors[$LimUP], strlen($vectors[$LimUP]) - 1, 1);
            if (($c == ",") or ( $c == ".") or ( $c == ";") or ( $c == ":") or ( $c == "+") or ( $c == "-") or ( $c == "@")) {

                $LimUP-=1;
            } else {
                $exitLoop = true;
            }
        } while ($exitLoop = false and $LimUP >= 0);
        $FinalText = "";
        for ($i = 0; $i <= $LimUP; $i++) {
            $FinalText.=$vectors[$i] . " ";
        }

        if ($link_more != null):
            $FinalText.="... " . $link_more;
        else:
            $FinalText.="... ";
        endif;
        return $FinalText;
    }
}

//function create_graphic($param_data, $param_names, $param_show_label,$param_show_percent,$param_show_text,$param_show_parts,$param_label_form,$param_width,$param_background_color,$param_text_color,$param_colors,$param_height_shadow,$param_shadow_dark) {
//function create_graphic($param_data, $param_names, $target, $param_colors, $bg_color, $font_color, $graphic_width, $shd_height) {
//
//    $show_label = true; // true = show label, false = don't show label.
//    $show_percent = true; // true = show percentage, false = don't show percentage.
//    $show_text = true; // true = show text, false = don't show text.
//    $show_parts = false; // true = show parts, false = don't show parts.
//    $label_form = 'square'; // 'square' or 'round' label.
//    $width = $graphic_width;
//    $background_color = $bg_color; // background-color of the chart...
//    $text_color = $font_color; // text-color.
//    //$colors = array('003366', 'CCD6E0', '7F99B2','F7EFC6', 'C6BE8C', 'CC6600','990000','520000','BFBFC1','808080'); // colors of the slices.
//
//    $colors = $param_colors;
//
//    $shadow_height = $shd_height; // Height on shadown.
//    $shadow_dark = true; // true = darker shadow, false = lighter shadow...
//
//    /*
//      $show_label = $label; // true = show label, false = don't show label.
//      $show_percent = $percent; // true = show percentage, false = don't show percentage.
//      $show_text = $text; // true = show text, false = don't show text.
//      $show_parts = $parts; // true = show parts, false = don't show parts.
//      $label_form = $lab_form; // 'square' or 'round' label.
//      $width = $wdt;
//      $background_color = $bgcolor; // background-color of the chart...
//      $text_color = $color_text; // text-color.
//      $colors = $colours; // colors of the slices.
//      $shadow_height = $hgh_shadow; // Height on shadown.
//      $shadow_dark = $dark_shadow; // true = darker shadow, false = lighter shadow...
//     */
//
//    // DON'T CHANGE ANYTHING BELOW THIS LINE...
//    //$data = $_GET["data"];
//    //$label = $_GET["label"];
//
//    $data = $param_data;
//    $label = $param_names;
//
//    $height = $width / 2;
//    //$data = explode('*',$data);
//    //if ($label != '') $label = explode('*',$label);	
//
//    for ($i = 0; $i < count($label); $i++) {
//        if ($data[$i] / array_sum($data) < 0.1)
//            $number[$i] = ' ' . number_format(($data[$i] / array_sum($data)) * 100, 1, ',', '.') . '%';
//        else
//            $number[$i] = number_format(($data[$i] / array_sum($data)) * 100, 1, ',', '.') . '%';
//        if (strlen($label[$i]) > $text_length)
//            $text_length = strlen($label[$i]);
//    }
//
//    if (is_array($label)) {
//        $antal_label = count($label);
//        $xtra = (5 + 15 * $antal_label) - ($height + ceil($shadow_height));
//        if ($xtra > 0)
//            $xtra_height = (5 + 15 * $antal_label) - ($height + ceil($shadow_height));
//
//        $xtra_width = 5;
//        if ($show_label)
//            $xtra_width += 20;
//        if ($show_percent)
//            $xtra_width += 45;
//        if ($show_text)
//            $xtra_width += $text_length * 8;
//        if ($show_parts)
//            $xtra_width += 35;
//    }
//
//    $img = ImageCreateTrueColor($width + $xtra_width, $height + ceil($shadow_height) + $xtra_height);
//
//
//
//    ImageFill($img, 0, 0, colorHex($img, $background_color));
//
//    foreach ($colors as $colorkode) {
//        $fill_color[] = colorHex($img, $colorkode);
//        $shadow_color[] = colorHexshadow($img, $colorkode, $shadow_dark);
//    }
//
//    $label_place = 5;
//
//    if (is_array($label)) {
//        for ($i = 0; $i < count($label); $i++) {
//            if ($label_form == 'round' && $show_label && $data[$i] > 0) {
//                imagefilledellipse($img, $width + 11, $label_place + 5, 10, 10, colorHex($img, $colors[$i % count($colors)]));
//                imageellipse($img, $width + 11, $label_place + 5, 10, 10, colorHex($img, $text_color));
//            } else if ($label_form == 'square' && $show_label && $data[$i] > 0) {
//                imagefilledrectangle($img, $width + 6, $label_place, $width + 16, $label_place + 10, colorHex($img, $colors[$i % count($colors)]));
//                imagerectangle($img, $width + 6, $label_place, $width + 16, $label_place + 10, colorHex($img, $text_color));
//            }
//
//            if ($data[$i] > 0) {
//                if ($show_percent)
//                    $label_output = $number[$i] . ' ';
//                if ($show_text)
//                    $label_output = $label_output . $label[$i] . ' ';
//                if ($show_parts)
//                    $label_output = $label_output . $data[$i];
//
//                imagestring($img, '2', $width + 20, $label_place, $label_output, colorHex($img, $text_color));
//                $label_output = '';
//
//                $label_place = $label_place + 15;
//            }
//        }
//    }
//    $centerX = round($width / 2);
//    $centerY = round($height / 2);
//    $diameterX = $width - 4;
//    $diameterY = $height - 4;
//
//    $data_sum = array_sum($data);
//
//    $start = 270;
//
//    for ($i = 0; $i < count($data); $i++) {
//        $value += $data[$i];
//        $end = ceil(($value / $data_sum) * 360) + 270;
//        $slice[] = array($start, $end, $shadow_color[$value_counter % count($shadow_color)], $fill_color[$value_counter % count($fill_color)]);
//        $start = $end;
//        $value_counter++;
//    }
//
//    for ($i = $centerY + $shadow_height; $i > $centerY; $i--) {
//        for ($j = 0; $j < count($slice); $j++) {
//            if ($slice[$j][0] != $slice[$j][1])
//                ImageFilledArc($img, $centerX, $i, $diameterX, $diameterY, $slice[$j][0], $slice[$j][1], $slice[$j][2], IMG_ARC_PIE);
//        }
//    }
//
//    for ($j = 0; $j < count($slice); $j++) {
//        if ($slice[$j][0] != $slice[$j][1])
//            ImageFilledArc($img, $centerX, $centerY, $diameterX, $diameterY, $slice[$j][0], $slice[$j][1], $slice[$j][3], IMG_ARC_PIE);
//    }
//
//    OutputImage($img, $target);
//
//    return $target;
//}

/**
 * 
 * @param type $param_data
 * @param type $param_names
 * @param type $target
 * @param type $param_colors
 * @param type $bg_color
 * @param type $font_color
 * @param type $graphic_width
 * @param type $shd_height
 * @return type
 * 
 * 
 * function create_graphic($param_data, $param_names, $target, $param_colors, $bg_color, $font_color, $graphic_width, $shd_height) {

  $show_label = true; // true = show label, false = don't show label.
  $show_percent = true; // true = show percentage, false = don't show percentage.
  $show_text = true; // true = show text, false = don't show text.
  $show_parts = false; // true = show parts, false = don't show parts.
  $label_form = 'round'; // 'square' or 'round' label.
  $width = $graphic_width;
  $background_color = $bg_color; // background-color of the chart...
  $text_color = $font_color; // text-color.
  //$colors = array('003366', 'CCD6E0', '7F99B2','F7EFC6', 'C6BE8C', 'CC6600','990000','520000','BFBFC1','808080'); // colors of the slices.

  $colors = $param_colors;

  $shadow_height = $shd_height; // Height on shadown.
  $shadow_dark = true; // true = darker shadow, false = lighter shadow...

  /*
  $show_label = $label; // true = show label, false = don't show label.
  $show_percent = $percent; // true = show percentage, false = don't show percentage.
  $show_text = $text; // true = show text, false = don't show text.
  $show_parts = $parts; // true = show parts, false = don't show parts.
  $label_form = $lab_form; // 'square' or 'round' label.
  $width = $wdt;
  $background_color = $bgcolor; // background-color of the chart...
  $text_color = $color_text; // text-color.
  $colors = $colours; // colors of the slices.
  $shadow_height = $hgh_shadow; // Height on shadown.
  $shadow_dark = $dark_shadow; // true = darker shadow, false = lighter shadow...


  // DON'T CHANGE ANYTHING BELOW THIS LINE...
  //$data = $_GET["data"];
  //$label = $_GET["label"];

  $data = $param_data;
  $label = $param_names;

  $height = $width / 3;
  //$data = explode('*',$data);
  //if ($label != '') $label = explode('*',$label);

  for ($i = 0; $i < count($label); $i++) {
  if ($data[$i] / array_sum($data) < 0.1)
  $number[$i] = '' . number_format(($data[$i] / array_sum($data)) * 100, 1, ',', '.') . '%';
  else
  $number[$i] = number_format(($data[$i] / array_sum($data)) * 100, 1, ',', '.') . '%';
  if (strlen($label[$i]) > $text_length)
  $text_length = strlen($label[$i]);
  }

  if (is_array($label)) {
  $antal_label = count($label);
  $xtra = (5 + 15 * $antal_label) - ($height + ceil($shadow_height));
  if ($xtra > 0)
  $xtra_height = (5 + 15 * $antal_label) - ($height + ceil($shadow_height));

  $xtra_width = 100;
  if ($show_label)
  $xtra_width += 20;
  if ($show_percent)
  $xtra_width += 45;
  if ($show_text)
  $xtra_width += $text_length * 8;
  if ($show_parts)
  $xtra_width += 35;
  }

  $img = ImageCreateTrueColor($width + $xtra_width, $height + ceil($shadow_height) + $xtra_height);



  ImageFill($img, 0, 0, colorHex($img, $background_color));

  foreach ($colors as $colorkode) {
  $fill_color[] = colorHex($img, $colorkode);
  $shadow_color[] = colorHexshadow($img, $colorkode, $shadow_dark);
  }

  $label_place = 15;

  if (is_array($label)) {
  for ($i = 0; $i < count($label); $i++) {
  if ($label_form == 'round' && $show_label && $data[$i] > 0) {
  imagefilledellipse($img, $width + 56, $label_place + 5, 10, 10, colorHex($img, $colors[$i % count($colors)]));
  imageellipse($img, $width + 56, $label_place + 5, 10, 10, colorHex($img, $text_color));
  } else if ($label_form == 'square' && $show_label && $data[$i] > 0) {
  imagefilledrectangle($img, $width + 56, $label_place, $width + 66, $label_place + 10, colorHex($img, $colors[$i % count($colors)]));
  imagerectangle($img, $width + 56, $label_place, $width + 66, $label_place + 10, colorHex($img, $text_color));
  }

  if ($data[$i] > 0) {
  if ($show_percent)
  $label_output = $number[$i] . ' ';
  if ($show_text)
  $label_output = $label_output . $label[$i] . ' ';
  if ($show_parts)
  $label_output = $label_output . $data[$i];

  imagestring($img, '4', $width + 70, $label_place, $label_output, colorHex($img, $text_color));
  $label_output = '';

  $label_place = $label_place + 20;
  }
  }
  }
  $centerX = round($width / 2);
  $centerY = round($height / 2);
  $diameterX = $width - 4;
  $diameterY = $height - 4;

  $data_sum = array_sum($data);

  $start = 270;

  for ($i = 0; $i < count($data); $i++) {
  $value += $data[$i];
  $end = ceil(($value / $data_sum) * 360) + 270;
  $slice[] = array($start, $end, $shadow_color[$value_counter % count($shadow_color)], $fill_color[$value_counter % count($fill_color)]);
  $start = $end;
  $value_counter++;
  }

  for ($i = $centerY + $shadow_height; $i > $centerY; $i--) {
  for ($j = 0; $j < count($slice); $j++) {
  if ($slice[$j][0] != $slice[$j][1])
  ImageFilledArc($img, $centerX, $i, $diameterX, $diameterY, $slice[$j][0], $slice[$j][1], $slice[$j][2], IMG_ARC_PIE);
  }
  }

  for ($j = 0; $j < count($slice); $j++) {
  if ($slice[$j][0] != $slice[$j][1])
  ImageFilledArc($img, $centerX, $centerY, $diameterX, $diameterY, $slice[$j][0], $slice[$j][1], $slice[$j][3], IMG_ARC_PIE);
  }

  OutputImage($img, $target);

  return $target;
  }
 */
function create_graphic($param_data, $param_names, $target, $param_colors, $bg_color, $font_color, $graphic_width, $shd_height) {

    $show_label = false; // true = show label, false = don't show label.
    $show_percent = false; // true = show percentage, false = don't show percentage.
    $show_text = false; // true = show text, false = don't show text.
    $show_parts = false; // true = show parts, false = don't show parts.
    $label_form = 'round'; // 'square' or 'round' label.
    $width = $graphic_width;
    $background_color = $bg_color; // background-color of the chart...
    $text_color = $font_color; // text-color.
    //$colors = array('003366', 'CCD6E0', '7F99B2','F7EFC6', 'C6BE8C', 'CC6600','990000','520000','BFBFC1','808080'); // colors of the slices.

    $colors = $param_colors;

    $shadow_height = $shd_height; // Height on shadown.
    $shadow_dark = false; // true = darker shadow, false = lighter shadow...

    /*
      $show_label = $label; // true = show label, false = don't show label.
      $show_percent = $percent; // true = show percentage, false = don't show percentage.
      $show_text = $text; // true = show text, false = don't show text.
      $show_parts = $parts; // true = show parts, false = don't show parts.
      $label_form = $lab_form; // 'square' or 'round' label.
      $width = $wdt;
      $background_color = $bgcolor; // background-color of the chart...
      $text_color = $color_text; // text-color.
      $colors = $colours; // colors of the slices.
      $shadow_height = $hgh_shadow; // Height on shadown.
      $shadow_dark = $dark_shadow; // true = darker shadow, false = lighter shadow...
     */

    // DON'T CHANGE ANYTHING BELOW THIS LINE...
    //$data = $_GET["data"];
    //$label = $_GET["label"];

    $data = $param_data;
    $label = $param_names;

    $height = $width / 3;
    $height = $width;
    //$data = explode('*',$data);
    //if ($label != '') $label = explode('*',$label);	

    for ($i = 0; $i < count($label); $i++) {
        if ($data[$i] / array_sum($data) < 0.1)
            $number[$i] = '' . number_format(($data[$i] / array_sum($data)) * 100, 1, ',', '.') . '%';
        else
            $number[$i] = number_format(($data[$i] / array_sum($data)) * 100, 1, ',', '.') . '%';
        if (strlen($label[$i]) > $text_length)
            $text_length = strlen($label[$i]);
    }

    if (is_array($label)) {
        $antal_label = count($label);
        $xtra = (5 + 15 * $antal_label) - ($height + ceil($shadow_height));
        if ($xtra > 0)
            $xtra_height = (5 + 15 * $antal_label) - ($height + ceil($shadow_height));

        $xtra_width = 0;
        if ($show_label)
            $xtra_width += 20;
        if ($show_percent)
            $xtra_width += 45;
        if ($show_text)
            $xtra_width += $text_length * 8;
        if ($show_parts)
            $xtra_width += 35;
    }

    $img = ImageCreateTrueColor($width + $xtra_width, $height + ceil($shadow_height) + $xtra_height);



    ImageFill($img, 0, 0, colorHex($img, $background_color));

    foreach ($colors as $colorkode) {
        $fill_color[] = colorHex($img, $colorkode);
        $shadow_color[] = colorHexshadow($img, $colorkode, $shadow_dark);
    }

    $label_place = 15;

    if (is_array($label)) {
        for ($i = 0; $i < count($label); $i++) {
            if ($label_form == 'round' && $show_label && $data[$i] > 0) {
                imagefilledellipse($img, $width + 0, $label_place + 5, 10, 10, colorHex($img, $colors[$i % count($colors)]));
                imageellipse($img, $width + 0, $label_place + 5, 10, 10, colorHex($img, $text_color));
            } else if ($label_form == 'square' && $show_label && $data[$i] > 0) {
                imagefilledrectangle($img, $width + 0, $label_place, $width + 0, $label_place + 10, colorHex($img, $colors[$i % count($colors)]));
                imagerectangle($img, $width + 0, $label_place, $width + 0, $label_place + 10, colorHex($img, $text_color));
            }

            if ($data[$i] > 0) {
                if ($show_percent)
                    $label_output = $number[$i] . ' ';
                if ($show_text)
                    $label_output = $label_output . $label[$i] . ' ';
                if ($show_parts)
                    $label_output = $label_output . $data[$i];

                imagestring($img, '4', $width + 70, $label_place, $label_output, colorHex($img, $text_color));
                $label_output = '';

                $label_place = $label_place + 20;
            }
        }
    }
    $centerX = round($width / 2);
    $centerY = round($height / 2);
    $diameterX = $width - 4;
    $diameterY = $height - 4;

    $data_sum = array_sum($data);

    $start = 270;

    for ($i = 0; $i < count($data); $i++) {
        $value += $data[$i];
        $end = ceil(($value / $data_sum) * 360) + 270;
        $slice[] = array($start, $end, $shadow_color[$value_counter % count($shadow_color)], $fill_color[$value_counter % count($fill_color)]);
        $start = $end;
        $value_counter++;
    }

    for ($i = $centerY + $shadow_height; $i > $centerY; $i--) {
        for ($j = 0; $j < count($slice); $j++) {
            if ($slice[$j][0] != $slice[$j][1])
                ImageFilledArc($img, $centerX, $i, $diameterX, $diameterY, $slice[$j][0], $slice[$j][1], $slice[$j][2], IMG_ARC_PIE);
        }
    }

    for ($j = 0; $j < count($slice); $j++) {
        if ($slice[$j][0] != $slice[$j][1])
            ImageFilledArc($img, $centerX, $centerY, $diameterX, $diameterY, $slice[$j][0], $slice[$j][1], $slice[$j][3], IMG_ARC_PIE);
    }

    OutputImage($img, $target);

    return $target;
}

function colorHex($img, $HexColorString) {
    $R = hexdec(substr($HexColorString, 0, 2));
    $G = hexdec(substr($HexColorString, 2, 2));
    $B = hexdec(substr($HexColorString, 4, 2));
    return ImageColorAllocate($img, $R, $G, $B);
}

function colorHexshadow($img, $HexColorString, $mork) {
    $R = hexdec(substr($HexColorString, 0, 2));
    $G = hexdec(substr($HexColorString, 2, 2));
    $B = hexdec(substr($HexColorString, 4, 2));

    if ($mork) {
        ($R > 99) ? $R -= 100 : $R = 0;
        ($G > 99) ? $G -= 100 : $G = 0;
        ($B > 99) ? $B -= 100 : $B = 0;
    } else {
        ($R < 220) ? $R += 35 : $R = 255;
        ($G < 220) ? $G += 35 : $G = 255;
        ($B < 220) ? $B += 35 : $B = 255;
    }

    return ImageColorAllocate($img, $R, $G, $B);
}

function OutputImage($img, $target) {
    ImageJPEG($img, $target, 100);
}

function invert_date($date) {
    $year = substr($date, 0, 4);
    $month = substr($date, 5, 2);
    $day = substr($date, 8, 2);
    $newdate = "$day-$month-$year";
    return $newdate;
}

//****************************************
//FUNCION PARA MOSTRAR UN MES EN LETRAS
//****************************************
function month_in_letters($month, $languaje = 'spa') {
    switch ($month) {
        case "01": ($languaje == 'spa') ? $month_letters = 'Enero' : $month_letters = 'January';
            break;
        case "02": ($languaje == 'spa') ? $month_letters = 'Febrero' : $month_letters = 'February';
            break;
        case "03": ($languaje == 'spa') ? $month_letters = 'Marzo' : $month_letters = 'March';
            break;
        case "04": ($languaje == 'spa') ? $month_letters = 'Abril' : $month_letters = 'April';
            break;
        case "05": ($languaje == 'spa') ? $month_letters = 'Mayo' : $month_letters = 'May';
            break;
        case "06": ($languaje == 'spa') ? $month_letters = 'Junio' : $month_letters = 'June';
            break;
        case "07": ($languaje == 'spa') ? $month_letters = 'Julio' : $month_letters = 'July';
            break;
        case "08": ($languaje == 'spa') ? $month_letters = 'Agosto' : $month_letters = 'August';
            break;
        case "09": ($languaje == 'spa') ? $month_letters = 'Setiembre' : $month_letters = 'September';
            break;
        case "10": ($languaje == 'spa') ? $month_letters = 'Octubre' : $month_letters = 'October';
            break;
        case "11": ($languaje == 'spa') ? $month_letters = 'Noviembre' : $month_letters = 'November';
            break;
        case "12": ($languaje == 'spa') ? $month_letters = 'Diciembre' : $month_letters = 'December';
            break;
    }
    return $month_letters;
}
?>