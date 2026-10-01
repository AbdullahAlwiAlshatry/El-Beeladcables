<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول | البلاد للكهربائيات</title>
    <link rel="stylesheet" href="{{ asset('assets/css/login.css') }}">
    <link rel="icon" type="image/png" href="{{ asset('assets/images/faviconHome.ico') }}" />
</head>

<body class="login-page">
    <main class="login-shell">
        <section class="login-visual" aria-label="معلومات المتجر">
            <div class="visual-orb visual-orb-one"></div>
            <div class="visual-orb visual-orb-two"></div>
            <div class="visual-content">
                <div class="brand-mark" aria-hidden="true">
                    <svg viewBox="0 0 64 64" fill="none">
                        <path d="M32 5 55 18v28L32 59 9 46V18L32 5Z" stroke="currentColor" stroke-width="2" />
                        <path d="m37 14-15 21h9l-4 15 16-23h-9l3-13Z" fill="currentColor" />
                    </svg>
                </div>
                <span class="visual-kicker">منصة البلاد</span>
                <h1>كل احتياجاتك<br><span>في مكان واحد</span></h1>
                <p>ادخل إلى حسابك لمتابعة طلباتك والوصول إلى أحدث المنتجات والأسعار.</p>
                <div class="visual-decoration">
                    <span></span><span></span><span></span>
                </div>
            </div>
            <div class="visual-footer">تجربة تسوق أبسط، أسرع، وأقرب إليك</div>
        </section>

        <section class="login-panel">
            <div class="login-card">
                <div class="mobile-brand">
                    <div class="brand-mark brand-mark-small" aria-hidden="true">
                        <svg viewBox="0 0 64 64" fill="none">
                            <path d="M32 5 55 18v28L32 59 9 46V18L32 5Z" stroke="currentColor" stroke-width="2" />
                            <path d="m37 14-15 21h9l-4 15 16-23h-9l3-13Z" fill="currentColor" />
                        </svg>
                    </div>
                    <strong>البلاد</strong>
                </div>

                <div class="login-heading">
                    <span class="heading-badge">مرحباً بعودتك</span>
                    <h2>تسجيل الدخول</h2>
                    <p>أدخل بياناتك للمتابعة إلى حسابك</p>
                </div>

                <form class="login-form" id="loginForm" action="{{ Route('login.post') }}" method="POST">
                    @csrf

                    <div class="form-field">
                        <label for="username">اسم المستخدم</label>
                        <div class="input-wrap">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                                aria-hidden="true">
                                <circle cx="12" cy="8" r="4" />
                                <path d="M4 21a8 8 0 0 1 16 0" />
                            </svg>
                            <input type="text" id="username" name="email" placeholder="أدخل اسم المستخدم"
                                autocomplete="username" required>
                        </div>
                    </div>

                    <div class="form-field">
                        <div class="field-label-row">
                            <label for="password">كلمة المرور</label>
                            <a href="#" class="forgot-link">هل نسيت كلمة المرور؟</a>
                        </div>
                        <div class="input-wrap">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                                aria-hidden="true">
                                <rect x="4" y="10" width="16" height="11" rx="2" />
                                <path d="M8 10V7a4 4 0 0 1 8 0v3" />
                            </svg>
                            <input type="password" id="password" name="password" placeholder="أدخل كلمة المرور"
                                autocomplete="current-password" required>
                            <button type="button" class="password-toggle" id="passwordToggle"
                                aria-label="إظهار كلمة المرور">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6S2 12 2 12Z" />
                                    <circle cx="12" cy="12" r="2.5" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <label class="remember-row">
                        <input type="checkbox" name="remember">
                        <span class="custom-checkbox"></span>
                        <span>تذكرني على هذا الجهاز</span>
                    </label>

                    <button type="submit" class="login-submit">
                        <span>دخول إلى الحساب</span>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            aria-hidden="true">
                            <path d="M5 12h14M13 6l6 6-6 6" />
                        </svg>
                    </button>
                    <p class="login-feedback" id="loginFeedback" role="status"></p>
                </form>
                <p class="login-note">بياناتك محمية ومشفرة بأمان</p>
            </div>
        </section>
    </main>

    <script src="{{ asset('assets/js/login.js') }}"></script>


</body>
</html>
