<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pet Care Platform</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <h1>VetDirect</h1>
<?php
session_start();
?>

<nav>
    <a href="index.php">Home</a>
    <a href="about.php">About Us</a>
    <a href="services.php">Services</a>
    <a href="doctors.php">Our Veterinarians</a>
    <a href="../user/appointments.php">Book Appointment</a>
    <a href="../user/forum.php">Forum</a> 
    <a href="contact.php">Contact</a>


<a href="tel:+201017323776" class="floating-call">📞 Emergency Call</a>


    <?php if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true): ?>
        <?php if ($_SESSION['user_role'] === 'admin'): ?>
            <a href="../admin/dashboard.php">Admin Dashboard</a>
        <?php elseif ($_SESSION['user_role'] === 'doctor'): ?>
            <a href="../doctor/dashboard.php">Doctor Dashboard</a>
        <?php else: ?>
            <a href="../user/profile.php">Profile</a>
        <?php endif; ?>
        <a href="../backend/logout.php">Logout</a>
    <?php else: ?>
        <a href="login.php">Login</a>
        <a href="signup.php">Signup</a>
    <?php endif; ?>
</nav>

    </header>

    <section id="hero">
        <img src="images/hero-banner.jpg" alt="Happy pets with veterinarian" style="width: 70%;"> 
        <h2>Your Trusted Veterinary Platform</h2>
        <p>Book appointments, track vaccinations, and get expert advice for your pets.</p>
        <a href="signup.php" class="cta-button">Get Started</a>
    </section>

    <section id="features">
    <h2>Why Choose Us?</h2>
<br>

<div class="feature-item">
            <img src="images/appointment.jpg" alt="Online Appointment Booking">
            <h3>Easy Online Booking</h3>
            <p>Schedule appointments conveniently from your home.</p>
        </div>
        <div class="feature-item">
            <img src="images/vaccination.jpg" alt="Vaccination Reminders">
            <h3>Vaccination Reminders</h3>
            <p>Never miss your pet’s vaccinations with automated reminders.</p>
        </div>
        <div class="feature-item">
            <img src="images/expert-advice.jpg" alt="Expert Veterinary Advice">
            <h3>Expert Advice</h3>
            <p>Get guidance from certified veterinarians anytime.</p>
        </div>
    </section>

    <section id="testimonials">
        <h2>What Pet Owners Say</h2>
        <blockquote>
            <p>"Pet Care Platform made it so easy to book vet visits! My dog is healthier than ever!"</p>
            <cite>- Sarah, Dog Owner</cite>
        </blockquote>
        <blockquote>
            <p>"The vaccination reminders saved me so much hassle. Highly recommended!"</p>
            <cite>- Mark, Cat Owner</cite>
        </blockquote>
    </section>

    <section id="contact">
        <h2>Contact Us</h2>
        <p>Have questions? Reach out to us anytime.</p>
        <form action="contact-form.php" method="post">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <label for="message">Message:</label>
            <textarea id="message" name="message" required></textarea>
            <button type="submit">Send Message</button>
        </form>
    </section>


    <section id="library">
        <h2>Veterinary Medical Library</h2>
        <div class="library-content">
            <div class="library-item">
                <h3>Common Animal Diseases</h3>
                <p>Learn about symptoms, treatments, and prevention methods for common pet illnesses.</p>
            </div>
            <div class="library-item">
                <h3>Daily Care & Nutrition</h3>
                <p>Get expert recommendations on the best food, grooming, and daily routines for different pets.</p>
            </div>
            <div class="library-item">
                <h3>Pet Types & Care Guide</h3>
                <p>Detailed information on various pet species and how to provide the best care for them.</p>
            </div>
        </div>
    </section>
    
    <section id="emergency">
        <h2>Emergency Services</h2>
        <p>Need urgent help? Call our emergency vet clinics for immediate assistance.</p>
        <a href="tel:+201062214020" class="cta-button">Call Now</a>
    </section>

   



    <div id="chatbot" class="floating-chatbot">
    <button id="chatbot-toggle" onclick="toggleChatbot()">💬 Pet Diagnosis Bot</button>
    <div id="chatbot-box" class="chatbot-box">
        <h3>🐾 Pet Diagnosis</h3>
        <p>Describe your pet’s symptoms, and we’ll provide some initial advice!</p>
        <textarea id="chat-input" placeholder="Enter symptoms..."></textarea>
        <button onclick="sendChat()">Send</button>

        <h4>🔍 Common Questions</h4>
        <ul id="faq-list">
            <li onclick="quickReply('My dog is vomiting')">Why is my dog vomiting?</li>
            <li onclick="quickReply('My cat is not eating')">Why is my cat not eating?</li>
            <li onclick="quickReply('My rabbit has diarrhea')">What to do if my rabbit has diarrhea?</li>
            <li onclick="quickReply('My pet has a fever')">How to check if my pet has a fever?</li>
        </ul>

        <div id="chat-response"></div>
    </div>
