<?php
declare(strict_types=1);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Information Form</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
</head>
<body>
    <main>
        <h1>Staff Information Form</h1>
        <p>Please complete the form below.</p>

        <form action="process.php" method="post" novalidate>
            <div>
                <label for="name">Full Name</label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    required
                    maxlength="100"
                    placeholder="Enter your full name"
                >
            </div>

            <div>
                <label for="email">Email Address</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    required
                    maxlength="150"
                    placeholder="Enter your email address"
                >
            </div>

            <div>
                <label for="bio">Personal Biography</label>
                <textarea
                    id="bio"
                    name="bio"
                    rows="5"
                    maxlength="1000"
                    placeholder="Write a short personal biography"
                ></textarea>
            </div>

            <div>
                <label for="department">Department</label>
                <select id="department" name="department" required>
                    <option value="">-- Please Select --</option>
                    <option value="IT">IT</option>
                    <option value="HR">HR</option>
                    <option value="Marketing">Marketing</option>
                    <option value="Finance">Finance</option>
                </select>
            </div>

            <fieldset>
                <legend>Gender</legend>

                <label for="gender_male">
                    <input type="radio" id="gender_male" name="gender" value="Male" required>
                    Male
                </label>

                <label for="gender_female">
                    <input type="radio" id="gender_female" name="gender" value="Female">
                    Female
                </label>

                <label for="gender_other">
                    <input type="radio" id="gender_other" name="gender" value="Other">
                    Other
                </label>
            </fieldset>

            <div>
                <label for="agree_terms">
                    <input type="checkbox" id="agree_terms" name="agree_terms" value="1" required>
                    I agree to the terms and conditions
                </label>
            </div>

            <div>
                <button type="submit">Submit</button>
                <button type="reset">Reset</button>
            </div>
        </form>
    </main>

    <footer><p>CISC3003 Web Programming: Zhang Jieding dc22892 2026</p></footer>
</body>
</html>
