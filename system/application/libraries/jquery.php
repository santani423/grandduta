<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * jQuery Library
 */
class Jquery {
    var $CI;
    
    /* paths */
    var $jq_base_url;
    var $jq_css_url;
    
    
    var $arr_scripts;
    var $arr_css;
    
    /* Output */
    var $output_scripts    = '';
    var $output_css        = '';
    var $output            = '';
    
    /**
     * Constructor
     * @return void
     */
    function Jquery () {
        $this->CI =& get_instance();
        $this->CI->load->config('jquery');
        
        $arr_config = $this->CI->config->item('jq_config');
        
        $this->jq_base_url     = $this->CI->config->item('base_url').$arr_config['js_path'];
        $this->jq_css_url      = $this->CI->config->item('base_url').$arr_config['css_path'];
        
        $this->add_scripts($this->CI->config->item('jq_scripts'));
        $this->add_css($this->CI->config->item('jq_css'));
    }
    
    /**
     * Add a Datepicker to an input field
     * @param string    $field_id    ID of the field to add the Datepicker to
     * @return void
     */
    function add_datepicker($field_id, $date_format = 'yy-mm-dd') {
        $str_script     = '';
        $str_script        .= '$(function() {';
        $str_script        .= '$("#'.$field_id.'").datepicker($.datepicker.regional["de"]);';
        $str_script     .= '$("#'.$field_id.'").datepicker("option", {dateFormat: "'.$date_format.'"});';
        $str_script     .= '});';
        $this->output .= $str_script;
    }
    
    /**
     * Add scripts 
     * @param mixed    $scripts    A string or an Array containing the scripts to load
     * @return void
     */
    function add_scripts($scripts) {
        $arr_scripts = array();
        if (!is_array($scripts)) {
            $arr_scripts[] = $scripts;
        } else {
            $arr_scripts = $scripts;
        }
        
        foreach ($arr_scripts as $script) {
            $this->output_scripts .= '<script type="text/javascript" src="'.$this->jq_base_url.$script.'"></script>';
        }
    }
    
    /**
     * Add css
     * @param mixed    $css    A string or an Array containing the css to load
     * @return void
     */
    function add_css($css) {
        $arr_css = array();
        if (!is_array($css)) {
            $arr_css[] = $css;
        } else {
            $arr_css = $css;
        }
        
        foreach ($arr_css as $css) {
            $this->output_css .=  '<link type="text/css" href="'.$this->jq_css_url.$css.'" rel="Stylesheet" />';
        }
    }
    
    /**
     * Generate output
     * @return string
     */
    function get_output () {
        $str_output = '';
        $str_output .= $this->output_css;
        $str_output .= $this->output_scripts;
        $str_output .= '<script type="text/javascript">';
        $str_output .= $this->output;
        $str_output .= '</script>';
        return $str_output;
    }
}

// END Class Jquery

/* End of file jquery.php */
/* Location: ./system/application/libraries/jquery.php */  
?>