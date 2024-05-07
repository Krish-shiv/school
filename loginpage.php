<?php include('header.php') ?>

      <!-- CONTACT SECTION STARTS HERE -->
      <section class="tl-7-contact">
    <div class="container">
        <div class="row gy-4 gy-md-5 justify-content-between align-items-center">
            <div class="col-lg-6">
                <h2 class="tl-8-section-title">Login</h2>
                <form id="loginForm" class="tl-7-contact-form">
                    <div class="row g-3 g-md-4">
                        <div class="col-8">
                            <input type="text" name="username" id="username" placeholder="Username">
                        </div>

                        <div class="col-8">
                            <input type="password" name="password" id="password" placeholder="Password">
                        </div>

                        <div class="col-5">
                            <button type="button" class="tl-7-def-btn" onclick="login()">Login</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

    <!-- CONTACT SECTION ENDS HERE -->
<?php include('footer.php') ?>