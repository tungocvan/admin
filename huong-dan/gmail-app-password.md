# Hướng dẫn đăng nhập admin bằng tài khoản gmail:
-B1: Mở trình duyệt -> đăng nhập vào tài khoản gmail bất kỳ
-B2: nhấn link
https://console.cloud.google.com/auth/clients
- có thể tạo mới Client ID for Web application hoặc chọn project  Web application đã tồn tại
- thêm link vào: Authorized JavaScript origins
https://ntd.laravel.tk
- thêm link vào: Authorized redirect URIs
https://ntd.laravel.tk/auth/google/callback

- lấy 3 thông tin gán vào .env
GOOGLE_CLIENT_ID ="....."
GOOGLE_CLIENT_SECRET ="...."
GOOGLE_REDIRECT ="/auth/google/callback"
- lưu ý: app/config/services.php phải bắt buộc có:
 'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_REDIRECT'),
    ],
# Hướng dẫn tạo App Password (Mật khẩu ứng dụng) cho Gmail

**Mục đích:** tạo "App Password" để dùng làm `MAIL_PASSWORD` cho ứng dụng (Laravel, SMTP client) khi tài khoản Google đã bật xác thực 2 bước.

> **Quan trọng:** App Password chỉ hoạt động khi **Google Account** đã bật **2-Step Verification** (Xác minh 2 bước). Bạn không dùng mật khẩu chính của tài khoản Google ở đây.

---

## 1. Bật Xác minh 2 bước (nếu chưa bật)

1. Mở trình duyệt và đăng nhập vào tài khoản Google của bạn: `https://myaccount.google.com`
2. Trong menu bên trái, chọn **Security (Bảo mật)**.
3. Tìm phần **Signing in to Google** → **2-Step Verification** và nhấn vào.
4. Nhấn **Get started** (Bắt đầu) và làm theo các bước để bật 2-step (số điện thoại để nhận mã SMS/ẩn danh app authenticator...).

> Sau khi hoàn tất, quay lại trang Security để tiếp tục tạo App Password.

---

## 2. Tạo App Password

1. Vẫn ở trang **Security**, trong phần **Signing in to Google**, tìm **App passwords** và nhấn vào.

   * Nếu bạn không thấy mục **App passwords**, có thể do: 2-Step Verification chưa bật, hoặc tài khoản bạn là Google Workspace mà admin đã chặn tính năng này.
2. Hệ thống có thể yêu cầu bạn nhập lại mật khẩu tài khoản để xác thực. Nhập và tiếp tục.
3. Ở mục **Select app**, chọn **Other (Custom name)**.
4. Điền tên mô tả (ví dụ: `MyApp SMTP` hoặc `Laravel SMTP`) rồi nhấn **Generate**.
5. Google sẽ hiển thị **một mật khẩu gồm 16 ký tự** (không có dấu cách). Đây chính là `App Password` — **sao chép ngay**, vì trang chỉ hiển thị một lần.

---

## 3. Cấu hình vào `.env` (ví dụ Laravel)

Dán mật khẩu vừa tạo vào `MAIL_PASSWORD` trong file `.env` của dự án:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=you@example.com
MAIL_PASSWORD="app_password_16chars"
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=you@example.com
MAIL_FROM_NAME="Tên hiển thị"
```

* `MAIL_USERNAME` thường là địa chỉ email chính của bạn (ví dụ `you@example.com`).
* Dùng port `587` và `MAIL_ENCRYPTION=tls` là tùy chọn an toàn và phổ biến.

> **Lưu ý:** không commit file `.env` vào git. Trên production, dùng secrets/environment variables của host.

---

## 4. Kiểm tra và khắc phục lỗi thường gặp

* **Không thấy App passwords:** kiểm tra lại đã bật 2-Step Verification chưa; tài khoản Workspace có thể bị admin chặn.
* **Lỗi xác thực SMTP (auth failed):** kiểm tra lại `MAIL_USERNAME` và `MAIL_PASSWORD` (App Password). Đảm bảo không có khoảng trắng/quote dư.
* **Port bị chặn:** một số VPS/ISP chặn cổng SMTP. Thử port 587 (STARTTLS) hoặc 465 (SSL). Với 465, đặt `MAIL_ENCRYPTION=ssl`.
* **Ứng dụng báo timeout kết nối:** kiểm tra `MAIL_HOST` đúng là `smtp.gmail.com`, và server có kết nối ra internet đến port trên.

---

## 5. Bảo mật

* **Không** ghi App Password vào repository công khai.
* Lưu App Password ở nơi an toàn (Secrets Manager, environment variables, hoặc file `.env` trên server với permissions chặt `600`).
* Nếu nghi ngờ bị lộ, vào lại **App passwords** trên Google và **revoke** (xóa) password đó.

---

## 6. Xóa / thu hồi App Password

1. Vào `https://myaccount.google.com/security` → **App passwords**.
2. Tìm app password đã tạo, nhấn **Remove** để thu hồi.

---

Nếu bạn muốn, mình có thể tạo sẵn tệp `.md` này trong `public/help/gmail-app-password.md` để bạn chỉ cần fetch — nhưng hiện tại tệp đã được tạo ở tài liệu này, bạn có thể sao chép nội dung vào file trên server.
