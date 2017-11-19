<div class='row'>
    <div class='col-md-12'>
        <?php
         $fa_cross = font_awesome('fa-exclamation-triangle');
         echo alertbuilder("$fa_cross Payment failed.",'danger');
         ?>
    </div>
</div>
<script>
    window.setTimeout(function(){
        window.location.href = '?view=home';
    },1000 * 5);
</script>