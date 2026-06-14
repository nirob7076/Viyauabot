<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Loading...</title>
    <!-- টেলিগ্রাম ওয়েব অ্যাপ স্ক্রিপ্ট -->
    <script src="https://telegram.org/js/telegram-web-app.js"></script>
    <style>
        body {
            margin: 0; background: #0f172a; color: #fff;
            display: flex; justify-content: center; align-items: center;
            height: 100vh; font-family: sans-serif;
        }
        .loader {
            text-align: center;
        }
        .spinner {
            width: 40px; height: 40px; border: 4px solid #3b82f6;
            border-top: 4px solid transparent; border-radius: 50%;
            animation: spin 1s linear infinite; margin: 0 auto 15px auto;
        }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
    </style>
</head>
<body>
    <div class="loader">
        <div class="spinner"></div>
        <h2>Loading...</h2>
    </div>

    <script>
        const tg = window.Telegram.WebApp;
        tg.expand(); // অ্যাপটিকে ফুলস্ক্রিন করা

        // ইউজারের টেলিগ্রাম ইনফো নেওয়া
        const user = tg.initDataUnsafe?.user;

        if (user) {
            // ডাটা auth.php তে পাঠানো
            fetch('api/auth.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(user)
            })
            .then(res => res.json())
            .then(data => {
                if(data.status === 'success') {
                    // লগিন সফল হলে হোম পেজে রিডাইরেক্ট
                    window.location.href = 'pages/home.php';
                } else {
                    alert("Authentication Failed!");
                }
            }).catch(err => {
                alert("Network Error!");
            });
        } else {
            document.body.innerHTML = "<h3 style='text-align:center;'>Please open this app from Telegram.</h3>";
        }
    </script>
</body>
</html>
