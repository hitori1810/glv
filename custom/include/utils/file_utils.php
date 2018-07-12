<?php
    function deleteFileInForder($path, $maxfile) {        
        $fi = new FilesystemIterator($path, FilesystemIterator::SKIP_DOTS);
        if(iterator_count($fi) > $maxfile) {
            array_map('unlink', glob($path."/*"));
        }
    }
?>
