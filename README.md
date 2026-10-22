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
- [x] Giai đoạn 5: Loki + Promtail thu thập log Docker và truy vấn LogQL
- [x] Giai đoạn 6: Hardening container, network, mật khẩu, database và cổng công khai
- [x] Giai đoạn 7: Báo cáo 10–15 trang, README và kịch bản bảo vệ

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

## Log tập trung với Loki và Promtail

- Loki lưu trữ log tại named volume `dtc245220055_loki_data`.
- Promtail tự động phát hiện các container thuộc Compose project và gửi log đến Loki.
- Grafana tự động nạp datasource Loki qua URL nội bộ `http://loki:3100`.
- Loki chỉ công khai cổng kiểm tra trên localhost tại `http://localhost:3105`.

Ba truy vấn LogQL dùng để kiểm tra:

```logql
{service="nginx"}
```

```logql
{service="nginx"} |~ " 4[0-9]{2} "
```

```logql
{service="wordpress"} |= "GET"
```

## Quy tắc bảo mật

- Không commit file `.env` hoặc mật khẩu thật.
- Chỉ lưu biến mẫu trong `.env.example`.
- Mật khẩu MySQL, WordPress và Grafana có độ dài từ 24 đến 29 ký tự và không dùng giá trị mặc định.
- MySQL nằm trong backend network có `internal: true`; cổng `3306` không được publish ra host.
- WordPress không publish cổng trực tiếp; người dùng chỉ truy cập website qua Nginx reverse proxy.
- Các cổng Nginx, phpMyAdmin, Prometheus, Grafana và Loki chỉ bind vào `127.0.0.1`.
- Tài khoản ứng dụng WordPress tách biệt với tài khoản `root` của MySQL.
- Grafana tắt chức năng tự đăng ký tài khoản bằng `GF_USERS_ALLOW_SIGN_UP=false`.
- Các file cấu hình và Docker socket dùng cho giám sát được mount ở chế độ read-only.
- Security headers được thiết lập tại Nginx và `server_tokens` đã tắt.
- Chỉ cAdvisor chạy privileged để đọc metrics hệ thống; các container còn lại đều `privileged=false`.

## Báo cáo và bảo vệ

- Báo cáo Word: `docs/report/Bao_cao_De_03_DTC245220055.docx`.
- Báo cáo gồm 14 trang, bám theo bảy giai đoạn triển khai và có chú thích cho các ảnh minh chứng chính.
- Kịch bản demo 7 phút và các câu hỏi bảo vệ dự kiến nằm ở chương cuối của báo cáo.

Trước khi bảo vệ, khởi động và kiểm tra nhanh hệ thống:

```powershell
docker compose up -d
docker compose ps
curl.exe -I http://localhost:8082
git log --oneline --all
```

Các địa chỉ demo:

- Website qua Nginx: `http://localhost:8082`
- phpMyAdmin: `http://localhost:8083`
- Prometheus targets: `http://localhost:9095/targets`
- Grafana: `http://localhost:3005`
- Loki readiness: `http://localhost:3105/ready`

