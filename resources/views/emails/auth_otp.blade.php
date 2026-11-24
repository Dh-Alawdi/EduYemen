<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>رمز التحقق — منصة EduYemen</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f1f5f9; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; direction: rtl; text-align: right; color: #1e293b;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #f1f5f9; padding: 40px 15px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" max-width="560" cellspacing="0" cellpadding="0" border="0" style="max-width: 560px; background-color: #ffffff; border-radius: 20px; overflow: hidden; box-shadow: 0 10px 30px rgba(11,61,107,0.08); border: 1px solid #e2e8f0;">
                    
                    {{-- رأس الرسالة --}}
                    <tr>
                        <td style="background: linear-gradient(135deg, #072b4f 0%, #0b3d6b 60%, #1565a8 100%); padding: 32px 30px; text-align: center;">
                            <div style="font-size: 26px; font-weight: 800; line-height: 1.2; letter-spacing: -0.5px; color: #ffffff;">
                                <span style="color: #ffffff;">Edu</span><span style="color: #f0c842;">Yemen</span>
                            </div>
                            <div style="color: rgba(255,255,255,0.85); font-size: 13px; margin-top: 6px;">
                                البوابة الموحدة للجامعات اليمنية • نظام التحقق والمصادقة
                            </div>
                        </td>
                    </tr>

                    {{-- متن الرسالة --}}
                    <tr>
                        <td style="padding: 36px 32px;">
                            <h2 style="margin: 0 0 12px; font-size: 20px; font-weight: 800; color: #0b3d6b; line-height: 1.4;">
                                @if($type === 'register')
                                    مرحباً بك في EduYemen، يرجى تأكيد بريدك الإلكتروني
                                @else
                                    مرحباً {{ $userName ?? 'بك' }}، رمز الدخول إلى حسابك
                                @endif
                            </h2>

                            <p style="margin: 0 0 24px; font-size: 14px; line-height: 1.8; color: #475569;">
                                @if($type === 'register')
                                    لقد بدأت عملية إنشاء حساب طالب جديد في منصة EduYemen. لإكمال التسجيل وتفعيل حسابك، يرجى استخدام رمز التحقق التالي:
                                @else
                                    لقد تلقينا طلباً لتسجيل الدخول إلى حسابك عبر منصة EduYemen. لتأكيد هويتك والوصول بأمان، استخدم رمز التحقق السريع التالي:
                                @endif
                            </p>

                            {{-- صندوق الكود المضيء --}}
                            <div style="background: #f8fafc; border: 2px dashed #0b3d6b; border-radius: 16px; padding: 22px; text-align: center; margin: 28px 0;">
                                <div style="font-size: 12px; font-weight: 700; color: #64748b; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 1px;">
                                    رمز التحقق الخاص بك (OTP)
                                </div>
                                <div style="font-size: 34px; font-weight: 900; letter-spacing: 8px; color: #0b3d6b; font-family: 'Courier New', monospace; direction: ltr; display: inline-block;">
                                    {{ $code }}
                                </div>
                                <div style="font-size: 12px; color: #94a3b8; margin-top: 8px;">
                                    صالح لمدة <strong style="color: #d97706;">10 دقائق</strong> فقط
                                </div>
                            </div>

                            <div style="background-color: #fef3c7; border-right: 4px solid #f59e0b; padding: 12px 16px; border-radius: 8px; font-size: 12.5px; color: #92400e; line-height: 1.7; margin-bottom: 24px;">
                                <strong>تنبيه أمني:</strong> لا تشارك هذا الرمز مع أي شخص، فريق الدعم في EduYemen لن يطلب منك هذا الرمز إطلاقاً. إذا لم تكن أنت من طلب هذا الرمز، يمكنك تجاهل هذه الرسالة بأمان.
                            </div>

                            <p style="margin: 0; font-size: 13px; color: #64748b; line-height: 1.6;">
                                نتمنى لك رحلة جامعية وأكاديمية موفقة،<br>
                                <strong style="color: #0b3d6b;">فريق منصة EduYemen</strong>
                            </p>
                        </td>
                    </tr>

                    {{-- تذييل الرسالة --}}
                    <tr>
                        <td style="background-color: #f8fafc; border-top: 1px solid #e2e8f0; padding: 20px 30px; text-align: center; font-size: 11.5px; color: #94a3b8; line-height: 1.6;">
                            تم إرسال هذا البريد تلقائياً من نظام المصادقة والأمان في منصة EduYemen.<br>
                            صنعاء — الجمهورية اليمنية • جميع الحقوق محفوظة © {{ date('Y') }}
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
