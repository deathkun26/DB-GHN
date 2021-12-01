-- yêu cầu số 1
-- hiển thị danh sách các nhân viên thuộc 1 cửa hàng có mã cửa hàng là 1230001 theo thứ tự tên của nhân viên. 
SELECT NGUOI_DUNG.maND AS ma_NV, hotenND AS ho_ten_NV, sodienthoaiND AS so_dien_thoai_NV
FROM LAM_VIEC_TAI, NGUOI_DUNG
WHERE LAM_VIEC_TAI.maCH = 1230001 AND LAM_VIEC_TAI.maND = NGUOI_DUNG.maND
ORDER BY hotenND ASC;
-- hiển thị danh sách tất cả các nhân viên đang làm việc cho người chủ có mã người dùng là 12300000 theo thứ tự tên của nhân viên.
SELECT DISTINCT NGUOI_DUNG.maND AS ma_NV, hotenND AS ho_ten_NV, sodienthoaiND AS so_dien_thoai_NV
FROM CUA_HANG, LAM_VIEC_TAI, NGUOI_DUNG
WHERE CUA_HANG.maCCH = 12300000 AND CUA_HANG.maCH = LAM_VIEC_TAI.maCH AND LAM_VIEC_TAI.maND = NGUOI_DUNG.maND
ORDER BY hotenND ASC;
-- hiển thị số nhân viên theo từng cửa hàng của người chủ có mã người dùng là 12300000 theo thứ tự giảm dần của số nhân viên (chỉ hiển thị những cửa hàng đã có ít nhất 1 nhân viên)
SELECT tenCH AS ten_CH, COUNT(*) AS so_nhan_vien
FROM CUA_HANG, LAM_VIEC_TAI
WHERE CUA_HANG.maCCH = 12300000 AND CUA_HANG.maCH = LAM_VIEC_TAI.maCH
GROUP BY tenCH
HAVING COUNT(*) > 0
ORDER BY COUNT(*) DESC;
-- hiển thị số cửa hàng cùng một chủ có mã người dùng là 12300000 mà nhân viên đang làm việc theo thứ tự giảm dần của số cửa hàng (chỉ hiển thị những nhân viên đã làm việc tại ít nhất 1 cửa hàng)
SELECT NGUOI_DUNG.hotenND AS ho_ten_NV, COUNT(*) AS so_cua_hang
FROM LAM_VIEC_TAI, NGUOI_DUNG, CUA_HANG
WHERE CUA_HANG.maCCH = 12300000 AND CUA_HANG.maCH = LAM_VIEC_TAI.maCH AND LAM_VIEC_TAI.maND = NGUOI_DUNG.maND
GROUP BY NGUOI_DUNG.hotenND
HAVING COUNT(*) > 0
ORDER BY COUNT(*) DESC;

-- yêu cầu số 2
-- thủ tục hiển thị danh sách các nhân viên thuộc 1 cửa hàng có mã cửa hàng là 1230001 theo thứ tự tên của nhân viên.
DROP PROCEDURE IF EXISTS locNhanVien;
DELIMITER //

CREATE PROCEDURE locNhanVien(
		IN ma_cua_hang INT
)
BEGIN
	IF (ma_cua_hang < 1000000 OR ma_cua_hang > 9999999) THEN
		SELECT CONCAT('Mã cửa hàng của bạn ', CAST(ma_cua_hang AS CHAR), ' nằm ngoài miền giá trị!') AS 'Error';
	ELSE
		SELECT NGUOI_DUNG.maND AS ma_NV, hotenND AS ho_ten_NV, sodienthoaiND AS so_dien_thoai_NV
		FROM LAM_VIEC_TAI, NGUOI_DUNG
		WHERE LAM_VIEC_TAI.maCH = ma_cua_hang AND LAM_VIEC_TAI.maND = NGUOI_DUNG.maND;
	END IF;
END //

DELIMITER ;

CALL locNhanVien(1230002);
-- thủ tục thêm nhân viên vào cửa hàng
DROP PROCEDURE IF EXISTS themNhanVien;
DELIMITER //

