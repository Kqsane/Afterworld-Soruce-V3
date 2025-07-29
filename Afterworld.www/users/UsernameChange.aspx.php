<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/config/main.php';
if(!isset($_COOKIE["_ROBLOSECURITY"])){header("Location: /newlogin");exit();}
$info = getuserinfo($_COOKIE["_ROBLOSECURITY"]);
if(!$info){logout();header("Location: /");exit();}
?>
<html>
<head>
    <title>Change Username</title>
    <link rel="icon" href="/favicon.ico" />
    <script src="//ajax.aspnetcdn.com/ajax/4.0/1/MicrosoftAjax.js"></script>
    <script src="//ajax.aspnetcdn.com/ajax/jQuery/jquery-1.7.2.min.js"></script>
    <script src="/js/jquery/jquery-1.7.2.min.js"></script>
    <script src="/js/GenericConfirmation.js"></script>
    <style>
        body {
            margin: 0;
            background: white;
            font-family: Verdana, sans-serif;
            font-size: 14px;
        }
        .topbar {
            height: 28px;
            background: #0074bd;
            padding: 0 10px;
            display: flex;
            align-items: center;
        }
        .topbar img {
            height: 18px;
        }
        .container {
            background: #f2f2f2;
            border: 1px solid #ccc;
            width: 550px;
            margin: 40px auto;
            padding: 20px;
            box-sizing: border-box;
        }
        .form-label {
            display: block;
            font-weight: bold;
            margin-bottom: 6px;
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 5px;
            border: 1px solid #aaa;
            box-sizing: border-box;
        }
        .btn-large {
            margin-top: 20px;
            padding: 8px 16px;
            font-size: 14px;
            background-color: #0074bd;
            color: white;
            border: none;
            cursor: pointer;
        }
        .btn-disabled-neutral {
            background-color: #ccc !important;
            cursor: not-allowed;
        }
        .validator-checkmark {
            display: none;
            color: green;
            font-weight: bold;
            margin-left: 5px;
        }
        .text-danger {
            color: #a94442;
            font-size: 12px;
            margin-top: 4px;
        }
        h1 {
            text-align: center;
            margin-top: 20px;
        }
        .status-confirm {
            color: green;
        }
        .status-error {
            color: #a94442;
        }
    </style>
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        const usernameInput = document.getElementById("username");
        const resultDisplay = document.querySelector(".validator-checkmark");
        const resultText = document.querySelector(".text-danger");
        const usernamebutton = document.getElementById("changeusername");

        function isValidUsername(username) {
            return /^[a-zA-Z0-9]+$/.test(username);
        }

        function checkUsernameLength(username) {
            return username.length >= 3 && username.length < 20;
        }

        usernameInput.addEventListener("input", async function () {
            const rawUsername = usernameInput.value.trim();

            if (!isValidUsername(rawUsername)) {
                resultDisplay.style.display = "none";
                resultText.style.display = "block";
                usernamebutton.classList.remove("btn-large");
                usernamebutton.classList.add("btn-disabled-neutral");
                resultText.textContent = "Username contains special characters";
                usernamebutton.disabled = true;
                return;
            }
            if (!checkUsernameLength(rawUsername)) {
                resultDisplay.style.display = "none";
                resultText.style.display = "block";
                usernamebutton.classList.remove("btn-large");
                usernamebutton.classList.add("btn-disabled-neutral");
                resultText.textContent = "Username must be in between 3-20 characters.";
                usernamebutton.disabled = true;
                return;
            }

            try {
                const response = await fetch(`/UserCheck/checkifinvalidusernameforsignup?username=${encodeURIComponent(rawUsername)}`);
                const data = await response.json();

                if (data.data === 0) {
                    resultDisplay.style.display = "inline-block";
                    resultText.style.display = "none";
                    usernamebutton.classList.remove("btn-disabled-neutral");
                    usernamebutton.classList.add("btn-large");
                    usernamebutton.disabled = false;
                } else {
                    resultDisplay.style.display = "none";
                    resultText.style.display = "block";
                    usernamebutton.classList.remove("btn-large");
                    usernamebutton.classList.add("btn-disabled-neutral");
                    usernamebutton.disabled = true;
                    if (data.data === 1) {
                        resultText.textContent = "Username is taken.";
                    } else if (data.data === 2) {
                        resultText.textContent = "Username is inappropriate.";
                    }
                }
            } catch (error) {
                resultDisplay.style.display = "none";
            }
        });
    });
    </script>
