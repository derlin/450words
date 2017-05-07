<?php
global $curpage;
?>


<div class="footer navbar-fixed-bottom navbar-inverse">
    <div class="text-center">
        <span><i class="fa fa-copyright"></i> 450 words </span>
        <span class="sep">|</span>
        <span><a href="mailto:lucy.derlin@gmail.com">contact the author</a></span>
        <?php if ($curpage == 'index') { ?>
            <span class="sep">|</span>
            <?= F::pretty($mydate) ?>
            <span class="sep">|</span>
            <!--<i class="fa fa-long-arrow-right"></i>-->
            <span class="wordCount">0</span> words.
        <?php } ?>
    </div>
</div>


<!-- script references -->
<script type="text/javascript" src="/js/jquery-1.11.3.min.js"></script>
<script type="text/javascript" src="/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/js/autosize.min.js"></script>
<script type="text/javascript" src="/js/counter.js"></script>
<script type="text/javascript" src="/js/autosave.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $("[data-toggle='tooltip']").tooltip();
    });
</script>
</body>
</html>