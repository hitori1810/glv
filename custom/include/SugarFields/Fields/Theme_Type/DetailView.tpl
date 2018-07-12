{*<div class="theme-option">

                        <div class="option option-1"><label><input type="radio" name='theme' value="theme1"
                                                                   {if $survey->theme eq 'theme1'}checked="checked"{/if}
                                                                   checked="checked"> Theme 1</label></div>
                        <div class="option option-2"><label><input type="radio" name='theme' value="theme2"
                                                                   {if $survey->theme eq 'theme2'}checked="checked"{/if}> Theme
                                2</label></div>
                        <div class="option option-3"><label><input type="radio" name='theme' value="theme3"
                                                                   {if $survey->theme eq 'theme3'}checked="checked"{/if}> Theme
                                3</label></div>
                        <div class="option option-4"><label><input type="radio" name='theme' value="theme4"
                                                                   {if $survey->theme eq 'theme4'}checked="checked"{/if}> Theme
                                4</label></div>
                    </div>
{*
/*********************************************************************************
 * SugarCRM Community Edition is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.
 * 
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation with the addition of the following permission added
 * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
 * IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
 * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
 * 
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE.  See the GNU Affero General Public License for more
 * details.
 * 
 * You should have received a copy of the GNU Affero General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 * 
 * You can contact SugarCRM, Inc. headquarters at 10050 North Wolfe Road,
 * SW2-130, Cupertino, CA 95014, USA. or at email address contact@sugarcrm.com.
 * 
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU Affero General Public License version 3.
 * 
 * In accordance with Section 7(b) of the GNU Affero General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "Powered by
 * SugarCRM" logo. If the display of the logo is not reasonably feasible for
 * technical reasons, the Appropriate Legal Notices must display the words
 * "Powered by SugarCRM".
 ********************************************************************************/

*}
{*
/**
 * Theme Selection DetailView
 * 
 * LICENSE: The contents of this file are subject to the license agreement ("License") which is included
 * in the installation package (LICENSE.txt). By installing or using this file, you have unconditionally
 * agreed to the terms and conditions of the License, and you may not use this file except in compliance
 * with the License.
 *
 * @author     Original Author Biztech Co.
 */
*}
<div class="theme-option">
{if isset({{sugarvar key='value' string=true}}) && {{sugarvar key='value' string=true}} == 'theme1'}<div class="option option-1"><label><input type="radio" name='theme' value="theme1"
    checked="checked"> Theme 1</label></div>{/if}
{if isset({{sugarvar key='value' string=true}}) && {{sugarvar key='value' string=true}} == 'theme2'}<div class="option option-2"><label><input type="radio" name='theme' value="theme2"
    checked="checked"> Theme 2</label></div>{/if}
{if isset({{sugarvar key='value' string=true}}) && {{sugarvar key='value' string=true}} == 'theme3'}<div class="option option-3"><label><input type="radio" name='theme' value="theme3"
    checked="checked"> Theme 3</label></div>{/if}
{if isset({{sugarvar key='value' string=true}}) && {{sugarvar key='value' string=true}} == 'theme4'}<div class="option option-4"><label><input type="radio" name='theme' value="theme4"
    checked="checked"> Theme 4</label></div>{/if}
</div>
