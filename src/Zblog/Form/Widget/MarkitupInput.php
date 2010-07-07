<?php
/* -*- tab-width: 4; indent-tabs-mode: nil; c-basic-offset: 4 -*- */
/*
# ***** BEGIN LICENSE BLOCK *****
# This file is part of Plume Framework, a simple PHP Application Framework.
# Copyright (C) 2001-2007 Loic d'Anterroches and contributors.
#
# Plume Framework is free software; you can redistribute it and/or modify
# it under the terms of the GNU Lesser General Public License as published by
# the Free Software Foundation; either version 2.1 of the License, or
# (at your option) any later version.
#
# Plume Framework is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU Lesser General Public License for more details.
#
# You should have received a copy of the GNU Lesser General Public License
# along with this program; if not, write to the Free Software
# Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
#
# ***** END LICENSE BLOCK ***** */

/**
 * Textarea with TinyMCE addition.
 */
class Zblog_Form_Widget_MarkitupInput extends Pluf_Form_Widget
{
	public $jquery_url = '/static/markitup/jquery-1.4.2.min.js';
	public $markitup_url = '/static/markitup/markitup/jquery.markitup.js';
	public $markitup_set_url = '/static/markitup/markitup/sets/markdown/set.js';

	public $markitup_css = '/static/markitup/markitup/skins/markitup/style.css';
	public $markitup_set_css = '/static/markitup/markitup/sets/markdown/style.css';

    public function __construct($attrs=array())
    {
        $defaults = array('cols' => '70',
                          'rows' => '20');
        $this->attrs = array_merge($defaults, $attrs);
    }

    /**
     * Renders the HTML of the input.
     *
     * @param string Name of the field.
     * @param mixed Value for the field, can be a non valid value.
     * @param array Extra attributes to add to the input form (array())
     * @return string The HTML string of the input.
     */
    public function render($name, $value, $extra_attrs=array())
    {
        if ($value === null) $value = '';

		$final_attrs = $this->buildAttrs(array('name' => $name),
                                         $extra_attrs);

		$out = '<link rel="stylesheet" type="text/css" href="'.$this->markitup_css.'" />
<link rel="stylesheet" type="text/css" href="'.$this->markitup_set_css.'" />
<script type="text/javascript" src="'.$this->jquery_url.'"></script>
<script type="text/javascript" src="'.$this->markitup_url.'"></script>
<script type="text/javascript" src="'.$this->markitup_set_url.'"></script>
<script type="text/javascript" >
   $(document).ready(function() {
      $("textarea").markItUp(mySettings);
   });
</script>';
		
        return new Pluf_Template_SafeString(
                       $out.sprintf('<textarea%s>%s</textarea>',
                               Pluf_Form_Widget_Attrs($final_attrs),
                               $value),
                       true);
    }
}