CREATE PROCEDURE themNhanVien(
		IN ma_cua_hang INT,
        IN ma_nhan_vien INT
)
BEGIN
	IF (ma_cua_hang < 1000000 OR ma_cua_hang > 9999999) THEN
		SELECT CONCAT('Mã cửa hàng của bạn ', CAST(ma_cua_hang AS CHAR), ' nằm ngoài miền giá trị!') AS 'Error';
	END IF;
    IF (ma_nhan_vien < 10000000 OR ma_nhan_vien > 99999999) THEN
		SELECT CONCAT('Mã nhân viên của bạn ', CAST(ma_nhan_vien AS CHAR), ' nằm ngoài miền giá trị!') AS 'Error';
	END IF;
	INSERT INTO LAM_VIEC_TAI
    VALUES (ma_cua_hang, ma_nhan_vien);
END //

DELIMITER ;

CALL themNhanVien(1230001, 12300003); -- thêm Dân vào CH1 of chủ 1
CALL themNhanVien(12300010, 12300003);
CALL themNhanVien(1230001, 123000030);

-- yêu cầu số 3
-- trigger cập nhật số lượng nhân viên của một cửa hàng
DELIMITER //

CREATE TRIGGER capNhatSoLuongNV
    AFTER INSERT
    ON LAM_VIEC_TAI FOR EACH ROW
BEGIN
	UPDATE CUA_HANG
    SET CUA_HANG.soluongnhanvien = (CUA_HANG.soluongnhanvien + 1)
    WHERE CUA_HANG.maCH = NEW.maCH;
END//  

DELIMITER ;

INSERT INTO LAM_VIEC_TAI
VALUES (1230001, 23400001);
INSERT INTO LAM_VIEC_TAI
VALUES (1230001, 23400002);

-- trigger khi xoá tài khoản người dùng là một nhân viên của một cửa hàng nào đó.
DELIMITER //

CREATE TRIGGER xoaTaiKhoanNhanVien
    BEFORE DELETE
    ON NGUOI_DUNG FOR EACH ROW
BEGIN
	DELETE FROM NHAN_VIEN WHERE NHAN_VIEN.maND = OLD.maND;
END//

DELIMITER ;


-- trigger khi xoá tài khoản người dùng là một nhân viên của một cửa hàng nào đó.
DELIMITER //

CREATE TRIGGER xoaTaiKhoanNhanVien2
    BEFORE DELETE
    ON NGUOI_DUNG FOR EACH ROW
BEGIN
	DELETE FROM LAM_VIEC_TAI WHERE LAM_VIEC_TAI.maND = OLD.maND;
END//

DELIMITER ;

DELETE FROM NGUOI_DUNG WHERE maND = 23400001;

-- yêu cầu số 4
-- hàm tính tổng số nhân viên làm việc cho một người chủ.
DROP FUNCTION IF EXISTS tinhTongSoNhanVien;
DELIMITER //

CREATE FUNCTION tinhTongSoNhanVien(
	ma_chu_cua_hang INT
) 
RETURNS INT
DETERMINISTIC
BEGIN
	DECLARE result INT DEFAULT 0;
	IF (ma_chu_cua_hang >= 10000000 AND ma_chu_cua_hang <= 99999999) OR (EXISTS (SELECT * FROM CHU_CUA_HANG WHERE maND = ma_chu_cua_hang) = 1) THEN
		SET result = (SELECT COUNT(*) FROM (SELECT DISTINCT LAM_VIEC_TAI.maND, COUNT(*) FROM CUA_HANG, LAM_VIEC_TAI WHERE CUA_HANG.maCCH = ma_chu_cua_hang AND CUA_HANG.maCH = LAM_VIEC_TAI.maCH GROUP BY LAM_VIEC_TAI.maND) AS T);
	END IF;
    RETURN result;
END//

DELIMITER ;

SELECT tinhTongSoNhanVien(12300000);
SELECT tinhTongSoNhanVien(876543210);
SELECT tinhTongSoNhanVien(12345678);




