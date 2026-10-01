<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="icon" type="image/png" href="{{ asset('assets/images/favicon.png') }}" />

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

</head>


<body>

    <img src="{{ asset('assets/images/cursor.png') }}" class="cursor" />

    <!-- Preloader -->
    <div id="preloader-active">
        <div class="preloader d-flex align-items-center justify-content-center">
            <div class="preloader-inner position-relative">
                <div class="preloader-circle"></div>
                <div class="preloader-img pere-text">
                    <img src="{{ asset('assets/images/loder-logo.png') }}" alt="Loading...">
                </div>
            </div>
        </div>
    </div>
    <!-- Preloader -->


    <!-- استبدل مكتبة الأيقونات -->
    <link rel="stylesheet" href="https://unpkg.com/phosphor-icons/css/phosphor.css">




    <nav>
        <!-- Checkbox for toggling menu -->
        <input type="checkbox" id="check" name="check">

        <!-- Menu icon -->
        <label for="check" class="checkbtn">
            <i class="fas fa-bars"></i>
        </label>

        <!-- Site logo -->
        <label class="logo">

            <!-- 🛠️ قمنا بإضافة وتغيير الحجم من هنا في السطر الأول -->
            <svg width="50px" height="50px" viewBox="0 0 64 64" xmlns="http://www.w3.org/2000/svg" fill="none">
                <!-- Outer hex shield (الدرع السداسي الخارجي) -->
                <path d="M32 4 L56 18 L56 46 L32 60 L8 46 L8 18 Z" fill="url(#classicGrad)" opacity="0.12" />
                <path d="M32 4 L56 18 L56 46 L32 60 L8 46 L8 18 Z" stroke="url(#classicGrad)" stroke-width="2"
                    stroke-linejoin="round" />

                <!-- Lightning bolt (رمز الصاعقة الداخلي) -->
                <path d="M36 14 L22 34 L30 34 L26 50 L42 28 L34 28 L38 14 Z" fill="url(#classicGrad)" stroke="#ff6a00"
                    stroke-width="0.5" stroke-linejoin="round" fill="#fff" />

                <!-- Circuit dots (الدوائر الإلكترونية - تم تحويلها لرمادي كلاسيكي) -->
                <circle cx="14" cy="20" r="2.5" fill="#8e8e93" />
                <circle cx="50" cy="20" r="2.5" fill="#8e8e93" />
                <circle cx="14" cy="44" r="2.5" fill="#8e8e93" />
                <circle cx="50" cy="44" r="2.5" fill="#8e8e93" />

                <defs>
                    <!-- التدرج الكلاسيكي الجديد (من الرمادي الداكن القريب للأسود إلى الرمادي الفاتح) -->
                    <linearGradient id="classicGrad" x1="8" y1="4" x2="56" y2="60"
                        gradientUnits="userSpaceOnUse">
                        <stop offset="0" stop-color="fff" /> <!-- رمادي غامق جداً (قريب للأسود) -->
                        <stop offset="0.5" stop-color="#fff" /> <!-- رمادي متوسط -->
                        <stop offset="1" stop-color="fff" /> <!-- رمادي فاتح -->
                    </linearGradient>
                </defs>
            </svg>



            <span class="name-logo"> البلاد للكهربائيات </span>
        </label>


        <!-- Navigation links -->
        <ul>
            <li><a href="{{ route('Main') }}">الرئيسية</a></li>
            <li><a href="">تواصل معنا</a></li>
            <li><a href="">من نحن</a></li>

            @auth
                <li>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit">خروج</button>
                    </form>
                </li>
            @endauth
        </ul>
    </nav>

    @yield('myapp')

    <link rel="stylesheet" href="{{ asset('assets/css/payment-cards.css') }}">

    <footer>
        <div class="footer-content">
            <div class="footer-copyright">
                <p><span class="copyright-text">&copy;</span> 2025 El-BelaadCables Interface. All quantum rights
                    reserved across dimensions.</p>
            </div>
            <div class="footer-design">
                Design : <a href="https://templatemo.com" target="_blank" rel="nofollow noopener">TemplateMo</a> |
                Enhanced by Bolt AI Systems |
                <a href="https://linkedin.com/in/abdullah-al-shatry">Abdullah Alawi</a>
            </div>


            <div class="footer-payments">
                <div class="payment-cards" aria-label="طرق الدفع المتاحة">
                    <div class="payment-card payment-card-paypal">
                        <span class="payment-card-brand">PayPal</span>
                        <span class="payment-card-caption">دفع إلكتروني</span>
                    </div>
                    <div class="payment-card payment-card-mastercard">
                        <span class="payment-card-symbol"><i></i><i></i></span>
                        <span class="payment-card-brand">mastercard</span>
                        <span class="payment-card-caption">بطاقات مصرفية</span>
                    </div>
                    <div class="payment-card payment-card-visa">
                        <span class="payment-card-brand">VISA</span>
                        <span class="payment-card-caption">بطاقات مصرفية</span>
                    </div>
                    <div class="payment-card payment-card-amex">
                        <span class="payment-card-brand">AMERICAN<br>EXPRESS</span>
                        <span class="payment-card-caption">دفع عالمي</span>
                    </div>
                    <div class="payment-card payment-card-tabby">
                        <span class="payment-card-brand">tabby</span>
                        <span class="payment-card-caption">اشترِ الآن وادفع لاحقاً</span>
                    </div>
                    <div class="payment-card payment-card-tamara">
                        <span class="payment-card-brand">تمارا</span>
                        <span class="payment-card-caption">تقسيط مرن</span>
                    </div>
                </div>
            </div>

        </div>
    </footer>





    {{-- ========================= --}}
    {{-- Contact Modal            --}}
    {{-- ========================= --}}
    <form action=" {{ Route('main.message') }} " method="POST" id="contactForm">
        @csrf
        <div class="modal-overlay" id="contactModal">
            <div class="modal-box">
                <button type="button" class="modal-close" data-close="contactModal">&times;</button>

                <div class="modal-form-wrapper" id="contactFormWrapper">
                    <h2 class="modal-title">تواصل معنا</h2>
                    <p class="modal-subtitle">نسعد بتلقي استفساراتك واقتراحاتك</p>

                    <div class="modal-field">
                        <label for="contactEmail">البريد الإلكتروني</label>
                        <input type="email" id="contactEmail" name="email" placeholder="example@email.com"
                            required>
                    </div>

                    <div class="modal-field">
                        <label for="contactSubject">عنوان الرسالة</label>
                        <input type="text" id="contactSubject" name="subject" placeholder="اكتب عنوان رسالتك هنا"
                            required>
                    </div>

                    <div class="modal-field">
                        <label for="contactMessage">محتوى الرسالة</label>
                        <textarea id="contactMessage" name="message" placeholder="اكتب رسالتك بالتفصيل هنا..." required></textarea>
                    </div>

                    <button type="submit" class="modal-send-btn">
                        <span>إرسال</span>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="22" y1="2" x2="11" y2="13" />
                            <polygon points="22 2 15 22 11 13 2 9 22 2" />
                        </svg>
                    </button>
                </div>

                <div class="modal-success" id="contactSuccess">
                    <div class="success-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"
                            stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="20 6 9 17 4 12" />
                        </svg>
                    </div>
                    <div class="success-text">تم إرسال رسالتك بنجاح</div>
                    <div class="success-sub">سنقوم بالرد عليك في أقرب وقت ممكن</div>
                </div>
            </div>
        </div>
    </form>

    {{-- ========================= --}}
    {{-- About Modal              --}}
    {{-- ========================= --}}
    <div class="modal-overlay" id="aboutModal">
        <div class="modal-box">
            <button class="modal-close" data-close="aboutModal">&times;</button>

            <div class="about-content">
                <div class="about-highlight">البلاد للكهربائيات</div>
                <p>
                    منذ تأسيسنا قبل أكثر من خمسة عشر عاماً، ونحن نسعى لأن نكون الوجهة الأولى
                    لكل من يبحث عن الجودة والموثوقية في عالم الكهربائيات. بدأت رحلتنا من
                    متجر صغير في قلب المدينة، وتطورنا اليوم لنخدم آلاف العملاء في جميع
                    أنحاء البلاد.
                </p>
                <p>
                    نوفّر تشكيلة واسعة من المنتجات الكهربائية المختارة بعناية، من الكابلات
                    والمفاتيح إلى أنظمة الإنارة الحديثة وأدوات السلامة. كل منتج في متجرنا
                    يخضع لاختبارات صارمة لضمان أعلى معايير الأداء والمتانة.
                </p>
                <p>
                    فريقنا مؤلف من مهندسين وفنيين متخصصين لا يقتصر دورهم على البيع فحسب،
                    بل يقدّمون الاستشارة الفنية المناسبة لكل مشروع، صغيراً كان أم كبيراً.
                    نؤمن بأن علاقتنا مع العميل تبدأ بعد الشراء، لا قبله.
                </p>

                <div class="about-stats">
                    <div class="about-stat">
                        <span class="stat-number">+15</span>
                        <span class="stat-label">سنة خبرة</span>
                    </div>
                    <div class="about-stat">
                        <span class="stat-number">+5000</span>
                        <span class="stat-label">منتج متنوع</span>
                    </div>
                    <div class="about-stat">
                        <span class="stat-number">+12000</span>
                        <span class="stat-label">عميل سعيد</span>
                    </div>
                </div>

                <p>
                    التزامنا بالجودة، وإخلاصنا في خدمة العملاء، وسعينا الدائم نحو التطوير،
                    هي ما يجعلنا اليوم اسماً تثق به آلاف العائلات والشركات على حد سواء.
                </p>
            </div>
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
    <script src="{{ asset('assets/js/myapp.js') }}"></script>



</body>

</html>
