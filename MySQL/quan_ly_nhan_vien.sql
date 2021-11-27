-- yêu cầu số 1
-- hiển thị danh sách các nhân viên thuộc 1 cửa hàng có mã cửa hàng là 1234567 theo thứ tự tên của nhân viên. 
SELECT NGUOI_DUNG.maND AS ma_NV, hotenND AS ho_ten_NV, sodienthoaiND AS so_dien_thoai_NV
FROM LAM_VIEC_TAI, NGUOI_DUNG
WHERE LAM_VIEC_TAI.maCH = 1234567 AND LAM_VIEC_TAI.maND = NGUOI_DUNG.maND
ORDER BY hotenND ASC;
-- hiển thị danh sách tất cả các nhân viên đang làm việc cho người chủ có mã người dùng là 12345678 theo thứ tự tên của nhân viên.
SELECT DISTINCT NGUOI_DUNG.maND AS ma_NV, hotenND AS ho_ten_NV, sodienthoaiND AS so_dien_thoai_NV
FROM CUA_HANG, LAM_VIEC_TAI, NGUOI_DUNG
WHERE CUA_HANG.maCCH = 12345678 AND CUA_HANG.maCH = LAM_VIEC_TAI.maCH AND LAM_VIEC_TAI.maND = NGUOI_DUNG.maND
ORDER BY hotenND ASC;
-- hiển thị số nhân viên theo từng cửa hàng của người chủ có mã người dùng là 12345678 theo thứ tự giảm dần của số nhân viên (chỉ hiển thị những cửa hàng đã có ít nhất 1 nhân viên)
SELECT tenCH AS ten_CH, COUNT(*) AS so_nhan_vien
FROM CUA_HANG, LAM_VIEC_TAI, NGUOI_DUNG
WHERE CUA_HANG.maCCH = 12345678 AND CUA_HANG.maCH = LAM_VIEC_TAI.maCH AND LAM_VIEC_TAI.maND = NGUOI_DUNG.maND
GROUP BY tenCH
HAVING COUNT(*) > 0
ORDER BY COUNT(*) DESC;
-- hiển thị số cửa hàng cùng một chủ có mã người dùng là 12345678 mà nhân viên đang làm việc theo thứ tự giảm dần của số cửa hàng (chỉ hiển thị những nhân viên đã làm việc tại ít nhất 1 cửa hàng)
SELECT DISTINCT NGUOI_DUNG.hotenND AS ho_ten_NV, COUNT(*) AS so_cua_hang
FROM LAM_VIEC_TAI, NGUOI_DUNG, CUA_HANG
WHERE CUA_HANG.maCCH = 12345678 AND CUA_HANG.maCH = LAM_VIEC_TAI.maCH AND LAM_VIEC_TAI.maND = NGUOI_DUNG.maND
GROUP BY NGUOI_DUNG.hotenND
HAVING COUNT(*) > 0
ORDER BY COUNT(*) DESC;

-- yêu cầu số 2
-- hiển thị tất cả các nhân viên
-- lọc nhân viên theo cửa hàng
-- thay đổi cửa hàng nhân viên
-- xoá khỏi cửa hàng
-- thêm nhân viên vào cửa hàng

-- thủ tục hiển thị danh sách các nhân viên thuộc 1 cửa hàng có mã cửa hàng là 1234567 theo thứ tự tên của nhân viên.
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

CALL locNhanVien(23456789);
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

DELETE FROM LAM_VIEC_TAI WHERE maCH = 1234567 AND maND = 34567812;
CALL themNhanVien(1234567, 34567812);

-- yêu cầu số 3
-- trigger phân loại người dùng
DELIMITER //

CREATE TRIGGER phanLoaiND
    AFTER INSERT
    ON NGUOI_DUNG FOR EACH ROW
BEGIN
	IF NGUOI_DUNG.loai = 0 THEN
		INSERT INTO CHU_CUA_HANG
        VALUES (NEW.maND);
	ELSE
		INSERT INTO NHAN_VIEN
        VALUES (NEW.maND);
	END IF;
END//  

DELIMITER ;

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

-- yêu cầu số 4
-- hàm tính tổng số nhân viên của một cửa hàng
DROP FUNCTION IF EXISTS tinhTongSoNhanVien;
DELIMITER //

CREATE FUNCTION tinhTongSoNhanVien(
	ma_cua_hang INT
) 
RETURNS INT
DETERMINISTIC
BEGIN
	DECLARE result INT;
	IF ma_cua_hang >= 1000000 OR ma_cua_hang <= 9999999 THEN
		SET result = (SELECT DISTINCT COUNT(*) FROM LAM_VIEC_TAI WHERE LAM_VIEC_TAI.maCH = ma_cua_hang);
	END IF;
    RETURN result;
END//

DELIMITER ;

SELECT tinhTongSoNhanVien(1234567);