</head>
<body>
    <div class="topbar">
        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/c/cb/Roblox_logo.svg/2560px-Roblox_logo.svg.png" alt="Roblox Logo">
    </div>
    <h1>Change Username</h1>
    <center><p><span class="status-confirm">Username changing is under development, and issues may happen.</span></p></center>
    <?php
    include_once $_SERVER['DOCUMENT_ROOT'] . '/config/miscfunctions.php';
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $swearWords = loadSwearWords($_SERVER['DOCUMENT_ROOT'] . '/config/includes/swearwords.txt');
        $username = trim($_POST["username"]);
        $password = $_POST["password"];
        if (password_verify($password, $info["Password"])) {
            $FindUser = $pdo->prepare('SELECT * FROM users WHERE Username = :username');
            $FindUser->execute(['username' => htmlspecialchars($username)]);
            $row = $FindUser->fetch(PDO::FETCH_ASSOC);
            if ($row) { echo('<center><p><span class="status-error">Username is taken</span></p></center>'); return; }
            if ($username === $info["Username"]) { echo('<center><p><span class="status-error">You can&#39;t repeat your username</span></p></center>'); return; }
            if (containsSwearWords($username, $swearWords) || $username == "LocalPlayer") { echo('<center><p><span class="status-error">Swear words arent allowed buddy.</span></p></center>'); return; }
            if (!preg_match("/^[a-zA-Z0-9]+$/", $username)) { echo('<center><p><span class="status-error">Usage of special characters arent allowed...</span></p></center>'); return; }
            if (strlen($username) <= 2 || strlen($username) >= 20) { echo('<center><p><span class="status-error">Username must be in between 3-20 characters.</span></p></center>'); return; }
            $getPreviousStmt = $pdo->prepare("SELECT previousUsernames FROM users WHERE UserId = :userid");
            $getPreviousStmt->execute(['userid' => $info["UserId"]]);
            $prevData = $getPreviousStmt->fetch(PDO::FETCH_ASSOC);
            $previousUsernames = [];
            if (!empty($prevData['previousUsernames'])) {
                $decoded = json_decode($prevData['previousUsernames'], true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    $previousUsernames = $decoded;
                }
            }
            $previousUsernames[] = $info["Username"];
            $updateQuery = $pdo->prepare("UPDATE users SET Username = :username, previousUsernames = :previousUsernames WHERE UserId = :userid");
            $updateQuery->bindParam(':username', $username);
            $updateQuery->bindValue(':previousUsernames', json_encode($previousUsernames), PDO::PARAM_STR);
            $updateQuery->bindParam(':userid', $info["UserId"]);
            $updateSuccess = $updateQuery->execute();
            if ($updateSuccess) { echo("<script type='text/javascript'>close()</script>"); exit(); }
            else { echo('<center><p><span class="status-error">An Unknown Error happened while changing your username.</span></p></center>'); }
        } else {
            echo('<center><p><span class="status-error">Incorrect Password</span></p></center>'); return;
        }
    }
    ?>
    <form action="/users/UsernameChange.aspx" method="post">
        <div class="container">
            <label class="form-label">Desired Username:</label>
            <input type="text" id="username" name="username" placeholder="<?= htmlspecialchars($info['Username']) ?>" />
            <span class="validator-checkmark">?</span>
            <div class="text-danger" style="display:none;">Error</div>
            <br><span style="font-size:12px;">3-20 letters & numbers</span>
            <br><br>
            <label class="form-label">Password:</label>
            <input type="password" id="password" name="password" />
            <input type="submit" id="changeusername" class="btn-large btn-disabled-neutral" disabled value="Change">
        </div>
    </form>
</body>
</html>
