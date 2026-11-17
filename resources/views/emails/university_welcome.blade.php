<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>وثيقة الاعتماد الأكاديمي وبيانات الحساب - منصة EduYemen</title>
    <style>
        body, table, td, p, a, li, blockquote {
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
            font-family: 'Segoe UI', Tahoma, Arial, sans-serif;
        }
        body {
            margin: 0;
            padding: 0;
            background-color: #f1f5f9;
            color: #1e293b;
            direction: rtl;
            text-align: right;
        }
        .email-wrapper {
            width: 100%;
            background-color: #f1f5f9;
            padding: 30px 10px;
        }
        .email-container {
            max-width: 620px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
            border: 1px solid #e2e8f0;
        }
        .email-header {
            background: linear-gradient(135deg, #072b4f 0%, #0d3b66 60%, #155e9c 100%);
            padding: 35px 30px;
            text-align: center;
            border-bottom: 4px solid #f0c842;
        }
        .brand-title {
            font-size: 28px;
            font-weight: 800;
            margin: 0;
            letter-spacing: 0.5px;
        }
        .brand-white { color: #ffffff; }
        .brand-gold { color: #f0c842; }
        .header-subtitle {
            color: rgba(255, 255, 255, 0.88);
            font-size: 13.5px;
            margin-top: 8px;
            margin-bottom: 0;
            font-weight: 500;
        }
        .email-body {
            padding: 32px 30px;
        }
        .greeting-title {
            font-size: 19px;
            font-weight: 800;
            color: #072b4f;
            margin-top: 0;
            margin-bottom: 12px;
        }
        .intro-text {
            font-size: 14.5px;
            line-height: 1.8;
            color: #334155;
            margin-bottom: 24px;
        }
        /* بطاقة بيانات الحساب */
        .credentials-card {
            background: linear-gradient(135deg, #f0f7ff 0%, #fefce8 100%);
            border: 2px solid #f0c842;
            border-radius: 14px;
            padding: 22px 24px;
            margin-bottom: 26px;
            box-shadow: 0 4px 14px rgba(240, 200, 66, 0.12);
        }
        .credentials-header {
            font-size: 15px;
            font-weight: 800;
            color: #072b4f;
            margin-bottom: 16px;
            padding-bottom: 10px;
            border-bottom: 1.5px dashed rgba(240, 200, 66, 0.6);
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .credential-row {
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 13.5px;
        }
        .credential-label {
            color: #64748b;
            font-weight: 600;
        }
        .credential-value {
            font-weight: 800;
            color: #072b4f;
            font-family: Consolas, 'Courier New', monospace;
            background: #ffffff;
            padding: 6px 14px;
            border-radius: 8px;
            border: 1px solid #cbd5e1;
            display: inline-block;
            direction: ltr;
            letter-spacing: 0.5px;
        }
        .btn-login {
            display: block;
            text-align: center;
            background: linear-gradient(135deg, #072b4f 0%, #0d3b66 100%);
            color: #ffffff !important;
            text-decoration: none;
            padding: 14px 28px;
            border-radius: 10px;
            font-weight: 800;
            font-size: 15px;
            margin: 26px 0;
            border: 1.5px solid #f0c842;
            box-shadow: 0 4px 15px rgba(7, 43, 79, 0.25);
        }
        /* بطاقة ملخص البيانات */
        .summary-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 16px 20px;
            margin-bottom: 24px;
            font-size: 13px;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 6px 0;
            border-bottom: 1px solid #edf2f7;
        }
        .summary-row:last-child {
            border-bottom: none;
        }
        .security-alert {
            background: #fffbeb;
            border-right: 4px solid #f59e0b;
            border-radius: 8px;
            padding: 12px 16px;
            font-size: 12.5px;
            color: #92400e;
            line-height: 1.6;
            margin-bottom: 24px;
        }
        .email-footer {
            background: #f8fafc;
            padding: 24px 30px;
            text-align: center;
            border-top: 1px solid #e2e8f0;
            font-size: 12px;
            color: #64748b;
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-container">
            
            <!-- الترويسة الفاخرة -->
            <div class="email-header">
                <div class="brand-title">
                    <span class="brand-white">Edu</span><span class="brand-gold">Yemen</span>
                </div>
                <p class="header-subtitle">المنصة الوطنية الموحدة للتنسيق والقبول بالجامعات اليمنية</p>
                <div style="margin-top:12px;display:inline-block;background:rgba(240,200,66,0.18);border:1px solid #f0c842;color:#f0c842;padding:4px 16px;border-radius:20px;font-size:12px;font-weight:700;">
                    وثيقة الاعتماد الأكاديمي وبيانات الدخول
                </div>
            </div>

            <!-- محتوى الرسالة -->
            <div class="email-body">
                <h2 class="greeting-title">
                    الأخوة في إدارة <span style="color:#0d3b66;">{{ is_object($university) ? $university->university_name : ($university['university_name'] ?? 'الجامعة') }}</span> المحترمون،
                </h2>
                
                <p class="intro-text">
                    تحية طيبة وبعد،،<br>
                    يسر إدارة <strong>منصة EduYemen</strong> إحاطتكم بأنه قد تم اعتماد جامعتكم الموقرة رسمياً ضمن المنظومة الوطنية الموحدة، وتفعيل البوابة الأكاديمية الخاصة بكم لإدارة التخصصات وتتبع طلبات التنسيق والقبول للطلاب.
                </p>

                <!-- بطاقة بيانات الدخول الرسمية -->
                <div class="credentials-card">
                    <div class="credentials-header">
                        <span>🔐</span>
                        <span>بيانات الدخول الأولية لحساب الجامعة</span>
                    </div>

                    <table style="width:100%;border-collapse:collapse;">
                        <tr>
                            <td style="padding:8px 0;color:#64748b;font-weight:600;font-size:13.5px;">رابط البوابة:</td>
                            <td style="padding:8px 0;text-align:left;direction:ltr;">
                                <a href="{{ $loginUrl }}" style="color:#0d3b66;font-weight:700;text-decoration:none;font-size:13px;">{{ $loginUrl }}</a>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding:8px 0;color:#64748b;font-weight:600;font-size:13.5px;">اسم المستخدم (Username):</td>
                            <td style="padding:8px 0;text-align:left;direction:ltr;">
                                <span class="credential-value">{{ $username }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding:8px 0;color:#64748b;font-weight:600;font-size:13.5px;">كلمة المرور المؤقتة (Password):</td>
                            <td style="padding:8px 0;text-align:left;direction:ltr;">
                                <span class="credential-value" style="color:#b91c1c;border-color:#fca5a5;background:#fff1f2;">{{ $rawPassword }}</span>
                            </td>
                        </tr>
                    </table>
                </div>

                <!-- زر تسجيل الدخول المباشر -->
                <div style="text-align:center;">
                    <a href="{{ $loginUrl }}" class="btn-login" target="_blank">
                        تسجيل الدخول إلى لوحة تحكم الجامعة &larr;
                    </a>
                </div>

                <!-- إرشادات الأمان والسرية -->
                <div class="security-alert">
                    <strong>⚠️ إرشادات أمنية هامة:</strong><br>
                    - كلمة المرور الموضحة أعلاه هي كلمة مرور أولية ومؤقتة؛ يرجى تغييرها فوراً عند تسجيل الدخول لأول مرة من شاشة (الملف الشخصي / الأمان).<br>
                    - يرجى الحفاظ على سرية هذه البيانات وعدم تزويدها إلا للمخولين بإدارة شؤون التسجيل والقبول في جامعتكم.
                </div>

                <!-- ملخص بيانات الصرح الأكاديمي -->
                <div class="summary-card">
                    <div style="font-weight:800;color:#072b4f;margin-bottom:10px;font-size:13px;">
                        🏛️ البيانات المسجلة للصرح الأكاديمي:
                    </div>
                    <table style="width:100%;border-collapse:collapse;font-size:12.5px;">
                        <tr>
                            <td style="padding:5px 0;color:#64748b;">نوع الجامعة:</td>
                            <td style="padding:5px 0;font-weight:700;color:#0f172a;text-align:left;">
                                {{ is_object($university) ? $university->university_type : ($university['university_type'] ?? 'جامعة يمنية') }}
                            </td>
                        </tr>
                        <tr>
                            <td style="padding:5px 0;color:#64748b;">المحافظة / المقر:</td>
                            <td style="padding:5px 0;font-weight:700;color:#0f172a;text-align:left;">
                                {{ is_object($university) ? $university->district : ($university['district'] ?? '-') }}
                            </td>
                        </tr>
                        @if((is_object($university) && $university->phone) || (!empty($university['phone'])))
                        <tr>
                            <td style="padding:5px 0;color:#64748b;">الهاتف المسجل:</td>
                            <td style="padding:5px 0;font-weight:700;color:#0f172a;text-align:left;direction:ltr;">
                                {{ is_object($university) ? $university->phone : ($university['phone'] ?? '-') }}
                            </td>
                        </tr>
                        @endif
                    </table>
                </div>

                <p style="font-size:13.5px;color:#475569;margin-bottom:0;line-height:1.7;">
                    إذا واجهتكم أي صعوبة في الدخول أو احتجتم لدعم فني، يمكنكم التواصل مع الإدارة المركزية للمنصة عبر الرد المباشر على هذه الرسالة.
                    <br><br>
                    <strong>وتقبلوا خالص التحية والتقدير،،</strong><br>
                    <span style="color:#072b4f;font-weight:700;">فريق إدارة منصة EduYemen</span>
                </p>
            </div>

            <!-- التذييل الرسمي -->
            <div class="email-footer">
                <p style="margin:0 0 6px 0;font-weight:700;color:#072b4f;">
                    منصة EduYemen للقبول والتنسيق الجامعي
                </p>
                <p style="margin:0 0 10px 0;">
                    بوابتكم الوطنية للربط الأكاديمي وخدمة الطلاب في عموم محافظات الجمهورية اليمنية
                </p>
                <p style="margin:0;font-size:11px;color:#94a3b8;">
                    تم إرسال هذا الإشعار الآلي رسمياً من النظام إلى بريد الجامعة المعتمد.
                </p>
            </div>

        </div>
    </div>
</body>
</html>
