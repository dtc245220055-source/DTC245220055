# DTC245220055 - Đề 03: Website quảng bá nước rau má

Dự án triển khai website giới thiệu sản phẩm nước rau má nguyên chất bằng WordPress trên Docker Compose.

## Stack

- WordPress
- MySQL
- phpMyAdmin
- Nginx reverse proxy
- Prometheus + Grafana
- Loki + Promtail

## Trạng thái triển khai

- [x] Giai đoạn 1: Khởi tạo repository và cấu trúc dự án
- [x] Giai đoạn 2: WordPress + MySQL + phpMyAdmin đã chạy và được xác minh
- [x] Giai đoạn 3: Nginx reverse proxy và security headers
- [x] Giai đoạn 4: Prometheus + Grafana, 4 targets UP và dashboard được tự động provision
- [ ] Giai đoạn 5: Loki + Promtail
- [ ] Giai đoạn 6: Hardening
- [ ] Giai đoạn 7: Báo cáo và bảo vệ

## Khởi chạy Giai đoạn 2

1. Sao chép `.env.example` thành `.env` và thay toàn bộ giá trị `CHANGE_ME` bằng mật khẩu mạnh.
2. Khởi động Docker Desktop.
3. Chạy hệ thống:

   ```powershell
   docker compose up -d
   ```

4. Kiểm tra container:

   ```powershell
   docker compose ps
   ```

5. Truy cập WordPress qua Nginx tại `http://localhost:8082`.
6. Truy cập phpMyAdmin tại `http://localhost:8083`.

MySQL chỉ nằm trong Docker network nội bộ và không công khai cổng `3306` ra máy chủ.

## Nginx reverse proxy

Nginx là điểm truy cập duy nhất của website tại `http://localhost:8082`. Container WordPress không công khai cổng trực tiếp. Cấu hình tại `nginx/conf.d/default.conf` chuyển tiếp yêu cầu tới `wordpress:80` và bổ sung các header:

- `X-Frame-Options: SAMEORIGIN`
- `X-Content-Type-Options: nosniff`
- `Referrer-Policy: strict-origin-when-cross-origin`
- `Permissions-Policy` tắt camera, microphone và geolocation

## Giám sát Prometheus và Grafana

- Prometheus: `http://localhost:9095`
- Grafana: `http://localhost:3005`
- Prometheus thu thập metrics từ chính Prometheus, cAdvisor, Nginx Exporter và MySQL Exporter.
- Dashboard `De 03 - WordPress Monitoring` được tự động nạp từ thư mục `monitoring/grafana/dashboards`.
- Dữ liệu Prometheus và Grafana được lưu trong named volumes để không mất khi tạo lại container.

Kiểm tra các target tại `http://localhost:9095/targets`; cả bốn target phải có trạng thái `UP`.

## Quy tắc bảo mật

- Không commit file `.env` hoặc mật khẩu thật.
- Chỉ lưu biến mẫu trong `.env.example`.
- Các dịch vụ nội bộ được tách network và giới hạn cổng công khai ở giai đoạn hardening.

