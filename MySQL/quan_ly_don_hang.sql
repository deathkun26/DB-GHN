-- hiển thị danh sách tất cả các hoá đơn của một cửa hàng theo thứ tự từ mới đến cũ nhất.
SELECT maVD, hotenNN, sodienthoaiNN, diachiNN, trangthai
FROM DON_HANG, CUA_HANG
WHERE DON_HANG.maCH = CUA_HANG.maCH
ORDER BY maVD DESC;
-- hiển thị danh sách tất cả các sản phẩm trong 1 hoá đơn theo thứ tự tăng dần của STT sản phẩm.
SELECT sttSP, tenSP, soluongSP, khoiluongSP
FROM SAN_PHAM, DON_HANG
WHERE SAN_PHAM.maVD = DON_HANG.maVD
ORDER BY sttSP ASC;
-- hiển thị thông tin bưu tá lấy hàng.
SELECT hotenBt, sodienthoaiBT
FROM BUU_TA, DON_HANG
WHERE BUU_TA.maBT = DON_HANG.maBT;
-- hiển thị danh sách các điểm giao nhận khi người dùng chọn option tự gửi.
SELECT DIEM_GIAO_NHAN.sttDGN, DIEM_GIAO_NHAN.khuvucDGN, diachiDGN, sodienthoaiDGN
FROM DIEM_GIAO_NHAN, DON_HANG
WHERE DIEM_GIAO_NHAN.sttDGN = DON_HANG.sttDGN AND DIEM_GIAO_NHAN.khuvucDGN = DON_HANG.khuvucDGN
ORDER BY khuvucDGN ASC, sttDGN ASC;
-- hiển thị danh sách các hoá đơn của cửa hàng trong phạm vi ngày-tháng-năm theo thứ tự từ mới đến cũ nhất.
SELECT maVD, hotenNN, sodienthoaiNN, diachiNN, trangthai
FROM DON_HANG, CUA_HANG
WHERE DON_HANG.maCH = CUA_HANG.maCH AND ngaytaoDH >= '2021-11-11' AND ngaytaoDH <= '2021-11-21'
ORDER BY maVD DESC;
-- hiển thị thông tin chi tiết của đơn hàng theo mã vận đơn (khi người dùng bấm nút tra cứu).
-- Dân check thử có cần những cái này không? --


-- hiển thị tất cả các đơn hàng của tất cả các cửa hàng của 1 chủ cửa hàng có mã người dùng là 12345678 theo thứ tự từ mới đến cũ nhất.
SELECT maVD, hotenNN, sodienthoaiNN, diachiNN, trangthai
FROM CUA_HANG, DON_HANG
WHERE CUA_HANG.maCCH = 12345678 AND CUA_HANG.maCH = DON_HANG.maCH
ORDER BY maVD DESC;
-- hiển thị danh sách tất cả các sản phẩm của một đơn hàng có họ tên người nhận là Hà Duy Anh theo thứ tự của đơn hàng từ mới đến cũ nhất.
SELECT sttSP, tenSP, soluongSP, khoiluongSP
FROM SAN_PHAM, DON_HANG
WHERE DON_HANG.hotenNN = 'Hà Duy Anh' AND SAN_PHAM.maVD = DON_HANG.maVD
ORDER BY DON_HANG.maVD DESC, sttSP ASC;
-- hiển thị những cửa hàng có trên 2 đơn hàng theo thứ tự tăng dần của số lượng đơn hàng.
SELECT CUA_HANG.maCH, tenCH, count(*)
FROM DON_HANG, CUA_HANG
WHERE DON_HANG.maCH = CUA_HANG.maCH
GROUP BY CUA_HANG.maCH, tenCH
HAVING count(*) > 2
ORDER BY count(*) ASC;
-- hiển thị những đơn hàng có trên 10 sản phẩm theo thứ tự tăng dần của số lượng sản phẩm.
SELECT DON_HANG.maVD, count(*)
FROM DON_HANG, SAN_PHAM
WHERE DON_HANG.maVD = SAN_PHAM.maVD
GROUP BY DON_HANG.maVD
HAVING count(*) > 3
ORDER BY count(*);

-- yêu cầu số 2
-- thủ tục hiển thị
-- hiển thị thông tin chi tiết của đơn hàng theo mã vận đơn
DELIMITER //

CREATE PROCEDURE traCuuDonHang(
		IN ma_van_don INT
)
BEGIN
	IF (ma_van_don < 10000000 OR ma_van_don > 99999999) THEN
		SELECT CONCAT('Mã vận đơn của bạn ', CAST(ma_van_don AS CHAR), 'nằm ngoài miền giá trị!');
	ELSE
		SELECT *  
		FROM DON_HANG
		WHERE maVD = ma_van_don;
	END IF;
END //

DELIMITER ;

CALL traCuuDonHang(11111111);
-- thủ tục thao tác dữ liệu với các tham số đầu vào
-- thêm thông tin người nhận cho đơn hàng nháp
DELIMITER //

CREATE PROCEDURE themThongTinNN(
		IN ma_van_don INT,
		IN ho_ten VARCHAR(30),
		IN so_dien_thoai CHAR(10),
		IN dia_chi VARCHAR(60)
)
BEGIN
	IF (so_dien_thoai REGEXP '^[0-9]{10}$') THEN
		UPDATE DON_HANG
        SET hotenNN = ho_ten, sodienthoaiNN = so_dien_thoai, diachiNN = dia_chi
        WHERE maVD = ma_van_don;
	ELSE 
		SELECT ('Số điện thoại không hợp lệ');
	END IF;
END //

DELIMITER ;

CALL themThongTinNN(11111115, 'Nguyễn Công Thành', '0352123210', 'Thủ Đức, TPHCM');

-- yêu cầu số 3
-- trigger tính tổng khối lượng của đơn hàng
DROP TRIGGER IF EXISTS capNhatTongKhoiLuong;
DELIMITER //

CREATE TRIGGER capNhatTongKhoiLuong
    AFTER INSERT
    ON SAN_PHAM FOR EACH ROW
BEGIN
	UPDATE DON_HANG
    SET DON_HANG.tongkhoiluong = (DON_HANG.tongkhoiluong + NEW.khoiluongSP)
    WHERE DON_HANG.maVD = NEW.maVD;
END//  

DELIMITER ;
-- trigger xoá các sản phẩm khi xoá đơn hàng nháp.
DELIMITER //

CREATE TRIGGER xoaSanPham
    BEFORE DELETE
    ON DON_HANG FOR EACH ROW
BEGIN
	DELETE FROM SAN_PHAM WHERE SAN_PHAM.maVD = OLD.maVD;
END//  

DELIMITER ;
-- trigger xoá thông tin của điểm giao nhận khi xoá đơn hàng nháp.
DELIMITER //

CREATE TRIGGER xoaThongTinDGN
    BEFORE DELETE
    ON DON_HANG FOR EACH ROW
BEGIN
	DELETE FROM TRA_HANG WHERE TRA_HANG.maVD = OLD.maVD;
END//

DELIMITER ;

-- yêu cầu số 4
-- 
DELIMITER //

CREATE FUNCTION tinhTongTienDH(
	diachitrahang VARCHAR(60)
) 
RETURNS INT
DETERMINISTIC
BEGIN
   
END//

DELIMITER ;