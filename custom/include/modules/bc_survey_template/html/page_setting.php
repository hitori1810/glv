<?php
/**
 * 
 * LICENSE: The contents of this file are subject to the license agreement ("License") which is included
 * in the installation package (LICENSE.txt). By installing or using this file, you have unconditionally
 * agreed to the terms and conditions of the License, and you may not use this file except in compliance
 * with the License.
 *
 * @author     Original Author Biztech Co.
 */
$que_number = $_REQUEST['que_number'];
?>
<div id="page_<?php echo $page_number; ?>" class="survey-add-page">
    <div width = '100%' border = '0' cellspacing = '0' cellpadding = '0' id = 'questionlist' class = 'survey-add-inner'> 
	<div class="survey-title">
	
	<div class="f-left"><label  id = 'pagetitle_label' >Page Title:<em>*</em></label><span><input class="input-text" type='text' name='page_title[<?php echo $page_number; ?>]' id='page_title'>
        <input class="input-text" type='hidden' name='page_number[<?php echo $page_number; ?>]' id='page_number<?php echo $page_number; ?>' style="width: 37.5%" value="<?php echo $page_number; ?>"></span>
	</div>
	<div class="f-right">
		<input class="remove-btn" type='button'  name='removepage' value='Remove Page' id='removepage'  onclick="remove_page(this);">
	</div>
		
	</div>
	<div class="survey-body">
		<div class="s-row" id="questionline<?php echo $page_number; ?>">
                <ul  id="question_table<?php echo $que_number ?>" class=qt_table1'>
					<li class="s-title"><label>Question<em>*</em></label><span><input  class="input-text que_title" type='text' name='que_title[<?php echo $page_number; ?>][<?php echo $que_number; ?>]'></span></li>
                                        <li class="s-title"><label>Help Tips</label><span><input  class="input-text question_help_comment" type='text' name='question_help_comment[<?php echo $page_number; ?>][<?php echo $que_number; ?>]'></span></li>
					<li class="s-type"><label>Answer Type</label><span><select onfocus="javascript:window.selectedType = this.value;" class="input-text" name="que_type[<?php echo $page_number; ?>][<?php echo $que_number; ?>]" id="que_type<?php echo $page_number; ?><?php echo $que_number; ?>" onchange="displayAnsOption(<?php echo $page_number; ?>,<?php echo $que_number; ?>,this,window.selectedType)">
                                <option value="Textbox">Textbox</option>
                                <option value="CommentTextbox">Comment Textbox</option>
                                <option value="Checkbox">Checkbox</option>
                                <option value="RadioButton">Radio Button</option>
                                <option value="DrodownList"> DrodownList </option>
                                <option value="MultiSelectList">MultiSelectList</option>
                                <option value="ContactInformation">Contact Information</option>
                                <option value="Rating">Rating</option>
                            </select></span>
					</li>
					<li class="s-required">
						<label>Is required</label>
						<span><input type="checkbox" name="is_required[<?php echo $page_number; ?>][<?php echo $que_number; ?>]" id="is_required"></span>
					</li>
                    <li class="s-add-que"><label>Questions</label><span><a class="add-btn-design add-btn<?php echo $page_number; ?>" href="javascript:void(0)"  id="add_que<?php echo $que_number; ?>" onclick="addQuestion(<?php echo $page_number; ?>, this);" title="Add Question">Add Question</a><a class='remove-btn-1-design remove-btn-1<?php echo $page_number; ?>' href='javascript:void(0)' id='remove_que' onclick='removeQuestion(this,<?php echo $page_number; ?>,<?php echo $que_number; ?>);' style="display: none" title="Remove Question">Remove</a></span></li>
					<li class="s-option">
					<ul id="answer_div<?php echo $page_number; ?><?php echo $que_number; ?>"></ul>
					</li>
				</ul>
		</div>
	</div>

        
        
        </div>
</div>   
<script type="text/javascript">
    var hidden_fields = "<input type='hidden' id='last_que_no_<?php echo $page_number ?>' value='0'>\n\
                  <input type='hidden' id='last_ans_no_<?php echo $que_number ?><?php echo $page_number ?>' value='0'>";
   $('#EditView_tabs').append(hidden_fields);
</script>
