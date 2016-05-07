<?php

	function build_form($id,$label,$name,$value)
	{
		$output  = "<p>\n";
		$output .= "   <label for='".$id."'>$label</label>\n";
		$output .= "   <input type='text' name='".$name."' placeholder=\"Your field Value\" id='".$id."' value=\"".$value."\" />\n";
		$output .= "</p>";
		$output .= "\n\n";
		return $output;
	}

	function replace_data($file,$old,$new)
	{
		$to = file_get_contents($file);
		$data = str_replace($old, $new, $to);
        file_put_contents($file, $data);
	}

	function set_null($file)
	{
		file_put_contents($file, "");
	}