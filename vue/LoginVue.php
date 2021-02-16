<?php

class LoginVue {

  /**
  * Method to display the Login vue.
  * @param error Error to display, by default NULL.
  **/
  public function vue($error = NULL) { ?>
    <!DOCTYPE html>
    <html lang="en">
      <head>
        <title>2048 - Login</title>
        <meta charset="utf-8" />
        <meta name="description" content="2048 full php login page made by Emilien Bidet.
                                          Log in to your account by entering your login and your password !">
        <link rel="stylesheet" href="vue/css/2048.css"/>
        <link rel="stylesheet" href="vue/css/account.css"/>
      </head>
      <body>
        <?php include("header.php"); ?>
        <div class="content">
          <div class="account">
            <div class="account-title">
              <h2>Login</h2>
            </div>
            <hr>
            <div class="acccount-form">
              <form action="" method="post">
                <label for="login">Login</label>
                <input name="login" type="text" placeholder="ex : emilien" required>
                <label for="password">Password</label>
                <input name="password" type="password" placeholder="ex : xD58f!s7d" required>
                <?php if($error != NULL) { ?>
                <p class="error"><?php echo $error; ?></p>
                <?php } ?>
                <button type="submit" value="login">Login</button>
              </form>
              <hr>
              <form action="" method="get">
                <button type="submit" value="register" name="page">Register</button>
              </form>
            </div>
          </div>
        </div>
        <?php include("footer.php"); ?>
      </body>
    </html>
  <?php }
} ?>
