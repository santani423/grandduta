<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2008 - 2011, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * Pagination Class
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Pagination
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/libraries/pagination.html
 */
class CI_Pagination {

	var $base_url			= ''; // The page we are linking to
	var $prefix				= ''; // A custom prefix added to the path.
	var $suffix				= ''; // A custom suffix added to the path.

	var $total_rows			=  0; // Total number of items (database results)
	var $per_page			= 10; // Max number of items you want shown per page
	var $num_links			=  2; // Number of "digit" links to show before/after the currently viewed page
	var $cur_page			=  0; // The current page being viewed
	var $use_page_numbers	= FALSE; // Use page number for segment instead of offset
	var $first_link			= '&lsaquo; First';
	var $next_link			= '&gt;';
	var $prev_link			= '&lt;';
	var $last_link			= 'Last &rsaquo;';
	var $uri_segment		= 3;
	var $full_tag_open		= '';
	var $full_tag_close		= '';
	var $first_tag_open		= '';
	var $first_tag_close	= '&nbsp;';
	var $last_tag_open		= '&nbsp;';
	var $last_tag_close		= '';
	var $first_url			= ''; // Alternative URL for the First Page.
	var $cur_tag_open		= '&nbsp;<strong>';
	var $cur_tag_close		= '</strong>';
	var $next_tag_open		= '&nbsp;';
	var $next_tag_close		= '&nbsp;';
	var $prev_tag_open		= '&nbsp;';
	var $prev_tag_close		= '';
	var $num_tag_open		= '&nbsp;';
	var $num_tag_close		= '';
	var $page_query_string	= FALSE;
	var $query_string_segment = 'per_page';
	var $display_pages		= TRUE;
	var $anchor_class		= '';

	/**
	 * Constructor
	 *
	 * @access	public
	 * @param	array	initialization parameters
	 */
	public function __construct($params = array())
	{
		if (count($params) > 0)
		{
			$this->initialize($params);
		}

		if ($this->anchor_class != '')
		{
			$this->anchor_class = 'class="'.$this->anchor_class.'" ';
		}

		log_message('debug', "Pagination Class Initialized");
	}

	// --------------------------------------------------------------------

	/**
	 * Initialize Preferences
	 *
	 * @access	public
	 * @param	array	initialization parameters
	 * @return	void
	 */
	function initialize($params = array())
	{
		if (count($params) > 0)
		{
			foreach ($params as $key => $val)
			{
				if (isset($this->$key))
				{
					$this->$key = $val;
				}
			}
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Generate the pagination links
	 *
	 * @access	public
	 * @return	string
	 */
	public function create_links()
{
    // If total rows or per-page total is zero, return nothing.
    if ($this->total_rows == 0 || $this->per_page == 0) {
        return '';
    }

    // Calculate total pages
    $num_pages = (int) ceil($this->total_rows / $this->per_page);

    if ($num_pages === 1) {
        return '';
    }

    $CI =& get_instance();

    // Determine the current page
    $base_page = ($this->use_page_numbers) ? 1 : 0;

    if ($CI->config->item('enable_query_strings') === TRUE || $this->page_query_string === TRUE) {
        $this->cur_page = (int) $CI->input->get($this->query_string_segment);
    } else {
        $this->cur_page = (int) $CI->uri->segment($this->uri_segment);
    }

    if ($this->use_page_numbers && $this->cur_page == 0) {
        $this->cur_page = $base_page;
    }

    $this->num_links = (int) $this->num_links;

    if (!is_numeric($this->cur_page)) {
        $this->cur_page = $base_page;
    }

    if ($this->use_page_numbers) {
        if ($this->cur_page > $num_pages) {
            $this->cur_page = $num_pages;
        }
    } else {
        if ($this->cur_page > $this->total_rows) {
            $this->cur_page = ($num_pages - 1) * $this->per_page;
        }
    }

    $uri_page_number = $this->cur_page;

    if (!$this->use_page_numbers) {
        $this->cur_page = floor(($this->cur_page / $this->per_page) + 1);
    }

    $start = (($this->cur_page - $this->num_links) > 0) ? $this->cur_page - ($this->num_links - 1) : 1;
    $end = (($this->cur_page + $this->num_links) < $num_pages) ? $this->cur_page + $this->num_links : $num_pages;

    if ($CI->config->item('enable_query_strings') === TRUE || $this->page_query_string === TRUE) {
        $this->base_url = rtrim($this->base_url) . '&' . $this->query_string_segment . '=';
    } else {
        $this->base_url = rtrim($this->base_url, '/') . '/';
    }

    // START Bootstrap pagination HTML
    $output = '<ul class="pagination justify-content-center">';

    // First link
    if ($this->first_link !== FALSE && $this->cur_page > ($this->num_links + 1)) {
        $first_url = ($this->first_url == '') ? $this->base_url : $this->first_url;
        $output .= '<li class="page-item"><a class="page-link" href="' . $first_url . '">' . $this->first_link . '</a></li>';
    }

    // Previous link
    if ($this->prev_link !== FALSE && $this->cur_page != 1) {
        $i = ($this->use_page_numbers) ? $uri_page_number - 1 : $uri_page_number - $this->per_page;
        $i = ($i == 0) ? '' : $this->prefix . $i . $this->suffix;
        $prev_url = ($i == '' && $this->first_url != '') ? $this->first_url : $this->base_url . $i;
        $output .= '<li class="page-item"><a class="page-link" href="' . $prev_url . '">' . $this->prev_link . '</a></li>';
    }

    // Numbered links
    for ($loop = $start; $loop <= $end; $loop++) {
        $i = ($this->use_page_numbers) ? $loop : ($loop * $this->per_page) - $this->per_page;
        $n = ($i == 0) ? '' : $this->prefix . $i . $this->suffix;
        $url = ($n == '' && $this->first_url != '') ? $this->first_url : $this->base_url . $n;

        if ($this->cur_page == $loop) {
            $output .= '<li class="page-item active"><span class="page-link">' . $loop . '</span></li>';
        } else {
            $output .= '<li class="page-item"><a class="page-link" href="' . $url . '">' . $loop . '</a></li>';
        }
    }

    // Next link
    if ($this->next_link !== FALSE && $this->cur_page < $num_pages) {
        $i = ($this->use_page_numbers) ? $this->cur_page + 1 : ($this->cur_page * $this->per_page);
        $output .= '<li class="page-item"><a class="page-link" href="' . $this->base_url . $this->prefix . $i . $this->suffix . '">' . $this->next_link . '</a></li>';
    }

    // Last link
    if ($this->last_link !== FALSE && ($this->cur_page + $this->num_links) < $num_pages) {
        $i = ($this->use_page_numbers) ? $num_pages : (($num_pages * $this->per_page) - $this->per_page);
        $output .= '<li class="page-item"><a class="page-link" href="' . $this->base_url . $this->prefix . $i . $this->suffix . '">' . $this->last_link . '</a></li>';
    }

    $output .= '</ul>';

    return $output;
}

}
// END Pagination Class

/* End of file Pagination.php */
/* Location: ./system/libraries/Pagination.php */