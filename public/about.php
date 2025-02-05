<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us | Pet Care Platform</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<header>
    <h1>Pet Care Platform</h1>
    <?php session_start(); ?>
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
            <a href="signup.php">Sign Up</a>
        <?php endif; ?>
    </nav>
</header>

<section id="about">
    <h2>Welcome to Pet Care Platform</h2>
    <p>Our mission is to provide top-notch veterinary services through technology, making pet care more accessible and efficient.</p>
    <p>This project is being presented at a major event organized by <strong>E_JUSR with IEEE</strong>.</p>
</section>

<section id="team">
    <h2>Meet Our Team</h2>
    <p>We are a group of dedicated students from <b>Borg El Arab Technological University</b>, College of Applied Health Sciences Technology.</p>

    <div class="team-container">
        <div class="team-member">
            <h3>Mariam Rajab</h3>
            <p>Project Leader</p>
            <a href="tel:+201017323776">📞 Contact</a>
        </div>
        <div class="team-member">
            <h3>Yasmin Mohamed</h3>
            <p>Veterinary Researcher</p>
            <a href="tel:+201062214020">📞 Contact</a>
        </div>
        <div class="team-member">
            <h3>Asmaa Said</h3>
            <p>Health Consultant</p>
            <a href="tel:+201228850649">📞 Contact</a>
        </div>
        <div class="team-member">
            <h3>Hemam Abdelmohsen</h3>
            <p>Technology Developer</p>
            <a href="tel:+201278661049">📞 Contact</a>
        </div>
    </div>
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

<style>

.team-container {
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
    margin-top: 20px;
}

.team-member {
    text-align: center;
    width: 45%;
    padding: 15px;
    background: #f9f9f9;
    border-radius: 10px;
}

.team-member img {
    width: 100px;
    height: 100px;
    border-radius: 50%;
}


</style>

</body>
</html>
