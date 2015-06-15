<?php

class UserAccessManagerPrivateExtension
{
	private $uam;
	
	function __construct() 
	{ 
		
	}
	
	public function setUserAccessManager($userAccessManager)
	{
		$this->uam = $userAccessManager;
	}
	
	public function add_shortcodes()
	{
		add_shortcode('private', array(&$this, 'process_private_shortcode'));
    add_shortcode('public', array(&$this, 'process_public_shortcode'));
	}
	
	public function process_private_shortcode($atts, $content = null) 
	{	
		extract(shortcode_atts(array(
			'group' => null,
			'showprivate' => get_option('uampe_private_show', 'y'),
			'shownotauthorized' => get_option('uampe_not_authorized_show', 'y'),	
		
		), $atts));
		
		$showprivate = bool_from_yn($showprivate);
		$shownotauthorized = bool_from_yn($shownotauthorized);
		
		$result = $this->_uamep_validate_group($group);
		
		if ($result == 'admin') {
			return do_shortcode($content.'<span style="color:red; font-size: 70%;">&nbsp;[Group '.$group.']</span>'); }
		else if ($result == 'true') {
			return do_shortcode($content); }
		else if ($result == 'private' && $showprivate) {
			return get_option('uampe_private_text'); }
		else if ($result == 'notauth' && $shownotauthorized) {
			return get_option('uampe_not_authorized_text'); }
		else {
			return ''; }
	}

	public function process_public_shortcode($atts, $content = null) 
	{	
		
		$result = $this->_uamep_validate_group($group);

		if ($result == 'notauth' || $result == 'private') {
			return do_shortcode($content); }
		else {
			return ''; }
	}
	
	public function activate()
	{
		if (!get_option('uampe_private_show'))
			add_option('uampe_private_show', 'y', '', true);
		if (!get_option('uampe_private_text'))
			add_option('uampe_private_text', '', '', 'yes');
		if (!get_option('uampe_not_authorized_show'))
			add_option('uampe_not_authorized_show', 'y', '', true);
		if (!get_option('uampe_not_authorized_text'))
			add_option('uampe_not_authorized_text', '', '', 'yes');
	}
	
	public function deactivate()
	{
		/*
		if (get_option('wp_private_replacement_type'))
			delete_option('wp_private_replacement_type');
		if(!get_option('wp_private_linkback_enable'))
			delete_option('wp_private_linkback_enable');
		if(!get_option('wp_private_before_html'))
			delete_option('wp_private_before_html');
		if(!get_option('wp_private_after_html'))
			delete_option('wp_private_after_html');
		if(!get_option('wp_private_not_authorized_text'))
			delete_option('wp_private_not_authorized_text');
		*/
	}
	
	public function _uamep_validate_group($group)
	{	
		// If is admin, show all
		if (current_user_can('administrator'))
			return 'admin';	
		
		// If no logged in, private content
		if (!is_user_logged_in())
			return 'private';

		// If no group, show to registered users. Here all users are logged in
		if ($group == null)
			return 'true';
		else
		{		
			$result = 'notauth';
				
			if (isset($this->uam))
			{
				$uamAccessHandler = $this->uam->getAccessHandler();
				$uamUserGroups = $uamAccessHandler->getUserGroupsForObject('user', get_current_user_id());
				
				// Split multigroups
				$groups = explode(',', $group);
				
				$allow = array();
				$deny = array();
				
				foreach ($groups as $value)
				{
					if ($value[0] == '!')
						array_push($deny, substr($value, 1, strlen($value) - 1));
					else 
						array_push($allow, $value);
				}
				
				foreach($uamUserGroups as $uamUserGroup)
				{
					if (is_numeric(str_replace("!", "", $groups[0])))
						$uamGroup = $uamUserGroup->getId();
					else 
						$uamGroup = $uamUserGroup->getGroupName();
					
					// If user is in deny, deny access
					if (count($deny) > 0)
					{
						if (in_array($uamGroup, $deny)) {
							return 'notauth';
						}
					}
					
					// If user is in allow, allow access
					if (count($allow) > 0)
					{
						if (in_array($uamGroup, $allow)) {
							$result = 'true';
							continue;
						}
					}
					
					// If user is neither in deny nor allow, allow
					if (count($deny) > 0 && count($allow) == 0) {
						$result = 'true';
						continue;
					}
				}
			}
			return $result;
		}
	} 
}





?>