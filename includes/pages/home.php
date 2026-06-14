<?php
session_start();
require '../includes/data.php';

// যদি ইউজার লগিন না থাকে, তবে ইনডেক্সে পাঠিয়ে দাও
if(!isset($_SESSION['tg_id'])) {
    header("Location: ../index.php");
    exit;
}

$tg_id = $_SESSION['tg_id'];

// ইউজার ডাটা তুলে আনা
$stmt = $pdo->prepare("SELECT * FROM users WHERE tg_id = ?");
$stmt->execute([$tg_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// সেটিংস থেকে Marquee লেখা আনা
$stmtSet = $pdo->query("SELECT top_marquee_text FROM settings WHERE id = 1");
$settings = $stmtSet->fetch(PDO::FETCH_ASSOC);
$marquee_text = $settings['top_marquee_text'] ?? "Welcome to our Mini App!";

// আপনার টেলিগ্রাম বটের ইউজারনেম এখানে দিন (যেমন: MyMoneyBot)
$bot_username = "YOUR_BOT_USERNAME"; 
$ref_link = "https://t.me/" . $bot_username . "?start=" . $user['ref_code'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Home</title>
    <style>
        body {
            margin: 0; padding: 0; background: #f3f4f6; font-family: 'Arial', sans-serif;
            color: #333; padding-bottom: 80px;
        }
        .marquee-box { background: #3b82f6; color: white; padding: 8px; font-size: 14px; font-weight: bold;}
        .profile-section {
            background: #fff; padding: 20px; text-align: center;
            border-bottom-left-radius: 20px; border-bottom-right-radius: 20px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }
        .profile-img { width: 80px; height: 80px; border-radius: 50%; border: 3px solid #3b82f6; }
        .balance { font-size: 28px; font-weight: bold; color: #10b981; margin: 10px 0; }
        
        .stats-container { display: flex; justify-content: space-between; padding: 15px; margin-top: 10px;}
        .stat-box {
            background: white; width: 48%; padding: 15px 0; text-align: center;
            border-radius: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        .stat-box h4 { margin: 0; color: #6b7280; font-size: 14px;}
        .stat-box p { margin: 5px 0 0 0; font-size: 20px; font-weight: bold; color: #3b82f6;}

        .ref-section { background: white; margin: 15px; padding: 15px; border-radius: 10px; text-align: center; }
        .ref-input {
            width: 90%; padding: 10px; margin-top: 10px; border: 1px solid #ccc;
            border-radius: 5px; background: #f9fafb; text-align: center; font-weight: bold; color: #555;
        }
        .copy-btn {
            margin-top: 10px; padding: 10px 20px; background: #3b82f6; color: white;
            border: none; border-radius: 5px; cursor: pointer; font-weight: bold;
        }

        /* Bottom Navigation */
        .bottom-nav {
            position: fixed; bottom: 0; left: 0; width: 100%;
            background: white; display: flex; justify-content: space-around;
            padding: 15px 0; box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
        }
        .bottom-nav a { text-decoration: none; color: #6b7280; font-weight: bold; font-size: 14px; text-align: center;}
        .bottom-nav a.active { color: #3b82f6; }
    </style>
</head>
<body>

    <!-- Marquee Text -->
    <div class="marquee-box">
        <marquee behavior="scroll" direction="left"><?php echo htmlspecialchars($marquee_text); ?></marquee>
    </div>

    <!-- Profile Section -->
    <div class="profile-section">
        <img src="<?php echo $user['photo_url']; ?>" alt="Profile" class="profile-img">
        <h3><?php echo htmlspecialchars($user['first_name']); ?></h3>
        <div class="balance">৳ <?php echo number_format($user['balance'], 4); ?></div>
        <p style="margin:0; color: #666; font-size: 14px;">Current Balance</p>
    </div>

    <!-- Stats Section -->
    <div class="stats-container">
        <div class="stat-box">
            <h4>সম্পূর্ণ কাজ</h4>
            <p><?php echo $user['total_tasks']; ?></p>
        </div>
        <div class="stat-box">
            <h4>বিজ্ঞাপন দেখা</h4>
            <p><?php echo $user['total_ads']; ?></p>
        </div>
    </div>

    <!-- Referral Section -->
    <div class="ref-section">
        <h4 style="margin-top:0;">আপনার রেফারেল লিংক</h4>
        <p style="font-size: 12px; color: #666;">বন্ধুদের ইনভাইট করে বোনাস পান!</p>
        <input type="text" id="refLink" class="ref-input" value="<?php echo $ref_link; ?>" readonly>
        <button class="copy-btn" onclick="copyRef()">Copy Link</button>
    </div>

    <!-- Bottom Navigation -->
    <div class="bottom-nav">
        <a href="home.php" class="active">🏠 হোম</a>
        <a href="tasks.php">📋 কাজ</a>
        <a href="ads.php">📺 বিজ্ঞাপন</a>
        <a href="withdraw.php">💳 উত্তোলন</a>
    </div>

    <script>
        function copyRef() {
            var copyText = document.getElementById("refLink");
            copyText.select();
            copyText.setSelectionRange(0, 99999); /* For mobile devices */
            navigator.clipboard.writeText(copyText.value);
            alert("Referral Link Copied!");
        }
    </script>
</body>
</html>
