<?php
    session_start();           // بدء الجلسة
    session_unset();           // حذف جميع متغيرات الجلسة
    session_destroy();         // تدمير الجلسة نهائيًا

    // إعادة التوجيه لصفحة تسجيل الدخول أو الرئيسية
    header("Location: login.php");
    exit;
?>