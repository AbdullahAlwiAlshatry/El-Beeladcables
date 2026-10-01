document.addEventListener('DOMContentLoaded', function () {
    var password = document.getElementById('password');
    var passwordToggle = document.getElementById('passwordToggle');
    var loginForm = document.getElementById('loginForm');
    var loginFeedback = document.getElementById('loginFeedback');

    if (password && passwordToggle) {
        passwordToggle.addEventListener('click', function () {
            var isPassword = password.type === 'password';
            password.type = isPassword ? 'text' : 'password';
            passwordToggle.setAttribute('aria-label', isPassword ? 'إخفاء كلمة المرور' : 'إظهار كلمة المرور');
        });
    }

    // if (loginForm && loginFeedback) {
    //     loginForm.addEventListener('submit', function (event) {
    //         event.preventDefault();
    //         loginFeedback.textContent = 'هناك خطأ في عملية التسجيل، يرجى التأكد من معلومات تسجيل الدخول لاحقاً.';
    //     });
    // }
});
