<?php
/*
 * Melt
 * version: 0.8.1 (10/05/2011)
 *
 * Licensed under the MIT:
 *   http://www.opensource.org/licenses/mit-license.php
 *
 * Copyright 2011 David Berube [ berube@gmail.com ]
 *
 * Usage:
 *
 * <script type="text/javascript" src="<?/*=$this->melt(Array('public/js/jquery.js',
 * 														   'public/js/master.js',
 * 														   'public/js/site_edit_elements.js',
 *														   'public/js/jquery_ui.js'));?>">
 * </script>
 *
 */
 															
class melt {
	public	$files,
			$extension,
			$expire;

	function __construct()
    {
        parent::__construct();	
    }

	public function melt() {
		if (!$this->files) { return false; }
		if (!$this->extension) { return false; }
		if (!$this->expire) { $this->expire = '15 minutes'; }
		
		$path = BASE_PATH . 'tmp/' . $this->extension . '/';
		$web_path = '/tmp/' . $this->extension . '/';
		
		// Build the filename 
		foreach ($this->files as $file) {
			$file_name .= $file;	
		}
		
		$file_name = md5($file_name) . '.' . $this->extension;
	
		if (file_exists($path . $file_name)) { 
			// See if the file is expired.
			if ((filemtime($path . $file_name)) < (strtotime($this->expire))) {
				// Cleanup the directory
				$dp = opendir($path);
				while ($file = readdir($dp)) {
					if ((filemtime($path . $file)) < (strtotime($this->expire))) {
						unlink($path . $file);
					}
				}
				closedir($dp);					
			} else {
				return $web_path . $file_name; 
			}
		}
		
		// Build the file if it doesn't exist
		$fp = fopen($path . $file_name, 'w+');
		foreach ($this->files as $file) {
			$fgc = file_get_contents(BASE_PATH . $file);
			fwrite($fp, $fgc);
		}
		fclose($fp);	
		
		return $web_path . $file_name;
	}
}
?>