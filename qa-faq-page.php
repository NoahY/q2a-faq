<?php

/*

	This program is free software; you can redistribute it and/or
	modify it under the terms of the GNU General Public License
	as published by the Free Software Foundation; either version 2
	of the License, or (at your option) any later version.
	
	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	More about this license: http://www.question2answer.org/license.php
*/

	class qa_faq_page {
		
		var $directory;
		var $urltoroot;
		
		function load_module($directory, $urltoroot)
		{
			$this->directory=$directory;
			$this->urltoroot=$urltoroot;
		}
		
		function suggest_requests() // for display in admin interface
		{	
			return array(
				array(
					'title' => 'FAQ',
					'request' => 'faq',
					'nav' => 'M', // 'M'=main, 'F'=footer, 'B'=before main, 'O'=opposite main, null=none
				),
			);
		}
		
		function match_request($request)
		{
			$faq = qa_opt('faq_page_url');
			if ($request==$faq)
				return true;
			return false;
		}
		
		function process_request($request)
		{
			$qa_content=qa_content_prepare();

			$qa_content['head_lines'][]='<style>'.qa_opt('faq_css').'</style>';

			$qa_content['title']=qa_opt('faq_page_title');

			$qa_content['custom_0']=$this->filter_subs(qa_opt('faq_pre_html')).'<'.(qa_opt('faq_list_type')?'o':'u').'l class="qa-faq-list">';
			
			$idx = 0;

			while(qa_opt('faq_section_'.$idx)) {
				$title = $this->filter_subs(qa_opt('faq_section_'.$idx.'_title'));
				$text = $this->filter_subs(qa_opt('faq_section_'.$idx));

				$qa_content['custom_'.($idx+1).'_title']='<li class="qa-faq-list-item"><div id="custom_'.$idx.'_title" onclick="jQuery(\'#custom_'.$idx.'_text\').toggle(\'fast\')" class="qa-faq-section-title">'.$title.'</div>';
				$qa_content['custom_'.($idx+1).'_text']='<div id="custom_'.$idx.'_text" class="qa-faq-section-text">'.$text.'</div></li>';
				$idx++;
			}

			$qa_content['custom_'.(++$idx)]='</'.(qa_opt('faq_list_type')?'o':'u').'l>';

			$qa_content['custom_'.++$idx]=$this->filter_subs(qa_opt('faq_post_html'));

			return $qa_content;
		}
	
		function filter_subs($text) {
			
			// text subs
			
			$subs = array(
				'site_title' => qa_opt('site_title'),
				'site_url' => qa_opt('site_url'),
			);
			
			foreach($subs as $i => $v) {
				$text = str_replace('^'.$i,$v,$text);
			}

			// function subs

			preg_match_all('/\^qa_path\(([^)]+)\)/',$text,$qa_path,PREG_SET_ORDER);
			
			foreach($qa_path as $match) {
				$text = str_replace($match[0],qa_path($match[1]),$text);
			}

			preg_match_all('/\^qa_opt\(([^)]+)\)/',$text,$qa_opt,PREG_SET_ORDER);
			
			foreach($qa_opt as $match) {
				
				// backwards compat
				
				if(in_array($match[1],array('points_per_q_voted_up','points_per_q_voted_down')) && !qa_opt('points_per_q_voted_up'))
					$match[1] = 'points_per_q_voted';
				else if(in_array($match[1],array('points_per_a_voted_up','points_per_a_voted_down')) && !qa_opt('points_per_a_voted_up'))
					$match[1] = 'points_per_a_voted';
					
				$text = str_replace($match[0],qa_opt($match[1]),$text);
			}
			
			// if subs
			
			if(qa_get_logged_in_userid()) {
				
				$text = preg_replace('/\^if_logged_in=`([^`]+)`/','$1',$text);
				$text = preg_replace('/\^if_not_logged_in=`[^`]+`/','',$text);
				
				$handle = qa_get_logged_in_handle();
				
				$subs = array(
					'profile_url' => qa_path('user/'.$handle),
					'handle' => $handle,
				);
				
				foreach($subs as $i => $v) {
					$text = str_replace('^'.$i,$v,$text);
				}
				
				
			}
			else {
				
				global $qa_root_url_relative;
				
				$userlinks=qa_get_login_links($qa_root_url_relative,null);
				
				$subs = array(
					'login' => $userlinks['login'],
					'register' => $userlinks['register'],
				);				
				
				foreach($subs as $i => $v) {
					$text = str_replace('^'.$i,$v,$text);
				}				
				
				$text = preg_replace('/\^if_not_logged_in=`([^`]+)`/','$1',$text);
				$text = preg_replace('/\^if_logged_in=`[^`]+`/','',$text);
			}
			
			// table subs
			
			if(strpos($text,'^pointstable') !== false) {
			
				require_once QA_INCLUDE_DIR.'qa-db-points.php';

				$optionnames=qa_db_points_option_names();
				$options=qa_get_options($optionnames);
				
				$table = '
<table class="qa-form-wide-table">
	<tbody>';
				$multi = (int)$options['points_multiple'];
				foreach ($optionnames as $optionname) {
					
					switch ($optionname) {
						case 'points_multiple':
							continue 2;
							
						case 'points_per_q_voted_up':
						case 'points_per_a_voted_up':
							$prefix='+';
							break;
						case 'points_per_q_voted_down':
						case 'points_per_a_voted_down':
							$prefix='-';
							break;
						case 'points_per_q_voted':
						case 'points_per_a_voted':
							$prefix='&#177;';
							break;
							
						case 'points_q_voted_max_gain':
						case 'points_a_voted_max_gain':
							$prefix='+';
							break;
						
						case 'points_q_voted_max_loss':
						case 'points_a_voted_max_loss':
							$prefix='&ndash;';
							break;
							
						case 'points_base':
							$prefix='+';
							break;
							
						default:
							$prefix='<SPAN STYLE="visibility:hidden;">+</SPAN>'; // for even alignment
							break;
					}
					
					$points = $optionname != 'points_base' ? (int)$options[$optionname]*$multi : (int)$options[$optionname];
					
					if ($points>0) {
						$table .= '
			<tr>
				<td class="qa-form-wide-label">
					'.qa_lang_html('options/'.$optionname).'
				</td>
				<td class="qa-form-wide-data" style="text-align:right">
					<span class="qa-form-wide-prefix"><span style="width: 1em; display: -moz-inline-stack;">'.$prefix.'</span></span>
					'.qa_html($points).($optionname=='points_multiple'?'':'
					<span class="qa-form-wide-note">'.qa_lang_html('admin/points').'</span>').'
				</td>
			</tr>';
					}
				}
				
				$table .= '
	</tbody>
</table>';
			
				
				$text = str_replace('^pointstable',$table,$text);
			
			}

			if(strpos($text,'^privilegestable') !== false) {


				$options = qa_get_permit_options();
				
				foreach ($options as $option) {
					if(qa_opt($option) == QA_PERMIT_POINTS) {
						$popts[$option] = (int)qa_opt($option.'_points');
					}
				}
				
				if(isset($popts)) {
				
					asort($popts);

					$table = '
	<table class="qa-form-wide-table">
		<tbody>';
					foreach ($popts as $key => $val) {

						// fudge
						if ($key=='permit_retag_cat')
							$name=qa_lang_html(qa_using_categories() ? 'profile/permit_recat' : 'profile/permit_retag');
						else 
							$name = qa_lang('profile/'.$key);
							
						if($name == '[profile/'.$key.']') {
							global $qa_lang_file_pattern;
							foreach($qa_lang_file_pattern as $k => $v)	{
								if(qa_lang($k.'/'.$key) != '['.$k.'/'.$key.']') {
									$name = qa_lang($k.'/'.$key);
									break;
								}
							}
						}	
						
						$table .= '
			<tr>
				<td class="qa-form-wide-label">
					'.$name.'
				</td>
				<td class="qa-form-wide-data" style="text-align:right">
					'.qa_html($val).'
					<span class="qa-form-wide-note">'.qa_lang_html('admin/points').'</span>'.'
				</td>
			</tr>';
					}
					
					$table .= '
		</tbody>
	</table>';
					$text = str_replace('^privilegestable',$table,$text);
				}
				else $text = str_replace('^privilegestable','',$text);
			}
				
			return $text;
		}
	
	};
	

/*
	Omit PHP closing tag to help avoid accidental output
*/