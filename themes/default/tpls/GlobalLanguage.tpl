<!--Custom Add Global Language in Footer - Add by MTN - 08/05/2015-->
<div id="globalLanguage">
    <select id="global_language" name="global_language">
        {php}
        echo get_select_options_with_id(get_languages(), $_COOKIE['login_language']);
        {/php}
    </select>
</div>