<?php include('header.php') ?>
    <!-- HEADER SECTI0N ENDS HERE -->


    <!-- BREADCRUMB STARTS HERE -->
    <div class="tl-breadcrumb tl-breadcrumb-3 pt-120 pb-120">
        <div class="container">
            <div class="row align-items-end">
                <div class="col-md-6">
                    <div class="banner-txt">
                        <h1 class="tl-breadcrumb-title">Contact</h1>
                    </div>
                </div>

                <div class="col-md-6">
                    <ul class="tl-breadcrumb-nav d-flex">
                        <li><a href="index.php">Home</a></li>
                        <li class="current-page">
                            <span class="dvdr"><i class="icofont-simple-right"></i></span>
                            <span>Contact</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- BREADCRUMB ENDS HERE -->


    <!-- CONTACT SECTION STARTS HERE -->
    <section class="tl-7-contact">
        <div class="container">
            <div class="row gy-4 gy-md-5 justify-content-between align-items-center">
                <div class="col-lg-6">
                    <h2 class="tl-8-section-title">Get In Touch</h2>
                    <form action="#" class="tl-7-contact-form" onsubmit="emailSend(); return false;">
                        <div class="row g-3 g-md-4">
                            <div class="col-6 col-xxs-12">
                                <input type="text" name="stud-name" id="cl-name" placeholder="Your Name">
                            </div>

                            <div class="col-6 col-xxs-12">
                                <input type="email" name="stud-mail" id="cl-email-address" placeholder="Your Email">
                            </div>

                            <div class="col-6 col-xxs-12">
                                <input type="text" name="stud-age" id="cl-subject" placeholder="Your Subject">
                            </div>

                            <div class="col-6 col-xxs-12">
                                <input type="tel" name="stud-number" id="cl-number" placeholder="Your Number">
                            </div>

                            <div class="col-12">
                                <textarea name="stud-message" id="cl-ques" placeholder="Your Message"></textarea>
                            </div>

                            <div class="col">
                                <button type="submit" class="tl-7-def-btn">Send Message</button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="col-lg-6">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3576.2206885582373!2d83.85541667608013!3d26.319352485336854!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3993cb6f61935f95%3A0x19a701050852a894!2sNav%20Jeevan%20Jyoti%20Mission%20School!5e0!3m2!1sen!2sin!4v1710695880370!5m2!1sen!2sin" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
        </div>
    </section>
    <!-- CONTACT SECTION ENDS HERE -->


    <!-- FOOTER SECTION STARTS HERE -->
    <?php include('footer.php') ?>