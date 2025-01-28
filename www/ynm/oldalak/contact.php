
<!-- Contact Us Section -->
<div class="container mt-5">
    <h2>Contact Us</h2>
    <p>If you need help or want to get in touch, here are some places you can reach us:</p>

    <ul>
        <li><strong>IRC:</strong> You can find us on our IRC server at <a href="irc://irc.ynm.hu">irc://irc.ynm.hu</a></li>
        <li><strong>GitHub:</strong> Visit our GitHub repository for the project code and more details: <a href="https://github.com/ynmhu/YnMEggWeb" target="_blank">YnMEggWeb GitHub</a></li>
        <li><strong>Forum:</strong> Join the discussion or ask questions on our forum: <a href="https://forum.ynm.hu/t/ynm-egg-web-eggdrop-bot-script/276" target="_blank">YNM Egg Web Forum</a></li>
    </ul>

    <hr>

    <h3>Send Us a Message</h3>
    <form action="send_message.php" method="POST">
        <div class="mb-3">
            <label for="name" class="form-label">Your Name</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Your Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="mb-3">
            <label for="message" class="form-label">Message</label>
            <textarea class="form-control" id="message" name="message" rows="4" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Send Message</button>
    </form>
</div>
