<?php 
    $heading = get_field('newsletter_heading', 'options');
?>
<?php if($heading) : ?>
<section class="newsletter component">
    <div class="newsletter-inner">
        <h2><?php echo $heading ?></h2>
        <form method="post">
            <input type="hidden" name="tags" value="newsletter_signups">
            <div class="form-input">
                <label for="name">
                    <p>Name</p>
                    <input type="text" name="name" id="name">
                </label>
                <label for="email">
                    <p>Email</p>
                    <input type="email" name="email" id="email">
                </label>
            </div>
            <button class="button button--ghost button--ghost--border" type="submit">Submit</button>
        </form>
    </div>
</section>
<?php endif; ?>