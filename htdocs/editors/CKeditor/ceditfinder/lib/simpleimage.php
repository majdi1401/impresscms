<?php
	$this->image = false;
}
}
	return false;
}
return true; }
	return false;
}
return imagesx($this->image); }
	return false;
}
return imagesy($this->image); }
	return false;
}
if ($this->image_type != IMAGETYPE_GIF && $this->image_type != IMAGETYPE_JPEG && $this->image_type != IMAGETYPE_PNG) {
	return false;
}
$final_width = 0; $final_height = 0; $width_old = imagesx($this->image); $height_old = imagesy($this->image); if ($proportional) {
	$factor = $height / $height_old;
} elseif ($height == 0) {
	$factor = $width / $width_old;
} else {
	$factor = min($width / $width_old, $height / $height_old);
}
$final_width = round($width_old * $factor); $final_height = round($height_old * $factor); } else {