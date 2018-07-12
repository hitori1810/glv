<div id="pictureDialog" class="pictureDialog">
    <input style="margin-bottom:10px" type="file" class="image" id="{$fields.picture.name}" name="{$fields.picture.name}">
    <br>
    <div class="pictureContainer">
        {if $fields.picture.value == ""}
            <img src="index.php?entryPoint=getImage&themeName=default&imageName=default-picture.png" class="editorPreview" alt="">
        {else}
            <img src="index.php?entryPoint=download&id={$fields.picture.value}&type=SugarFieldImage&isTempFile=1" class="editorPreview" alt="" />
        {/if}
    </div>
    <div class="inputs">
        <input type="hidden" class="picture" name="picture">
        <input type="hidden" class="viewPortW" name="viewPortW">
        <input type="hidden" class="viewPortH" name="viewPortH">
        <input type="hidden" class="selectorX" name="selectorX">
        <input type="hidden" class="selectorY" name="selectorY">
        <input type="hidden" class="selectorW" name="selectorW">
        <input type="hidden" class="selectorH" name="selectorH">
        <input type="hidden" class="imageRotate" name="imageRotate">
        <input type="hidden" class="imageX" name="imageX">
        <input type="hidden" class="imageY" name="imageY">
        <input type="hidden" class="imageW" name="imageW">
        <input type="hidden" class="imageH" name="imageH">
    </div>
    <div class="buttons" style="margin-top:10px">
        <button type="button" class="btnRestore button">{$MOD.LBL_RESTORE}</button>
        <button type="button" class="btnCancelImage button">{$MOD.LBL_CANCEL_GO_BACK}</button>
        <button type="button" class="btnSaveImage button primary">{$MOD.LBL_SAVE_PICTURE}</button>
    </div>
</div>
<div class="imagePreview">
    {if $fields.picture.value == ""}
        <img src="index.php?entryPoint=getImage&themeName=default&imageName=default-picture.png" />
    {else}
        <img src="index.php?entryPoint=download&id={$fields.picture.value}&type=SugarFieldImage&isTempFile=1" />
    {/if}
</div>
<input id="remove_imagefile_{$fields.picture.name}" name="remove_imagefile_{$fields.picture.name}" type="hidden" db-data="">
<button class="button primary" type="button" id="btnUploadPicture" style="margin-top:10px">{$MOD.LBL_UPLOAD_PICTURE}</button>
{if $fields.picture.value != ""}<button class="button" type="button" id="btnRemove" style="margin-top:10px">{$MOD.LBL_REMOVE}</button>{/if}