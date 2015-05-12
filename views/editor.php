<script src="ckeditor/ckeditor.js"></script>
<script src="ckeditor/samples/assets/uilanguages/languages.js"></script>
<script>
    window.onload = function() {
        CKEDITOR.replace( '<?=$nomeEditor?>', {height: '400px'});
    };
</script>
<textarea id="<?=$nomeEditor?>" name="<?=$nomeEditor?>" class="col-sm-12" rows="10"><?=$conteudoEditor?></textarea>