</div>



    <footer>
        <p>&copy; 2025 Pet Care Platform. All Rights Reserved.</p>
    </footer>
    <script>
const responses = {
    "My dog is vomiting": "If your dog is vomiting frequently, ensure it stays hydrated. Avoid feeding for a few hours and offer small amounts of water. If symptoms persist, consult a vet.",
    "My cat is not eating": "A loss of appetite in cats can be caused by stress, illness, or dental issues. Try offering wet food. If your cat doesn't eat for more than 24 hours, visit a vet.",
    "My rabbit has diarrhea": "Diarrhea in rabbits is serious. Ensure fresh hay and avoid sugary foods. If the diarrhea continues, seek veterinary help immediately.",
    "My pet has a fever": "Check for a dry nose and warm ears. Normal pet temperature is 101-102.5°F (38-39°C). If fever is high, consult a vet."
};

function toggleChatbot() {
    let chatbotBox = document.getElementById('chatbot-box');
    chatbotBox.style.display = chatbotBox.style.display === 'block' ? 'none' : 'block';
}

function sendChat() {
    let input = document.getElementById('chat-input').value;
    let responseDiv = document.getElementById('chat-response');

    if (input in responses) {
        responseDiv.innerHTML = `<p>🤖 Bot: ${responses[input]}</p>`;
    } else {
        responseDiv.innerHTML = `<p>🤖 Bot: I don't have an answer for that. Please consult a vet.</p>`;
    }
}

function quickReply(question) {
    document.getElementById('chat-input').value = question;
    sendChat();
}

    </script>

    <script>
const responses = {
    "My dog is vomiting": "If your dog is vomiting frequently, ensure it stays hydrated. Avoid feeding for a few hours and offer small amounts of water. If symptoms persist, consult a vet.",
    "My cat is not eating": "A loss of appetite in cats can be caused by stress, illness, or dental issues. Try offering wet food. If your cat doesn't eat for more than 24 hours, visit a vet.",
    "My rabbit has diarrhea": "Diarrhea in rabbits is serious. Ensure fresh hay and avoid sugary foods. If the diarrhea continues, seek veterinary help immediately.",
    "My pet has a fever": "Check for a dry nose and warm ears. Normal pet temperature is 101-102.5°F (38-39°C). If fever is high, consult a vet."
};

function toggleChatbot() {
    let chatbotBox = document.getElementById('chatbot-box');
    chatbotBox.style.display = chatbotBox.style.display === 'block' ? 'none' : 'block';
}

function sendChat() {
    let input = document.getElementById('chat-input').value;
    let responseDiv = document.getElementById('chat-response');

    if (input in responses) {
        responseDiv.innerHTML = `<p>🤖 Bot: ${responses[input]}</p>`;
    } else {
        responseDiv.innerHTML = `<p>🤖 Bot: I don't have an answer for that. Please consult a vet.</p>`;
    }
}

function quickReply(question) {
    document.getElementById('chat-input').value = question;
    sendChat();
}

    </script>

    
</body>
</html>