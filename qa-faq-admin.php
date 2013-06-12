<?php
    class qa_faq_admin {

		function allow_template($template)
		{
			return ($template!='admin');
		}

		function option_default($option) {
			
			$idx = 0;
			
			switch($option) {
			case 'faq_page_url':
				return 'faq';
			case 'faq_page_title':
				return 'Frequently Asked Questions';
			case 'faq_page_slug':
				return 'FAQ';
			case 'faq_list_type':
				return false;
			case 'faq_pre_html':
				return 'The following is basic information about our forum.  Please read this before posting questions or answers if you are unfamiliar with this sort of forum.  Click on any question to show or hide the answer.';
			case 'faq_post_html':
				return '<br/>If there is anything lacking, please use the <a href="^qa_path(feedback)">feedback form</a>.';
			case 'faq_notify_text':
				return 'First time here?  Checkout the ^faq!';
			case 'faq_css':
				return '.notify-container {
	left: 0;
	right: 0;
	top: 0;
	padding: 0;
	position: fixed;
	width: 100%;
	z-index: 10000;
}
.notify {
	background-color: #F6DF30;
	color: #444444;
	font-weight: bold;
	width: 100%;
	text-align: center;
	font-family: sans-serif;
	font-size: 14px;
	padding: 10px 0;
	position:relative;
}
.notify-close {
	color: #735005;
	cursor: pointer;
	font-size: 18px;
	line-height: 18px;
	padding: 0 3px;
	position: absolute;
	right: 8px;
	text-decoration: none;
	top: 8px;
}
.qa-faq-section-title {
	font-size:125%;
	font-weight:bold;
	margin:10px 0 5px;
	cursor:pointer;
}
.qa-faq-section-text {
	display:none;
	margin:0 0 10px 10px;
}';
			case 'faq_section_0_title':
				return 'What kinds of questions can I ask here? ';
			case 'faq_section_0':
				return 'Most importantly, questions should be <strong>relevant to our community</strong>.  Before you ask, please make sure to search for a similar question. You can search for questions by their title or tags.';
			case 'faq_section_1_title':
				return 'What kinds of questions should be avoided? ';
			case 'faq_section_1':
				return 'Please avoid asking questions that are not related to our community, too subjective or argumentative.';
			case 'faq_section_2_title':
				return 'What should I avoid in my answers? ';
			case 'faq_section_2':
				return '^site_title is a <strong>question and answer</strong> site - it is <strong>not</strong> a discussion group. Please avoid holding debates in your answers as they tend to dilute the quality of  the forum. <br/><br/>For brief discussion, or to thank someone for their answer, please post comments, not answers.';
			case 'faq_section_3_title':
				return 'Who moderates this community? ';
			case 'faq_section_3':
				return 'The short answer is: <strong>you.</strong>  This website is moderated by the users.  Points system allows users to earn rights to perform a variety of moderation tasks.';
			case 'faq_section_4_title':
				return 'How does point system work? ';
			case 'faq_section_4':
				return 'When a question or answer is voted up, the user who posted it will gain points. These points serve as a rough measure of the community trust in that person. Various moderation tasks are gradually assigned to the users based on those points. <br/><br/>For example, if you ask an interesting question or useful answer, it  will likely be voted up. On the other hand if the question is poorly-worded or the answer is misleading - it will likely be voted down. Each up vote on a question will generate <strong>^qa_opt(points_per_q_voted_up) points</strong>, whereas each vote against will subtract <strong>^qa_opt(points_per_q_voted_down) points</strong>. The following table lists points gained per activity:<br/><br/>^pointstable<br/><br/>The following table lists point requirements for each type of moderation task. <br/><br/>^privilegestable';
			case 'faq_section_5_title':
				return 'How to change my picture (gravatar), and what is gravatar? ';
			case 'faq_section_5':
				return 'The picture that appears in user profiles is called a <strong>gravatar</strong>, which means <strong>globally recognized avatar</strong>.<br/><br/>Here is how it works: You upload your picture (or your favorite alter ego image) to the website <a href="http://gravatar.com"><strong>gravatar.com</strong></a> from where we later retrieve your image using a cryptographic key based on your email address.<br/><br/>This way all the websites you trust can show your image next to your posts and your email address remains private.<br/><br/>Please <strong>personalize your account with an image</strong> - just register at <a href="http://gravatar.com"><strong>gravatar.com</strong></a> (just please be sure to use the same email address that you used to register with us). The default gray image is generated automatically.';
			case 'faq_section_6_title':
				return 'Still have questions? ';
			case 'faq_section_6':
				return 'Please <a href="^qa_path(ask)">ask your question</a> and help make our community better! ';
			default:
				return null;
			}

		}

		function admin_form(&$qa_content)
		{

			$qa_content['head_lines'][]='<script>
	function moveFaqSection(idx,odx) {
		var self = [jQuery("#faq_section_"+idx+"_title").val(),jQuery("#faq_section_"+idx).val()];
		var other = [jQuery("#faq_section_"+odx+"_title").val(),jQuery("#faq_section_"+odx).val()];
		
		jQuery("#qa-faq-section-table-"+idx).fadeOut(200,function(){
			jQuery("#faq_section_"+idx+"_title").val(other[0]);
			jQuery("#faq_section_"+idx).val(other[1]);
			jQuery("#qa-faq-section-table-"+idx).fadeIn(200);
		});
		
		jQuery("#qa-faq-section-table-"+odx).fadeOut(200,function(){
			jQuery("#faq_section_"+odx+"_title").val(self[0]);
			jQuery("#faq_section_"+odx).val(self[1]);
			jQuery("#qa-faq-section-table-"+odx).fadeIn(200);
		});
		
	}
</script>';
			
		
		//	Process form input
			$ok = null;

		
			if (qa_clicked('faq_save')) {

				qa_opt('faq_css',qa_post_text('faq_css'));

				qa_opt('faq_page_url',qa_post_text('faq_page_url'));
				qa_opt('faq_page_title',qa_post_text('faq_page_title'));
				qa_opt('faq_page_slug',qa_post_text('faq_page_slug'));
				qa_opt('faq_pre_html',qa_post_text('faq_pre_html'));
				qa_opt('faq_post_html',qa_post_text('faq_post_html'));
				
				qa_opt('faq_list_type',(bool)qa_post_text('faq_list_type'));
				
				qa_opt('faq_notify_show',(bool)qa_post_text('faq_notify_show'));
				qa_opt('faq_notify_text',qa_post_text('faq_notify_text'));
				
				$idx = 0;
				while($idx < (int)qa_post_text('faq_section_number')) {
					qa_opt('faq_section_'.$idx,qa_post_text('faq_section_'.$idx));
					qa_opt('faq_section_'.$idx.'_title',qa_post_text('faq_section_'.$idx.'_title'));
					$idx++;
				}
				
				$ok = qa_lang('admin/options_saved');
			}
			else if (qa_clicked('faq_reset')) {
				foreach($_POST as $i => $v) {
					$def = $this->option_default($i);
					if($def !== null) qa_opt($i,$def);
				}
					
				$idx = 0;
				while($idx < (int)qa_post_text('faq_section_number')) {
					qa_opt('faq_section_'.$idx,$this->option_default('faq_section_'.$idx)?$this->option_default('faq_section_'.$idx):'');
					qa_opt('faq_section_'.$idx.'_title',$this->option_default('faq_section_'.$idx.'_title')?$this->option_default('faq_section_'.$idx.'_title'):'');
					$idx++;
				}

				// reset in case removed

				$idx = 0;
				while($this->option_default('faq_section_'.$idx)) {
					qa_opt('faq_section_'.$idx,$this->option_default('faq_section_'.$idx));
					qa_opt('faq_section_'.$idx.'_title',$this->option_default('faq_section_'.$idx.'_title'));
					$idx++;
				}
				$ok = qa_lang('admin/options_reset');
			}

		// Create the form for display

			$fields = array();

			$fields[] = array(
				'label' => 'FAQ page url',
				'tags' => 'NAME="faq_page_url"',
				'value' => qa_opt('faq_page_url'),
				'note' => '(set this in admin/pages as well!)',
			);
			
			$fields[] = array(
				'label' => 'FAQ page title',
				'tags' => 'NAME="faq_page_title"',
				'value' => qa_opt('faq_page_title'),
			);
			
			$fields[] = array(
				'label' => 'FAQ page slug',
				'tags' => 'NAME="faq_page_slug"',
				'value' => qa_opt('faq_page_slug'),
				'note' => 'short title used in notification',
			);
			$fields[] = array(
				'label' => 'FAQ page pre html',
				'tags' => 'NAME="faq_pre_html"',
				'value' => qa_html(qa_opt('faq_pre_html')),
				'note' => 'filters allowed as for sections (below)',
			);
			$fields[] = array(
				'label' => 'FAQ page post html',
				'tags' => 'NAME="faq_post_html"',
				'value' => qa_html(qa_opt('faq_post_html')),
				'note' => 'filters allowed as for sections (below)',
			);

			$fields[] = array(
				'label' => 'Use ordered list (default is unordered)',
				'tags' => 'NAME="faq_list_type"',
				'value' => qa_opt('faq_list_type'),
				'type' => 'checkbox',
			);
				
			
			$fields[] = array(
				'label' => 'FAQ custom css',
				'tags' => 'NAME="faq_css"',
				'value' => qa_opt('faq_css'),
				'type' => 'textarea',
				'rows' => 20
			);
			$fields[] = array(
				'type' => 'blank',
			);

			$fields[] = array(
				'label' => 'Show notification for new visitors',
				'tags' => 'NAME="faq_notify_show"',
				'value' => qa_opt('faq_notify_show'),
				'type' => 'checkbox',
			);
				
			$fields[] = array(
				'label' => 'Notification text for new visitors',
				'tags' => 'NAME="faq_notify_text"',
				'value' => qa_html(qa_opt('faq_notify_text')),
				'note' => '^faq is substituted by a link to the faq page, displaying the FAQ slug set above: '.'<a href="'.qa_path_html(qa_opt('faq_page_url')).'">'.qa_opt('faq_page_slug').'</a>',
			);
			
			$fields[] = array(
				'type' => 'blank',
			);

			$fields[] = array(
				'type' => 'static',
				'value' => 'in the fields below, the following values will be substituted:<br/><br/>
	^site_title - the site title ('.qa_opt('site_title').')<br/>
	^site_url - the site url ('.qa_opt('site_url').')<br/>
	^if_logged_in=`text` - text to show if user is logged in.<br/>
	^if_not_logged_in=`text` - text to show if user is not logged in.<br/>
	^login - login url (for links)<br/>
	^register - register url (for links)<br/>
	^profile_url - current user\'s profile page url (for links)<br/>
	^handle - current user\'s username<br/>
	^qa_path(url) - the relative path for given url<br/>
	^qa_opt(option) - any q2a option that exists in the database<br/>
	^pointstable - a preformatted table of points awarded by activity<br/>
	^privilegestable - a preformatted table of points required for each privilege'
			);
			
			$sections = '<div id="qa-faq-sections">';

			$idx = 0;
			while(qa_opt('faq_section_'.$idx)) {
				$sections .='
<table id="qa-faq-section-table-'.$idx.'" width="100%">
	<tr>
		<td width="30">
			<input type="button" id="faq-up-button-'.$idx.'" value="-" style="width:30px" title="move section up" onclick="moveFaqSection('.$idx.','.($idx-1).')"'.($idx==0?' disabled':'').'>
			<br/><br/>
			<input type="button" id="faq-down-button-'.$idx.'" value="+" style="width:30px" title="move section down" onclick="moveFaqSection('.$idx.','.($idx+1).')"'.(!qa_opt('faq_section_'.($idx+1))?' disabled':'').'>
		</td>
		<td>
			<b>Faq section '.($idx+1).' title:</b><br/>
			<input class="qa-form-tall-text" type="text" value="'.qa_html(qa_opt('faq_section_'.$idx.'_title')).'" id="faq_section_'.$idx.'_title" name="faq_section_'.$idx.'_title"><br/><br/>
			<b>Faq section '.($idx+1).' content:</b><br/>
			<textarea class="qa-form-tall-text" rows="10" id="faq_section_'.$idx.'" name="faq_section_'.$idx.'">'.qa_html(qa_opt('faq_section_'.$idx)).'</textarea>
		</td>
	</tr>
</table>
<hr/>';

				$idx++;
			}
			$sections .= '</div>';

			$fields[] = array(
				'type' => 'static',
				'value' => $sections
			);


			$fields[] = array(
				'type' => 'static',
				'value' =>'
<script>
	var next_faq_section = '.$idx.'; 
	function addFaqSection(){
		jQuery("#qa-faq-sections").append(\'<table id="qa-faq-section-table-\'+next_faq_section+\'" width="100%"><tr><td width="30"><input type="button" id="faq-up-button-\'+next_faq_section+\'" value="-" style="width:30px" title="move section up" onclick="moveFaqSection(\'+next_faq_section+\',\'+(next_faq_section-1)+\')"\'+(next_faq_section==0?\' disabled\':\'\')+\'><br/><br/><input type="button" id="faq-down-button-\'+next_faq_section+\'" value="+" style="width:30px" title="move section down" onclick="moveFaqSection(\'+next_faq_section+\',\'+(next_faq_section+1)+\')" disabled></td><td>Faq section \'+(next_faq_section+1)+\' title<br/><input type="text" id="faq_section_\'+next_faq_section+\'_title" name="faq_section_\'+next_faq_section+\'_title"><br/><br/>Faq section \'+(next_faq_section+1)+\' content<br/><textarea class="qa-form-tall-text" rows="10" id="faq_section_\'+next_faq_section+\'" name="faq_section_\'+next_faq_section+\'"></textarea></td></tr></table><hr/>\');
		
		jQuery("#faq-down-button-"+(next_faq_section-1)).removeAttr("disabled");
		next_faq_section++;
		jQuery("input[name=faq_section_number]").val(next_faq_section);
	}
</script>
<input type="button" value="add section" onclick="addFaqSection()">'
			);

			$form['hidden']['faq_section_number'] = $idx;

			return array(           
				'ok' => ($ok && !isset($error)) ? $ok : null,
					
				'fields' => $fields,
				
				'hidden' => array(
							'faq_section_number' => $idx
				),
				 
				'buttons' => array(
					array(
					'label' => qa_lang_html('main/save_button'),
					'tags' => 'NAME="faq_save"',
					),
					array(
					'label' => qa_lang_html('admin/reset_options_button'),
					'tags' => 'NAME="faq_reset"',
					),
				),
			);
		}
    }