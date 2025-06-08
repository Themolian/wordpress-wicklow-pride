<section class="countdown">
    <div class="countdown-inner">
        <?php if(is_front_page()) : ?>
            <?php if(get_field('countdown_text', 'options')) : ?>
                <h2><?php echo get_field('countdown_text', 'options'); ?></h2>
            <?php else: ?>
                <h2>We can't wait to see you!</h2>
            <?php endif; ?>
        <?php endif; ?>
        <div class="counter">
            <p><span class="num" id="days">days</span></p>
            <p><span class="num" id="hours">hours</span></p>
            <p><span class="num" id="minutes">minutes</span></p>
            <p><span class="num" id="seconds">seconds</span></p>
        </div>
    </div>
</section>

<script defer>
document.addEventListener('DOMContentLoaded', function() {
    // Parse the dates from PHP into JavaScript Date objects
    let startDate = new Date(<?php get_field('start_date') ?>)
    let endDate = new Date("<?php date('c') ?>")

    // Update the countdown every second
    let timer = setInterval(function() {
        // Calculate time difference
        let now = new Date().getTime();
        let distance = startDate - now;

        // Time calculations for days, hours, minutes, and seconds
        let days = Math.floor(distance / (1000 * 60 * 60 * 24));
        let hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        let minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        let seconds = Math.floor((distance % (1000 * 60)) / 1000);

        // Output the result in the elements with id="days", "hours", "minutes", "seconds"
        document.getElementById("days").innerHTML = days;
        document.getElementById("hours").innerHTML = hours;
        document.getElementById("minutes").innerHTML = minutes;
        document.getElementById("seconds").innerHTML = seconds;

        // If the countdown is over, stop the timer
        if (distance < 0) {
            clearInterval(timer);
            document.getElementById("days").innerHTML = 0;
            document.getElementById("hours").innerHTML = 0;
            document.getElementById("minutes").innerHTML = 0;
            document.getElementById("seconds").innerHTML = 0;
        }
    }, 1000);
})

{% endjs %}
</script>